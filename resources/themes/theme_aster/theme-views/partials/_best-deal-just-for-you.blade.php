@php use App\Utils\Helpers;use App\Utils\ProductManager;use Illuminate\Support\Str; @endphp
<section>
    <div class="container">
        <div class="row g-3">
            @if(isset($deal_of_the_day->product))
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="p-30">
                            @php($overall_rating = getOverallRating($deal_of_the_day->product->reviews))
                            <div class="today-best-deal d-flex justify-content-between gap-2 gap-sm-3">
                                <div class="d-flex flex-column gap-1">
                                    <div class="mb-3 mb-sm-4">
                                        <div
                                            class="sub-title text-muted mb-1 text-capitalize">{{ translate('don’t_miss_the_chance').'!' }}
                                        </div>
                                        <h2 class="title text-primary fw-extra-bold text-capitalize">{{ translate('today’s_best_deal') }}</h2>
                                    </div>
                                    <div class="mb-3 mb-sm-4 d-flex flex-column gap-1">
                                        <h6 class="text-capitalize">{{ Str::limit($deal_of_the_day->product->name,30) }}</h6>
                                        <div class="d-flex gap-2 align-items-center">
                                            <div class="star-rating text-gold fs-12">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $overall_rating[0])
                                                        <i class="bi bi-star-fill"></i>
                                                    @elseif ($overall_rating[0] != 0 && $i <= $overall_rating[0] + 1.1)
                                                        <i class="bi bi-star-half"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span>({{ $deal_of_the_day->product->reviews->count() }})</span>
                                        </div>

                                        <div class="product__price d-flex flex-wrap align-items-end gap-2 mt-2">
                                            @if($deal_of_the_day->product->discount > 0)
                                                <del
                                                    class="product__old-price">{{Helpers::currency_converter($deal_of_the_day->product->unit_price)}}</del>
                                            @endif
                                            <ins class="product__new-price">
                                                {{ Helpers::currency_converter($deal_of_the_day->product->unit_price-Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price)) }}
                                            </ins>
                                            <span class="product__save-amount">{{ translate('save') }}
                                                {{ Helpers::currency_converter(Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price)) }}
                                        </span>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <a href="{{route('product',$deal_of_the_day->product->slug)}}"
                                           class="btn btn-primary text-capitalize">{{ translate('buy_now') }}</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img width="309" alt="" class="dark-support rounded"
                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$deal_of_the_day->product->thumbnail, type: 'product') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="{{isset($deal_of_the_day->product) ? 'col-lg-6':'col-lg-12' }}">
                <div class="card h-100">
                    <div class="p-30">
                        <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 align-items-center">
                            <h3 class="mb-1">
                                <span class="text-primary">{{translate('just')}}</span>
                                {{translate('for_you')}}
                            </h3>
                        </div>
                        <div class="auto-col just-for-you gap-3">
                            @foreach($just_for_you as $key=>$product)
                                <a href="{{route('product',$product->slug)}}"
                                   class="hover-zoom-in d-flex flex-column gap-2 align-items-center">
                                    <div class="position-relative">
                                        @if($product->discount > 0)
                                            <span class="product__discount-badge">
                                                <span>
                                                    @if ($product->discount_type == 'percent')
                                                        {{'-'.' '.round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}
                                                        {{translate('%')}}
                                                    @elseif($product->discount_type =='flat')
                                                        {{'-'.' '.Helpers::currency_converter($product->discount)}}
                                                    @endif
                                                </span>
                                            </span>
                                        @endif
                                        <img width="100" alt="" loading="lazy" class="dark-support rounded"
                                             src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                                    </div>
                                    <div class="product__price d-flex flex-wrap justify-content-center column-gap-2">
                                        @if($product->discount > 0)
                                            <del class="product__old-price">
                                                {{Helpers::currency_converter($product->unit_price)}}
                                            </del>
                                        @endif
                                        <ins
                                            class="product__new-price">{{Helpers::currency_converter($product->unit_price-(Helpers::get_product_discount($product,$product->unit_price)))}}</ins>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
