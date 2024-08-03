<div class="navbar-tool dropdown me-2 {{Session::get('direction') === "rtl" ? 'mr-md-3' : 'ml-md-3'}}">
    @if($web_config['guest_checkout_status'] || auth('customer')->check())
        <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{route('shop-cart')}}">
            <span class="navbar-tool-label">
                @php($cart=\App\Utils\CartManager::get_cart())
                {{$cart->count()}}
            </span>
            <i class="navbar-tool-icon czi-cart"></i>
        </a>
        <a class="navbar-tool-text ms-2"
           href="{{route('shop-cart')}}"><small>{{translate('my_cart')}}</small>
            <span class="cart-total-price font-bold fs-14">
                {{ webCurrencyConverter(amount: \App\Utils\CartManager::cart_total_applied_discount(\App\Utils\CartManager::get_cart()))}}
            </span>
        </a>
    @else
        <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{ route('customer.auth.login') }}">
            <span class="navbar-tool-label">
                @php($cart=\App\Utils\CartManager::get_cart())
                {{$cart->count()}}
            </span>
            <i class="navbar-tool-icon czi-cart"></i>
        </a>
        <a class="navbar-tool-text ms-2"
           href="{{ route('customer.auth.login') }}">
            <small>{{translate('my_cart')}}</small>
            <span class="cart-total-price font-bold fs-14">
                {{ webCurrencyConverter(amount: \App\Utils\CartManager::cart_total_applied_discount(\App\Utils\CartManager::get_cart()))}}
            </span>
        </a>
    @endif

    <div
        class="dropdown-menu dropdown-menu-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} __w-20rem cart-dropdown py-0">
        <div class="widget widget-cart px-3 pt-2 pb-3">
            <div class="widget-cart-top rounded">
                <h6 class="m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M3.03986 2.29234C2.85209 2.22644 2.64582 2.23782 2.46644 2.324C2.28707 2.41017 2.14927 2.56407 2.08336 2.75184C2.01745 2.93962 2.02884 3.14588 2.11501 3.32526C2.20119 3.50464 2.35509 3.64244 2.54286 3.70834L2.80386 3.79934C3.47186 4.03434 3.91086 4.18934 4.23386 4.34834C4.53686 4.49734 4.66986 4.61834 4.75786 4.74634C4.84786 4.87834 4.91786 5.06034 4.95786 5.42334C4.99786 5.80334 4.99986 6.29834 4.99986 7.03834V9.64034C4.99986 12.5823 5.06286 13.5523 5.92986 14.4663C6.79586 15.3803 8.18986 15.3803 10.9799 15.3803H16.2819C17.8429 15.3803 18.6239 15.3803 19.1749 14.9303C19.7269 14.4803 19.8849 13.7163 20.1999 12.1883L20.6999 9.76334C21.0469 8.02334 21.2199 7.15434 20.7759 6.57734C20.3319 6.00034 18.8159 6.00034 17.1309 6.00034H6.49186C6.4876 5.75386 6.47326 5.50765 6.44886 5.26234C6.39486 4.76534 6.27886 4.31234 5.99686 3.90034C5.71286 3.48434 5.33486 3.21834 4.89386 3.00134C4.48186 2.79934 3.95786 2.61534 3.34186 2.39834L3.03986 2.29234ZM12.9999 8.25034C13.1988 8.25034 13.3895 8.32936 13.5302 8.47001C13.6708 8.61067 13.7499 8.80143 13.7499 9.00034V10.2503H14.9999C15.1988 10.2503 15.3895 10.3294 15.5302 10.47C15.6708 10.6107 15.7499 10.8014 15.7499 11.0003C15.7499 11.1993 15.6708 11.39 15.5302 11.5307C15.3895 11.6713 15.1988 11.7503 14.9999 11.7503H13.7499V13.0003C13.7499 13.1993 13.6708 13.39 13.5302 13.5307C13.3895 13.6713 13.1988 13.7503 12.9999 13.7503C12.8009 13.7503 12.6102 13.6713 12.4695 13.5307C12.3289 13.39 12.2499 13.1993 12.2499 13.0003V11.7503H10.9999C10.8009 11.7503 10.6102 11.6713 10.4695 11.5307C10.3289 11.39 10.2499 11.1993 10.2499 11.0003C10.2499 10.8014 10.3289 10.6107 10.4695 10.47C10.6102 10.3294 10.8009 10.2503 10.9999 10.2503H12.2499V9.00034C12.2499 8.80143 12.3289 8.61067 12.4695 8.47001C12.6102 8.32936 12.8009 8.25034 12.9999 8.25034Z"
                              fill="#1455AC"/>
                        <path
                            d="M7.5 18C7.89782 18 8.27936 18.158 8.56066 18.4393C8.84196 18.7206 9 19.1022 9 19.5C9 19.8978 8.84196 20.2794 8.56066 20.5607C8.27936 20.842 7.89782 21 7.5 21C7.10218 21 6.72064 20.842 6.43934 20.5607C6.15804 20.2794 6 19.8978 6 19.5C6 19.1022 6.15804 18.7206 6.43934 18.4393C6.72064 18.158 7.10218 18 7.5 18ZM16.5 18C16.8978 18 17.2794 18.158 17.5607 18.4393C17.842 18.7206 18 19.1022 18 19.5C18 19.8978 17.842 20.2794 17.5607 20.5607C17.2794 20.842 16.8978 21 16.5 21C16.1022 21 15.7206 20.842 15.4393 20.5607C15.158 20.2794 15 19.8978 15 19.5C15 19.1022 15.158 18.7206 15.4393 18.4393C15.7206 18.158 16.1022 18 16.5 18Z"
                            fill="#1455AC"/>
                    </svg>
                    <span class="text-capitalize">
                        {{translate('shopping_cart')}}
                    </span>
                </h6>
            </div>
            @if($cart->count() > 0)

                <?php
                    $getShippingCostSavedForFreeDelivery=\App\Utils\CartManager::get_shipping_cost_saved_for_free_delivery();
                    $totalDiscountOnProduct = 0;
                    foreach ($cart as $cartItem) {
                        $totalDiscountOnProduct += $cartItem->discount * $cartItem->quantity;
                    }

                    $totalSavedAmount = $totalDiscountOnProduct;
                    if(session()->has('coupon_discount') && session('coupon_discount') > 0 && session('coupon_type') !='free_delivery') {
                        $totalSavedAmount += session('coupon_discount');
                    }
                    if($getShippingCostSavedForFreeDelivery > 0) {
                        $totalSavedAmount += $getShippingCostSavedForFreeDelivery;
                    }
                ?>

                <div class="dropdown-saved-amount text-center  align-items-center justify-content-center text-accent mb-3 {{$totalSavedAmount <= 0 ? 'd-none' : 'd-flex'}}">
                    <img src="{{theme_asset(path: 'public/assets/front-end/img/party-popper.svg')}}" class="mr-2" alt="">
                    <small>{{translate('you_have_saved')}} <span
                            class="total_discount">{{ webCurrencyConverter(amount: $totalSavedAmount)}}</span>!</small>
                </div>
                <div class="__h-20rem" data-simplebar data-simplebar-auto-hide="false">
                    @php($sub_total=0)
                    @php($total_tax=0)
                    @foreach($cart as  $cartItem)
                        @php($product=\App\Models\Product::find($cartItem['product_id']))

                        <?php
                            $getProductCurrentStock = $product->current_stock;
                            if(!empty($product->variation)) {
                                foreach(json_decode($product->variation, true) as $productVariantSingle) {
                                    if($productVariantSingle['type'] == $cartItem->variant) {
                                        $getProductCurrentStock = $productVariantSingle['qty'];
                                    }
                                }
                            }
                        ?>

                        <div class="widget-cart-item">
                            <div class="media">
                                <a class="d-block me-2 position-relative overflow-hidden"
                                   href="{{route('product',$cartItem['slug'])}}">
                                    <img width="64" class="{{ $product ? ($product->status == 0?'blur-section':'') : 'blur-section' }}"
                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$cartItem['thumbnail'], type: 'backend-product') }}"
                                         alt="{{ translate('product') }}"/>
                                    @if (!$product || $product->status == 0)
                                        <span class="temporary-closed position-absolute text-center p-2">
                                            <span>{{ translate('N/A') }}</span>
                                        </span>
                                    @endif
                                </a>
                                <div
                                    class="media-body min-height-0 d-flex align-items-center {{ $product ? ($product->status == 0?'blur-section':'') : 'blur-section' }}">
                                    <div class="w-0 flex-grow-1">
                                        <h6 class="widget-product-title mb-0 mr-2">
                                            <a href="{{route('product',$cartItem['slug'])}}" class="line--limit-1">
                                                {{$cartItem['name']}}
                                            </a>
                                        </h6>
                                        @if(!empty($cartItem['variant']))
                                            <div>
                                                <span
                                                    class="__text-12px">{{translate('variant')}} : {{$cartItem['variant']}}</span>
                                            </div>
                                        @endif
                                        <div class="widget-product-meta">
                                            <span
                                                class="text-muted me-2">x <span
                                                    class="cart_quantity_multiply{{$cartItem['id']}}">{{$cartItem['quantity']}}</span></span>
                                            <span
                                                class="text-accent me-2 discount_price_of_{{$cartItem['id']}}">
                                                    {{ webCurrencyConverter(amount: ($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])}}
                                            </span>
                                        </div>
                                    </div>
                                    @if( isset($product->status) && $product->status == 1)
                                        <div class="__quantity">
                                            <div class="quantity__minus cart-qty-btn action-update-cart-quantity"
                                                data-cart-id="{{ $cartItem['id'] }}"
                                                data-product-id="{{ $cartItem['product_id'] }}"
                                                data-action="-1"
                                                data-event="minus"
                                                >
                                                @if($getProductCurrentStock < $cartItem['quantity'] || $cartItem['quantity'] == (isset($product->minimum_order_qty) ? $product->minimum_order_qty : 1))
                                                    <i class="tio-delete-outlined text-danger fs-10"></i>
                                                @else
                                                    <i class="tio-remove fs-10"></i>
                                                @endif

                                            </div>
                                            <input type="text"
                                                class="quantity__qty cart-qty-input form-control p-0 text-center cartQuantity{{$cartItem['id']}} action-update-cart-quantity"
                                                value="{{$cartItem['quantity']}}" name="quantity"
                                                id="cartQuantity{{$cartItem['id']}}"
                                                data-cart-id="{{ $cartItem['id'] }}"
                                                data-product-id="{{ $cartItem['product_id'] }}"
                                                data-action="0"
                                                data-event=""
                                               data-current-stock="{{ $getProductCurrentStock }}"
                                                data-min="{{ isset($product->minimum_order_qty) ? $product->minimum_order_qty : 1 }}"
                                                autocomplete="off" required
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <div class="quantity__plus cart-qty-btn action-update-cart-quantity"
                                                data-cart-id="{{ $cartItem['id'] }}"
                                                data-product-id="{{ $cartItem['product_id'] }}"
                                                data-action="1"
                                                data-event=""
                                                >
                                                <i class="tio-add"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="__quantity mr-29 mb-4">
                                            <div class="quantity__minus cart-qty-btn form-control action-update-cart-quantity"
                                                data-cart-id="{{ $cartItem['id'] }}"
                                                data-product-id="{{ $cartItem['product_id'] }}"
                                                data-action="-1"
                                                data-event="minus"
                                                >
                                                <i class="tio-delete-outlined text-danger fs-10"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @php($sub_total+=($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])
                        @php($total_tax+=$cartItem['tax']*$cartItem['quantity'])
                    @endforeach
                </div>
                @php($free_delivery_status = \App\Utils\OrderManager::free_delivery_order_amount($cart[0]->cart_group_id))
                @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                    <div class="py-3">
                        <img src="{{theme_asset(path: 'public/assets/front-end/img/truck.svg')}}" alt="">
                        <span
                            class="amount_fullfill text-accent __text-12px {{$free_delivery_status['amount_need'] <= 0 ? '' :'d-none'}}">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                        <small
                            class="amount_need_to_fullfill {{$free_delivery_status['amount_need'] <= 0 ? 'd-none' :''}}"><span
                                class="text-accent __text-12px free_delivery_amount_need">{{ webCurrencyConverter(amount: $free_delivery_status['amount_need']) }}</span> {{ translate('add_more_for_free_delivery') }}
                        </small>
                        <div class="progress __progress bg-DFEDFF">
                            <div class="progress-bar"
                                 style="width: {{$free_delivery_status['percentage']}}%; background:var(--primary-clr)"></div>
                        </div>
                    </div>
                @endif
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-2">
                    <div
                        class="font-size-sm {{Session::get('direction') === "rtl" ? 'ml-2 float-left' : 'mr-2 float-right'}} py-2 ">
                        <span>{{translate('subtotal')}} :</span>
                        <span
                            class="text-accent font-size-base cart_total_amount {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                {{ webCurrencyConverter(amount: $sub_total) }}
                        </span>
                    </div>

                    @if($web_config['guest_checkout_status'] || auth('customer')->check())
                        <a class="btn btn-outline-secondary btn-sm" href="{{route('shop-cart')}}">
                            {{translate('expand_cart')}}<i
                                class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                        </a>
                    @else
                        <a class="btn btn-outline-secondary btn-sm" href="{{route('customer.auth.login')}}">
                            {{translate('expand_cart')}}<i
                                class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                        </a>
                    @endif
                </div>

                @if($web_config['guest_checkout_status'] || auth('customer')->check())
                    <a class="btn btn--primary btn-sm btn-block font-bold rounded text-capitalize"
                       href="{{route('checkout-details')}}">
                        {{translate('proceed_to_checkout')}}
                    </a>
                @else
                    <a class="btn btn--primary btn-sm btn-block font-bold rounded text-capitalize"
                       href="{{route('customer.auth.login')}}">
                        {{translate('proceed_to_checkout')}}
                    </a>
                @endif

            @else
                <div class="widget-cart-item">
                    <div class="text-center text-capitalize">
                        <img class="mb-3 mw-100" src="{{theme_asset(path: 'public/assets/front-end/img/icons/empty-cart.svg')}}"
                             alt="{{ translate('cart') }}">
                        <p class="text-capitalize">{{ translate('Your_Cart_is_Empty') }}!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
