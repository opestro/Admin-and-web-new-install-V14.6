@php
    use App\Models\Product;
    use App\Utils\CartManager;
    use App\Utils\Helpers;
    use App\Utils\ProductManager;
@endphp
<div class="dropdown">
    <a href="{{route('shop-cart')}}" class="position-relative" data-bs-toggle="dropdown" data-bs-auto-close="outside"
       aria-expanded="false">
        @php($cart=CartManager::get_cart())
        <i class="bi bi-bag fs-18"></i>
        <span class="count">{{$cart->count()}}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end scrollY-60 bs-dropdown-min-width--22-5rem">
        @if($cart->count() > 0)
            @php($sub_total=0)
            @php($total_tax=0)
            @foreach($cart as  $cartItem)
                @php($product=Product::find($cartItem['product_id']))
                <li>
                    <div class="media gap-3">
                        <div class="avatar avatar-xxl position-relative overflow-hidden rounded">
                            <img loading="lazy" alt="Product"
                                src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$cartItem['thumbnail'], type: 'product') }}"
                                class="img-fit dark-support rounded img-fluid overflow-hidden {{ $product && $product->status == 0?'blur-section':'' }}">
                            @if ($product && $product->status == 0)
                                <span class="temporary-closed position-absolute text-center p-2">
                                    <span class="text-capitalize">{{ translate('not_available') }}</span>
                                </span>
                            @endif
                        </div>
                        <div class="media-body">
                            <h6 class="mb-2 {{ $product && $product->status == 0 ? 'blur-section':'' }}">
                                <a href="{{ $product && $product->status == 1 ? route('product',$cartItem['slug']) : 'javascript:'}}">{{Str::limit($cartItem['name'],30)}}</a>
                            </h6>
                            <div class="d-flex gap-3 justify-content-between align-items-end">
                                <div class="d-flex flex-column gap-1 {{ $product && $product->status == 0?'blur-section':'' }}">
                                    <div class="fs-12"><span
                                            class="cart_quantity_{{ $cartItem['id'] }}">{{$cartItem['quantity']}}</span>
                                        {{'×'.Helpers::currency_converter(($cartItem['price']-$cartItem['discount']))}}
                                    </div>
                                    <div class="product__price d-flex flex-wrap gap-2">
                                        @if($cartItem['discount'] >0)
                                            <del class="product__old-price quantity_price_of_{{ $cartItem['id'] }}">{{Helpers::currency_converter($cartItem['price']*$cartItem['quantity'])}}</del>
                                        @endif
                                        <ins class="product__new-price discount_price_of_{{ $cartItem['id'] }}">{{Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])}}</ins>
                                    </div>
                                </div>
                                <div class="quantity">
                                    @if ($product && $product->status == 1)
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
                                        <span
                                            class="quantity__minus cart-quantity-update cart_quantity__minus{{ $cartItem['id'] }}"
                                            data-cart-id="{{ $cartItem['id'] }}"
                                            data-product-id="{{ $cartItem['product_id'] }}"
                                            data-value=-1
                                            data-event="minus"
                                            data-prevent="true">

                                            @if($getProductCurrentStock < $cartItem['quantity'] || $cartItem['quantity'] == ($product ? $product->minimum_order_qty : 1))
                                                <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                            @else
                                                <i class="bi bi-dash"></i>
                                            @endif

                                        </span>
                                        <input type="text"
                                               class="quantity__qty cart-quantity-update-input cart_quantity_of_{{ $cartItem['id'] }}"
                                               data-min="{{ $product?->minimum_order_qty ?? 1 }}"
                                               value="{{$cartItem['quantity']}}"
                                               data-cart-id="{{ $cartItem['id'] }}"
                                               data-product-id="{{ $cartItem['product_id'] }}"
                                               data-value=0
                                               data-current-stock="{{ $getProductCurrentStock }}"
                                               data-prevent="true">
                                        <span class="quantity__plus cart-quantity-update"
                                              data-cart-id="{{ $cartItem['id'] }}"
                                              data-product-id="{{ $cartItem['product_id'] }}"
                                              data-value=1
                                              data-event="plus"
                                              data-prevent="true">
                                            <i class="bi bi-plus"></i>
                                        </span>
                                    @else
                                        <span
                                            class="quantity__minus cart-quantity-update cart_quantity__minus{{ $cartItem['id'] }}"
                                            data-cart-id="{{ $cartItem['id'] }}"
                                            data-product-id="{{ $cartItem['product_id'] }}"
                                            data-value="-{{$cartItem['quantity']}}"
                                            data-event="minus" data-prevent="true">
                                            <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                        </span>
                                        <input type="text"
                                               class="quantity__qty cart-quantity-update-input cart_quantity_of_{{ $cartItem['id'] }}"
                                               data-min="{{ $product?->minimum_order_qty ?? 1 }}" value="0"
                                               data-cart-id="{{ $cartItem['id'] }}"
                                               data-product-id="{{ $cartItem['product_id'] }}"
                                               data-value=0
                                               data-current-stock="{{ $getProductCurrentStock ?? 0 }}"
                                               data-prevent="true">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @php($sub_total+=($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])
                @php($total_tax+=$cartItem['tax']*(int)$cartItem['quantity'])
            @endforeach
            <li>
                <div class="flex-between-gap-3 pt-2 pb-4">
                    <h6>{{translate('total')}}</h6>
                    <h3 class="text-primary cart_total_amount">{{Helpers::currency_converter($sub_total)}}</h3>
                </div>
                <div class="d-flex gap-3">
                    @if($web_config['guest_checkout_status'] || auth('customer')->check())
                        <a href="{{route('shop-cart')}}"
                           class="btn btn-outline-primary btn-block">{{translate('view_cart')}}</a>
                        <a href="{{route('checkout-details')}}"
                           class="btn btn-primary btn-block">{{translate('go_to_checkout')}}</a>
                    @else
                        <button class="btn btn-outline-primary btn-block" data-bs-toggle="modal"
                                data-bs-target="#loginModal">{{translate('view_cart')}}</button>
                        <button class="btn btn-primary btn-block" data-bs-toggle="modal"
                                data-bs-target="#loginModal">{{translate('go_to_checkout')}}</button>
                    @endif
                </div>
            </li>
        @else
            <div class="widget-cart-item">
                <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
                    <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-cart.svg') }}" alt="">
                    <h5 class="text-center text-muted px-2 px-md-5">
                        {{ translate('You_have_not_added_anything_to_your_cart_yet') }}!
                    </h5>
                </div>
            </div>
        @endif
    </ul>
</div>
