@php use App\Utils\CartManager;use App\Utils\Helpers; @endphp
@if ($method)
    @php($product_price_total=0)
    @php($total_tax=0)
    @php($total_shipping_cost=0)
    @php($order_wise_shipping_discount=CartManager::order_wise_shipping_discount())
    @php($total_discount_on_product=0)
    @php($cart=CartManager::get_cart(type: 'checked'))
    @php($cart_group_ids=CartManager::get_cart_group_ids())
    @php($shipping_cost=CartManager::get_shipping_cost(type: 'checked'))
    @php($get_shipping_cost_saved_for_free_delivery=CartManager::get_shipping_cost_saved_for_free_delivery(type: 'checked'))
    @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
    @php($coupon_dis=session()->has('coupon_discount')?session('coupon_discount'):0)
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
    @else
        <span>{{ translate('empty_cart') }}</span>
    @endif
    @php($total_offline_amount = $product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)

    <div class="payment-list-area">
        <div class="p-3 my-3 rounded --bg-light-sky-blue">
            <h6 class="pb-2" style="color: {{$web_config['primary_color']}};">{{ $method['method_name'] }}</h6>
            <input type="hidden" value="offline_payment" name="payment_method">
            <input type="hidden" value="{{ $method['id'] }}" name="method_id">

            <div class="row">
                @foreach ($method['method_fields'] as $method_field)
                    <div class="col-6 pb-2">
                        <span>{{ translate($method_field['input_name']).' '.':'}}</span>
                        <span>{{ $method_field['input_data'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <h4 class="text-center py-3 fw-6 font-weight--600">
            {{ translate('amount').' '.':' }} {{ Helpers::currency_converter($total_offline_amount) }}
        </h4>

        <div class="row">
            @foreach ($method['method_informations'] as $information)
                <div class="col-md-12 col-lg-6 mb-3">
                    <label style="font-weight: 600;">
                        {{ translate($information['customer_input']) }}
                        <span class="text-danger">{{ $information['is_required'] == 1?'*':''}}</span>
                    </label>
                    <input type="text" class="form-control" name="{{ $information['customer_input'] }}"
                           placeholder="{{ translate($information['customer_placeholder']) }}" {{ $information['is_required'] == 1?'required':''}}>
                </div>
            @endforeach

            <div class="col-12 mb-3">
                <label class="font-weight--600">{{translate('payment_note')}}</label>
                <textarea name="payment_note" id="" class="form-control"
                          placeholder="{{translate('payment_note')}}"></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-10 justify-content-end pt-4">
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">{{translate('close')}}</button>
        <button type="submit" class="btn btn-sm btn-primary">{{translate('submit')}}</button>
    </div>

@else
    <div class="text-center py-5">
        <img class="pt-5" src="{{ theme_asset('assets/img/offline-payments-vectors.png') }}" alt="">
        <p class="py-2 pb-5 text-muted">{{ translate('select_a_payment_method first') }}</p>
    </div>
@endif
