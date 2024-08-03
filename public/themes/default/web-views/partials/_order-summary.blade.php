<aside class="col-lg-4 pt-4 pt-lg-2 px-max-md-0 order-summery-aside">
    <div class="__cart-total __cart-total_sticky">
        <div class="cart_total p-0">
            @php($shippingMethod=getWebConfig(name: 'shipping_method'))
            @php($subTotal=0)
            @php($totalTax=0)
            @php($totalShippingCost=0)
            @php($orderWiseShippingDiscount=\App\Utils\CartManager::order_wise_shipping_discount())
            @php($totalDiscountOnProduct=0)
            @php($cart=\App\Utils\CartManager::get_cart(type: 'checked'))
            @php($cartGroupIds=\App\Utils\CartManager::get_cart_group_ids())
            @php($getShippingCost=\App\Utils\CartManager::get_shipping_cost(type: 'checked'))
            @php($getShippingCostSavedForFreeDelivery=\App\Utils\CartManager::get_shipping_cost_saved_for_free_delivery(type: 'checked'))
            @if($cart->count() > 0)
                @foreach($cart as $key => $cartItem)
                    @php($subTotal+=$cartItem['price']*$cartItem['quantity'])
                    @php($totalTax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
                    @php($totalDiscountOnProduct+=$cartItem['discount']*$cartItem['quantity'])
                @endforeach

                @if(session()->missing('coupon_type') || session('coupon_type') !='free_delivery')
                    @php($totalShippingCost=$getShippingCost - $getShippingCostSavedForFreeDelivery)
                @else
                    @php($totalShippingCost=$getShippingCost)
                @endif
            @endif

            @php($totalSavedAmount = $totalDiscountOnProduct)

            @if(session()->has('coupon_discount') && session('coupon_discount') > 0 && session('coupon_type') !='free_delivery')
                @php($totalSavedAmount += session('coupon_discount'))
            @endif

            @if($getShippingCostSavedForFreeDelivery > 0)
                @php($totalSavedAmount += $getShippingCostSavedForFreeDelivery)
            @endif

            @if($totalSavedAmount > 0)
                <h6 class="text-center text-primary mb-4 d-flex align-items-center justify-content-center gap-2">
                    <img src="{{theme_asset(path: 'public/assets/front-end/img/icons/offer.svg')}}" alt="">
                    {{translate('you_have_Saved')}}
                    <strong>{{ webCurrencyConverter(amount: $totalSavedAmount) }}!</strong>
                </h6>
            @endif

            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('sub_total')}}</span>
                <span class="cart_value">
                    {{ webCurrencyConverter(amount: $subTotal) }}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('tax')}}</span>
                <span class="cart_value">
                    {{ webCurrencyConverter(amount: $totalTax) }}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('shipping')}}</span>
                <span class="cart_value">
                    {{ webCurrencyConverter(amount: $totalShippingCost) }}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('discount_on_product')}}</span>
                <span class="cart_value">
                    - {{ webCurrencyConverter(amount: $totalDiscountOnProduct) }}
                </span>
            </div>
            @php($coupon_dis=0)
            @if(auth('customer')->check())

                @if(session()->has('coupon_discount'))
                    @php($couponDiscount = session()->has('coupon_discount')?session('coupon_discount'):0)

                    <div class="d-flex justify-content-between">
                            <span class="cart_title">{{translate('coupon_discount')}}</span>
                            <span class="cart_value">
                        - {{ webCurrencyConverter(amount: $couponDiscount) }}
                    </span>
                        </div>

                    <div class="pt-2">
                        <div class="d-flex align-items-center form-control rounded-pill pl-3 p-1">
                            <img width="24" src="{{asset('public/assets/front-end/img/icons/coupon.svg')}}" alt="">
                            <div class="px-2 d-flex justify-content-between w-100">
                                <div>
                                    {{ session('coupon_code') }}
                                    <span class="text-primary small">( -{{ webCurrencyConverter(amount: $couponDiscount) }} )</span>
                                </div>
                                <div class="bg-transparent text-danger cursor-pointer px-2 get-view-by-onclick" data-link="{{ route('coupon.remove') }}">x</div>
                            </div>
                        </div>
                    </div>
                    @php($coupon_dis=session('coupon_discount'))
                @else
                    <div class="pt-2">
                        <form class="needs-validation coupon-code-form" action="javascript:" method="post" novalidate
                              id="coupon-code-ajax">
                            <div class="d-flex form-control rounded-pill ps-3 p-1">
                                <img width="24" src="{{theme_asset(path: 'public/assets/front-end/img/icons/coupon.svg')}}" alt="">
                                <input class="input_code border-0 px-2 text-dark bg-transparent outline-0 w-100"
                                       type="text" name="code" placeholder="{{translate('coupon_code')}}" required>
                                <button class="btn btn--primary rounded-pill text-uppercase py-1 fs-12" type="button" id="apply-coupon-code">
                                        {{translate('apply')}}
                                    </button>
                            </div>
                            <div class="invalid-feedback">{{translate('please_provide_coupon_code')}}</div>
                        </form>
                    </div>
                    @php($coupon_dis=0)
                @endif
            @endif
            <hr class="my-2">
            <div class="d-flex justify-content-between">
                <span class="cart_title text-primary font-weight-bold">{{translate('total')}}</span>
                <span class="cart_value">
                {{ webCurrencyConverter(amount: $subTotal+$totalTax+$totalShippingCost-$coupon_dis-$totalDiscountOnProduct-$orderWiseShippingDiscount) }}
                </span>
            </div>
        </div>
        @php($company_reliability = getWebConfig(name: 'company_reliability'))
        @if($company_reliability != null)
            <div class="mt-5">
                <div class="row justify-content-center g-4">
                    @foreach ($company_reliability as $key=>$value)
                        @if ($value['status'] == 1 && !empty($value['title']))
                            <div class="col-sm-3 px-0 text-center mobile-padding">
                                <img class="order-summery-footer-image" alt=""
                                     src="{{ getValidImage(path: 'storage/app/public/company-reliability/'.$value['image'], type: 'source', source: theme_asset(path: 'public/assets/front-end/img').'/'.$value['item'].'.png') }}">
                                <div class="deal-title">{{translate($value['title'])}}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-4">
            <a class="btn btn--primary btn-block proceed_to_next_button {{$cart->count() <= 0 ? 'disabled' : ''}} action-checkout-function">{{translate('proceed_to_Checkout')}}</a>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <a href="{{route('home')}}" class="d-flex align-items-center gap-2 text-primary font-weight-bold">
                <i class="tio-back-ui fs-12"></i> {{translate('continue_Shopping')}}
            </a>
        </div>

    </div>
</aside>

<div class="bottom-sticky3 bg-white p-3 shadow-sm w-100 d-lg-none">
    <div class="d-flex justify-content-center align-items-center fs-14 mb-2">
        <div class="product-description-label fw-semibold text-capitalize">{{translate('total_price')}} :</div>
        &nbsp; <strong
                class="text-base">{{ webCurrencyConverter(amount: $subTotal+$totalTax+$totalShippingCost-$coupon_dis-$totalDiscountOnProduct-$orderWiseShippingDiscount) }}</strong>
    </div>
    <a data-route="{{ Route::currentRouteName() }}"
       class="btn btn--primary btn-block proceed_to_next_button text-capitalize {{$cart->count() <= 0 ? 'disabled' : ''}} action-checkout-function">{{translate('proceed_to_next')}}</a>
</div>

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            orderSummaryStickyFunction()
        });
    </script>
@endpush
