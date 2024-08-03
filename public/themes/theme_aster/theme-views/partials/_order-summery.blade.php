@php
    use App\Utils\CartManager;
    use App\Utils\Helpers;
@endphp
<div class="col-lg-4">
    <div class="card text-dark sticky-top-80">
        <div class="card-body px-sm-4 d-flex flex-column gap-2">
            @php($current_url=request()->segment(count(request()->segments())))
            @php($shippingMethod=getWebConfig(name: 'shipping_method'))
            @php($product_price_total=0)
            @php($total_tax=0)
            @php($total_shipping_cost=0)
            @php($order_wise_shipping_discount=CartManager::order_wise_shipping_discount())
            @php($total_discount_on_product=0)
            @php($cart=CartManager::get_cart(type: 'checked'))
            @php($cartAll=CartManager::get_cart())
            @php($cart_group_ids=CartManager::get_cart_group_ids())
            @php($shipping_cost=CartManager::get_shipping_cost(type: 'checked'))
            @php($get_shipping_cost_saved_for_free_delivery=CartManager::get_shipping_cost_saved_for_free_delivery(type: 'checked'))
            @if($cart->count() > 0)
                @foreach($cart as $key => $cartItem)
                    @php($product_price_total+=$cartItem['price']*$cartItem['quantity'])
                    @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
                    @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
                @endforeach

                @if(session()->missing('coupon_type') || session('coupon_type') !='free_delivery')
                    @php($total_shipping_cost=$shipping_cost - $get_shipping_cost_saved_for_free_delivery)
                @else
                    @php($total_shipping_cost=$shipping_cost)
                @endif
            @endif

            @if($cartAll->count() > 0 && $cart->count() == 0)
                <span>{{ translate('Please_checked_items_before_proceeding_to_checkout') }}</span>
            @elseif($cartAll->count() == 0)
                <span>{{ translate('empty_cart') }}</span>
            @endif

            <div class="d-flex mb-3">
                <h5 class="text-capitalize">{{ translate('order_summary') }}</h5>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>{{ translate('item_price') }}</div>
                <div>{{Helpers::currency_converter($product_price_total)}}</div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="text-capitalize">{{ translate('product_discount') }}</div>
                <div>{{Helpers::currency_converter($total_discount_on_product)}}</div>
            </div>
            @php($coupon_discount = 0)
            @php($coupon_dis=0)
            @if(auth('customer')->check() && !session()->has('coupon_discount'))
                <form class="needs-validation" action="{{ route('coupon.apply') }}" method="post" id="submit-coupon-code">
                    @csrf
                    <div class="form-group my-3">
                        <label for="promo-code" class="fw-semibold">{{ translate('Promo_Code') }}</label>
                        <div class="form-control focus-border pe-1 rounded d-flex align-items-center">
                            <input type="text" name="code" id="promo-code"
                                   class="w-100 text-dark bg-transparent border-0 focus-input"
                                   placeholder="{{ translate('write_coupon_code_here') }}" required>
                            <button class="btn btn-primary text-nowrap" id="coupon-code-apply">{{ translate('apply') }}</button>
                        </div>
                    </div>
                </form>
            @endif

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>{{ translate('sub_total') }}</div>
                <div>{{Helpers::currency_converter($product_price_total - $total_discount_on_product)}}</div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>{{ translate('tax') }}</div>
                <div>{{Helpers::currency_converter($total_tax)}}</div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>{{ translate('shipping') }}</div>
                <div class="text-primary">{{Helpers::currency_converter($total_shipping_cost)}}</div>
            </div>

            @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
            @php($coupon_dis=session()->has('coupon_discount')?session('coupon_discount'):0)
            @if(auth('customer')->check() && session()->has('coupon_discount'))
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>{{ translate('coupon_discount') }}</div>
                    <div class="text-primary">
                         {{'-'.Helpers::currency_converter($coupon_discount+$order_wise_shipping_discount)}}</div>
                </div>
            @endif
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4>{{ translate('total') }}</h4>
                <h2 class="text-primary">{{Helpers::currency_converter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</h2>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4">
                <a href="{{ route('home') }}" class="btn-link text-primary text-capitalize"><i
                        class="bi bi-chevron-double-left fs-10"></i> {{ translate('continue_shopping') }}</a>
                <button
                    class="btn btn-primary text-capitalize {{ str_contains(request()->url(), 'checkout-payment') ? 'd-none':''}}"
                    id="proceed-to-next-action" data-goto-checkout="{{route('customer.choose-shipping-address-other')}}"
                    data-checkout-payment="{{ route('checkout-payment') }}"
                    {{ (isset($product_null_status) && $product_null_status == 1) ? 'disabled':''}}
                    type="button">{{translate('proceed_to_next')}}</button>
            </div>
        </div>
    </div>
</div>
