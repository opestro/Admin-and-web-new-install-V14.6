@php use App\Utils\Helpers;use App\Utils\ProductManager;use Illuminate\Support\Str; @endphp
@php($overallRating = getOverallRating($product->reviews))
<div class="product border rounded text-center d-flex flex-column gap-10 get-view-by-onclick"
     data-link="{{route('product',$product->slug)}}">
    <div class="product__top width--100 height-12-5-rem aspect-1">
        @if($product->discount > 0)
            <span class="product__discount-badge">
                <span>
                    @if ($product->discount_type == 'percent')
                        {{'-'.' '.round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0)).'%'}}
                    @elseif($product->discount_type =='flat')
                        {{'-'.' '.Helpers::currency_converter($product->discount)}}
                    @endif
                </span>
            </span>
        @endif
        <div class="product__actions d-flex flex-column gap-2">
            @php($wishlist = count($product->wishList)>0 ? 1 : 0)
            @php($compare_list = count($product->compareList)>0 ? 1 : 0)
            <a class="btn-wishlist stopPropagation add-to-wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist == 1?'wishlist_icon_active':'')}}"
               data-action="{{route('store-wishlist')}}"
               data-product-id="{{$product['id']}}"
               title="{{translate('add_to_wishlist')}}">
                <i class="bi bi-heart"></i>
            </a>
            <a href="javascript:"
               class="btn-compare stopPropagation add-to-compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
               data-action="{{route('product-compare.index')}}"
               data-product-id="{{$product['id']}}"
               id="compare_list-{{$product['id']}}" title="{{translate('add_to_compare')}}">
                <i class="bi bi-repeat"></i>
            </a>
            <a href="javascript:" class="btn-quickview stopPropagation get-quick-view"
               data-action="{{route('quick-view')}}"
               data-product-id="{{$product['id']}}" title="{{translate('quick_view')}}"
            >
                <i class="bi bi-eye"></i>
            </a>
        </div>

        <div class="product__thumbnail align-items-center d-flex h-100 justify-content-center">
            <img src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                 loading="lazy" class="dark-support rounded"
                 alt="{{ $product['name'] }}">
        </div>
    </div>
    <div class="product__summary d-flex flex-column align-items-center gap-1 pb-3">
        <div class="d-flex gap-2 align-items-center">
            <span class="star-rating text-gold fs-12">
                @for ($index = 1; $index <= 5; $index++)
                    @if ($index <= (int)$overallRating[0])
                        <i class="bi bi-star-fill"></i>
                    @elseif ($overallRating[0] != 0 && $index <= (int)$overallRating[0] + 1.1 && $overallRating[0] == ((int)$overallRating[0]+.50))
                        <i class="bi bi-star-half"></i>
                    @else
                        <i class="bi bi-star"></i>
                    @endif
                @endfor
            </span>
            <span>({{ count($product->reviews) }})</span>
        </div>

        <div class="text-muted fs-12">
            @if($product->added_by=='seller')
                {{ isset($product->seller->shop->name) ? Str::limit($product->seller->shop->name, 20) : '' }}
            @elseif($product->added_by=='admin')
                {{ $web_config['name']->value }}
            @endif
        </div>

        <h6 class="product__title text-truncate width--80">
            <a href="{{route('product',$product->slug)}}"
               class="text-capitalize">{{ Str::limit($product['name'], 23) }}</a>
        </h6>
        <a href="{{route('product',$product->slug)}}">
            <div class="product__price d-flex flex-wrap column-gap-2">
                @if($product->discount > 0)
                    <del class="product__old-price">{{Helpers::currency_converter($product->unit_price)}}</del>
                @endif
                <ins class="product__new-price">
                    {{Helpers::currency_converter(
                        $product->unit_price-(Helpers::get_product_discount($product,$product->unit_price))
                    )}}
                </ins>
            </div>
        </a>
    </div>
</div>
