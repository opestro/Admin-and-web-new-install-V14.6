@php use App\Utils\Helpers;use App\Utils\ProductManager; @endphp
@extends('theme-views.layouts.app')

@section('title', $product['name'].' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    <meta name="description" content="{{$product->slug}}">
    <meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @if($product->added_by=='seller')
        <meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
    @elseif($product->added_by=='admin')
        <meta name="author" content="{{$web_config['name']->value}}">
    @endif
    @if($product['meta_image'])
        <meta property="og:image" content="{{dynamicStorage(path: "storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
        <meta property="twitter:card"
              content="{{dynamicStorage(path: "storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
    @else
        <meta property="og:image" content="{{dynamicStorage(path: "storage/app/public/product/thumbnail")}}/{{$product->thumbnail}}"/>
        <meta property="twitter:card"
              content="{{dynamicStorage(path: "storage/app/public/product/thumbnail/")}}/{{$product->thumbnail}}"/>
    @endif

    @if($product['meta_title'])
        <meta property="og:title" content="{{$product->meta_title}}"/>
        <meta property="twitter:title" content="{{$product->meta_title}}"/>
    @else
        <meta property="og:title" content="{{$product->name}}"/>
        <meta property="twitter:title" content="{{$product->name}}"/>
    @endif
    <meta property="og:url" content="{{route('product',[$product->slug])}}">

    @if($product['meta_description'])
        <meta property="twitter:description" content="{!! $product['meta_description'] !!}">
        <meta property="og:description" content="{!! $product['meta_description'] !!}">
    @else
        <meta property="og:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="twitter:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @endif
    <meta property="twitter:url" content="{{route('product',[$product->slug])}}">
@endpush

@section('content')
    <main class="main-content d-flex flex-column gap-3 pt-3 mb-sm-5">
        <div class="container">
            <div class="row gx-3 gy-4">
                <div class="col-lg-8 col-xl-9">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="quickview-content">
                                <div class="row gy-4">
                                    <div class="col-lg-5">
                                        <div class="pd-img-wrap position-relative h-100">
                                            <div
                                                class="swiper-container quickviewSlider2 border rounded aspect-1 border--gray">
                                                <div class="product__actions d-flex flex-column gap-2">
                                                    <a class="btn-wishlist add-to-wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlistStatus == 1?'wishlist_icon_active':'')}}"
                                                       data-action="{{route('store-wishlist')}}"
                                                       data-product-id="{{$product['id']}}"
                                                       id="wishlist-{{$product['id']}}"
                                                       title="{{translate('add_to_wishlist')}}">
                                                        <i class="bi bi-heart"></i>
                                                    </a>
                                                    <a id="compare_list-{{$product['id']}}"
                                                       class="btn-compare stopPropagation add-to-compare compare_list-{{$product['id']}} {{($compareList == 1?'compare_list_icon_active':'')}}"
                                                       data-action="{{route('product-compare.index')}}"
                                                       data-product-id="{{$product['id']}}"
                                                       title="{{translate('add_to_compare_list')}}">
                                                        <i class="bi bi-repeat"></i>
                                                    </a>
                                                    <div class="product-share-icons">
                                                        <a href="javascript:" title="Share">
                                                            <i class="bi bi-share-fill"></i>
                                                        </a>
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:" class="share-on-social-media"
                                                                   data-action="{{route('product',$product->slug)}}"
                                                                   data-social-media-name="facebook.com/sharer/sharer.php?u=">
                                                                    <i class="bi bi-facebook"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:"
                                                                   class="share-on-social-media"
                                                                   data-action="{{route('product',$product->slug)}}"
                                                                   data-social-media-name="twitter.com/intent/tweet?text=">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                         height="12" fill="currentColor"
                                                                         class="bi bi-twitter-x" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                                    </svg>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:"
                                                                   class="share-on-social-media"
                                                                   data-action="{{route('product',$product->slug)}}"
                                                                   data-social-media-name="linkedin.com/shareArticle?mini=true&url=">
                                                                    <i class="bi bi-linkedin"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:"
                                                                   class="share-on-social-media"
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
                                                                        @if ($product->discount > 0 && $product->discount_type === "percent")
                                                                            <span class="product__discount-badge">
                                                                                <span>
                                                                                    {{'-'.$product->discount.'%'}}
                                                                                </span>
                                                                            </span>
                                                                        @elseif($product->discount > 0)
                                                                            <span class="product__discount-badge">
                                                                                <span>
                                                                                    {{'-'.Helpers::currency_converter($product->discount)}}
                                                                                </span>
                                                                            </span>
                                                                        @endif

                                                                        <div class="easyzoom easyzoom--overlay">
                                                                            <a href="{{ getValidImage(path: 'storage/app/public/product/'.($photo->image_name), type:'product') }}">
                                                                                <img class="dark-support rounded" alt=""
                                                                                    src="{{ getValidImage(path: 'storage/app/public/product/'.($photo->image_name), type:'product') }}">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="swiper-slide position-relative"
                                                                         id="preview-box-{{ $photo->color }}">
                                                                        @if ($product->discount > 0 && $product->discount_type === "percent")
                                                                            <span class="product__discount-badge">
                                                                                <span>
                                                                                    {{'-'.$product->discount.'%'}}
                                                                                </span>
                                                                            </span>
                                                                        @elseif($product->discount > 0)
                                                                            <span class="product__discount-badge">
                                                                                    -{{Helpers::currency_converter($product->discount)}}
                                                                                </span>
                                                                        @endif
                                                                        <div class="easyzoom easyzoom--overlay">
                                                                            <a href="{{ getValidImage(path: 'storage/app/public/product/'.($photo->image_name), type:'product') }}">
                                                                                <img class="dark-support rounded" alt=""
                                                                                    src="{{ getValidImage(path: 'storage/app/public/product/'.($photo->image_name), type:'product') }}">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @foreach (json_decode($product->images) as $key => $photo)
                                                                <div class="swiper-slide position-relative">
                                                                    @if ($product->discount > 0 && $product->discount_type === "percent")
                                                                        <span class="product__discount-badge">
                                                                            <span>
                                                                                -{{$product->discount}}%
                                                                            </span>
                                                                        </span>
                                                                    @elseif($product->discount > 0)
                                                                        <span class="product__discount-badge">
                                                                            <span>
                                                                                {{'-'.Helpers::currency_converter($product->discount)}}
                                                                            </span>
                                                                        </span>
                                                                    @endif
                                                                    <div class="easyzoom easyzoom--overlay">
                                                                        <a href="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type:'product') }}">
                                                                            <img class="dark-support rounded" alt=""
                                                                                src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type:'product') }}">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mt-2 user-select-none">
                                                <div class="quickviewSliderThumb2 swiper-container position-relative ">
                                                    @if($product->images!=null && json_decode($product->images)>0)
                                                        <div
                                                            class="swiper-wrapper auto-item-width justify-content-center border--gray width--4rem">
                                                            @if(json_decode($product->colors) && $product->color_image)
                                                                @foreach (json_decode($product->color_image) as $key => $photo)
                                                                    @if($photo->color != null)
                                                                        <div
                                                                            class="swiper-slide position-relative aspect-1">
                                                                            <img class="dark-support rounded" alt=""
                                                                                src="{{ getValidImage(path: 'storage/app/public/product/'.($photo->image_name), type:'product') }}">
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                                @foreach (json_decode($product->color_image) as $key => $photo)
                                                                    @if($photo->color == null)
                                                                        <div class="swiper-slide position-relative aspect-1">
                                                                            <img class="dark-support rounded" alt=""
                                                                                src="{{ getValidImage(path: 'storage/app/public/product/'.($photo->image_name), type:'product') }}">
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                @foreach (json_decode($product->images) as $key => $photo)
                                                                    <div
                                                                        class="swiper-slide position-relative aspect-1">
                                                                        <img class="dark-support rounded" alt=""
                                                                            src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type:'product') }}">
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @endif

                                                    <div
                                                        class="swiper-button-next swiper-quickview-button-next size-1-5rem"></div>
                                                    <div
                                                        class="swiper-button-prev swiper-quickview-button-prev size-1-5rem"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="product-details-content position-relative">
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                                <h2 class="product_title">{{$product->name}}</h2>
                                                @if ($product->discount > 0 && $product->discount_type === "percent")
                                                    <span
                                                        class="product__save-amount">{{translate('save')}} {{$product->discount.'%'}}</span>
                                                @elseif($product->discount > 0)
                                                    <span
                                                        class="product__save-amount">{{translate('save')}} {{Helpers::currency_converter($product->discount)}}</span>
                                                @endif
                                            </div>

                                            <div class="d-flex gap-2 align-items-center mb-2">
                                                <div class="star-rating text-gold fs-12">
                                                    @for ($index = 1; $index <= 5; $index++)
                                                        @if ($index <= (int)$overallRating[0])
                                                            <i class="bi bi-star-fill"></i>
                                                        @elseif ($overallRating[0] != 0 && $index <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
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
                                                    <p class="fw-semibold text-muted">
                                                        <span class="in_stock_status">{{$product->current_stock}}</span>
                                                        {{translate('in_Stock')}}
                                                    </p>
                                                @endif
                                            @endif
                                            <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4 ">
                                                <div class="text-primary fs-1-5rem d-flex align-items-end gap-2">
                                                    {!! getPriceRangeWithDiscount(product: $product) !!}
                                                </div>
                                            </div>
                                            <form class="cart add-to-cart-form" action="{{ route('cart.add') }}"
                                                  id="add-to-cart-form" data-redirecturl="{{route('checkout-details')}}"
                                                  data-varianturl="{{ route('cart.variant_price') }}"
                                                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                                                  data-outofstock="{{translate('Sorry_Out_of_stock')}}.">
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
                                                    @foreach (json_decode($product->choice_options) as  $choice)
                                                        <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                                            <h6 class="fw-semibold">{{translate($choice->title)}}</h6>
                                                            <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2">
                                                                @foreach ($choice->options as $key =>$option)
                                                                    <li>
                                                                        <label>
                                                                            <input type="radio" hidden=""
                                                                                   id="{{ $choice->name }}-{{ $option }}"
                                                                                   name="{{ $choice->name }}"
                                                                                   value="{{ $option }}"
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
                                                            <span class="quantity__minus single-quantity-minus" >
                                                                <i class="bi bi-dash"></i>
                                                            </span>
                                                            <input type="text"
                                                                   data-details-page="1"
                                                                   class="quantity__qty product_quantity__qty"
                                                                   name="quantity"
                                                                   value="{{ $product?->minimum_order_qty ?? 1 }}"
                                                                   min="{{ $product?->minimum_order_qty ?? 1 }}"
                                                                   max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                                            <span class="quantity__plus single-quantity-plus" {{($product->current_stock == 1?'disabled':'')}}>
                                                                <i class="bi bi-plus"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="product-generated-variation-code" name="product_variation_code">
                                                    <input type="hidden" value="" class="in_cart_key form-control w-50" name="key">
                                                    <div class="mx-w width--24rem">
                                                        <div class="bg-light w-100 rounded p-4">
                                                            <div class="flex-between-gap-3">
                                                                <div class="">
                                                                    <h6 class="flex-middle-gap-2 mb-2">
                                                                        <span
                                                                            class="text-muted">{{translate('total_price').':'}}</span>
                                                                        <span
                                                                            class="total_price">{{Helpers::currency_converter($product->unit_price)}}</span>
                                                                    </h6>
                                                                    <h6 class="flex-middle-gap-2">
                                                                        <span
                                                                            class="text-muted">{{translate('tax').':'}}</span>
                                                                        <span
                                                                            class="product_vat">{{ $product->tax_model == 'include' ? 'incl.' : Helpers::currency_converter($product->tax)}}</span>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mx-w d-flex flex-wrap gap-3 mt-4 width--24rem">
                                                        @if(($product->added_by == 'seller' && ($sellerTemporaryClose || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $currentDate >= $sellerVacationStartDate && $currentDate <= $sellerVacationEndDate))) ||
                                                        ($product->added_by == 'admin' && ($inHouseTemporaryClose || ($inHouseVacationStatus && $currentDate >= $inHouseVacationStartDate && $currentDate <= $inHouseVacationEndDate))))
                                                            <button type="button"
                                                                    class="btn btn-secondary fs-16 flex-grow-1"
                                                                    disabled>{{translate('buy_now')}}</span></button>
                                                            <button type="button"
                                                                    class="btn btn-primary fs-16 flex-grow-1 text-capitalize"
                                                                    data-bs-toggle="modal" data-bs-target="#buyNowModal"
                                                                    disabled>{{translate('add_to_cart')}}</button>
                                                        @else
                                                            @php($guest_checkout=getWebConfig(name: 'guest_checkout'))
                                                            <button type="button"
                                                                    class="btn btn-secondary fs-16 buy-now"
                                                                    data-form-id="add-to-cart-form"
                                                                    data-redirect-status="{{($guest_checkout==1 || Auth::guard('customer')->check()?'true':'false')}}"
                                                                    data-action="{{route('shop-cart')}}">{{translate('buy_now')}}</span>
                                                            </button>
                                                            <button type="button"
                                                                    class="btn btn-primary fs-16 text-capitalize add-to-cart"
                                                                    data-form-id="add-to-cart-form"
                                                                    data-update-text="{{ translate('update_cart') }}"
                                                                    data-add-text="{{ translate('add_to_cart') }}">
                                                                {{ translate('add_to_cart') }}</button>
                                                        @endif
                                                    </div>
                                                    @if(($product->added_by == 'seller' && ($sellerTemporaryClose || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $currentDate >= $sellerVacationStartDate && $currentDate <= $sellerVacationEndDate))) ||
                                                        ($product->added_by == 'admin' && ($inHouseTemporaryClose || ($inHouseVacationStatus && $currentDate >= $inHouseVacationStartDate && $currentDate <= $inHouseVacationEndDate))))

                                                        <div class="alert alert-danger mt-3" role="alert">
                                                            {{translate('this_shop_is_temporary_closed_or_on_vacation').'.'.translate('you_cannot_add_product_to_cart_from_this_shop_for_now')}}
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
                    <div class="card">
                        <div class="card-body">
                            <nav>
                                <div class="nav justify-content-center gap-4 nav--tabs" id="nav-tab" role="tablist">
                                    <button class="active text-capitalize" id="product-details-tab" data-bs-toggle="tab"
                                            data-bs-target="#product-details" type="button" role="tab"
                                            aria-controls="product-details"
                                            aria-selected="true">{{translate('product_details')}}</button>
                                    <button id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                            type="button" role="tab" aria-controls="reviews"
                                            aria-selected="false">{{translate("reviews")}}</button>
                                </div>
                            </nav>
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="product-details" role="tabpanel"
                                     aria-labelledby="product-details-tab" tabindex="0">
                                    <div class="details-content-wrap custom-height ov-hidden show-more--content active">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th class="border-0 text-capitalize">{{translate('details_description')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        @if($product->video_url != null && (str_contains($product->video_url, "youtube.com/embed/")))
                                                            <div class="col-12 mb-4 text-center">
                                                                <iframe width="560" height="315"
                                                                        src="{{$product->video_url}}">
                                                                </iframe>
                                                            </div>
                                                        @endif
                                                        <div class="rich-editor-html-content">
                                                            {!! $product->details !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <button class="btn btn-outline-primary see-more-details">{{translate('see_more')}}</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab"
                                     tabindex="0">
                                    <div class="details-content-wrap custom-height ov-hidden show-more--content active">
                                        <div class="row gy-4">
                                            <div class="col-lg-5">
                                                <div class="rating-review mx-auto text-center mb-30">
                                                    <h2 class="rating-review__title"><span
                                                            class="rating-review__out-of">{{round($overallRating[0], 1)}}</span>/5
                                                    </h2>
                                                    <div class="rating text-gold mb-2">
                                                        @for ($increment = 1; $increment <= 5; $increment++)
                                                            @if ($increment <= (int)$overallRating[0])
                                                                <i class="bi bi-star-fill"></i>
                                                            @elseif ($overallRating[0] != 0 && $increment <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                                                <i class="bi bi-star-half"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <div class="rating-review__info">
                                                        <span>{{$productReviews->total().' '.translate($productReviews->total() <=1 ? 'review' : 'reviews')}}</span>
                                                    </div>
                                                </div>
                                                <ul class="list-rating gap-10">
                                                    <li>
                                                        <span class="review-name">5 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[0] != 0?number_format($rating[0]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="95" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">4 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[1] != 0?number_format($rating[1]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="35" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">3 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[2] != 0?number_format($rating[2]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="35" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">2 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[3] != 0?number_format($rating[3]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="20" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="review-name">1 {{translate('star')}}</span>

                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: {{($rating[4] != 0?number_format($rating[4]*100 / array_sum($rating)):0)}}%"
                                                                 aria-valuenow="10" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="d-flex flex-wrap gap-3" id="product-review-list">
                                                    @foreach ($productReviews as $review)
                                                        <div class="card border-primary-light flex-grow-1">
                                                            <div class="media flex-wrap align-items-centr gap-3 p-3">
                                                                <div
                                                                    class="avatar overflow-hidden border rounded-circle size-3-437rem">
                                                                    <img alt="" class="img-fit dark-support"
                                                                        src="{{ getValidImage(path: 'storage/app/public/profile/'.(isset($review->user)?$review->user->image : ''), type: 'avatar') }}">
                                                                </div>
                                                                <div class="media-body d-flex flex-column gap-2">
                                                                    <div
                                                                        class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                                        <div>
                                                                            <h6 class="mb-1 text-capitalize">{{isset($review->user)?$review->user->f_name:translate('user_not_exist')}}</h6>
                                                                            <div
                                                                                class="d-flex gap-2 align-items-center">
                                                                                <div
                                                                                    class="star-rating text-gold fs-12">
                                                                                    @for ($inc=0; $inc < 5; $inc++)
                                                                                        @if ($inc < $review->rating)
                                                                                            <i class="bi bi-star-fill"></i>
                                                                                        @else
                                                                                            <i class="bi bi-star"></i>
                                                                                        @endif
                                                                                    @endfor
                                                                                </div>
                                                                                <span>({{$review->rating}}/5)</span>
                                                                            </div>
                                                                        </div>
                                                                        <div>{{ $review->created_at ? $review->created_at->format("d M Y h:i:s A") : ($review->updated_at->format("d M Y h:i:s A")) }}</div>
                                                                    </div>
                                                                    <p>{{$review->comment}}</p>
                                                                    @isset($review->attachment)
                                                                    <div
                                                                        class="d-flex flex-wrap gap-2 products-comments-img custom-image-popup-init">
                                                                        @foreach(json_decode($review->attachment) as $img)
                                                                            <a href="{{ getValidImage(path: 'storage/app/public/review/'.$img, type:'product') }}" class="custom-image-popup mx-3">
                                                                                <img class="remove-mask-img" alt=""
                                                                                    src="{{ getValidImage(path: 'storage/app/public/review/'.$img, type:'product') }}">
                                                                            </a>
                                                                        @endforeach
                                                                    </div>
                                                                   @endisset
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if(count($productReviews)==0)
                                                        <div class="d-flex justify-content-center align-items-center w-100">
                                                            <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
                                                                <img width="60" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-review.svg') }}" alt="">
                                                                <h5 class="text-center text-muted">
                                                                    {{ translate('No_review_yet') }}!
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        @if($productReviews->total() > 2)
                                            <button
                                                class="btn btn-outline-primary see-more-details-review m-1 view_text"
                                                id="see-more"
                                                data-product-id="{{$product->id}}"
                                                data-action="{{route('review-list-product')}}"
                                                data-after-extend="{{translate('see_less')}}"
                                                data-see-more="{{translate('see_more')}}"
                                                data-onerror="{{translate('no_more_review_remain_to_load')}}">{{translate('see_more')}}</button>
                                        @else
                                            <button
                                                class="btn btn-outline-primary see-more-details m-1">{{translate('see_more')}}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3 d-flex flex-column gap-3">
                    @if (count($moreProductFromSeller)>0)
                    <div class="card order-1 order-sm-0">
                        <div class="card-body">
                            <h5 class="mb-3 text-capitalize">
                                @if(getWebConfig(name: 'business_mode')=='multi')
                                    {{translate('more_from_the_store')}}
                                @else
                                    {{ translate('you_may_also_like')}}
                                @endif
                            </h5>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($moreProductFromSeller as $key => $item)
                                    <div class="card border-primary-light flex-grow-1">
                                        <a href="{{route('product',$item->slug)}}"
                                           class="media align-items-centr gap-3 p-3 ">
                                            <div class="avatar size-4-375rem">
                                                <img class="img-fit dark-support rounded img-fluid overflow-hidden"
                                                    alt=""
                                                    src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$item['thumbnail'], type: 'product') }}">
                                            </div>
                                            @php($itemReview = getOverallRating($item->reviews))
                                            <div class="media-body d-flex flex-column gap-2">
                                                <h6 class="text-capitalize">{{ Str::limit($item['name'], 18) }}</h6>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="star-rating text-gold fs-12">
                                                        @for ($index = 1; $index <= 5; $index++)
                                                            @if ($index <= (int)$itemReview[0])
                                                                <i class="bi bi-star-fill"></i>
                                                            @elseif ($itemReview[0] != 0 && $index <= (int)$itemReview[0] + 1.1 && $itemReview[0] > ((int)$itemReview[0]))
                                                                <i class="bi bi-star-half"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span>({{ count($item->reviews) }})</span>
                                                </div>
                                                <div class="product__price">
                                                    <ins class="product__new-price">
                                                        {{Helpers::currency_converter($item->unit_price-(Helpers::get_product_discount($item,$item->unit_price)))}}
                                                    </ins>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($product->added_by=='seller')
                        @if(isset($product->seller->shop))
                            <div class="card order-0 order-sm-1">
                                <div class="card-body">
                                    <div class="p-2 overlay shop-bg-card"
                                         data-bg-img="{{ getValidImage(path: 'storage/app/public/shop/banner/'.($product->seller->shop->banner), type: 'shop-banner') }}">
                                        <div class="media flex-wrap gap-3 p-2">
                                            <div class="avatar border rounded-circle size-3-437rem get-view-by-onclick cursor-pointer aspect-1 overflow-hidden d-flex align-items-center" data-link="{{ route('shopView',[$product->seller->id]) }}">
                                                <img alt="" class="img-fit dark-support rounded-circle"
                                                    src="{{ getValidImage(path: 'storage/app/public/shop/'.($product->seller->shop->image), type:'shop') }}">
                                            </div>
                                            <div class="media-body d-flex flex-column gap-2 text-absolute-whtie get-view-by-onclick" data-link="{{ route('shopView',[$product->seller->id]) }}">
                                                <div class="d-flex flex-column gap-1 justify-content-start">
                                                    <h5 class="cursor-pointer">{{$product->seller->shop->name}}</h5>
                                                    <div class="d-flex gap-2 align-items-center ">
                                                        <div class="star-rating text-gold fs-12">
                                                            @for ($increment = 1; $increment <= 5; $increment++)
                                                                @if ($increment <= (int)$avgRating)
                                                                    <i class="bi bi-star-fill"></i>
                                                                @elseif ($avgRating != 0 && $increment <= (int)$avgRating + 1.1 && $avgRating > ((int)$avgRating))
                                                                    <i class="bi bi-star-half"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span>({{$totalReviews}})</span>
                                                    </div>
                                                    <h6 class="fw-semibold">{{$productsForReview->count()}} {{translate('products')}}</h6>
                                                </div>

                                                <div class="mb-3">
                                                    <div class="text-center d-inline-block">
                                                        <h3 class="mb-1">{{round($positiveReview).'%'}}</h3>
                                                        <div class="fs-12">{{translate('positive_review')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (auth('customer')->id() == '')
                                                <div class="btn-circle chat-btn size-2-5rem"
                                                     data-bs-toggle="modal" data-bs-target="#loginModal">
                                                    <i class="bi bi-chat-square-dots"></i>
                                                </div>
                                            @else
                                                <div class="btn-circle chat-btn size-2-5rem"
                                                     data-bs-toggle="modal" data-bs-target="#contact_sellerModal">
                                                    <i class="bi bi-chat-square-dots"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <a href="{{ route('shopView',[$product->seller->id]) }}"
                                           class="btn btn-primary btn-block text-capitalize">{{translate('visit_store')}}</a>
                                    </div>
                                </div>
                            </div>

                            @include('theme-views.layouts.partials.modal._chat-with-seller',['shop'=>$product->seller->shop, 'user_type' => 'seller'])
                        @endif
                    @else
                        <div class="card  order-0 order-sm-1">
                            <div class="card-body">
                                <div class="p-2 overlay shop-bg-card"
                                     data-bg-img="{{dynamicStorage(path: 'storage/app/public/shop/'.getWebConfig(name: 'shop_banner'))}}">
                                    <div class="media flex-wrap gap-3 p-2">
                                        <div class="avatar border rounded-circle size-3-437rem cursor-pointer aspect-1 overflow-hidden d-flex align-items-center">
                                            <img alt="" class="img-fit dark-support rounded-circle"
                                                src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['fav_icon']->value), type:'shop') }}">
                                        </div>

                                        <div class="media-body d-flex flex-column gap-2 text-absolute-whtie">
                                            <div class="d-flex flex-column gap-1 justify-content-start">
                                                <h5 class="cursor-pointer get-view-by-onclick" data-link="{{ route('shopView',[0]) }}">{{$web_config['name']->value}}</h5>
                                                <div class="d-flex gap-2 align-items-center ">
                                                    <div class="star-rating text-gold fs-12">
                                                        @for ($index = 1; $index <= 5; $index++)
                                                            @if ($index <= (int)$avgRating)
                                                                <i class="bi bi-star-fill"></i>
                                                            @elseif ($avgRating != 0 && $index <= (int)$avgRating + 1.1 && $avgRating > ((int)$avgRating))
                                                                <i class="bi bi-star-half"></i>
                                                            @else
                                                                <i class="bi bi-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>

                                                    <span>({{$totalReviews}})</span>
                                                </div>
                                                <h6 class="fw-semibold">{{$productsForReview->count()}} {{translate('Products')}}</h6>

                                                <div class="mb-3">
                                                    <div class="text-center d-inline-block">
                                                        <h3 class="mb-1">{{round($positiveReview).'%'}}</h3>
                                                        <div class="fs-12">{{translate('positive_review')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if (auth('customer')->id() == '')
                                            <div class="btn-circle chat-btn size-2-5rem"
                                                 data-bs-toggle="modal" data-bs-target="#loginModal">
                                                <i class="bi bi-chat-square-dots"></i>
                                            </div>
                                        @else
                                            <div class="btn-circle chat-btn size-2-5rem"
                                                 data-bs-toggle="modal" data-bs-target="#contact_sellerModal">
                                                <i class="bi bi-chat-square-dots"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('shopView',[0]) }}"
                                       class="btn btn-primary btn-block text-capitalize">{{translate('visit_store')}}</a>
                                </div>
                            </div>
                        </div>

                        @include('theme-views.layouts.partials.modal._chat-with-seller',['shop'=>0, 'user_type' => 'admin'])
                    @endif
                </div>
            </div>
            @if (count($relatedProducts)>0)
                <div class="py-4 mt-3">
                    <div class="d-flex justify-content-between gap-3 mb-4">
                        <h2 class="text-capitalize">{{translate('similar_products_from_other_stores')}}</h2>
                        <div class="swiper-nav d-flex gap-2 align-items-center">
                            <div class="swiper-button-prev top-rated-nav-prev position-static rounded-10"></div>
                            <div class="swiper-button-next top-rated-nav-next position-static rounded-10"></div>
                        </div>
                    </div>
                    <div class="swiper-container">
                        <div class="position-relative">
                            <div class="swiper" data-swiper-loop="false" data-swiper-margin="20" data-swiper-autoplay="true"
                                 data-swiper-pagination-el="null" data-swiper-navigation-next=".top-rated-nav-next"
                                 data-swiper-navigation-prev=".top-rated-nav-prev"
                                 data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "320": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                                <div class="swiper-wrapper">
                                    @foreach($relatedProducts as $key=>$relatedProduct)
                                        <div class="swiper-slide">
                                            @include('theme-views.partials._similar-product-large-card',['product'=>$relatedProduct])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
@push('script')
    <script src="{{ theme_asset('assets/plugins/easyzoom/easyzoom.min.js') }}"></script>
    <script>
        'use strict';
        $(".easyzoom").each(function () {
            $(this).easyZoom();
        });
        getVariantPrice();
    </script>
@endpush
