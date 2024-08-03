@php
    $overallRating = getOverallRating($product->reviews);
    $rating = getRating($product->reviews);
    $productReviews = \App\Utils\ProductManager::get_product_review($product->id);
@endphp

<div class="modal-header rtl">
    <div>
        <h4 class="modal-title product-title">
            <a class="product-title2" href="{{route('product',$product->slug)}}" data-toggle="tooltip"
               data-placement="right"
               title="Go to product page">{{$product['name']}}
                <i class="czi-arrow-{{ Session::get('direction') === "rtl" ? 'left' : 'right' }} ms-2 font-size-lg mr-0"></i>
            </a>
        </h4>
    </div>
    <div>
        <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

<div class="modal-body rtl">
    <div class="row ">
        <div class="col-lg-5 col-md-4 col-12">
            <div class="cz-product-gallery position-relative">
                <div class="cz-preview">
                    <div id="sync1" class="owl-carousel owl-theme product-thumbnail-slider">
                        @if($product->images!=null && json_decode($product->images)>0)
                            @if(json_decode($product->colors) && $product->color_image)
                                @foreach (json_decode($product->color_image) as $key => $photo)
                                    @if($photo->color != null)
                                        <div class="product-preview-item d-flex align-items-center justify-content-center">
                                            <img class="show-imag img-responsive max-height-500px"
                                                 src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'product') }}"
                                                 alt="{{ translate('product') }}" width="">
                                        </div>
                                    @else
                                        <div class="product-preview-item d-flex align-items-center justify-content-center">
                                            <img class="show-imag img-responsive max-height-500px"
                                                 src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'product') }}"
                                                 alt="{{ translate('product') }}" width="">
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                @foreach (json_decode($product->images) as $key => $photo)
                                    <div class="product-preview-item d-flex align-items-center justify-content-center">
                                        <img class="show-imag img-responsive max-height-500px"
                                             src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'product') }}"
                                             alt="{{ translate('product') }}">
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>

                <div class="cz-product-gallery-icons">
                    <div class="d-flex flex-column">
                        <button type="button" data-product-id="{{ $product['id'] }}"
                                class="btn __text-18px border wishList-pos-btn d-sm-none product-action-add-wishlist">
                            <i class="fa {{($wishlist_status == 1?'fa-heart':'fa-heart-o')}} wishlist_icon_{{$product['id']}} web-text-primary"
                               id="wishlist_icon_{{$product['id']}}" aria-hidden="true"></i>
                        </button>

                        <div class="sharethis-inline-share-buttons share--icons text-align-direction">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <div class="d-flex">
                        <div id="sync2" class="owl-carousel owl-theme product-thumb-slider max-height-100px d--none">
                            @if($product->images!=null && json_decode($product->images)>0)
                                @if(json_decode($product->colors) && $product->color_image)
                                    @foreach (json_decode($product->color_image) as $key => $photo)
                                        @if($photo->color != null)
                                            <div class="">
                                                <a href="javascript:"
                                                   class="product-preview-thumb d-flex align-items-center justify-content-center">
                                                    <img class="click-img" id="preview-img{{$photo->color}}"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'product') }}"
                                                         alt="{{ translate('product') }}">
                                                </a>
                                            </div>
                                        @else
                                            <div class="">
                                                <a href="javascript:"
                                                   class="product-preview-thumb d-flex align-items-center justify-content-center">
                                                    <img class="click-img" id="preview-img{{$key}}"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type: 'product') }}"
                                                         alt="{{ translate('product') }}">
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach (json_decode($product->images) as $key => $photo)
                                        <div class="">
                                            <a href="javascript:"
                                               class="product-preview-thumb d-flex align-items-center justify-content-center">
                                                <img class="click-img" id="preview-img{{$key}}"
                                                     src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'product') }}"
                                                     alt="{{ translate('product') }}">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-md-8 col-12 mt-md-0 mt-sm-3 web-direction">
            <div class="details __h-100">
                <a href="{{route('product',$product->slug)}}" class="h3 mb-2 product-title">{{$product->name}}</a>

                <div class="d-flex flex-wrap align-items-center mb-2 pro">
                    <div class="star-rating me-2">
                        @for($inc=0;$inc<5;$inc++)
                            @if($inc<$overallRating[0])
                                <i class="sr-star czi-star-filled active"></i>
                            @else
                                <i class="sr-star czi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span
                            class="d-inline-block  align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-md-2 ml-sm-0' : 'mr-md-2 mr-sm-0'}} fs-14 text-muted">({{$overallRating[0]}})</span>
                    <span class="font-regular font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}"><span class="web-text-primary">{{$overallRating[1]}}</span> {{translate('reviews')}}</span>
                    <span class="__inline-25"></span>
                    <span class="font-regular font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}">
                        <span class="web-text-primary">
                            {{$countOrder}}
                        </span> {{translate('orders')}}   </span>
                    <span class="__inline-25">    </span>
                    <span class="font-regular font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-0 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-0 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}} text-capitalize">
                        <span class="web-text-primary countWishlist-{{ $product->id }}"> {{$countWishlist}}</span> {{translate('wish_listed')}}
                    </span>

                </div>

                <div class="mb-3">
                    <span class="font-weight-normal text-accent d-flex align-items-end gap-2">
                        {!! getPriceRangeWithDiscount(product: $product) !!}
                    </span>
                </div>
                <form id="add-to-cart-form" class="mb-2">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-3">
                        @if (count(json_decode($product->colors)) > 0)
                            <div class="flex-start">
                                <div class="product-description-label text-dark font-bold">
                                    {{translate('color')}}:
                                </div>
                                <div class="__pl-15 mt-1">
                                    <ul class="flex-start checkbox-color mb-0 p-0 list-inline">
                                        @foreach (json_decode($product->colors) as $key => $color)
                                            <li>
                                                <input type="radio"
                                                       id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                       name="color" value="{{ $color }}"
                                                       @if($key == 0) checked @endif>
                                                <label style="background: {{ $color }};"
                                                    class="quick-view-preview-image-by-color shadow-border"
                                                    for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                    data-toggle="tooltip"
                                                    data-key="{{ str_replace('#','',$color) }}" data-title="{{ \App\Utils\get_color_name($color) }}">
                                                    <span class="outline"></span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @php
                            $qty = 0;
                            foreach (json_decode($product->variation) as $key => $variation) {
                                $qty += $variation->qty;
                            }
                        @endphp

                    </div>

                    @foreach (json_decode($product->choice_options) as $key => $choice)
                        <div class="flex-start">
                            <div class="product-description-label text-dark font-bold mt-1 text-capitalize">
                                {{ $choice->title }}:
                            </div>
                            <div>
                                <ul class="checkbox-alphanumeric checkbox-alphanumeric--style-1 mt-1">
                                    @foreach ($choice->options as $index => $option)
                                        <span>
                                            <input type="radio" id="{{ $choice->name }}-{{ $option }}" name="{{ $choice->name }}"
                                                   value="{{ $option }}" @if($index==0) checked @endif>
                                            <label class="user-select-none" for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                        </span>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach

                    <div class="mb-3">
                        <div class="product-quantity d-flex flex-column __gap-15">
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-description-label text-dark font-bold mt-0">{{translate('quantity')}}
                                    :
                                </div>
                                <div class="d-flex justify-content-center align-items-center quantity-box border rounded border-base web-text-primary">
                                <span class="input-group-btn">
                                    <button class="btn btn-number __p-10 web-text-primary" type="button" data-type="minus"
                                            data-field="quantity"
                                            disabled="disabled">
                                        -
                                    </button>
                                </span>
                                    <input type="text" name="quantity"
                                           class="form-control input-number text-center cart-qty-field __inline-29 border-0 "
                                           placeholder="{{ translate('1') }}" value="{{ $product->minimum_order_qty ?? 1 }}"
                                           data-producttype="{{ $product->product_type }}"
                                           min="{{ $product->minimum_order_qty ?? 1 }}"
                                           max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                    <span class="input-group-btn">
                                    <button class="btn btn-number __p-10 web-text-primary" type="button"
                                            data-producttype="{{ $product->product_type }}"
                                            data-type="plus" data-field="quantity">
                                        +
                                    </button>
                                </span>
                                </div>
                                <input type="hidden" class="product-generated-variation-code" name="product_variation_code">
                                <input type="hidden" value="" class="in_cart_key form-control w-50" name="key">
                            </div>
                            <div id="chosen_price_div">
                                <div
                                        class="d-flex justify-content-start align-items-center me-2">
                                    <div class="product-description-label text-dark font-bold text-capitalize">
                                        <strong>{{translate('total_price')}}</strong> :
                                    </div>
                                    &nbsp; <strong id="chosen_price" class="text-base"></strong>
                                    <small class="ms-2 font-regular">
                                        (<small>{{translate('tax')}} : </small>
                                        <small id="set-tax-amount"></small>)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="__btn-grp align-items-center mb-2">
                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) &&
                        $product->seller->shop->vacation_status && $currentDate >= $seller_vacation_start_date && $currentDate
                        <= $seller_vacation_end_date))) || ($product->added_by == 'admin' && ($inhouse_temporary_close ||
                            ($inHouseVacationStatus && $currentDate >= $inhouse_vacation_start_date && $currentDate <=
                                $inhouse_vacation_end_date))))

                            <button class="btn btn-secondary" type="button" disabled>
                                {{translate('buy_now')}}
                            </button>

                            <button class="btn btn--primary string-limit" type="button" disabled>
                                {{translate('add_to_cart')}}
                            </button>
                        @else
                            <button class="btn btn-secondary action-buy-now-this-product"
                            type="button">
                                {{translate('buy_now')}}
                            </button>
                            <button class="btn btn--primary string-limit action-add-to-cart-form" type="button" data-update-text="{{ translate('update_cart') }}" data-add-text="{{ translate('add_to_cart') }}">
                                {{translate('add_to_cart')}}
                            </button>
                        @endif

                        <button type="button" data-product-id="{{$product['id']}}" class="btn __text-18px border product-action-add-wishlist">
                            <i class="fa {{($wishlist_status == 1?'fa-heart':'fa-heart-o')}} wishlist_icon_{{$product['id']}} web-text-primary"
                            id="wishlist_icon_{{$product['id']}}" aria-hidden="true"></i>
                            <span class="fs-14 text-muted align-bottom countWishlist-{{$product['id']}}">
                                {{$countWishlist}}
                            </span>
                        </button>

                        @if(($product->added_by == 'seller' && ($seller_temporary_close ||
                        (isset($product->seller->shop) && $product->seller->shop->vacation_status && $currentDate >=
                        $seller_vacation_start_date && $currentDate <= $seller_vacation_end_date))) || ($product->
                            added_by == 'admin' && ($inhouse_temporary_close || ($inHouseVacationStatus &&
                            $currentDate >= $inhouse_vacation_start_date && $currentDate <= $inhouse_vacation_end_date))))
                            <div class="alert alert-danger" role="alert">
                                {{translate('this_shop_is_temporary_closed_or_on_vacation._You_cannot_add_product_to_cart_from_this_shop_for_now')}}
                            </div>
                       @endif
                    </div>

                    <div class="row no-gutters d-none flex-start d-flex">
                        <div class="col-12">
                            @if(($product['product_type'] == 'physical'))
                                <h5 class="text-danger out-of-stock-element d--none">{{translate('out_of_stock')}}</h5>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    "use strict";
    productQuickViewFunctionalityInitialize();
</script>

<script type="text/javascript" async="async"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons"></script>

