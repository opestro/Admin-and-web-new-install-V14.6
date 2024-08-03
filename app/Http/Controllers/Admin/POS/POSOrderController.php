<?php

namespace App\Http\Controllers\Admin\POS;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\OrderDetailRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\SessionKey;
use App\Enums\ViewPaths\Admin\POSOrder;
use App\Events\DigitalProductDownloadEvent;
use App\Http\Controllers\BaseController;
use App\Services\CartService;
use App\Services\OrderDetailsService;
use App\Services\OrderService;
use App\Services\POSService;
use App\Traits\CalculatorTrait;
use App\Traits\CustomerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class POSOrderController extends BaseController
{
    use CustomerTrait;
    use CalculatorTrait;


    /**
     * @param ProductRepositoryInterface $productRepo
     * @param CustomerRepositoryInterface $customerRepo
     * @param OrderRepositoryInterface $orderRepo
     * @param OrderDetailRepositoryInterface $orderDetailRepo
     * @param VendorRepositoryInterface $vendorRepo
     * @param POSService $POSService
     * @param CartService $cartService
     * @param OrderDetailsService $orderDetailsService
     * @param OrderService $orderService
     */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly OrderDetailRepositoryInterface $orderDetailRepo,
        private readonly VendorRepositoryInterface $vendorRepo,
        private readonly POSService $POSService,
        private readonly CartService $cartService,
        private readonly OrderDetailsService $orderDetailsService,
        private readonly OrderService $orderService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View|Collection|LengthAwarePaginator|callable|RedirectResponse|null
     */
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getOrderDetailsView(id:$type);
    }


    /**
     * @param string $id
     * @return View|RedirectResponse
     */
    public function getOrderDetailsView(string $id):View|RedirectResponse
    {
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params: ['id' => $vendorId]);
        $getPOSStatus = getWebConfig('seller_pos');
        if ($vendor['pos_status'] == 0 || $getPOSStatus == 0) {
            Toastr::warning(translate('access_denied!!'));
            return redirect()->back();
        }
        $order = $this->orderRepo->getFirstWhere(params:['id'=>$id],relations:['details', 'shipping', 'seller']);
        return view(POSOrder::ORDER_DETAILS[VIEW], compact('order'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function placeOrder(Request $request):JsonResponse
    {
        $amount = $request['amount'];
        $cartId =session(SessionKey::CURRENT_USER);
        $condition = $this->POSService->checkConditions(amount: $amount);
        if ($condition == 'true'){
            return response()->json();
        }
        $userId = $this->cartService->getUserId();
        $checkProductTypeDigital = $this->cartService->checkProductTypeDigital(cartId: $cartId);
        if($userId == 0 && $checkProductTypeDigital){
            return response()->json(['checkProductTypeForWalkingCustomer' =>true,'message'=> translate('To_order_digital_product').','.translate('_kindly_fill_up_the_“Add_New_Customer”_form').'.']);
        }
        if($request['type'] == 'wallet' && $userId != 0)
        {
            $customerBalance = $this->customerRepo->getFirstWhere(params:['id' => $userId]) ?? 0;
            if($customerBalance['wallet_balance'] >= $amount)
            {
                $this->createWalletTransaction(user_id: $userId, amount: floatval($amount), transaction_type: 'order_place', reference: 'order_place_in_pos');
            }else{
                Toastr::error(translate('need_Sufficient_Amount_Balance'));
                return response()->json();
            }
        }
        $cart = session($cartId);
        $orderId = 100000 + $this->orderRepo->getList()->count() + 1;
        $order = $this->orderRepo->getFirstWhere(params:['id'=>$orderId]);
        if ($order) {
            $orderId =$this->orderRepo->getList(orderBy:['id'=>'DESC'])->first()->id + 1;
        }
        foreach($cart as $item)
        {
            if(is_array($item))
            {
                $product = $this->productRepo->getFirstWhere(params:['id'=>$item['id']]);
                if($product)
                {
                    $tax = $this->getTaxAmount($item['price'], $product['tax']);
                    $price = $product['tax_model'] == 'include' ? $item['price']-$tax : $item['price'];
                    $orderDetail = $this->orderDetailsService->getPOSOrderDetailsData(
                        orderId:$orderId, item: $item,
                        product: $product,price:$price,tax: $tax
                    );
                    if ($item['variant'] != null) {
                        $variantData = $this->POSService->getVariantData(
                            type:$item['variant'],
                            variation: json_decode($product['variation'],true),
                            quantity: $item['quantity']
                        );
                        $this->productRepo->update(id: $product['id'],data: ['variation' => json_encode($variantData)]);
                    }

                    if($product['product_type'] == 'physical') {
                        $currentStock = $product['current_stock'] - $item['quantity'];
                        $this->productRepo->update(id: $product['id'],data: ['current_stock' =>$currentStock]);
                    }
                    $this->orderDetailRepo->add(data: $orderDetail);
                }
            }
        }
        $order = $this->orderService->getPOSOrderData(
            orderId: $orderId,
            cart: $cart,
            amount: $amount,
            paymentType: $request['type'],
            addedBy: 'admin',
            userId: $userId
        );
        $this->orderRepo->add(data: $order);
        if ($checkProductTypeDigital){
            $order = $this->orderRepo->getFirstWhere(params:['id'=>$orderId],relations: ['details.productAllStatus']);
            $data = [
                'userName'=>$order->customer->f_name,
                'userType' =>'customer',
                'templateName' =>'digital-product-download',
                'order' => $order,
                'subject' => translate('download_Digital_Product'),
                'title' => translate('Congratulations').'!',
                'emailId' => $order->customer['email'],
            ];
            event(new DigitalProductDownloadEvent(email: $order->customer['email'],data: $data));
        }
        session()->forget($cartId);
        session(['last_order' => $orderId]);
        $this->cartService->getNewCartId();
        Toastr::success(translate('order_placed_successfully'));
        return response()->json();
    }
    public function cancelOrder(Request $request):JsonResponse
    {
        session()->remove($request['cart_id']);
        $totalHoldOrders = $this->POSService->getTotalHoldOrders();
        $cartNames = $this->POSService->getCartNames();
        $cartItems = $this->getHoldOrderCalculationData(cartNames:$cartNames);
        return response()->json([
            'message' => $request['cart_id'].' '.translate('order_is_cancel'),
            'status' => 'success',
            'view' => view(POSOrder::CANCEL_ORDER[VIEW], compact('totalHoldOrders', 'cartItems'))->render(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getAllHoldOrdersView(Request $request):JsonResponse
    {
        $totalHoldOrders = $this->POSService->getTotalHoldOrders();
        $cartNames = $this->POSService->getCartNames();
        $cartItems = $this->getHoldOrderCalculationData(cartNames:$cartNames);
        if(!empty($request['customer'])){
            $searchValue = strtolower($request['customer']);
            $filteredItems = collect($cartItems)->filter(function ($item) use ($searchValue) {
                return str_contains(strtolower($item['customerName']), $searchValue) !== false;
            });
            $cartItems = $filteredItems->all();
        }
        return response()->json([
            'flag' => 'inactive',
            'totalHoldOrders'=>$totalHoldOrders,
            'view' => view(POSOrder::HOLD_ORDERS[VIEW],compact('totalHoldOrders','cartItems'))->render(),
        ]);
    }

    /**
     * @return array
     */
    protected function getCustomerDataFromSessionForPOS():array
    {
        if( Str::contains(session(SessionKey::CURRENT_USER), 'walking-customer'))
        {
            $currentCustomer = 'Walking Customer';
            $currentCustomerData =$this->customerRepo->getFirstWhere(params:['id'=>'0']);
        }else{
            $userId = explode('-',session(SessionKey::CURRENT_USER))[2];
            $currentCustomerData = $this->customerRepo->getFirstWhere(params:['id'=>$userId]);
            $currentCustomer = $currentCustomerData['f_name'].' '.$currentCustomerData['l_name']. ' (' .$currentCustomerData['phone'].')';
        }
        return [
            'currentCustomer' => $currentCustomer,
            'currentCustomerData' => $currentCustomerData
        ];
    }


    /**
     * @param array $cartNames
     * @return array
     */
    protected function getHoldOrderCalculationData(array $cartNames):array
    {
        $cartData = [];
        foreach ($cartNames as $cartName) {
            $customerCartData =$this->getCustomerCartData(cartName:$cartName);
            $CartItemData = $this->calculateCartItemsData(cartName: $cartName, customerCartData: $customerCartData);
            $cartData[$cartName] = array_merge($customerCartData[$cartName],$CartItemData);
        }
        return $cartData;
    }

    /**
     * @param string $cartName
     * @return array
     */
    protected function getCustomerCartData(string $cartName):array
    {
        $customerCartData = [];
        if (Str::contains($cartName, 'walking-customer')) {
            $currentCustomerInfo =  [
                'customerName'=>'Walking Customer',
                'customerPhone'=>"",
            ];
            $customerId = 0 ;
        } else {
            $customerId = explode('-', $cartName)[2];
            $currentCustomerData = $this->customerRepo->getFirstWhere(params: ['id' => $customerId]);
            $currentCustomerInfo = $this->cartService->getCustomerInfo(currentCustomerData:$currentCustomerData, customerId: $customerId);

        }
        $customerCartData[$cartName] = [
            'customerName' => $currentCustomerInfo['customerName'],
            'customerPhone' => $currentCustomerInfo['customerPhone'],
            'customerId'=> $customerId,
        ];
        return $customerCartData;
    }

    protected function calculateCartItemsData(string $cartName, array $customerCartData):array
    {
        $cartItemValue = [];
        $subTotalCalculation = [
            'countItem' => 0,
            'taxCalculate' => 0 ,
            'totalTaxShow' => 0,
            'totalTax' => 0,
            'subtotal' => 0,
            'discountOnProduct' => 0,
            'productSubtotal' => 0,
        ];
        if(session()->get($cartName)) {
            foreach (session()->get($cartName) as $cartItem) {
                if (is_array($cartItem)) {
                    $product = $this->productRepo->getFirstWhere(params: ['id' => $cartItem['id']]);
                    $subTotalCalculation = $this->cartService->getCartSubtotalCalculation(
                        product: $product,
                        cartItem: $cartItem,
                        calculation : $subTotalCalculation
                    );
                    if ($cartItem['customerId'] == $customerCartData[$cartName]['customerId']) {
                        $cartItem['productSubtotal'] = $subTotalCalculation['productSubtotal'];
                        $cartItemValue[] = $cartItem;
                        $subTotalCalculation['customerOnHold']=$cartItem['customerOnHold'];
                    }
                }
            }
        }
        $totalCalculation = $this->cartService->getTotalCalculation(
            subTotalCalculation:$subTotalCalculation,cartName: $cartName
        );
        return  [
            'countItem' => $subTotalCalculation['countItem'],
            'total' => $totalCalculation['total'],
            'subtotal' => $subTotalCalculation['subtotal'],
            'taxCalculate' => $subTotalCalculation['taxCalculate'],
            'totalTaxShow' => $subTotalCalculation['totalTaxShow'],
            'totalTax' => $subTotalCalculation['totalTax'],
            'discountOnProduct' => $subTotalCalculation['discountOnProduct'],
            'productSubtotal' => $subTotalCalculation['productSubtotal'],
            'cartItemValue' => $cartItemValue,
            'couponDiscount' => $totalCalculation['couponDiscount'],
            'extraDiscount' => $totalCalculation['extraDiscount'],
            'customerOnHold' => $subTotalCalculation['customerOnHold']??false,
        ];
    }

    protected function getCartData(string $cartName):array
    {
        $customerCartData =$this->getCustomerCartData(cartName:$cartName);
        $cartItemData = $this->calculateCartItemsData(cartName: $cartName,customerCartData:$customerCartData);
        return array_merge($customerCartData[$cartName], $cartItemData);
    }
}
