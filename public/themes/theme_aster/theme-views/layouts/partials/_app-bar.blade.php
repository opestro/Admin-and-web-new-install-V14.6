@php
    use App\Models\Product;use App\Utils\CartManager;use App\Utils\Helpers;use App\Utils\ProductManager;
@endphp
<ul class="list-unstyled d-flex justify-content-around gap-3 mb-0 position-relative bg-white shadow-lg">
    <li>
        <a href="{{ route('home') }}"
           class="d-flex align-items-center flex-column py-2 {{ (Request::is('/') || Request::is('home')) ? 'active' : '' }}">
            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_8487_6234)">
            <path d="M13 15.4932C11.6193 15.4932 10.5 16.6125 10.5 17.9932V22.9932H15.5V17.9932C15.5 16.6125 14.3807 15.4932 13 15.4932Z" fill="currentColor"/>
            <path d="M17.1667 17.9934V22.9934H20.5C21.8807 22.9934 23 21.8741 23 20.4934V12.8926C23.0002 12.4597 22.832 12.0436 22.5308 11.7326L15.4492 4.07673C14.1996 2.72477 12.0907 2.64177 10.7388 3.8913C10.6746 3.95067 10.6127 4.01251 10.5534 4.07673L3.48418 11.7301C3.17395 12.0424 2.99988 12.4649 3 12.9051V20.4934C3 21.8741 4.1193 22.9934 5.5 22.9934H8.83332V17.9934C8.84891 15.7211 10.6836 13.8654 12.8987 13.812C15.1879 13.7568 17.1492 15.644 17.1667 17.9934Z" fill="#1B7FED"/>
            <path d="M13 15.4932C11.6193 15.4932 10.5 16.6125 10.5 17.9932V22.9932H15.5V17.9932C15.5 16.6125 14.3807 15.4932 13 15.4932Z" fill="currentColor"/>
            </g>
            <defs>
            <clipPath id="clip0_8487_6234">
            <rect width="20" height="20" fill="white" transform="translate(3 3)"/>
            </clipPath>
            </defs>
            </svg>
        </a>
    </li>
    <li>
        @if(auth('customer')->check())
            <a href="{{ route('wishlists') }}"
               class="d-flex align-items-center flex-column  py-2 {{ Request::is('wishlists') ? 'active' : '' }}">
                <div class="position-relative">
                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_8487_6242)">
                        <path d="M14.917 1.59766C13.9786 1.61225 13.0607 1.8742 12.2559 2.35705C11.4512 2.8399 10.7881 3.52655 10.3336 4.34766C9.87916 3.52655 9.21607 2.8399 8.41132 2.35705C7.60657 1.8742 6.68867 1.61225 5.75029 1.59766C4.25441 1.66265 2.84505 2.31703 1.83011 3.41785C0.815174 4.51867 0.277165 5.97643 0.333624 7.47266C0.333624 11.2618 4.32196 15.4002 7.66696 18.206C8.41381 18.8336 9.35809 19.1777 10.3336 19.1777C11.3092 19.1777 12.2534 18.8336 13.0003 18.206C16.3453 15.4002 20.3336 11.2618 20.3336 7.47266C20.3901 5.97643 19.8521 4.51867 18.8371 3.41785C17.8222 2.31703 16.4128 1.66265 14.917 1.59766ZM11.9295 16.931C11.4828 17.3071 10.9176 17.5134 10.3336 17.5134C9.74966 17.5134 9.18447 17.3071 8.73779 16.931C4.45612 13.3385 2.00029 9.89182 2.00029 7.47266C1.94332 6.41825 2.3056 5.3841 3.00809 4.59574C3.71059 3.80738 4.69632 3.32878 5.75029 3.26432C6.80426 3.32878 7.78999 3.80738 8.49249 4.59574C9.19499 5.3841 9.55726 6.41825 9.50029 7.47266C9.50029 7.69367 9.58809 7.90563 9.74437 8.06191C9.90065 8.21819 10.1126 8.30599 10.3336 8.30599C10.5546 8.30599 10.7666 8.21819 10.9229 8.06191C11.0792 7.90563 11.167 7.69367 11.167 7.47266C11.11 6.41825 11.4723 5.3841 12.1748 4.59574C12.8773 3.80738 13.863 3.32878 14.917 3.26432C15.9709 3.32878 16.9567 3.80738 17.6592 4.59574C18.3617 5.3841 18.7239 6.41825 18.667 7.47266C18.667 9.89182 16.2111 13.3385 11.9295 16.9277V16.931Z" fill="currentColor"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_8487_6242">
                        <rect width="20" height="20" fill="white" transform="translate(0.333496)"/>
                        </clipPath>
                        </defs>
                    </svg>

                    <span class="count wishlist_count_status top-0">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                </div>
            </a>
        @else
            <a href="javascript:" class="d-flex align-items-center flex-column py-2" data-bs-toggle="modal"
               data-bs-target="#loginModal">
                <div class="position-relative">
                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_8487_6242)">
                        <path d="M14.917 1.59766C13.9786 1.61225 13.0607 1.8742 12.2559 2.35705C11.4512 2.8399 10.7881 3.52655 10.3336 4.34766C9.87916 3.52655 9.21607 2.8399 8.41132 2.35705C7.60657 1.8742 6.68867 1.61225 5.75029 1.59766C4.25441 1.66265 2.84505 2.31703 1.83011 3.41785C0.815174 4.51867 0.277165 5.97643 0.333624 7.47266C0.333624 11.2618 4.32196 15.4002 7.66696 18.206C8.41381 18.8336 9.35809 19.1777 10.3336 19.1777C11.3092 19.1777 12.2534 18.8336 13.0003 18.206C16.3453 15.4002 20.3336 11.2618 20.3336 7.47266C20.3901 5.97643 19.8521 4.51867 18.8371 3.41785C17.8222 2.31703 16.4128 1.66265 14.917 1.59766ZM11.9295 16.931C11.4828 17.3071 10.9176 17.5134 10.3336 17.5134C9.74966 17.5134 9.18447 17.3071 8.73779 16.931C4.45612 13.3385 2.00029 9.89182 2.00029 7.47266C1.94332 6.41825 2.3056 5.3841 3.00809 4.59574C3.71059 3.80738 4.69632 3.32878 5.75029 3.26432C6.80426 3.32878 7.78999 3.80738 8.49249 4.59574C9.19499 5.3841 9.55726 6.41825 9.50029 7.47266C9.50029 7.69367 9.58809 7.90563 9.74437 8.06191C9.90065 8.21819 10.1126 8.30599 10.3336 8.30599C10.5546 8.30599 10.7666 8.21819 10.9229 8.06191C11.0792 7.90563 11.167 7.69367 11.167 7.47266C11.11 6.41825 11.4723 5.3841 12.1748 4.59574C12.8773 3.80738 13.863 3.32878 14.917 3.26432C15.9709 3.32878 16.9567 3.80738 17.6592 4.59574C18.3617 5.3841 18.7239 6.41825 18.667 7.47266C18.667 9.89182 16.2111 13.3385 11.9295 16.9277V16.931Z" fill="currentColor"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_8487_6242">
                        <rect width="20" height="20" fill="white" transform="translate(0.333496)"/>
                        </clipPath>
                        </defs>
                    </svg>
                </div>
            </a>
        @endif
    </li>
    <li>
        <div class="dropup position-static">
            <a
                href="javascript"
                class="d-flex align-items-center flex-column py-2 {{ Request::is('shop-cart') ? 'active' : '' }}"
                data-toggle="collapse"
                data-target="cart_dropdown"
            >
                <div class="position-relative">
                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_8487_6247)">
                        <path d="M18.1665 5H15.6665C15.6665 3.67392 15.1397 2.40215 14.202 1.46447C13.2644 0.526784 11.9926 0 10.6665 0C9.34042 0 8.06865 0.526784 7.13097 1.46447C6.19329 2.40215 5.6665 3.67392 5.6665 5H3.1665C2.50346 5 1.86758 5.26339 1.39874 5.73223C0.929896 6.20107 0.666504 6.83696 0.666504 7.5L0.666504 15.8333C0.667827 16.938 1.10724 17.997 1.88835 18.7782C2.66947 19.5593 3.72851 19.9987 4.83317 20H16.4998C17.6045 19.9987 18.6635 19.5593 19.4447 18.7782C20.2258 17.997 20.6652 16.938 20.6665 15.8333V7.5C20.6665 6.83696 20.4031 6.20107 19.9343 5.73223C19.4654 5.26339 18.8295 5 18.1665 5ZM10.6665 1.66667C11.5506 1.66667 12.3984 2.01786 13.0235 2.64298C13.6486 3.2681 13.9998 4.11594 13.9998 5H7.33317C7.33317 4.11594 7.68436 3.2681 8.30948 2.64298C8.9346 2.01786 9.78245 1.66667 10.6665 1.66667ZM18.9998 15.8333C18.9998 16.4964 18.7364 17.1323 18.2676 17.6011C17.7988 18.0699 17.1629 18.3333 16.4998 18.3333H4.83317C4.17013 18.3333 3.53424 18.0699 3.0654 17.6011C2.59656 17.1323 2.33317 16.4964 2.33317 15.8333V7.5C2.33317 7.27899 2.42097 7.06702 2.57725 6.91074C2.73353 6.75446 2.94549 6.66667 3.1665 6.66667H5.6665V8.33333C5.6665 8.55435 5.7543 8.76631 5.91058 8.92259C6.06686 9.07887 6.27882 9.16667 6.49984 9.16667C6.72085 9.16667 6.93281 9.07887 7.08909 8.92259C7.24537 8.76631 7.33317 8.55435 7.33317 8.33333V6.66667H13.9998V8.33333C13.9998 8.55435 14.0876 8.76631 14.2439 8.92259C14.4002 9.07887 14.6122 9.16667 14.8332 9.16667C15.0542 9.16667 15.2661 9.07887 15.4224 8.92259C15.5787 8.76631 15.6665 8.55435 15.6665 8.33333V6.66667H18.1665C18.3875 6.66667 18.5995 6.75446 18.7558 6.91074C18.912 7.06702 18.9998 7.27899 18.9998 7.5V15.8333Z" fill="currentColor"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_8487_6247">
                        <rect width="20" height="20" fill="white" transform="translate(0.666504)"/>
                        </clipPath>
                        </defs>
                    </svg>
                    @php($cart_mobile=CartManager::get_cart())
                    <span class="count top-0">{{$cart_mobile->count()}}</span>
                </div>
            </a>

            <ul class="dropdown-menu scrollY-60 z-n1 bs-dropdown-min-width--100"
                id="cart_dropdown">
                @if($cart_mobile->count() > 0)
                    @php($sub_total=0)
                    @php($total_tax=0)
                    @foreach($cart_mobile as $cartItem)
                        @php($product=$cartItem->product)
                        <li>
                            <div class="media gap-3">
                                <div class="avatar avatar-xxl overflow-hidden position-relative rounded">
                                    <img loading="lazy" alt="Product"
                                        src="{{ getStorageImages(path: $cartItem?->product?->thumbnail_full_url, type: 'product') }}"
                                        class="img-fit dark-support rounded img-fit overflow-hidden {{ $product && $product->status == 0?'blur-section':'' }}"/>

                                    @if ($product && $product->status == 0)
                                        <span
                                            class="temporary-closed position-absolute text-center p-2 text-capitalize">
                                    <span>{{ translate('not_available') }}</span>
                                </span>
                                    @endif
                                </div>
                                <div class="media-body">
                                    <h6 class="mb-1 {{ $product && $product->status == 0?'blur-section':'' }}">
                                        <a href="{{ $product && $product->status == 1? route('product',$cartItem['slug']) :'javascript:'}}">{{Str::limit($cartItem['name'],30)}}</a>
                                    </h6>
                                    @if(!empty($cartItem['variant']))
                                        <div>
                                            <span class="fs-12">{{translate('variant')}} : {{ $cartItem['variant'] }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex gap-3 justify-content-between align-items-end">
                                        <div
                                            class="d-flex flex-column gap-1 {{ $product && $product->status == 0?'blur-section':'' }}">
                                            <div class="fs-12"><span
                                                    class="cart_quantity_{{ $cartItem['id'] }}">{{$cartItem['quantity']}}</span>
                                                 {{'×'.Helpers::currency_converter(($cartItem['price']-$cartItem['discount']))}}
                                            </div>
                                            <div class="product__price d-flex flex-wrap gap-2">
                                                <del
                                                    class="product__old-price quantity_price_of_{{ $cartItem['id'] }}">{{Helpers::currency_converter($cartItem['price']*$cartItem['quantity'])}}</del>
                                                <ins
                                                    class="product__new-price discount_price_of_{{ $cartItem['id'] }}">{{Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])}}</ins>
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
                                                       data-current-stock="{{ $getProductCurrentStock }}"
                                                       data-value=0
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
                        <h6 class="text-danger text-center m-0 p-2"><i
                                class="fa fa-cart-arrow-down"></i> {{translate('empty_Cart')}}
                        </h6>
                    </div>
                @endif
            </ul>
        </div>
    </li>
    <li>
        @if(auth('customer')->check())
            <a href="{{ route('product-compare.index') }}"
               class="d-flex align-items-center flex-column py-2 {{ Request::is('compare-list') ? 'active' : '' }}">
                <div class="position-relative">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_8487_6252)">
                        <path d="M20 4.16648C20 2.32815 18.505 0.833151 16.6667 0.833151C14.8283 0.833151 13.3333 2.32815 13.3333 4.16648C13.3333 5.71648 14.3975 7.02315 15.8333 7.39398V12.4998C15.8333 13.8782 14.7117 14.9998 13.3333 14.9998H10.5258L12.3617 13.164C12.6875 12.8382 12.6875 12.3115 12.3617 11.9857C12.0358 11.6598 11.5092 11.6598 11.1833 11.9857L9.06583 14.1032C8.60833 14.5598 8.33333 14.9998 8.33333 15.8332C8.33333 16.6665 8.58083 17.154 9.06583 17.639L11.1833 19.7565C11.3458 19.919 11.5592 20.0007 11.7725 20.0007C11.9858 20.0007 12.1992 19.919 12.3617 19.7565C12.6875 19.4307 12.6875 18.904 12.3617 18.5782L10.4508 16.6673H13.3333C15.6308 16.6673 17.5 14.7982 17.5 12.5007V7.39482C18.9358 7.02398 20 5.71648 20 4.16648ZM16.6667 5.83315C15.7475 5.83315 15 5.08565 15 4.16648C15 3.24732 15.7475 2.49982 16.6667 2.49982C17.5858 2.49982 18.3333 3.24732 18.3333 4.16648C18.3333 5.08565 17.5858 5.83315 16.6667 5.83315ZM11.0033 5.93482C11.4758 5.46232 11.7358 4.83482 11.7358 4.16648C11.7358 3.49815 11.4758 2.87065 11.0033 2.39898L8.88583 0.281484C8.56 -0.044349 8.03333 -0.044349 7.7075 0.281484C7.38167 0.607318 7.38167 1.13398 7.7075 1.45982L9.58 3.33315H6.66667C4.36917 3.33315 2.5 5.20232 2.5 7.49982V12.6057C1.06417 12.9765 0 14.2832 0 15.8332C0 17.6715 1.495 19.1665 3.33333 19.1665C5.17167 19.1665 6.66667 17.6715 6.66667 15.8332C6.66667 14.2832 5.6025 12.9765 4.16667 12.6057V7.49982C4.16667 6.12148 5.28833 4.99982 6.66667 4.99982H9.58083L7.7075 6.87315C7.38167 7.19898 7.38167 7.72565 7.7075 8.05148C7.87 8.21398 8.08333 8.29565 8.29667 8.29565C8.51 8.29565 8.72333 8.21398 8.88583 8.05148L11.0033 5.93482ZM5 15.8332C5 16.7523 4.2525 17.4998 3.33333 17.4998C2.41417 17.4998 1.66667 16.7523 1.66667 15.8332C1.66667 14.914 2.41417 14.1665 3.33333 14.1665C4.2525 14.1665 5 14.914 5 15.8332Z" fill="currentColor"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_8487_6252">
                        <rect width="20" height="20" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                    <span
                        class="count compare_list_count_status top-0">{{session()->has('compare_list')?count(session('compare_list')):0}}</span>
                </div>
            </a>
        @else
            <a href="javascript:" class="d-flex align-items-center flex-column py-2" data-bs-toggle="modal"
               data-bs-target="#loginModal">
                <div class="position-relative">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_8487_6252)">
                        <path d="M20 4.16648C20 2.32815 18.505 0.833151 16.6667 0.833151C14.8283 0.833151 13.3333 2.32815 13.3333 4.16648C13.3333 5.71648 14.3975 7.02315 15.8333 7.39398V12.4998C15.8333 13.8782 14.7117 14.9998 13.3333 14.9998H10.5258L12.3617 13.164C12.6875 12.8382 12.6875 12.3115 12.3617 11.9857C12.0358 11.6598 11.5092 11.6598 11.1833 11.9857L9.06583 14.1032C8.60833 14.5598 8.33333 14.9998 8.33333 15.8332C8.33333 16.6665 8.58083 17.154 9.06583 17.639L11.1833 19.7565C11.3458 19.919 11.5592 20.0007 11.7725 20.0007C11.9858 20.0007 12.1992 19.919 12.3617 19.7565C12.6875 19.4307 12.6875 18.904 12.3617 18.5782L10.4508 16.6673H13.3333C15.6308 16.6673 17.5 14.7982 17.5 12.5007V7.39482C18.9358 7.02398 20 5.71648 20 4.16648ZM16.6667 5.83315C15.7475 5.83315 15 5.08565 15 4.16648C15 3.24732 15.7475 2.49982 16.6667 2.49982C17.5858 2.49982 18.3333 3.24732 18.3333 4.16648C18.3333 5.08565 17.5858 5.83315 16.6667 5.83315ZM11.0033 5.93482C11.4758 5.46232 11.7358 4.83482 11.7358 4.16648C11.7358 3.49815 11.4758 2.87065 11.0033 2.39898L8.88583 0.281484C8.56 -0.044349 8.03333 -0.044349 7.7075 0.281484C7.38167 0.607318 7.38167 1.13398 7.7075 1.45982L9.58 3.33315H6.66667C4.36917 3.33315 2.5 5.20232 2.5 7.49982V12.6057C1.06417 12.9765 0 14.2832 0 15.8332C0 17.6715 1.495 19.1665 3.33333 19.1665C5.17167 19.1665 6.66667 17.6715 6.66667 15.8332C6.66667 14.2832 5.6025 12.9765 4.16667 12.6057V7.49982C4.16667 6.12148 5.28833 4.99982 6.66667 4.99982H9.58083L7.7075 6.87315C7.38167 7.19898 7.38167 7.72565 7.7075 8.05148C7.87 8.21398 8.08333 8.29565 8.29667 8.29565C8.51 8.29565 8.72333 8.21398 8.88583 8.05148L11.0033 5.93482ZM5 15.8332C5 16.7523 4.2525 17.4998 3.33333 17.4998C2.41417 17.4998 1.66667 16.7523 1.66667 15.8332C1.66667 14.914 2.41417 14.1665 3.33333 14.1665C4.2525 14.1665 5 14.914 5 15.8332Z" fill="currentColor"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_8487_6252">
                        <rect width="20" height="20" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                </div>
            </a>
        @endif
    </li>
</ul>
