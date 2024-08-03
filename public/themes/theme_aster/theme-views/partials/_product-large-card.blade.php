@php use App\Utils\Helpers;use App\Utils\ProductManager;use Illuminate\Support\Str; @endphp
@php($overallRating = $product->reviews ? getOverallRating($product->reviews) : 0)
<div class="product border rounded text-center d-flex flex-column gap-10 ov-hidden cursor-pointer get-view-by-onclick"
     data-link="{{route('product',$product->slug)}}">
    <div class="product__top width--100 aspect-1">
        @if($product->discount > 0)
            <span class="product__discount-badge">
                <span>
                    @if ($product->discount_type == 'percent')
                        {{'-'.' '.round($product->discount, $web_config['decimal_point_settings'])}}%
                    @elseif($product->discount_type =='flat')
                        {{'-'.' '.Helpers::currency_converter($product->discount)}}
                    @endif
                </span>
            </span>
        @endif

        @if(isset($product->flash_deal_status) && $product->flash_deal_status)
            <div class="product__power-badge">
                <img src="{{ theme_asset('assets/img/svg/power.svg') }}" alt=""
                     class="svg text-white">
            </div>
        @endif
        @php($wishlist = count($product->wishList)>0 ? 1 : 0)
        @php($compare_list = count($product->compareList)>0 ? 1 : 0)
        <div class="product__actions d-flex flex-column gap-2">
            <a href="javascript:"
               data-action="{{route('store-wishlist')}}"
               data-product-id="{{$product['id']}}"
               id="wishlist-{{$product['id']}}"
               class="btn-wishlist stopPropagation add-to-wishlist wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
               title="{{ translate('add_to_wishlist') }}">
                <i class="bi bi-heart"></i>
            </a>
            <a href="javascript:"
               class="btn-compare stopPropagation add-to-compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
               data-action="{{route('product-compare.index')}}"
               data-product-id="{{$product['id']}}"
               id="compare_list-{{$product['id']}}" title="{{translate('add_to_compare_list')}}">
                <i class="bi bi-repeat"></i>
            </a>
            <a href="javascript:" class="btn-quickview stopPropagation get-quick-view"
               data-action="{{route('quick-view')}}"
               data-product-id="{{$product['id']}}" title="{{ translate('quick_view') }}">
                <i class="bi bi-eye"></i>
            </a>
        </div>

        <div class="product__thumbnail align-items-center d-flex h-100 justify-content-center">
            <img class="dark-support rounded" alt=""
                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
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
    <div class="product__summary d-flex flex-column align-items-center gap-1 pb-3 cursor-pointer">
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
            <span>( {{count($product->reviews)}} )</span>
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

        <div class="product__price d-flex justify-content-center flex-wrap column-gap-2">
            @if($product->discount > 0)
                <del class="product__old-price">{{Helpers::currency_converter($product->unit_price)}}</del>
            @endif
            <ins class="product__new-price">
                {{Helpers::currency_converter($product->unit_price-Helpers::get_product_discount($product,$product->unit_price))}}
            </ins>
        </div>
    </div>
</div>
