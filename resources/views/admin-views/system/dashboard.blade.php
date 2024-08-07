@php use App\Utils\Helpers; @endphp
@extends('layouts.back-end.app')
@section('title', translate('dashboard'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @if(auth('admin')->user()->admin_role_id==1 || Helpers::module_permission_check('dashboard'))
        <div class="content container-fluid">
            <div class="page-header pb-0 mb-0 border-0">
                <div class="flex-between align-items-center">
                    <div>
                        <h1 class="page-header-title">{{translate('welcome').' '.auth('admin')->user()->name}}</h1>
                        <p>{{ translate('monitor_your_business_analytics_and_statistics').'.'}}</p>
                    </div>
                </div>
            </div>
            <div class="card mb-2 remove-card-shadow">
                <div class="card-body">
                    <div class="row flex-between align-items-center g-2 mb-3">
                        <div class="col-sm-6">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business_analytics.png')}}"
                                     alt="">{{translate('business_analytics')}}</h4>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-sm-end">
                            <select class="custom-select w-auto" name="statistics_type" id="statistics_type">
                                <option
                                    value="overall" {{session()->has('statistics_type') && session('statistics_type') == 'overall'?'selected':''}}>
                                    {{ translate('overall_statistics')}}
                                </option>
                                <option
                                    value="today" {{session()->has('statistics_type') && session('statistics_type') == 'today'?'selected':''}}>
                                    {{ translate("todays_Statistics")}}
                                </option>
                                <option
                                    value="this_month" {{session()->has('statistics_type') && session('statistics_type') == 'this_month'?'selected':''}}>
                                    {{ translate("this_Months_Statistics")}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2" id="order_stats">
                        @include('admin-views.partials._dashboard-order-status',['data'=>$data])
                    </div>
                </div>
            </div>

            <div class="card mb-3 remove-card-shadow">
                <div class="card-body">
                    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-3">
                        <img width="20" class="mb-1" src="{{dynamicAsset(path: 'public/assets/back-end/img/admin-wallet.png')}}"
                             alt="">
                        {{translate('admin_wallet')}}
                    </h4>

                    <div class="row g-2" id="order_stats">
                        @include('admin-views.partials._dashboard-wallet-stats',['data'=>$data])
                    </div>
                </div>
            </div>

            <div class="row g-1">
                <div class="col-lg-8" id="order-statistics-div">
                    @include('admin-views.system.partials.order-statistics')
                </div>
                <div class="col-lg-4">
                    <div class="card remove-card-shadow h-100">
                        <div class="card-header">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0 ">
                                {{translate('user_overview')}}
                            </h4>
                        </div>
                        <div class="card-body justify-content-center d-flex flex-column">
                            <div>
                                <div class="position-relative">
                                    <div id="chart" class="d-flex justify-content-center"></div>
                                    <div class="total--orders">
                                        <h3>{{$data['getTotalCustomerCount']+$data['getTotalVendorCount']+$data['getTotalDeliveryManCount']}}</h3>
                                        <span>{{translate('user')}}</span>
                                    </div>
                                </div>
                                <div class="apex-legends flex-column">
                                    <div class="before-bg-017EFA">
                                        <span >{{translate('customer').' '.'('.$data['getTotalCustomerCount'].')'}} </span>
                                    </div>
                                    <div class="before-bg-51CBFF">
                                        <span class="text-capitalize">{{translate('vendor').' '.'('.$data['getTotalVendorCount'].')'}}</span>
                                    </div>
                                    <div class="before-bg-56E7E7">
                                        <span class="text-capitalize">{{translate('delivery_man').' '.'('.$data['getTotalDeliveryManCount'].')'}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="earn-statistics-div">
                    @include('admin-views.system.partials.earning-statistics')
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('admin-views.partials._top-customer',['top_customer'=>$data['top_customer']])
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('admin-views.partials._top-store-by-order',['top_store_by_order_received'=>$data['top_store_by_order_received']])
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('admin-views.partials._top-selling-store',['topVendorByEarning'=>$data['topVendorByEarning']])
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('admin-views.partials._most-rated-products',['mostRatedProducts'=>$data['mostRatedProducts']])
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('admin-views.partials._top-selling-products',['topSellProduct'=>$data['topSellProduct']])
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('admin-views.partials._top-delivery-man',['topRatedDeliveryMan'=>$data['topRatedDeliveryMan']])
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-12 mb-2 mb-sm-0">
                        <h3 class="text-center">{{translate('hi')}} {{auth('admin')->user()->name}}
                            {{' , '.translate('welcome_to_dashboard')}}.</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <span id="earning-statistics-url" data-url="{{ route('admin.dashboard.earning-statistics') }}"></span>
    <span id="order-status-url" data-url="{{ route('admin.dashboard.order-status') }}"></span>
    <span id="seller-text" data-text="{{ translate('vendor')}}"></span>
    <span id="message-commission-text" data-text="{{ translate('commission')}}"></span>
    <span id="in-house-text" data-text="{{ translate('In-house')}}"></span>
    <span id="customer-text" data-text="{{ translate('customer')}}"></span>
    <span id="store-text" data-text="{{ translate('store')}}"></span>
    <span id="product-text" data-text="{{ translate('product')}}"></span>
    <span id="order-text" data-text="{{ translate('order')}}"></span>
    <span id="brand-text" data-text="{{ translate('brand')}}"></span>
    <span id="business-text" data-text="{{ translate('business')}}"></span>
    <span id="orders-text" data-text="{{ $data['order'] }}"></span>
    <span id="user-overview-data"
          data-customer="{{$data['getTotalCustomerCount']}}"
          data-customer-title="{{ translate('customer') }}"
          data-vendor="{{$data['getTotalVendorCount']}}"
          data-vendor-title="{{ translate('vendor') }}"
          data-delivery-man="{{$data['getTotalDeliveryManCount']}}"
          data-delivery-man-title="{{ translate('delivery_man') }}"
    ></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/dashboard.js')}}"></script>
@endpush
