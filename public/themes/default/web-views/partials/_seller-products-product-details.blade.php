@if(isset($product))
@php($overallRating = getOverallRating($product->reviews))
<div class="flash_deal_product rtl cursor-pointer mb-2 get-view-by-onclick"
    data-link="{{ route('product',$product->slug) }}">
    @if($product->discount > 0)
    <div class="d-flex position-absolute z-2">
        <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
            <span class="direction-ltr d-block">
                @if ($product->discount_type == 'percent')
                    -{{ round($product->discount,(!empty($decimalPointSettings) ? $decimalPointSettings: 0))}}%
                @elseif($product->discount_type =='flat')
                    -{{ webCurrencyConverter(amount: $product->discount) }}
                @endif
            </span>
        </span>
    </div>
    @endif
    <div class="d-flex">
        <div class="d-flex align-items-center justify-content-center p-3">
            <div class="flash-deals-background-image image-default-bg-color">
                <img class="__img-125px" alt="" src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
            </div>
        </div>
        <div class=" flash_deal_product_details pl-3 pr-3 pr-1 d-flex align-items-center">
            <div>
                <div>
                    <span class="flash-product-title">
                        {{$product['name']}}
                    </span>
                </div>
                @if($overallRating[0] != 0 )
                <div class="flash-product-review">
                    @for($inc=1;$inc<=5;$inc++) @if ($inc <=(int)$overallRating[0]) <i class="tio-star text-warning">
                        </i>
                        @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0]>
                            ((int)$overallRating[0]))
                            <i class="tio-star-half text-warning"></i>
                            @else
                            <i class="tio-star-outlined text-warning"></i>
                            @endif
                            @endfor
                            <label class="badge-style2">
                                ( {{ count($product->reviews) }} )
                            </label>
                </div>
                @endif
                <div class="d-flex flex-wrap gap-8 align-items-center row-gap-0">
                    @if($product->discount > 0)
                    <del class="category-single-product-price">
                        {{ webCurrencyConverter(amount: $product->unit_price) }}
                    </del>
                    @endif
                    <span class="flash-product-price fw-semibold text-dark">
                        {{ webCurrencyConverter(amount: $product->unit_price-getProductDiscount(product: $product, price: $product->unit_price)) }}
                    </span>
                </div>

            </div>
        </div>
    </div>
</div>
@endif
