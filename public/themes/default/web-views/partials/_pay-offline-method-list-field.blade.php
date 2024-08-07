@if ($method)
        <?php
        $productPriceTotal = 0;
        $totalTax = 0;
        $totalShippingCost = 0;
        $orderWiseShippingDiscount = \App\Utils\CartManager::order_wise_shipping_discount();
        $totalDiscountOnProduct = 0;
        $cart = \App\Utils\CartManager::get_cart();
        $shippingCost = \App\Utils\CartManager::get_shipping_cost(type: 'checked');
        $getShippingCostSavedForFreeDelivery = \App\Utils\CartManager::get_shipping_cost_saved_for_free_delivery();
        $couponDiscount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        if ($cart->count() > 0) {
            foreach ($cart as $key => $cartItem) {
                $productPriceTotal += $cartItem['price'] * $cartItem['quantity'];
                $totalTax += $cartItem['tax_model'] == 'exclude' ? ($cartItem['tax'] * $cartItem['quantity']) : 0;
                $totalDiscountOnProduct += $cartItem['discount'] * $cartItem['quantity'];
            }

            if (session()->missing('coupon_type') || session('coupon_type') != 'free_delivery') {
                $totalShippingCost = $shippingCost - $getShippingCostSavedForFreeDelivery;
            } else {
                $totalShippingCost = $shippingCost;
            }

            $totalOfflineAmount = $productPriceTotal + $totalTax + $totalShippingCost - $couponDiscount - $totalDiscountOnProduct - $orderWiseShippingDiscount;
        }
        ?>

    <div class="payment-list-area">

        <div class="bg-primary-light rounded p-4 mt-4 mx-xl-5">
            <h6 class="text-capitalize">{{ $method['method_name'] }} {{translate('info')}}</h6>
            <div class="row g-2 fs-12">
                @foreach ($method['method_fields'] as $methodField)
                    <div class="col-xl-5 col-sm-6">
                        <div class="d-flex gap-2">
                            <span class="text-muted text-capitalize">{{ translate($methodField['input_name']) }}</span>
                            : <span class="text-dark">{{ translate($methodField['input_data']) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <h4 class="mt-4 font-weight-bold text-center">
            {{translate('amount')}} : {{ webCurrencyConverter(amount: $totalOfflineAmount) }}
        </h4>

        <div class="mx-xl-5">
            <div class="row">
                <input type="hidden" value="offline_payment" name="payment_method">
                <input type="hidden" value="{{ $method['id'] }}" name="method_id">
                    <?php
                    $count = count($method['method_informations']);
                    $count_status = $count % 2 == 1 ? 'odd' : 'even';
                    ?>
                @foreach ($method['method_informations'] as $key=> $information)
                    <div class="col-sm-{{$key == 0 && $count_status==="odd" ? 12 : 6}}">
                        <div class="form-group">
                            <label for="payment_by">{{ translate($information['customer_input']) }}
                                <span class="text-danger">{{ $information['is_required'] == 1?'*':''}}</span>
                            </label>
                            <input type="text" name="{{ $information['customer_input'] }}" class="form-control"
                                   placeholder="{{ translate($information['customer_placeholder']) }}" {{ $information['is_required'] == 1?'required':''}}>
                        </div>
                    </div>
                @endforeach

                <div class="col-12">
                    <div class="form-group">
                        <label for="account_no">{{translate('payment_note')}}</label>
                        <textarea class="form-control" name="payment_note" rows="4"
                                  placeholder="{{translate('insert_note')}}"></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{translate('close')}}
                        </button>
                        <button type="submit" class="btn btn--primary">
                            {{translate('submit')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
