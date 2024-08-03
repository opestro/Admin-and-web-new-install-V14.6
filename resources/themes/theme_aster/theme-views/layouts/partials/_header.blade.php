@php
    use App\Models\Brand;
    use App\Models\Category;
    use App\Utils\Helpers;
@endphp
@if (isset($web_config['announcement']) && $web_config['announcement']['status']==1)
    <div class="offer-bar py-3 announcement-color" data-bg-img="{{theme_asset('assets/img/media/top-offer-bg.png')}}">
        <div class="d-flex gap-2 align-items-center">
            <div class="offer-bar-close">
                <i class="bi bi-x-lg"></i>
            </div>
            <div class="top-offer-text flex-grow-1 d-flex justify-content-center fw-semibold ">
                {{ $web_config['announcement']['announcement'] }}
            </div>
        </div>
    </div>
@endif

@php($categories = \App\Utils\CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting(dataLimit: 11))
@php($brands = \App\Utils\BrandManager::getActiveBrandWithCountingAndPriorityWiseSorting())
<header class="header">
    <div class="header-top py-2">
        <div class="container">
            <div class="d-flex align-items-center flex-wrap justify-content-between gap-2">
                <a href="tel:+{{ $web_config['phone']->value }}" class="d-flex gap-2 align-items-center direction-ltr">
                    <i class="bi bi-telephone text-primary"></i>
                    {{ $web_config['phone']->value }}
                </a>

                <ul class="nav justify-content-center justify-content-sm-end align-items-center gap-4">
                    <li>
                        <div class="language-dropdown">
                            @if($web_config['currency_model']=='multi_currency')
                                <button
                                    type="button"
                                    class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    {{session('currency_code')}} {{session('currency_symbol')}}
                                </button>
                                <ul class="dropdown-menu bs-dropdown-min-width--10rem">
                                    @foreach ($web_config['currencies'] as $key => $currency)
                                        <li class="currency-change" data-currency-code="{{$currency['code']}}">
                                            <a href="javascript:">{{ $currency->name }}</a>
                                        </li>
                                    @endforeach
                                    <span id="currency-route" data-currency-route="{{route('currency.change')}}"></span>
                                </ul>
                            @endif
                        </div>
                    </li>
                    <li>
                        <div class="language-dropdown">
                            <button
                                type="button"
                                class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                @php( $local = Helpers::default_lang())
                                @foreach(json_decode($language['value'],true) as $data)
                                    @if($data['code']==$local)
                                        <img width="20"
                                             src="{{theme_asset('assets/img/flags')}}/{{ $data['code'].'.png' }}"
                                             class="dark-support" alt="{{translate('Eng')}}"/>
                                        {{ ucwords($data['name']) }}
                                    @endif
                                @endforeach
                            </button>
                            <ul class="dropdown-menu bs-dropdown-min-width--10rem">
                                @foreach(json_decode($language['value'],true) as $key =>$data)
                                    @if($data['status']==1)
                                        <li class="change-language" data-action="{{route('change-language')}}" data-language-code="{{$data['code']}}">
                                            <a class="d-flex gap-2 align-items-center" href="javascript:">
                                                <img width="20" src="{{theme_asset('assets/img/flags')}}/{{ $data['code'].'.png' }}"
                                                     loading="lazy" class="dark-support" alt="{{$data['name']}}"/>
                                                {{ ucwords($data['name']) }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @if($web_config['business_mode'] == 'multi' && $web_config['seller_registration'])
                        <li class="d-none d-xl-block">
                            <a href="{{route('vendor.auth.registration.index')}}" class="d-flex">
                                <div class="fz-16 text-capitalize">{{ translate('become_a_vendor')}}</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="header-middle border-bottom py-2 d-none d-xl-block">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <a class="logo" href="{{route('home')}}">
                    <img class="dark-support svg h-45" alt="{{ translate('Logo') }}"
                         src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['web_logo']->value), type:'logo') }}">
                </a>
                <div class="search-box position-relative">
                    <form action="{{route('products')}}" type="submit">
                        <div class="d-flex">
                            <div class="select-wrap focus-border border border-end-logical-0 d-flex align-items-center">
                                <div class="border-end">
                                    <div class="dropdown search_dropdown">
                                        <button type="button"
                                                class="border-0 px-3 bg-transparent dropdown-toggle text-dark py-0 text-capitalize"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ translate('all_categories') }}
                                        </button>
                                        <input type="hidden" name="search_category_value" id="search_category_value"
                                               value="all">
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="d-flex text-capitalize" data-value="all" href="javascript:">
                                                    {{ translate('all_categories') }}
                                                </a>
                                            </li>
                                            @if($categories)
                                                @foreach($categories as $category)
                                                    <li>
                                                        <a class="d-flex" data-value="{{ $category->id }}"
                                                           href="javascript:">
                                                            {{ $category['name'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <input
                                    type="search"
                                    class="form-control border-0 focus-input search-bar-input" name="name"
                                    id="global-search"
                                    placeholder="{{ translate('search_for_items').'...' }}"
                                />
                            </div>
                            <input name="data_from" value="search" hidden>
                            <input name="page" value="1" hidden>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <div
                        class="card search-card __inline-13 position-absolute z-99 w-100 bg-white top-100 start-0 search-result-box"></div>
                </div>
                <div class="offer-btn">
                    @if($web_config['header_banner'])
                        <a href="{{ $web_config['header_banner']['url'] }}">
                            <img width="180" loading="lazy" class="dark-support" alt="{{ translate('image') }}"
                                src="{{ getValidImage(path: 'storage/app/public/banner/'.($web_config['header_banner']['photo']), type:'wide-banner') }}">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="header-main love-sticky py-2 py-lg-3 py-xl-0 shadow-sm">
        <div class="container">
            <aside class="aside d-flex flex-column d-xl-none">
                <div class="aside-close p-3 pb-2">
                    <i class="bi bi-x-lg"></i>
                </div>
                <div>
                    <div class="aside-body" data-trigger="scrollbar">
                        <form action="{{route('products')}}" class="mb-3">
                            <div class="search-bar">
                                <input type="search" name="name" class="form-control search-bar-input-mobile"
                                       autocomplete="off" placeholder="{{ translate('search_for_items').'...' }}">
                                <input name="data_from" value="search" hidden="">
                                <input name="page" value="1" hidden="">
                                <button type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div
                                class="card search-card __inline-13 position-absolute z-99 w-100 bg-white start-0 search-result-box d--none"></div>
                        </form>
                        <ul class="main-nav nav">
                            <li>
                                <a href="{{route('categories')}}">{{ translate('categories') }}</a>
                                <ul class="sub_menu">
                                    @foreach($categories as $key=>$category)
                                        <li>
                                            <a href="javascript:">
                                                <span class="get-view-by-onclick"
                                                      data-link="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{ $category['name'] }}</span>
                                            </a>
                                            @if ($category->childes->count() > 0)
                                                <ul class="sub_menu">
                                                    @foreach($category['childes'] as $subCategory)
                                                        <li>
                                                            <a href="javascript:">
                                                                <span class="get-view-by-onclick" data-link="{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}">{{$subCategory['name']}}</span>
                                                            </a>
                                                            @if($subCategory->childes->count()>0)
                                                                <ul class="sub_menu">
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
                                </ul>
                            </li>
                            <li>
                                <a href="{{route('home')}}">{{ translate('home') }}</a>
                            </li>
                            @if($web_config['featured_deals']->count()>0 || $web_config['flash_deals'])
                                <li>
                                    <a href="javascript:">{{ translate('offers')}}</a>
                                    <ul class="sub_menu">
                                        @if($web_config['featured_deals']->count()>0)
                                            <li>
                                                <a href="{{route('products',['data_from'=>'featured_deal'])}}">{{ translate('featured_Deal') }}</a>
                                            </li>
                                        @endif
                                        @if($web_config['flash_deals'])
                                            <li>
                                                <a href="{{route('flash-deals',[ $web_config['flash_deals']['id'] ?? 0])}}">{{ translate('flash_deal') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if($web_config['business_mode'] == 'multi')
                                <li>
                                    <a href="javascript:">{{ translate('stores') }}</a>
                                    <ul class="sub_menu">
                                        <li>
                                            <a href="{{ route('shopView',['id'=>0]) }}">
                                                {{ Str::limit($web_config['name']->value, 14) }}
                                            </a>
                                        </li>
                                        @foreach($web_config['shops'] as $shop)
                                            <li>
                                                <a href="{{route('shopView',['id'=>$shop['seller_id']])}}">{{Str::limit($shop->name, 14)}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if($web_config['brand_setting'])
                                <li>
                                    <a href="javascript:">{{ translate('brands') }}</a>
                                    <ul class="sub_menu">
                                        @foreach($brands as $brand)
                                            <li>
                                                <a href="{{ route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1]) }}">{{ $brand->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if ($web_config['discount_product']>0)
                                <li>
                                    <a class="d-flex gap-2 align-items-center text-capitalize"
                                       href="{{route('products',['data_from'=>'discounted','page'=>1])}}">
                                        {{ translate('discounted_products')}}
                                        <i class="bi bi-patch-check-fill text-warning"></i>
                                    </a>
                                </li>
                            @endif
                            @if($web_config['business_mode'] == 'multi' &&  $web_config['seller_registration'])
                                <li class="d-xl-none">
                                    <a href="{{route('vendor.auth.registration.index')}}" class="d-flex text-capitalize">
                                        <div class="fz-16 text-capitalize">{{ translate('become_a_vendor')}}</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="d-flex align-items-center gap-2 justify-content-between p-4">
                        <span class="text-dark">{{ translate('theme_mode') }}</span>
                        <div class="theme-bar p-1">
                            <button class="light_button active">
                                <img src="{{theme_asset('assets/img/svg/light.svg')}}" alt="{{translate('image')}}"
                                     class="svg">
                            </button>
                            <button class="dark_button">
                                <img src="{{theme_asset('assets/img/svg/dark.svg')}}" alt="{{translate('image')}}"
                                     class="svg">
                            </button>
                        </div>
                    </div>
                </div>

                @if(auth('customer')->check())
                    <div class="d-flex justify-content-center mb-5 pb-5 mt-auto px-4">
                        <a href="{{route('customer.auth.logout')}}"
                           class="btn btn-primary w-100">{{ translate('logout') }}</a>
                    </div>
                @else
                    <div class="d-flex justify-content-center mb-5 pb-5 mt-auto px-4">
                        <a href="" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-primary w-100">
                            {{ translate('login').'/'.translate('register')}}
                        </a>
                    </div>
                @endif
            </aside>

            <div class="d-flex justify-content-between gap-3 align-items-center position-relative">
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown d-none d-xl-block">
                        <button
                            class="btn btn-primary rounded-0 text-uppercase fw-bold fs-14 dropdown-toggle select-category-button text-capitalize"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-list fs-4"></i>
                            {{ translate('select_category')}}
                        </button>
                        <ul class="dropdown-menu dropdown--menu">
                            @foreach($categories as $key=>$category)
                                @if($key<8)
                                    <li class="{{ $category->childes->count() > 0 ? 'menu-item-has-children':'' }}">
                                        <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                                            {{$category['name']}}
                                        </a>
                                        @if ($category->childes->count() > 0)
                                            <ul class="sub-menu">
                                                @foreach($category['childes'] as $subCategory)
                                                    <li class="{{ $subCategory->childes->count()>0 ? 'menu-item-has-children':'' }}">
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
                                @endif
                            @endforeach
                            <li>
                                <a href="{{route('products',['data_from'=>'latest'])}}" class="btn-link text-primary">
                                    {{ translate('view_all') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="nav-wrapper">
                        <div class="d-xl-none">
                            <a class="logo" href="{{route('home')}}">
                                <img class="dark-support mobile-logo-cs" alt="{{ translate('logo') }}"
                                     src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['mob_logo']->value), type:'logo') }}">
                            </a>
                        </div>
                        <ul class="nav main-menu align-items-center d-none d-xl-flex flex-nowrap">
                            <li class="{{request()->is('/')?'active':''}}">
                                <a href="{{route('home')}}">{{ translate('home')}}</a>
                            </li>
                            @if($web_config['featured_deals']->count()>0 || $web_config['flash_deals'])
                                <li>
                                    <a class="cursor-pointer">{{ translate('offers')}}</a>
                                    <ul class="sub-menu">
                                        @if($web_config['featured_deals']->count()>0)
                                            <li>
                                                <a class="text-capitalize"
                                                   href="{{route('products',['data_from'=>'featured_deal'])}}">{{ translate('featured_deal') }}</a>
                                            </li>
                                        @endif

                                        @if($web_config['flash_deals'])
                                            <li>
                                                <a class="text-capitalize"
                                                   href="{{route('flash-deals',[$web_config['flash_deals']['id']??0])}}">{{ translate('flash_deal') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if($web_config['business_mode'] == 'multi')
                                <li>
                                    <a class="cursor-pointer">{{ translate('stores') }}</a>
                                    <div
                                        class="sub-menu megamenu p-3 bs-dropdown-min-width--max-content">
                                        <div class="d-flex gap-5">
                                            <div>
                                                <div class="column-2 row-gap-3">
                                                    <a href="{{route('shopView',['id'=>0])}}"
                                                       class="media gap-3 align-items-center border-bottom">
                                                        <div class="avatar rounded size-2-5rem">
                                                            <img loading="lazy" alt="{{ translate('image') }}"
                                                                 src="{{ getValidImage(path: 'storage/app/public/company/'.$web_config['fav_icon']->value, type:'shop') }}"
                                                                 class="img-fit rounded dark-support overflow-hidden">
                                                        </div>
                                                        <div class="media-body text-truncate width--7rem"
                                                             title="{{translate('morning_mart')}}">
                                                            {{Str::limit($web_config['name']->value, 14)}}
                                                        </div>
                                                    </a>

                                                    @foreach($web_config['shops'] as $shop)
                                                        <a href="{{route('shopView',['id'=>$shop['id']])}}"
                                                           class="media gap-3 align-items-center border-bottom">
                                                            <div class="avatar rounded size-2-5rem">
                                                                <img loading="lazy" alt="{{ translate('image') }}"
                                                                    src="{{ getValidImage(path: 'storage/app/public/shop/'.($shop->image), type: 'shop') }}"
                                                                    class="img-fit rounded dark-support overflow-hidden">
                                                            </div>
                                                            <div class="media-body text-truncate width--7rem"
                                                                 title="{{translate('morning_mart')}}">
                                                                {{Str::limit($shop->name, 14)}}
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                                <div class="d-flex">
                                                    <a href="{{route('vendors')}}"
                                                       class="fw-bold text-primary d-flex justify-content-center">
                                                        {{ translate('view_all').'...' }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="javascript:">
                                                    <img
                                                        width="277"
                                                        src="{{theme_asset('assets/img/media/super-market.png')}}"
                                                        class="dark-support"
                                                        alt="{{translate('image')}}"
                                                    />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if($web_config['brand_setting'])
                                <li>
                                    <a class="cursor-pointer">{{ translate('brands') }}</a>
                                    <div class="sub-menu megamenu p-3 bs-dropdown-min-width--max-content">
                                        <div class="d-flex gap-4">
                                            <div class="column-2">
                                                @foreach($brands as $brand)
                                                    <a href="{{ route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1]) }}"
                                                       class="media gap-3 align-items-center border-bottom">
                                                        <div class="avatar rounded-circle size-1-25rem">
                                                            <img class="img-fit rounded-circle dark-support"
                                                                src="{{ getValidImage(path: 'storage/app/public/brand/'.($brand->image), type: 'brand') }}"
                                                                loading="lazy" alt="{{ translate('image') }}"/>
                                                        </div>
                                                        <div class="media-body text-truncate width--7rem"
                                                             title="{{translate('bata')}}">
                                                            {{ $brand->name }}
                                                        </div>
                                                    </a>
                                                @endforeach
                                                <div class="d-flex">
                                                    <a href="{{route('brands')}}"
                                                       class="fw-bold text-primary d-flex justify-content-center">{{ translate('view_all').'...' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if ($web_config['discount_product']>0)
                                <li class="">
                                    <a class="d-flex gap-2 align-items-center discount-product-menu {{request()->is('/')?'active':''}}"
                                       href="{{route('products',['data_from'=>'discounted','page'=>1])}}">
                                        {{ translate('discounted_products') }}
                                        <i class="bi bi-patch-check-fill text-warning"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <ul class="list-unstyled list-separator mb-0 pe-2">
                    @if(auth('customer')->check())
                        <li class="login-register d-flex align-items-center gap-4">
                            <div class="profile-dropdown">
                                <button
                                    type="button"
                                    class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0 user"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <span class="avatar overflow-hidden header-avatar rounded-circle size-1-5rem">
                                      <img loading="lazy" class="img-fit" alt="{{ translate('image') }}"
                                          src="{{ getValidImage(path: 'storage/app/public/profile/'.(auth('customer')->user()->image), type:'avatar') }}">
                                    </span>
                                </button>
                                <ul class="dropdown-menu bs-dropdown-min-width--10rem">
                                    <li><a href="{{route('account-oder')}}">{{ translate('my_order') }}</a></li>
                                    <li><a href="{{route('user-profile')}}">{{ translate('my_profile') }}</a></li>
                                    <li><a href="{{route('customer.auth.logout')}}">{{ translate('logout') }}</a></li>
                                </ul>
                            </div>
                            <div class="menu-btn d-xl-none">
                                <i class="bi bi-list fs-30"></i>
                            </div>
                        </li>
                    @else
                        <li class="login-register d-flex gap-4">
                            <button
                                class="media gap-2 align-items-center text-uppercase fs-12 bg-transparent border-0 p-0"
                                data-bs-toggle="modal"
                                data-bs-target="#loginModal"
                            >
                                <span class="avatar header-avatar rounded-circle d-xl-none size-1-5rem">
                                    <img
                                        loading="lazy"
                                        src="{{theme_asset('assets/img/user.png')}}"
                                        class="img-fit rounded-circle"
                                        alt="{{translate('image')}}"
                                    />
                              </span>
                                <span
                                    class="media-body d-none d-xl-block hover-primary">{{ translate('login').'/'. translate('register')}}</span>
                            </button>
                            <div class="menu-btn d-xl-none">
                                <i class="bi bi-list fs-30"></i>
                            </div>
                        </li>
                    @endif
                    <li class="d-none d-xl-block">
                        @if(auth('customer')->check())
                            <a href="{{ route('product-compare.index') }}" class="position-relative">
                                <i class="bi bi-repeat fs-18"></i>
                                <span
                                    class="count compare_list_count_status">{{session()->has('compare_list')?count(session('compare_list')):0}}</span>
                            </a>
                        @else
                            <a href="javascript:" class="position-relative" data-bs-toggle="modal"
                               data-bs-target="#loginModal">
                                <i class="bi bi-repeat fs-18"></i>
                            </a>
                        @endif
                    </li>
                    <li class="d-none d-xl-block">
                        @if(auth('customer')->check())
                            <a href="{{ route('wishlists') }}" class="position-relative">
                                <i class="bi bi-heart fs-18"></i>
                                <span
                                    class="count wishlist_count_status">{{session()->has('wish_list') ? count(session('wish_list')):0}}</span>
                            </a>
                        @else
                            <a href="javascript:" class="position-relative" data-bs-toggle="modal"
                               data-bs-target="#loginModal">
                                <i class="bi bi-heart fs-18"></i>
                            </a>
                        @endif
                    </li>
                    <li class="d-none d-xl-block" id="cart_items">
                        @include('theme-views.layouts.partials._cart')
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
