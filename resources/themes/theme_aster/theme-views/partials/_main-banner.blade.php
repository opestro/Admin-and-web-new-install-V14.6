@php
    use App\Utils\Helpers;
@endphp
<section class="banner">
    <div class="container">
        <div class="card moble-border-0">
            <div class="p-0 p-sm-3 m-sm-1">
                <div class="row g-3">
                    <div class="col-xl-3 col-lg-4 d-none d-xl-block">
                        <div class="">
                            <ul class="dropdown-menu dropdown-menu--static bs-dropdown-min-width--auto">
                                @foreach($categories as $key=>$category)
                                    <li class="{{ $category->childes->count() > 0 ? 'menu-item-has-children' : '' }}">
                                        <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                                            {{$category['name']}}
                                        </a>
                                        @if ($category->childes->count() > 0)
                                            <ul class="sub-menu">
                                                @foreach($category['childes'] as $subCategory)
                                                    <li class="{{ $subCategory->childes->count()>0 ? 'menu-item-has-children' : '' }}">
                                                        <a href="{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}">
                                                            {{$subCategory['name']}}
                                                        </a>
                                                        @if($subCategory->childes->count()>0)
                                                            <ul class="sub-menu">
                                                                @foreach($subCategory['childes'] as $subSubCategory)
                                                                    <li>
                                                                        <a href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">
                                                                            {{$subSubCategory['name']}}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                                <li class="d-flex justify-content-center mt-3 py-0">
                                    <a href="{{route('products',['data_from'=>'latest'])}}"
                                       class="btn-link text-primary">
                                        {{ translate('view_all') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row g-2 g-sm-3 mt-lg-0">
                            <div class="col-12">
                                <div class="swiper-container shadow-sm rounded">
                                    <div class="swiper" data-swiper-loop="true"
                                         data-swiper-navigation-next="null" data-swiper-navigation-prev="null">
                                        <div class="swiper-wrapper">
                                            @foreach($main_banner as $key=>$banner)
                                                <div class="swiper-slide">
                                                    <a href="{{ $banner['url'] }}" class="h-100">
                                                        <img loading="lazy" alt="" class="dark-support rounded"
                                                            src="{{ getValidImage(path: 'storage/app/public/banner/'.$banner['photo'], type:'banner') }}">
                                                    </a>
                                                </div>
                                            @endforeach
                                            @if(count($main_banner)==0)
                                                <img src="{{ theme_asset('assets/img/image-place-holder-2_1.png') }}"
                                                     loading="lazy" alt="" class="dark-support rounded">
                                            @endif
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                </div>
                            </div>
                            @foreach($footer_banner as $key=>$banner)
                                <div class="col-6 d-none d-sm-block">
                                    <a href="{{ $banner['url'] }}" class="ad-hover h-100">
                                        <img src="{{ getValidImage(path: 'storage/app/public/banner/'.$banner['photo'], type:'banner') }}"
                                             loading="lazy" alt="" class="dark-support rounded w-100 img-fit">
                                    </a>
                                </div>
                            @endforeach
                            @if(count($footer_banner)==0)
                                <div class="col-6 d-none d-sm-block">
                                    <span class="ad-hover h-100">
                                        <img src="{{ getValidImage(path: '', type:'banner') }}"
                                             loading="lazy" alt=""
                                             class="dark-support rounded w-100 img-fit">
                                    </span>
                                </div>
                                <div class="col-6 d-none d-sm-block">
                                    <span class="ad-hover h-100">
                                        <img src="{{ getValidImage(path: '', type:'banner') }}"
                                             loading="lazy" alt=""
                                             class="dark-support rounded w-100 img-fit">
                                    </span>
                                </div>
                            @endif
                            @if(count($footer_banner)==1)
                                <div class="col-6 d-none d-sm-block">
                                    <span class="ad-hover h-100">
                                        <img src="{{ getValidImage(path: '', type:'banner') }}"
                                             loading="lazy" alt=""
                                             class="dark-support rounded w-100">
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(count($random_coupon)>0)
                        <div class="col-xl-3 d-none d-sm-block">
                            <div class="bg-primary-light rounded p-3 mt-lg-3">
                                <h3 class="text-primary my-3">{{ translate('Happy_Club') }}</h3>
                                <p>{{ translate('collect_coupons_from_stores_and_apply_to_get_special_discount_from_stores') }}</p>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($random_coupon as $coupon)
                                        <div
                                            class="club-card card custom-border-color hover-shadow flex-grow-1 click-to-copy-code"
                                            data-copy-code="{{ $coupon->code }}">
                                            <div class="d-flex flex-column gap-2 p-3">
                                                <h5 class="d-flex gap-2 align-items-center">
                                                    @if($coupon->coupon_type == 'free_delivery')
                                                        {{translate($coupon->coupon_type)}}
                                                        <img src="{{ theme_asset('assets/img/svg/delivery-car.svg') }}"
                                                             alt="" class="svg">
                                                    @else
                                                        {{ $coupon->discount_type == 'amount' ? Helpers::currency_converter($coupon->discount) : $coupon->discount.'%'}}
                                                        {{translate('off')}}
                                                        <img src="{{ theme_asset('assets/img/svg/dollar.svg') }}" alt=""
                                                             class="svg">
                                                    @endif
                                                </h5>
                                                <h6 class="fs-12">
                                                    <span class="text-muted">{{ translate('for') }}</span>
                                                    <span class="text-uppercase ">
                                                    @if($coupon->seller_id == '0')
                                                            {{ translate('All_Shops') }}
                                                        @elseif($coupon->seller_id == NULL)
                                                            <a class="shop-name" href="{{route('shopView',['id'=>0])}}">
                                                            {{ $web_config['name']->value }}
                                                        </a>
                                                        @else
                                                            <a class="shop-name get-view-by-onclick"
                                                              data-link="{{isset($coupon->seller->shop) ? route('shopView',['id'=>$coupon->seller->shop['id']]) : 'javascript:'}}">
                                                            {{ isset($coupon->seller->shop) ? $coupon->seller->shop->name : translate('shop_not_found') }}
                                                        </a>
                                                        @endif
                                                </span>
                                                </h6>
                                                <h6 class="text-primary fs-12">{{ translate('code').':' }}{{ $coupon->code }}</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-xl-3 d-none d-sm-block">
                            @if($top_side_banner)
                                <a href="{{ $top_side_banner['url'] }}">
                                    <img alt="" class="dark-support rounded w-100"
                                        src="{{ getValidImage(path: 'storage/app/public/banner/'.($top_side_banner ? $top_side_banner['photo'] : ''), type:'banner', source: theme_asset('assets/img/top-side-banner-placeholder.png')) }}">
                                </a>
                            @else
                                <img src="{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}"
                                     class="dark-support rounded w-100" alt="">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
