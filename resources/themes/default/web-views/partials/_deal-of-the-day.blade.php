@if(isset($product))
    <div class="container rtl">
        <div class="row g-4 pt-2 mt-0 pb-2 __deal-of align-items-start">
            <div class="col-xl-3 col-md-4">
                <div class="deal_of_the_day h-100 bg--light">
                    @if(isset($deal_of_the_day->product))
                        <div class="d-flex justify-content-center align-items-center py-4">
                            <h4 class="font-bold fs-16 m-0 align-items-center text-uppercase text-center px-2 web-text-primary">
                                {{ translate('deal_of_the_day') }}
                            </h4>
                        </div>
                        <div class="recommended-product-card mt-0 min-height-auto">
                            <div class="d-flex justify-content-center align-items-center __pt-20 __m-20-r">
                                <div class="position-relative">
                                    <img class="__rounded-top aspect-1 h-auto" alt=""
                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$deal_of_the_day->product['thumbnail'], type: 'product') }}">
                                    @if($deal_of_the_day->discount > 0)
                                        <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
                                            <span class="direction-ltr d-block">
                                                @if ($deal_of_the_day->discount_type == 'percent')
                                                    -{{round($deal_of_the_day->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                                @elseif($deal_of_the_day->discount_type =='flat')
                                                    -{{ webCurrencyConverter(amount: $deal_of_the_day->discount) }}
                                                @endif
                                            </span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="__i-1 bg-transparent text-center mb-0">
                                <div class="px-0">
                                    @php($overallRating = getOverallRating($deal_of_the_day->product['reviews']))
                                    @if($overallRating[0] != 0 )
                                        <div class="rating-show">
                                            <span class="d-inline-block font-size-sm text-body">
                                                @for($inc=1;$inc<=5;$inc++)
                                                    @if ($inc <= (int)$overallRating[0])
                                                        <i class="tio-star text-warning"></i>
                                                    @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1)
                                                        <i class="tio-star-half text-warning"></i>
                                                    @else
                                                        <i class="tio-star-outlined text-warning"></i>
                                                    @endif
                                                @endfor
                                                <label class="badge-style">( {{ count($deal_of_the_day->product['reviews']) }} )</label>
                                            </span>
                                        </div>
                                    @endif
                                    <h6 class="font-semibold pt-1">
                                        {{ Str::limit($deal_of_the_day->product['name'], 80) }}
                                    </h6>
                                    <div class="mb-4 pt-1 d-flex flex-wrap justify-content-center align-items-center text-center gap-8">

                                        @if($deal_of_the_day->product->discount > 0)
                                            <del class="fs-14 font-semibold __color-9B9B9B">
                                                {{ webCurrencyConverter(amount: $deal_of_the_day->product->unit_price) }}
                                            </del>
                                        @endif
                                        <span class="text-accent fs-18 font-bold text-dark">
                                            {{ webCurrencyConverter(amount:
                                                $deal_of_the_day->product->unit_price-(getProductDiscount(product: $deal_of_the_day->product, price: $deal_of_the_day->product->unit_price))
                                            ) }}
                                        </span>
                                    </div>
                                    <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase get-view-by-onclick"
                                            data-link="{{ route('product',$deal_of_the_day->product->slug) }}">
                                            {{translate('buy_now')}}
                                    </button>

                                </div>
                            </div>
                        </div>
                    @else
                        @if(isset($recommendedProduct))
                            <div class="d-flex justify-content-center align-items-center py-4">
                                <h4 class="font-bold fs-16 m-0 align-items-center text-uppercase text-center px-2 web-text-primary">
                                    {{ translate('recommended_product') }}
                                </h4>
                            </div>
                            <div class="recommended-product-card mt-0">

                                <div class="d-flex justify-content-center align-items-center __pt-20 __m-20-r">
                                    <div class="position-relative">
                                        <img src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$recommendedProduct['thumbnail'], type: 'product') }}"
                                            alt="">
                                        @if($recommendedProduct->discount > 0)
                                            <span class="for-discount-value p-1 pl-2 pr-2 font-bold fs-13">
                                                <span class="direction-ltr d-block">
                                                    @if ($recommendedProduct->discount_type == 'percent')
                                                        -{{ round($recommendedProduct->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                                    @elseif($recommendedProduct->discount_type =='flat')
                                                        -{{ webCurrencyConverter(amount: $recommendedProduct->discount) }}
                                                    @endif
                                                </span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="__i-1 bg-transparent text-center mb-0 min-height-auto">
                                    <div class="px-0 pb-0">
                                        @php($overallRating = getOverallRating($recommendedProduct['reviews']))
                                        @if($overallRating[0] != 0 )
                                            <div class="rating-show">
                                                <span class="d-inline-block font-size-sm text-body">
                                                    @for($inc=0;$inc<5;$inc++)
                                                        @if ($inc <= (int)$overallRating[0])
                                                            <i class="tio-star text-warning"></i>
                                                        @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                                            <i class="tio-star-half text-warning"></i>
                                                        @else
                                                            <i class="tio-star-outlined text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <label class="badge-style">( {{ count($recommendedProduct->reviews) }} )</label>
                                                </span>

                                            </div>
                                        @endif
                                        <h6 class="font-semibold pt-1">
                                            {{ Str::limit($recommendedProduct['name'],30) }}
                                        </h6>
                                        <div class="mb-4 pt-1 d-flex flex-wrap justify-content-center align-items-center text-center gap-8">
                                            @if($recommendedProduct->discount > 0)
                                                <del class="__text-12px __color-9B9B9B">
                                                    {{ webCurrencyConverter(amount: $recommendedProduct->unit_price) }}
                                                </del>
                                            @endif
                                            <span class="text-accent __text-22px text-dark">
                                                {{ webCurrencyConverter(amount:
                                                    $recommendedProduct->unit_price-(getProductDiscount(product: $recommendedProduct, price: $recommendedProduct->unit_price))
                                                ) }}
                                            </span>
                                        </div>
                                        <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase get-view-by-onclick"
                                                data-link="{{ route('product',$recommendedProduct->slug) }}">
                                            {{translate('buy_now')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-xl-9 col-md-8">
                <div class="latest-product-margin">
                    <div class="d-flex justify-content-between mb-14px">
                        <div class="text-center">
                            <span class="for-feature-title __text-22px font-bold text-center">
                                {{ translate('latest_products')}}
                            </span>
                        </div>
                        <div class="mr-1">
                            <a class="text-capitalize view-all-text web-text-primary"
                               href="{{route('products',['data_from'=>'latest'])}}">
                                {{ translate('view_all')}}
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                            </a>
                        </div>
                    </div>

                    <div class="row mt-0 g-2">
                        @foreach($latest_products as $product)
                            <div class="col-xl-3 col-sm-4 col-md-6 col-lg-4 col-6">
                                <div>
                                    @include('web-views.partials._inline-single-product',['product'=>$product,'decimal_point_settings'=>$decimal_point_settings])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
