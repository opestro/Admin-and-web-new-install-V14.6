@php use App\Utils\Helpers;use App\Utils\ProductManager;use Illuminate\Support\Str; @endphp
@php($overallRating = $product->reviews ? getOverallRating($product->reviews) : 0)
<div class="product border rounded text-center d-flex flex-column gap-10"
     onclick="location.href='{{route('product',$product->slug)}}'">
    <div class="product__top width--100 height-12-5-rem aspect-1">
        @if($product->discount > 0)
            <span class="product__discount-badge">
                -@if ($product->discount_type == 'percent')
                    {{round($product->discount, $web_config['decimal_point_settings'])}}%
                @elseif($product->discount_type =='flat')
                    {{Helpers::currency_converter($product->discount)}}
                @endif
            </span>
        @endif
        @if(isset($product->flash_deal_status) && $product->flash_deal_status)
            <div class="product__power-badge">
                <img src="{{ theme_asset('assets/img/svg/power.svg') }}" alt=""
                     class="svg text-white">
            </div>
        @endif
        @php($wishList = count($product->wishList)>0 ? 1 : 0)
        @php($compareList = count($product->compareList)>0 ? 1 : 0)
        <div class="product__actions d-flex flex-column gap-2">
            <a href="javascript:"
               data-action="{{route('store-wishlist')}}"
               data-product-id="{{$product['id']}}"
               id="wishlist-{{$product['id']}}"
               class="btn-wishlist stopPropagation add-to-wishlist wishlist-{{$product['id']}} {{($wishList == 1?'wishlist_icon_active':'')}}"
               title="Add to wishlist">
                <i class="bi bi-heart"></i>
            </a>
            <a href="javascript:"
               data-action="{{route('product-compare.index')}}"
               data-product-id="{{$product['id']}}"
               class="btn-compare stopPropagation add-to-compare compare_list-{{$product['id']}} {{($compareList == 1?'compare_list_icon_active':'')}}"
               title="{{ translate('add_to_compare') }}"
               id="compare_list-{{$product['id']}}">
                <i class="bi bi-repeat"></i>
            </a>
        </div>

        <div class="product__thumbnail align-items-center d-flex h-100 justify-content-center">
            <img src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                 loading="lazy" class="dark-support rounded" alt="">
        </div>
        @if(($product['product_type'] == 'physical') && ($product['current_stock'] < 1))
            <div class="product__notify">
                {{ translate('sorry_this_item_is_currently_sold_out') }}
            </div>
        @endif

        @if(isset($product->flash_deal_status) && $product->flash_deal_status)
            <div class="product__countdown d-flex gap-2 gap-sm-3 justify-content-center"
                 data-date="{{ $product->flash_deal_end_date }}">
                <div class="days d-flex flex-column gap-2"></div>
                <div class="hours d-flex flex-column gap-2"></div>
                <div class="minutes d-flex flex-column gap-2"></div>
                <div class="seconds d-flex flex-column gap-2"></div>
            </div>
        @endif
    </div>
    <div class="product__summary d-flex flex-column align-items-center gap-1 pb-3  cursor-pointer">
        <div class="d-flex gap-2 align-items-center">
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
            <span>( {{$product->reviews->count()}} )</span>
        </div>

        <div class="text-muted fs-12">
            @if($product->added_by=='seller')
                {{ isset($product->seller->shop->name) ? Str::limit($product->seller->shop->name, 20) : '' }}
            @elseif($product->added_by=='admin')
                {{$web_config['name']->value}}
            @endif
        </div>

        <h6 class="product__title text-truncate">
            {{ Str::limit($product['name'], 25) }}
        </h6>

        <div class="product__price d-flex flex-wrap column-gap-2">
            @if($product->discount > 0)
                <del class="product__old-price">{{Helpers::currency_converter($product->unit_price)}}</del>
            @endif
            <ins class="product__new-price">
                {{Helpers::currency_converter($product->unit_price-Helpers::get_product_discount($product,$product->unit_price))}}
            </ins>
        </div>
    </div>
</div>
