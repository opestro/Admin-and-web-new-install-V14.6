<?php

namespace App\Http\Controllers\Vendor\POS;

use App\Contracts\Repositories\ColorRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Enums\SessionKey;
use App\Enums\ViewPaths\Vendor\Cart;
use App\Enums\ViewPaths\Vendor\POS;
use App\Http\Controllers\BaseController;
use Brian2694\Toastr\Facades\Toastr;
use App\Services\CartService;
use App\Services\POSService;
use App\Traits\CalculatorTrait;
use App\Traits\CustomerTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CartController extends BaseController
{
    use CalculatorTrait;
    use CustomerTrait;

    /**
     * @param ProductRepositoryInterface $productRepo
     * @param ColorRepositoryInterface $colorRepo
     * @param CustomerRepositoryInterface $customerRepo
     * @param CartService $cartService
     * @param POSService $POSService
     */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepo,
        private readonly ColorRepositoryInterface $colorRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly CartService $cartService,
        private readonly POSService $POSService,
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
        // TODO: Implement index() method.
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getVariantPrice(Request $request):JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(params:['id'=>$request['id']]);
        $colorName = $this->colorRepo->getFirstWhere(['code'=>$request['color']])->name ?? null;
        $data = $this->cartService->getVariantData(
            request:$request,product: $product,colorName:$colorName
        );
        return response()->json($data);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuantity(Request $request):JsonResponse
    {
        $cartId = session(SessionKey::CURRENT_USER);
        if($request['quantity'] > 0){
            $product = $this->productRepo->getFirstWhere(params:['id'=>$request['key']]);
            $quantity = $this->cartService->getQuantityAndUpdateTime(request: $request,product:$product);
            $cartItems = $this->getCartData(cartName: $cartId);
            if($product['product_type'] =='physical' && $quantity < 0)
            {
                return response()->json([
                    'qty' => $quantity,
                    'productType' => $product['product_type'],
                    'view' => view(Cart::CART[VIEW],compact('cartId','cartItems'))->render()
                ]);
            }else{
                return response()->json([
                    'quantityUpdate'=>1,
                    'view' => view(Cart::CART[VIEW],compact('cartId','cartItems'))->render()
                ]);
            }
        }else{
            $cartItems = $this->getCartData(cartName: $cartId);
            return response()->json([
                'upQty'=>'zeroNegative',
                'view' => view(Cart::CART[VIEW],compact('cartId','cartItems'))->render()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addToCart(Request $request):JsonResponse
    {
        $cartId =session(SessionKey::CURRENT_USER);
        $product = $this->productRepo->getFirstWhere(params:['id'=>$request['id']]);
        $colorName = $this->colorRepo->getFirstWhere(['code'=>$request['color']])->name ?? null;
        $variations['color'] = $colorName;
        $variant = $this->cartService->makeVariation(
            request:$request,
            colorName: $colorName,
            choiceOptions: json_decode($product['choice_options'])
        );
        foreach (json_decode($product['choice_options']) as $choice) {
            $variations[$choice->title] = $request[$choice->name];
        }
        $discount = $this->getDiscountAmount(price: $product['unit_price'], discount: $product['discount'],discountType: $product['discount_type']);
        $price = $product['unit_price'] ;
        $cartData = session($cartId);
        if (session()->has($cartId) && count($cartData) > 0) {
            foreach ($cartData as $key => $cartItem) {
                if (is_array($cartItem) && $cartItem['id'] == $request['id'] && $cartItem['variant'] == $variant) {
                    if ($variant != null) {
                        $price = $this->cartService->getVariationPrice(variation: json_decode($product['variation']),variant: $variant);
                    }
                    $currentQty = $this->cartService->checkCurrentStock(variant: $variant,variation: json_decode($product['variation']),productQty: $product['current_stock'],quantity: $request['quantity_in_cart']);
                    if($product['product_type'] == 'physical' && $currentQty<0)
                    {
                        $cartItems = $this->getCartData(cartName: $cartId);
                        return response()->json([
                            'data' => 0,
                            'view' => view(Cart::CART[VIEW],compact('cartId','cartItems'))->render()
                        ]);
                    }
                    $cartItem = $this->cartService->addCartDataOnSession(
                        product: $product,
                        quantity: $request['quantity_in_cart'],
                        price: $price,
                        discount: $discount,
                        variant: $variant,
                        variations: $variations
                    );
                    unset($cartData[$key]);
                    $cartData[] = $cartItem;
                    session([$cartId => $cartData]);
                    $getCurrentCustomerData = $this->getCustomerDataFromSessionForPOS();
                    $summaryData = array_merge($this->POSService->getSummaryData(), $getCurrentCustomerData);
                    $cartItems = $this->getCartData(cartName: $cartId);
                    return response()->json([
                        'data' => 1,
                        'inCartData' => 1,
                        'requestQuantity' => $request['quantity_in_cart'],
                        'view' => view(Cart::SUMMARY[VIEW],compact('summaryData','cartItems'))->render(),
                    ]);
                }
            }
        }
        if ($variant != null) {
            $price = $this->cartService->getVariationPrice(variation: json_decode($product['variation']),variant: $variant);
        }
        $currentQty = $this->cartService->checkCurrentStock(variant: $variant,variation: json_decode($product['variation']),productQty: $product['current_stock'],quantity: $request['quantity']);
        if($product['product_type'] == 'physical' && $currentQty<0)
        {
            $cartItems = $this->getCartData(cartName: $cartId);
            return response()->json([
                'data' => 0,
                'view' => view(Cart::CART[VIEW],compact('cartId','cartItems'))->render()
            ]);
        }
        $sessionData = $this->cartService->addCartDataOnSession(
            product: $product,
            quantity: $request['quantity'],
            price: $price,
            discount: $discount,
            variant: $variant,
            variations: $variations
        );
        $cartItems = $this->getCartData(cartName: $cartId);
        return response()->json([
            'data' => $sessionData,
            'view' => view(Cart::CART[VIEW],compact('cartId','cartItems'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeCart(Request $request) :JsonResponse
    {
        $cartId = session(SessionKey::CURRENT_USER);
        $cart = session($cartId);
        $cartKeeper = [];
        if (session()->has($cartId) && count($cart) > 0) {
            foreach ($cart as $cartItem) {
                if (is_array($cartItem) ){
                    if ($cartItem['id'] != $request['id']) {
                        $cartKeeper[] = $cartItem;
                    }else{
                        if($cartItem['variant'] != $request['variant']){
                            $cartKeeper[] = $cartItem;
                        }
                    }
                }
            }
        }
        session()->put($cartId, $cartKeeper);
        $cartItems = $this->getCartData(cartName: $cartId);
        return response()->json(
            ['view' => view(Cart::CART[VIEW],compact('cartId','cartItems'))->render()]
        );
    }

    /**
     * @return RedirectResponse
     */
    public function clearSessionCartIds():RedirectResponse
    {
        session()->forget(SessionKey::CART_NAME);
        session()->forget(session(SessionKey::CURRENT_USER));
        session()->forget(SessionKey::CURRENT_USER);
        return redirect()->route(POS::INDEX[ROUTE]);
    }

    /**
     * @return JsonResponse
     */
    public function getCartIds():JsonResponse
    {
        $this->cartService->getCartKeeper();
        $getCurrentCustomerData = $this->getCustomerDataFromSessionForPOS();
        $summaryData = array_merge($this->POSService->getSummaryData(), $getCurrentCustomerData);
        $cartItems = $this->getCartData(cartName: session(SessionKey::CURRENT_USER));
        return response()->json([
            'view' => view(Cart::SUMMARY[VIEW],compact('summaryData','cartItems'))->render(),
        ]);
    }


    public function emptyCart():JsonResponse
    {
        $cartId =session(SessionKey::CURRENT_USER);
        session()->forget($cartId);
        $this->cartService->getNewCartSession(cartId:$cartId);
        $getCurrentCustomerData = $this->getCustomerDataFromSessionForPOS();
        $summaryData = array_merge($this->POSService->getSummaryData(), $getCurrentCustomerData);
        $cartItems = $this->getCartData(cartName: $cartId);
        return response()->json([
            'view' => view(Cart::SUMMARY[VIEW],compact('summaryData','cartItems'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeCart(Request $request):RedirectResponse
    {
        $this->cartService->customerOnHoldStatus(status: true);
        session()->put(SessionKey::CURRENT_USER, $request['cart_id']);
        $this->cartService->customerOnHoldStatus(status: false);
        Toastr::success($request['cart_id'].' '.translate('order_is_now_resumed'));
        return redirect()->route(POS::INDEX[ROUTE]);
    }

    /**
     * @return RedirectResponse
     */
    public function addNewCartId():RedirectResponse
    {

        $cart = session(session(SessionKey::CURRENT_USER));
        if (session()->has(session(SessionKey::CURRENT_USER)) && count($cart) > 0) {
            Toastr::success(translate('this_order_is_now_on_hold'));
        }
        $this->cartService->customerOnHoldStatus(status: true);
        $this->cartService->getNewCartId();
        return redirect()->route(POS::INDEX[ROUTE]);
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
     * @param string $cartName
     * @return array
     * @function getCustomerCartData ,used for process data
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
                        $subTotalCalculation['customerOnHold']=$cartItem['customerOnHold'];
                        $cartItemValue[] = $cartItem;
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
        return array_merge($customerCartData[$cartName],$cartItemData);
    }
}
