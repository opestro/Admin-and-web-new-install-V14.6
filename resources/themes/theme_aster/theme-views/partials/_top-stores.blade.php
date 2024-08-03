@php
    use App\Utils\Helpers;
    use App\Utils\ProductManager;
@endphp
<section>
    <div class="container">
        <div class="card">
            <div class="p-3 p-sm-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 mb-sm-4">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <h2><span class="text-primary">{{ translate('top') }}</span> {{ translate('stores') }}</h2>
                    </div>
                    <div class="swiper-nav d-flex gap-2 align-items-center">
                        <a href="{{ route('vendors', ['filter'=>'top-vendors']) }}" class="btn-link text-primary text-capitalize">{{ translate('view_all') }}</a>
                        <div class="swiper-button-prev top-stores-nav-prev position-static rounded-10"></div>
                        <div class="swiper-button-next top-stores-nav-next position-static rounded-10"></div>
                    </div>
                </div>
                <div class="swiper-container">
                    <div class="position-relative">
                        <div class="swiper" data-swiper-loop="true" data-swiper-margin="20"
                             data-swiper-pagination-el="null" data-swiper-navigation-next=".top-stores-nav-next"
                             data-swiper-navigation-prev=".top-stores-nav-prev"
                             data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "768": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}}'>
                            <div class="swiper-wrapper">
                                @foreach($topVendorsList as $vendorData)
{{--                                    @if($vendorData['id'] == 11)--}}
{{--                                        @dd($vendorData)--}}
{{--                                    @endif--}}
                                    @if($vendorData)
                                        <div class="swiper-slide align-items-start bg-light rounded">
                                            <div class="bg-light position-relative rounded p-3 p-sm-4 w-100">
                                                @if(isset($vendorData?->coupon_list) && count($vendorData?->coupon_list)>0)
                                                    <div class="offer-text">
                                                        {{ translate('USE_COUPON').':'}}
                                                        <span class="cursor-pointer coupon-copy"
                                                              data-copy-coupun="{{$vendorData?->coupon_list[0]['code']}}">
                                                            {{$vendorData?->coupon_list[0]['code']}}
                                                         </span>
                                                    </div>
                                                @endif
                                                <div class="{{ isset($vendorData?->seller?->coupon) && count($vendorData?->seller?->coupon)>0 ? 'mt-4' :'' }} mb-3">
                                                    <h5 class="mb-1"><a href="{{route('shopView',['id'=>$vendorData['id']])}}">{{ $vendorData->name }}</a>
                                                    </h5>
                                                    <div
                                                        class="text-muted">{{ $vendorData->products_count }} {{ translate('products') }}</div>
                                                    <div class="d-flex gap-2 align-items-center mt-1">
                                                        <div class="star-rating text-gold fs-12">
                                                            @for($inc=0;$inc<5;$inc++)
                                                                @if($inc<$vendorData->average_rating)
                                                                    <i class="bi bi-star-fill"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span>({{ $vendorData->review_count }})</span>
                                                    </div>
                                                </div>
                                                @if($vendorData->products)
                                                    <div class="auto-col gap-3 minWidth-3-75rem"
                                                         style="--maxWidth: {{ count($vendorData->products)==1 ? '6.5rem' : '1fr' }}">
                                                        @foreach($vendorData->products as $product)
                                                            <a href="{{route('product',$product['slug'])}}"
                                                               class="store-product d-flex flex-column gap-2 align-items-center">
                                                                <div class="store-product__top border rounded">
                                                                    <span class="store-product__action preventDefault get-quick-view"
                                                                            data-product-id = "{{$product['id']}}"
                                                                            data-action = "{{route('quick-view')}}"
                                                                            >
                                                                        <i class="bi bi-eye fs-12"></i>
                                                                    </span>
                                                                    <img width="100"
                                                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                                                                         alt="" loading="lazy"
                                                                         class="dark-support rounded">
                                                                </div>
                                                                <div
                                                                    class="product__price d-flex justify-content-center flex-wrap column-gap-2">
                                                                    @if($product['discount'] > 0)
                                                                        <del
                                                                            class="product__old-price">{{Helpers::currency_converter($product['unit_price'])}}</del>
                                                                    @endif
                                                                    <ins class="product__new-price">
                                                                        {{Helpers::currency_converter($product['unit_price']-Helpers::get_product_discount($product,$product['unit_price']))}}
                                                                    </ins>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($footer_banner[1]))
            <div class="col-12 mt-3 d-sm-none">
                <a href="{{ $footer_banner[1]['url'] }}" class="ad-hover">
                    <img src="{{ getValidImage(path: 'storage/app/public/banner/'.$footer_banner[1]['photo'], type:'banner') }}" loading="lazy"
                         class="dark-support rounded w-100" alt="">
                </a>
            </div>
        @endif
    </div>
</section>
