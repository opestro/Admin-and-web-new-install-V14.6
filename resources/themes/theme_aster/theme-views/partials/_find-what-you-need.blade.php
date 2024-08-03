@php
    use App\Utils\Helpers;
    use App\Utils\ProductManager;
@endphp
<section>
    <div class="container">
        <div class="flexible-grid lg-down-1 gap-3">
            @if(isset($footer_banner[0]))
                <div class="col-12 d-sm-none">
                    <a href="{{ $footer_banner[0]['url'] }}" class="ad-hover">
                        <img loading="lazy" class="dark-support rounded w-100" alt=""
                            src="{{ getValidImage(path: 'storage/app/public/banner/'.($footer_banner[0]['photo']), type:'banner') }}">
                    </a>
                </div>
            @endif
            @if(auth('customer')->check() && count($order_again)>0)
                <div class="bg-primary-light rounded p-3 d-none d-sm-block">
                    <h3 class="text-primary mb-3 mt-2 text-capitalize">{{ translate('order_again') }}</h3>
                    <p>{{ translate('want_to_order_your_usuals') }}
                        ? {{ translate('just_reorder_from_your_previous_orders').'.' }}</p>

                    <div class="d-flex flex-wrap gap-3 custom-scrollbar height-26-5-rem ">
                        @foreach($order_again as $order)
                            <div class="card rounded-10 flex-grow-1">
                                <div class="p-3">
                                    <h6 class="fs-12 text-primary mb-1">
                                        @if($order['order_status'] =='processing')
                                            {{ translate('packaging') }}
                                        @elseif($order['order_status'] =='failed')
                                            {{ translate('failed_to_deliver') }}
                                        @elseif($order['order_status'] == 'all')
                                            {{ translate('all') }}
                                        @else
                                            {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                        @endif
                                    </h6>
                                    <div
                                        class="fs-10">{{ translate('on') }} {{date('d M Y',strtotime($order['updated_at']))}}</div>

                                    <div class="bg-light my-2 rounded-10 p-4">
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            @foreach($order['details']->take(3) as $key=>$detail)
                                                <div>
                                                    <img width="42" alt="" class="dark-support rounded" loading="lazy"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.($detail['product']['thumbnail'] ?? ''), type: 'product') }}">
                                                </div>
                                            @endforeach

                                            @if(count($order['details']) > 3)
                                                <h6 class="fw-medium fs-12 text-center">
                                                    {{translate('+')}}{{ count($order['details'])-3 }} <br>
                                                    <a href="{{ route('account-order-details', ['order_id'=>$order['id']]) }}">{{ translate('more') }}</a>
                                                </h6>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div class="">
                                            <h6 class="fs-10 mb-2">
                                                <a href="{{ route('account-order-details', ['id' => $order['id']]) }}">{{ translate('order_ID').':'.' ' .'#' }}
                                                    {{ $order['id'] }}
                                                </a>
                                            </h6>
                                            <h6 class="text-capitalize">{{ translate('final_total').':' }}
                                                {{ Helpers::currency_converter($order['order_amount']) }}</h6>
                                        </div>
                                        <a href="javascript:" data-order-id="{{ $order['id'] }}"
                                           class="btn btn-primary order-again">{{ translate('order_again') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="d-none d-sm-block">
                    @if($sidebar_banner)
                        <a href="{{ $sidebar_banner['url'] }}">
                            <img alt="" class="dark-support rounded w-100"
                                src="{{ getValidImage(path: 'storage/app/public/banner/'.($sidebar_banner ? $sidebar_banner['photo'] : ''),type:'banner') }}">
                        </a>
                    @else
                        <img src="{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}"
                             class="dark-support rounded w-100" alt="">
                    @endif
                </div>
            @endif

            <div class="card">
                <div class="p-3 p-sm-4">
                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 mb-sm-4">
                        <h2><span class="text-primary">{{translate('find')}}</span> {{translate('what_you_need')}}</h2>
                        <div class="swiper-nav d-flex gap-2 align-items-center">
                            <div
                                class="swiper-button-prev find-what-you-need-nav-prev position-static rounded-10"></div>
                            <div
                                class="swiper-button-next find-what-you-need-nav-next position-static rounded-10"></div>
                        </div>
                    </div>
                    <div class="swiper-container">
                        <div class="position-relative d-none d-md-block">
                            <div class="swiper" data-swiper-loop="true" data-swiper-margin="16"
                                 data-swiper-speed="2000" data-swiper-pagination-el="null"
                                 data-swiper-navigation-next=".find-what-you-need-nav-next"
                                 data-swiper-navigation-prev=".find-what-you-need-nav-prev">
                                <div class="swiper-wrapper">
                                    @foreach($category_slider as $ke=>$all_category)
                                        <div class="swiper-slide align-items-start bg-white">
                                            <div class="flexible-grid md-down-1 gap-3 w-100 width-1fr">
                                                @foreach($all_category as $key=>$category)

                                                    <div class="bg-light rounded p-4">
                                                        <div
                                                            class="d-flex flex-wrap justify-content-between gap-3 mb-3 align-items-start">
                                                            <div class="">
                                                                <h5 class="mb-1 text-truncate width--16ch">
                                                                    {{$category['name']}}</h5>
                                                                <div
                                                                    class="text-muted">{{$category['product_count']}} {{translate('products')}}</div>
                                                            </div>

                                                            <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}"
                                                               class="btn-link">{{translate('view_all')}}<i
                                                                    class="bi bi-chevron-right text-primary"></i></a>
                                                        </div>
                                                        <div class="find-what-you-need-items">
                                                            @foreach($category['childes'] as $sub_category)
                                                                <a href="{{route('products',['id'=> $sub_category['id'],'data_from'=>'category','page'=>1])}}"
                                                                   class="d-flex flex-column gap-2 mb-3 align-items-center">
                                                                    <div
                                                                        class="img-wrap bg-white w-100 rounded justify-content-center d-flex">
                                                                        <div class="floting-text">
                                                                        <span class="truncate text-center">
                                                                            <span>
                                                                                {{
                                                                                    count($category['childes'])<4 && in_array($key, [0,1,2]) && !array_key_exists(++$key, $category['childes']) ?
                                                                                            ($sub_category['subCategoryProduct_count'] > 1 ? ($sub_category['subCategoryProduct_count']-1).'+' : $sub_category['subCategoryProduct_count'])
                                                                                    : $sub_category['subCategoryProduct_count']
                                                                                }}
                                                                            </span>
                                                                            {{ translate('products') }}
                                                                        </span>
                                                                        </div>
                                                                        <div
                                                                            class="ov-hidden rounded w-100 height--5rem">
                                                                            <img alt="" loading="lazy" class="dark-support img-fit"
                                                                                src="{{ getValidImage(path: 'storage/app/public/category/'.$sub_category['icon'], type:'category') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="truncate text-center">
                                                                        {{ $sub_category['name'] }}
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="position-relative d-md-none">
                            <div class="swiper" data-swiper-loop="true"
                                 data-swiper-speed="2000" data-swiper-margin="10" data-swiper-pagination-el="null"
                                 data-swiper-navigation-next=".find-what-you-need-nav-next"
                                 data-swiper-navigation-prev=".find-what-you-need-nav-prev">
                                <div class="swiper-wrapper">
                                    @foreach($final_category as $key=>$category)
                                        <div
                                            class="swiper-slide align-items-start d-block bg-light rounded p-3 p-sm-4 align-items-stretch">
                                            <div>
                                                <div
                                                    class="d-flex flex-wrap justify-content-between gap-3 mb-3 align-items-start">
                                                    <div class="">
                                                        <h5 class="mb-1 text-truncate width--16ch">
                                                            {{$category['name']}}</h5>
                                                        <div
                                                            class="text-muted">{{$category['product_count']}} {{translate('products')}}</div>
                                                    </div>

                                                    <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}"
                                                       class="btn-link">{{translate('view_all')}}<i
                                                            class="bi bi-chevron-right text-primary"></i></a>
                                                </div>

                                                <div class="auto-col gap-3 minWidth-3-75rem max-width-5rem">
                                                    @foreach($category['childes'] as $sub_category)
                                                        <a href="{{route('products',['id'=> $sub_category['id'],'data_from'=>'category','page'=>1])}}"
                                                           class="d-flex flex-column gap-2 mb-3 align-items-start">
                                                            <div
                                                                class="avatar avatar-xxl ov-hidden hover-zoom-in rounded">
                                                                <img alt="" loading="lazy" class="dark-support img-fit"
                                                                    src="{{ getValidImage(path: 'storage/app/public/category/'.$sub_category['icon'], type:'category') }}">
                                                            </div>
                                                            <div class="text-truncate">{{ $sub_category['name'] }}</div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(count($random_coupon)>0)
                <div class="col-12 d-sm-none">
                    <div class="bg-primary-light rounded p-3 mt-lg-3">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <h3 class="text-primary my-3 text-capitalize">{{ translate('happy_club') }}</h3>
                        </div>
                        <p>{{ translate('collect_coupons_from_stores_and_apply_to_get_special_discount_from_stores') }}</p>

                        <div class="d-flex flex-wrap gap-3">
                            @foreach($random_coupon as $coupon)
                                <div class="club-card card custom-border-color hover-shadow flex-grow-1 click-to-copy-code" data-copy-code="{{ $coupon->code }}">
                                    <div class="d-flex flex-column gap-2 p-3">
                                        <h5 class="d-flex gap-2 align-items-center">
                                            @if($coupon->coupon_type == 'free_delivery')
                                                {{translate($coupon->coupon_type)}}
                                                <img src="{{ theme_asset('assets/img/svg/delivery-car.svg') }}" alt=""
                                                     class="svg">
                                            @else
                                                {{ $coupon->discount_type == 'amount' ? Helpers::currency_converter($coupon->discount) : $coupon->discount.'%' .' '.translate('OFF')}}
                                                <img src="{{ theme_asset('assets/img/svg/dollar.svg') }}" alt=""
                                                     class="svg">
                                            @endif
                                        </h5>
                                        <h6 class="fs-12">
                                            <span class="text-muted">{{ translate('for') }}</span>
                                            <span class="text-uppercase ">
                                            @if($coupon->seller_id == '0')
                                                    {{ translate('all_shops') }}
                                                @elseif($coupon->seller_id == NULL)
                                                    {{ $web_config['name']->value }}
                                                @else
                                                    <a class="shop-name get-view-by-onclick"
                                                       data-link="{{isset($coupon->seller->shop) ? route('shopView',['id'=>$coupon->seller->shop['id']]) : 'javascript:'}}">
                                                    {{ isset($coupon->seller->shop) ? $coupon->seller->shop->name : translate('shop_not_found') }}
                                                </a>
                                                @endif
                                        </span>
                                        </h6>
                                        <h6 class="text-primary fs-12">{{ translate('code') }}: {{ $coupon->code }}</h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 d-sm-none">
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
</section>
