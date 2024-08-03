@php use App\Utils\Helpers; @endphp
<div class="modal-body">
    <div class="product-quickview">
        <button type="button" class="btn-close outside" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="quickview-content">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5">
                    <div class="pd-img-wrap position-relative h-100">
                        <div class="swiper-container quickviewSlider2 border rounded aspect-1 border--gray">
                            <div class="product__actions d-flex flex-column gap-2">
                                <a class="btn-wishlist add-to-wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist_status == 1?'wishlist_icon_active':'')}}"
                                   title="{{ translate('add_to_wishlist') }}"
                                   data-action="{{route('store-wishlist')}}"
                                   data-product-id = "{{$product['id']}}">
                                    <i class="bi bi-heart"></i>
                                </a>
                                <div class="product-share-icons">
                                    <a href="javascript:" title="{{translate('Share')}}">
                                        <i class="bi bi-share-fill"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="javascript:"
                                               class="share-on-social-media"
                                               data-action="{{route('product',$product->slug)}}"
                                               data-social-media-name="facebook.com/sharer/sharer.php?u=">
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="share-on-social-media"
                                               data-action="{{route('product',$product->slug)}}"
                                               data-social-media-name="twitter.com/intent/tweet?text=">
                                                <i class="bi bi-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="share-on-social-media"
                                               data-action="{{route('product',$product->slug)}}"
                                               data-social-media-name="linkedin.com/shareArticle?mini=true&url=">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="share-on-social-media"
                                               data-action="{{route('product',$product->slug)}}"
                                               data-social-media-name="api.whatsapp.com/send?text=">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @if($product->images!=null && json_decode($product->images)>0)
                                <div class="swiper-wrapper">
                                    @if(json_decode($product->colors) && $product->color_image)
                                        @foreach (json_decode($product->color_image) as $key => $photo)
                                            @if($photo->color != null)
                                                <div class="swiper-slide position-relative"
                                                     id="preview-box-{{ $photo->color }}">
                                                    <div class="easyzoom easyzoom--overlay">
                                                        @if ($product->discount > 0 && $product->discount_type === "percent")
                                                            <span class="product__discount-badge">{{'-'.$product->discount}}%</span>
                                                        @elseif($product->discount > 0)
                                                            <span
                                                                class="product__discount-badge">{{'-'.Helpers::currency_converter($product->discount)}}</span>
                                                        @endif
                                                        <a href="{{dynamicStorage(path: "storage/app/public/product/".$photo->image_name)}}">
                                                            <img class="dark-support rounded" alt=""
                                                                src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="swiper-slide position-relative thumb_{{$key}}"
                                                     id="preview-box-{{ $photo->color }}">
                                                    <div class="easyzoom easyzoom--overlay">
                                                        @if ($product->discount > 0 && $product->discount_type === "percent")
                                                            <span class="product__discount-badge">{{'-'.$product->discount.'%'}}</span>
                                                        @elseif($product->discount > 0)
                                                            <span
                                                                class="product__discount-badge">{{'-'.Helpers::currency_converter($product->discount)}}</span>
                                                        @endif
                                                        <a href="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                            <img class="dark-support rounded" alt=""
                                                                src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach (json_decode($product->images) as $key => $photo)
                                            <div class="swiper-slide position-relative">
                                                <div class="easyzoom easyzoom--overlay">
                                                    @if ($product->discount > 0 && $product->discount_type === "percent")
                                                        <span class="product__discount-badge">{{'-'.$product->discount.'%'}}</span>
                                                    @elseif($product->discount > 0)
                                                        <span class="product__discount-badge">-{{Helpers::currency_converter($product->discount)}}</span>
                                                    @endif
                                                    <a href="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'product') }}">
                                                        <img class="dark-support rounded" alt=""
                                                            src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'product') }}">
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            <div class="quickviewSliderThumb2 swiper-container position-relative">
                                @if($product->images!=null && json_decode($product->images)>0)
                                    <div class="swiper-wrapper auto-item-width justify-content-center width--4rem border--gray">
                                        @if(json_decode($product->colors) && $product->color_image)
                                            @foreach (json_decode($product->color_image) as $key => $photo)
                                                @if($photo->color != null)
                                                    <div class="swiper-slide position-relative aspect-1 focus-preview-image-by-color"
                                                         data-slide-id="preview-box-{{ str_replace('#','',$photo->color) }}">
                                                        <img class="dark-support rounded" alt=""
                                                            src="{{dynamicStorage(path: "storage/app/public/product/$photo->image_name")}}">
                                                    </div>
                                                @endif
                                            @endforeach

                                            @foreach (json_decode($product->color_image) as $key => $photo)
                                                @if($photo->color == null)
                                                    <div class="swiper-slide position-relative aspect-1 slider-thumb-img-preview"
                                                         data-thumb-key="thumb_{{$key}}">
                                                        <img class="dark-support rounded" alt=""
                                                            src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'product') }}">
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach (json_decode($product->images) as $key => $photo)
                                                <div class="swiper-slide position-relative aspect-1 slider-thumb-img-preview"
                                                     data-thumb-key="thumb_{{$key}}">
                                                    <img src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type:'product') }}"
                                                         class="dark-support rounded" alt="">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                                <div class="swiper-button-next swiper-quickview-button-next size-1-5rem"></div>
                                <div class="swiper-button-prev swiper-quickview-button-prev size-1-5rem"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="product-details-content position-relative">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <h2 class="product_title">{{$product['name']}}</h2>
                            @if ($product->discount > 0 && $product->discount_type === "percent")
                                <span class="product__save-amount">{{translate('save')}} {{$product->discount}}%</span>
                            @elseif($product->discount > 0)
                                <span
                                    class="product__save-amount">{{translate('save')}} {{Helpers::currency_converter($product->discount)}}</span>
                            @endif
                        </div>

                        <div class="d-flex gap-2 align-items-center mb-2">
                            <div class="star-rating text-gold fs-12">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= (int)$overallRating[0])
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] == ((int)$overallRating[0]+.50))
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span>({{ count($product->reviews) }})</span>
                        </div>
                        @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                            <p class="fw-semibold text-muted">{{translate('out_of_stock')}}</p>
                        @else
                            @if($product['product_type'] === 'physical')
                                <p class="fw-semibold text-muted"><span
                                        class="in_stock_status">{{$product->current_stock}}</span> {{translate('in_Stock')}}
                                </p>
                            @endif
                        @endif

                        <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4 ">
                            <div class="text-primary fs-1-5rem d-flex align-items-end gap-2">
                                {!! getPriceRangeWithDiscount(product: $product) !!}
                            </div>
                        </div>
                        <form class="cart add-to-cart-form" id="add-to-cart-form" action="{{ route('cart.add') }}"
                              data-redirecturl="{{route('checkout-details')}}"
                              data-varianturl="{{ route('cart.variant_price') }}"
                              data-errormessage="{{translate('please_choose_all_the_options')}}"
                              data-outofstock="{{translate('sorry_out_of_stock').'.'}}">
                            @csrf
                            <div class="">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                @if (count(json_decode($product->colors)) > 0)
                                    <div class="d-flex gap-4 flex-wrap align-items-center mb-3">
                                        <h6 class="fw-semibold">{{translate('color')}}</h6>
                                        <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2 pt-2">
                                            @foreach (json_decode($product->colors) as $key => $color)
                                                <li>
                                                    <label>
                                                        <input type="radio" hidden=""
                                                               id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                               name="color" value="{{ $color }}"
                                                            {{ $key == 0 ? 'checked' : '' }}
                                                        >
                                                        <span
                                                            class="color_variants rounded-circle focus-preview-image-by-color p-0 {{ $key == 0 ? 'color_variant_active':''}}"
                                                            style="background: {{ $color }};"
                                                            data-slide-id="preview-box-{{ str_replace('#','',$color) }}"
                                                            id="color_variants_preview-box-{{ str_replace('#','',$color) }}"
                                                        ></span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @foreach (json_decode($product->choice_options) as $choice)
                                    <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                        <h6 class="fw-semibold">{{translate($choice->title)}}</h6>
                                        <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2">
                                            @foreach ($choice->options as $key=>$option)
                                                <li>
                                                    <label>
                                                        <input type="radio" hidden=""
                                                               id="{{$choice->name}}-{{$option}}"
                                                               name="{{$choice->name}}" value="{{$option}}"
                                                               @if($key == 0) checked @endif >
                                                        <span>{{$option}}</span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach

                                <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                    <h6 class="fw-semibold">{{translate('quantity')}}</h6>

                                    <div class="quantity quantity--style-two">
                                        <span class="quantity__minus single-quantity-minus">
                                            <i class="bi bi-dash"></i>
                                        </span>
                                        <input type="text" class="quantity__qty product_quantity__qty" name="quantity"
                                               data-details-page="1"
                                               value="{{ $product->minimum_order_qty ?? 1 }}"
                                               min="{{ $product->minimum_order_qty ?? 1 }}"
                                               max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                        <span class="quantity__plus single-quantity-plus" {{($product->current_stock == 1?'disabled':'')}}>
                                            <i class="bi bi-plus"></i>
                                        </span>
                                    </div>
                                </div>
                                <input type="hidden" class="product-generated-variation-code" name="product_variation_code">
                                <input type="hidden" value="" class="in_cart_key form-control w-50" name="key">

                                <div class="bg-light mx-w rounded p-4">
                                    <div class="flex-between-gap-3">
                                        <div class="">
                                            <h6 class="flex-middle-gap-2 mb-2">
                                                <span class="text-muted">{{translate('total_price').':'}}</span>
                                                <span
                                                    class="total_price">{{Helpers::currency_converter($product->unit_price)}}</span>
                                            </h6>
                                            <h6 class="flex-middle-gap-2">
                                                <span class="text-muted">{{translate('tax').':'}}</span>
                                                <span
                                                    class="product_vat">{{Helpers::currency_converter($product->tax)}}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-4">
                                    @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $currentDate >= $seller_vacation_start_date && $currentDate <= $seller_vacation_end_date))) ||
                                    ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inHouseVacationStatus && $currentDate >= $inhouse_vacation_start_date && $currentDate <= $inhouse_vacation_end_date))))
                                        <button type="button" class=" btn btn-secondary fs-16" data-bs-toggle="modal" data-bs-target="#buyNowModal" data-bs-dismiss="#quickViewModal"
                                                disabled>{{translate('buy_now')}}
                                        </button>
                                        <button type="button" class=" btn btn-primary fs-16" disabled>
                                            {{translate('add_to_Cart')}}
                                        </button>
                                    @else
                                        @php($guest_checkout=getWebConfig(name: 'guest_checkout'))
                                        <button type="button"
                                                class="btn btn-secondary fs-16 buy-now"
                                                data-form-id="add-to-cart-form"
                                                data-redirect-status="{{($guest_checkout==1 || Auth::guard('customer')->check()?'true':'false')}}"
                                                data-action="{{ route('shop-cart') }}"
                                                >
                                            {{translate('buy_now')}}
                                        </button>
                                        <button type="button"
                                                class="btn btn-primary fs-16 text-capitalize add-to-cart"
                                                data-form-id="add-to-cart-form" data-update-text="{{ translate('update_cart') }}"
                                                data-add-text="{{ translate('add_to_cart') }}">{{translate('add_to_cart')}}</button>
                                    @endif
                                </div>
                                @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $currentDate >= $seller_vacation_start_date && $currentDate <= $seller_vacation_end_date))) ||
                                ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inHouseVacationStatus && $currentDate >= $inhouse_vacation_start_date && $currentDate <= $inhouse_vacation_end_date))))
                                    <div class="alert alert-danger mt-3" role="alert">
                                        {{translate('this_shop_is_temporary_closed_or_on_vacation.')}}
                                        {{translate('You_cannot_add_product_to_cart_from_this_shop_for_now')}}
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ theme_asset('assets/js/quick-view.js') }}"></script>
<script>
    'use strict';

    $('.modal').on('hidden.bs.modal', function () {
        if($(".modal:visible").length > 0) {
            setTimeout(function() {
                $('body').addClass('modal-open');
            },200)
        }
    });

        buyNow();
        addToWishlist();
        addToCompare();
        focusPreviewImageByColor();
        shareOnSocialMedia();
</script>
