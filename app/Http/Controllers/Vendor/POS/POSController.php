<?php

namespace App\Http\Controllers\Vendor\POS;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\DeliveryZipCodeRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Enums\SessionKey;
use App\Enums\ViewPaths\Vendor\POS;
use App\Http\Controllers\BaseController;
use App\Models\DeliveryCountryCode;
use App\Services\CartService;
use App\Services\POSService;
use App\Traits\CalculatorTrait;
use App\Traits\CommonTrait;
use App\Traits\CustomerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class POSController extends BaseController
{
    use CalculatorTrait,CommonTrait,CustomerTrait;


    /**
     * @param VendorRepositoryInterface $vendorRepo
     * @param CategoryRepositoryInterface $categoryRepo
     * @param ProductRepositoryInterface $productRepo
     * @param CustomerRepositoryInterface $customerRepo
     * @param ShopRepositoryInterface $shopRepo
     * @param CouponRepositoryInterface $couponRepo
     * @param OrderRepositoryInterface $orderRepo
     * @param CartService $cartService
     * @param POSService $POSService
     */
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepo,
        private readonly CategoryRepositoryInterface $categoryRepo,
        private readonly ProductRepositoryInterface $productRepo,
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly ShopRepositoryInterface $shopRepo,
        private readonly CouponRepositoryInterface $couponRepo,
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly CartService $cartService,
        private readonly POSService $POSService,
        private readonly DeliveryZipCodeRepositoryInterface $deliveryZipCodeRepo,
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
        return $this->getPOSView(request:$request);
    }

    /**
     * @param object $request
     * @return View
     */
    public function getPOSView(object $request):View
    {
        $vendorId = auth('seller')->id();
        $vendor = $this->vendorRepo->getFirstWhere(params: ['id' => $vendorId]);
        $getPOSStatus = getWebConfig('seller_pos');
        if ($vendor['pos_status'] == 0 || $getPOSStatus == 0) {
            Toastr::warning(translate('access_denied!!'));
        }

        $shop = $this->shopRepo->getFirstWhere(params: ['id' => $vendorId]);
        $categoryId = $request['category_id'];
        $categories = $this->categoryRepo->getListWhere(orderBy: ['id'=>'desc'],filters: ['position'=>0]);
        $searchValue = $request['searchValue'] ?? null;
        $products = $this->productRepo->getListWhere(
            orderBy: ['id' => 'desc'],
            searchValue:$request['searchValue'],
            filters: [
                'added_by' => 'seller',
                'seller_id' => $vendorId,
                'category_id' => $categoryId,
                'status' => 1,
            ],
            dataLimit: getWebConfig('pagination_limit'),
        );
        $cartId = 'walking-customer-'.rand(10,1000);
        $this->cartService->getNewCartSession(cartId:$cartId);
        $customers = $this->customerRepo->getListWhereNotIn(ids:[0]);
        $getCurrentCustomerData = $this->getCustomerDataFromSessionForPOS();
        $summaryData = array_merge($this->POSService->getSummaryData(), $getCurrentCustomerData);
        $cartItems = $this->getCartData(cartName: session(SessionKey::CURRENT_USER));
        $order = $this->orderRepo->getFirstWhere(params: ['id'=>session(SessionKey::LAST_ORDER)]);
        $totalHoldOrder = $summaryData['totalHoldOrders'];
        $countries = getWebConfig(name: 'delivery_country_restriction') ? $this->get_delivery_country_array() : COUNTRIES;
        $zipCodes = getWebConfig(name: 'delivery_zip_code_area_restriction') ? $this->deliveryZipCodeRepo->getListWhere(dataLimit: 'all') : 0;
        return view(POS::INDEX[VIEW],compact(
            'categories',
            'categoryId',
            'products',
            'cartId',
            'customers',
            'shop',
            'searchValue',
            'summaryData',
            'cartItems',
            'order',
            'totalHoldOrder',
            'countries',
            'zipCodes'
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeCustomer(Request $request):JsonResponse
    {
        $cartId = ($request['user_id']!=0 ? 'saved-customer-'.$request['user_id']: 'walking-customer-'.rand(10,1000));
        $this->POSService->UpdateSessionWhenCustomerChange(cartId:$cartId);
        $getCurrentCustomerData = $this->getCustomerDataFromSessionForPOS();
        $summaryData = array_merge($this->POSService->getSummaryData(), $getCurrentCustomerData);
        $cartItems = $this->getCartData(cartName: $cartId);

        return response()->json([
            'view' => view(POS::SUMMARY[VIEW],compact('summaryData','cartItems'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDiscount(Request $request):JsonResponse
    {
        $cartId = session(SessionKey::CURRENT_USER);
        if ($request['type'] == 'percent' && ($request['discount'] < 0 ||  $request['discount'] > 100)) {
            $cartItems = $this->getCartData(cartName: $cartId);
            $text = $request['discount'] > 0 ? 'Extra_discount_can_not_be_less_than_0_percent' :
                'Extra_discount_can_not_be_more_than_100_percent';
            Toastr::error(translate($text));
            return response()->json([
                'extraDiscount' =>"amount_low",
                'view' => view(POS::CART[VIEW],compact('cartId','cartItems'))->render()
            ]);
        }
        $cart = session($cartId, collect());
        if($cart)
        {
            $totalProductPrice = 0;
            $productDiscount = 0;
            $productTax =0;
            $couponDiscount = $cart['coupon_discount']??0;
            $includeTax = 0;

            foreach($cart as $item)
            {
                if(is_array($item))
                {
                    $product = $this->productRepo->getFirstWhere(params:['id'=>$item['id']]);
                    $totalProductPrice += $item['price'] * $item['quantity'];
                    $productDiscount += $item['discount'] * $item['quantity'];
                    $productTax += $this->getTaxAmount($item['price'], $product['tax'])*$item['quantity'];
                    if($product['tax_model'] == 'include'){
                        $includeTax += $productTax;
                    }
                }
            }
            if ($request['type'] == 'percent') {
                $extraDiscount = (($totalProductPrice - $includeTax) / 100) * $request['discount'];
            } else {
                $extraDiscount = $request['discount'];
            }
            $total = $totalProductPrice - $productDiscount + $productTax - $couponDiscount - $extraDiscount - $includeTax;
            if($total < 0)
            {
                $cartItems = $this->getCartData(cartName: $cartId);
                return response()->json([
                    'extraDiscount' =>"amount_low",
                    'view' => view(POS::CART[VIEW],compact('cartId','cartItems'))->render()
                ]);
            }
            else{
                $cart['ext_discount'] = $request['type'] == 'percent' ? $request['discount']: currencyConverter(amount: $request['discount']);
                $cart['ext_discount_type'] = $request['type'];
                session()->put($cartId, $cart);
                $cartItems = $this->getCartData(cartName: $cartId);
                return response()->json([
                    'extraDiscount' =>"success",
                    'view' => view(POS::CART[VIEW],compact('cartId','cartItems'))->render()
                ]);
            }
        }else{
            $cartItems = $this->getCartData(cartName: $cartId);
            return response()->json([
                'extraDiscount' =>"empty",
                'view' => view(POS::CART[VIEW],compact('cartId','cartItems'))->render()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCouponDiscount(Request $request):JsonResponse
    {
        $cartId =session(SessionKey::CURRENT_USER);
        $userId = $this->cartService->getUserId();
        if($userId !=0)
        {
            $usedCoupon = $this->orderRepo->getListWhere(filters:['customer_type'=>'customer','coupon_code'=>$request['coupon_code']])->count();
            $coupon = $this->couponRepo->getFirstWhereFilters(
                filters:[
                    'code' => $request['coupon_code'],
                    'coupon_bearer'=>'seller',
                    'limit' => $usedCoupon,
                    'start_date' =>  now(),
                    'expire_date' => now(),
                    'status' => 1
                ]
            );

        }else{
            $coupon = $this->couponRepo->getFirstWhereFilters(
                filters:[
                    'code' => $request['coupon_code'],
                    'coupon_bearer'=> 'seller',
                    'start_date' =>  now(),
                    'expire_date' => now(),
                    'status' => 1
                ]
            );
        }
        if(!$coupon || $coupon['coupon_type'] == 'free_delivery' || $coupon['coupon_type'] == 'first_order') {
            $cartItems = $this->getCartData(cartName: $cartId);
            return response()->json([
                'coupon' => 'coupon_invalid',
                'view' => view(POS::CART[VIEW], compact('cartId','cartItems'))->render()
            ]);
        }

        $carts = session($cartId);
        $totalProductPrice = 0;
        $productDiscount = 0;
        $productTax =0;

        if(($coupon['seller_id'] =='0' || $coupon['seller_id'] ==auth('seller')->id()) && ($coupon['customer_id'] == '0' || $coupon['customer_id'] == $userId)) {
            if ($carts != null) {
                foreach ($carts as $cart) {
                    if (is_array($cart)) {
                        $product = $this->productRepo->getFirstWhere(params:['id'=>$cart['id']]);
                        $totalProductPrice += $cart['price'] * $cart['quantity'];
                        $productDiscount += $cart['discount'] * $cart['quantity'];
                        $productTax += ($this->getTaxAmount($cart['price'], $product['tax']))*$cart['quantity'];
                    }
                }
                if ($totalProductPrice >= $coupon['min_purchase']) {
                    $calculation = $this->POSService->getCouponCalculation(coupon:$coupon,totalProductPrice: $totalProductPrice,productDiscount:$productDiscount,productTax: $productTax );
                    $total = $calculation['total'];
                    $discount = $calculation['discount'];
                    if ($total < 0) {
                        $cartItems = $this->getCartData(cartName: $cartId);
                        return response()->json([
                            'coupon' => "amount_low",
                            'view' => view(POS::CART[VIEW], compact('cartId','cartItems'))->render()
                        ]);
                    }

                    $this->POSService->putCouponDataOnSession(
                        cartId:$cartId,
                        discount:$discount,
                        couponTitle:$coupon['title'],
                        couponBearer:$coupon['coupon_bearer'],
                        couponCode:$request['coupon_code']
                    );
                    $cartItems = $this->getCartData(cartName: $cartId);
                    return response()->json([
                        'coupon' => 'success',
                        'view' => view(POS::CART[VIEW], compact('cartId','cartItems'))->render()
                    ]);
                }
            } else {
                $cartItems = $this->getCartData(cartName: $cartId);
                return response()->json([
                    'coupon' => 'cart_empty',
                    'view' => view(POS::CART[VIEW], compact('cartId','cartItems'))->render()
                ]);
            }
        }
        $cartItems = $this->getCartData(cartName: $cartId);
        return response()->json([
            'coupon' =>'coupon_invalid',
            'view' => view(POS::CART[VIEW], compact('cartId','cartItems'))->render()
        ]);
    }
    public function getQuickView(Request $request):JsonResponse
    {
        $product = $this->productRepo->getFirstWhereWithCount(
            params:['id'=> $request['product_id']],
            withCount: ['reviews'],
            relations: ['brand','category','rating','tags'],
        );
        return response()->json([
            'success' => 1,
            'view' => view(POS::QUICK_VIEW[VIEW], compact('product'))->render(),
        ]);
    }

    /**
     * @return array
     */
    protected function getCustomerDataFromSessionForPOS(): array
    {
        if (Str::contains(session(SessionKey::CURRENT_USER), 'walking-customer')) {
            $currentCustomerInfo =  ['customerName'=>'Walking Customer' ];
            $currentCustomerData = $this->customerRepo->getFirstWhere(params: ['id' => '0']);
        } else {
            $userId = explode('-', session(SessionKey::CURRENT_USER))[2];
            $currentCustomerData = $this->customerRepo->getFirstWhere(params: ['id' => $userId]);
            $currentCustomerInfo = $this->cartService->getCustomerInfo(currentCustomerData:$currentCustomerData, customerId: $userId);

        }
        return [
            'currentCustomer' => $currentCustomerInfo['customerName'],
            'currentCustomerData' => $currentCustomerData
        ];
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

    public function getSearchedProductsView(Request $request):JsonResponse
    {
        $products = $this->productRepo->getListWithScope(
            filters: [
                'added_by' => 'seller',
                'seller_id' => auth('seller')->id(),
                'keywords' => $request['name'],
                'search_from' => 'pos',
                'status' => 1
            ],
            dataLimit: 'all'
        );

        $data = [
            'count' => $products->count(),
            'result' => view(POS::SEARCH[VIEW], compact('products'))->render()
        ];
        if ($products->count() > 0) {
            $data += ['id' => $products[0]->id];
        }

        return response()->json($data);
    }
}
