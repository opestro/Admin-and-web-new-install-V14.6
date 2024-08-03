@php
    use App\Utils\Helpers;
    use App\Utils\ProductManager;
@endphp
<div class="swiper-slide">
    <a href="javascript:"
       class="store-product d-flex flex-column gap-2 align-items-center ov-hidden">
        <div class="store-product__top border rounded mb-2 aspect-1 overflow-hidden">
            @if(isset($product->flash_deal_status) && $product->flash_deal_status == 1)
                <div class="product__power-badge">
                    <img src="{{theme_asset('assets/img/svg/power.svg')}}" alt="" class="svg text-white">
                </div>
            @endif
            @if($product->discount > 0)
                <span class="product__discount-badge">
                    <span>
                         @if ($product->discount_type == 'percent')
                            {{'-'.' '.round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0)).'%'}}
                        @elseif($product->discount_type =='flat')
                            {{'-'.' '.Helpers::currency_converter($product->discount)}}
                        @endif
                    </span>
                </span>
            @else
            @endif
            <span class="store-product__action preventDefault get-quick-view"
                  data-action="{{route('quick-view')}}"
                  data-product-id="{{$product['id']}}">
                <i class="bi bi-eye fs-12"></i>
            </span>
            <img width="155" alt="" loading="lazy" class="dark-support rounded"
                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
        </div>
        <a class="fs-16 text-truncate text-muted text-capitalize width--9rem"  href="{{route('product',$product->slug)}}">
            {{ Str::limit($product['name'], 18) }}
            <div class="product__price d-flex justify-content-center align-items-center flex-wrap column-gap-2 mt-1">
                @if($product->discount > 0)
                    <del class="product__old-price">
                        {{Helpers::currency_converter($product->unit_price)}}
                    </del>
                @endif
                <ins class="product__new-price fs-14">
                    {{Helpers::currency_converter($product->unit_price-(Helpers::get_product_discount($product,$product->unit_price)))}}
                </ins>
            </div>
        </a>
    </a>
</div>

