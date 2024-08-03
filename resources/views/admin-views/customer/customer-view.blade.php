@extends('layouts.back-end.app')

@section('title', translate('customer_Details'))

@push('css_or_js')
    <link rel="stylesheet" href="{{dynamicAsset(path:'public/assets/back-end/css/owl.min.css')}}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="mb-3">
                        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                            {{translate('customer_details')}}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-xl-6 col-xxl-4 col--xxl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h4 class="mb-4 d-flex align-items-center gap-2">
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" alt="">
                            {{translate('customer').' # '.$customer['id']}}
                        </h4>

                        <div class="customer-details-new-card">
                            <img src="{{ getValidImage(path: 'storage/app/public/profile/'. $customer['image'] , type: 'backend-profile') }}"
                                alt="{{translate('image')}}">
                            <div class="customer-details-new-card-content">
                                <h6 class="name line--limit-2" data-toggle="tooltip" data-placement="top" title="{{$customer['f_name'].' '.$customer['l_name']}}">{{$customer['f_name'].' '.$customer['l_name']}}</h6>
                                <ul class="customer-details-new-card-content-list">
                                    <li>
                                        <span class="key">{{translate('contact')}}</span>
                                        <span class="mr-3">:</span>
                                        <strong class="value">{{!empty($customer['phone']) ? $customer['phone'] : translate('no_data_found')}}</strong>
                                    </li>
                                    <li>
                                        <span class="key">{{translate('email')}}</span>
                                        <span class="mr-3">:</span>
                                        <strong class="value">{{$customer['email'] ?? translate('no_data_found')}}</strong>
                                    </li>
                                    <li>
                                        <span class="key text-capitalize">{{translate('joined_date')}}</span>
                                        <span class="mr-3">:</span>
                                        <strong class="value">{{date('d M Y',strtotime($customer['created_at']))}}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(count($customer->addresses)>0)
                <div class="col-xl-6 col-xxl-8 col--xxl-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4 d-flex align-items-center gap-2 text-capitalize">{{translate('saved_address')}}</h4>
                            <div class="address-slider owl-theme owl-carousel">
                                @foreach($customer->addresses as $address)
                                    <div class="customer-address-card customer-details-new-card">
                                    <div class="customer-details-new-card-content">
                                        <h6 class="name text-14 mb-2">{{$address['address_type'].' ( '.translate($address['is_billing'] == 0 ? 'shipping_address': 'billing_address').' )'}} </h6>
                                        <ul class="customer-details-new-card-content-list">
                                            <li>
                                                <strong class="key">{{translate('name')}}</strong>
                                                <span class="mr-3">:</span>
                                                <span class="value">{{$address['contact_person_name']}}</span>
                                            </li>
                                            <li>
                                                <strong class="key">{{translate('phone')}}</strong>
                                                <span class="mr-3">:</span>
                                                <span class="value">{{$address['phone']}}</span>
                                            </li>
                                            <li>
                                                <strong class="key">{{translate('address')}}</strong>
                                                <span class="mr-3">:</span>
                                                <span class="value">{{$address['address']}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-xl-6 col-xxl-8 col--xxl-8 d-none d-lg-block">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4">
                                <a href="" class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/total-order.png')}}" alt="">
                                        <h6 class="order-stats__subtitle text-capitalize">{{translate('total_orders')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['total_order']}}</span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/ongoing.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('ongoing')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['ongoing']}}</span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/completed.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('completed')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['completed']}}</span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/canceled.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('canceled')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['canceled']}}</span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/returned.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('returned')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['returned']}}</span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-6 col-xxl-4">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/failed.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('failed')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['failed']}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-12 @if(count($customer->addresses)>0)@else d-lg-none @endif">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="order-statistics-slider owl-carousel owl-theme">
                            <div class="slide-item">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/total-order.png')}}" alt="">
                                        <h6 class="order-stats__subtitle text-capitalize">{{translate('total_orders')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['total_order']}}</span>
                                </a>
                            </div>
                            <div class="slide-item">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/ongoing.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('ongoing')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['ongoing']}}</span>
                                </a>
                            </div>
                            <div class="slide-item">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/completed.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('completed')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['completed']}}</span>
                                </a>
                            </div>
                            <div class="slide-item">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/canceled.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('canceled')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['canceled']}}</span>
                                </a>
                            </div>
                            <div class="slide-item">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/returned.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('returned')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['returned']}}</span>
                                </a>
                            </div>
                            <div class="slide-item">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/failed.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('failed')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['failed']}}</span>
                                </a>
                            </div>
                            <div class="slide-item">
                                <a class="order-stats">
                                    <div class="order-stats__content">
                                        <img width="20" src="{{dynamicAsset(path:'public/assets/back-end/img/customer/refunded.png')}}" alt="">
                                        <h6 class="order-stats__subtitle">{{translate('refunded')}}</h6>
                                    </div>
                                    <span class="order-stats__title text--title">{{$orderStatusArray['refunded']}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <h5 class="card-title m-0">{{translate('orders')}} <span class="badge badge-secondary">{{$orders->total()}}</span> </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="row">
                                <div class="col-auto">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="searchValue"
                                                class="form-control"
                                                placeholder="{{translate('search_orders')}}" aria-label="Search orders"
                                                value="{{ request('searchValue') }}"
                                                required>
                                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="dropdown text-nowrap">
                                <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.customer.order-list-export',[$customer['id'],'searchValue' => request('searchValue')])}}">
                                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                            {{translate('excel')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('sl')}}</th>
                                <th>{{translate('order_ID')}}</th>
                                <th>{{translate('total')}}</th>
                                <th>{{translate('order_Status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $key=>$order)
                                <tr>
                                    <td>{{$orders->firstItem()+$key}}</td>
                                    <td>
                                        <a href="{{route('admin.orders.details',['id'=>$order['id']])}}"
                                           class="title-color hover-c1">{{$order['id']}}</a>
                                    </td>
                                    <td>
                                        <div class="">
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order['order_amount']))}}
                                        </div>
                                        @if($order->payment_status=='paid')
                                            <span class="badge badge-soft-success">{{translate('paid')}}</span>
                                        @else
                                            <span class="badge badge-soft-danger">{{translate('unpaid')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order['order_status']=='pending')
                                            <span class="badge badge-soft-info fz-12">
                                                {{translate($order['order_status'])}}
                                            </span>

                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span class="badge badge-soft-warning fz-12">
                                                    {{str_replace('_',' ',$order['order_status'] == 'processing' ? translate('packaging'):translate($order['order_status']))}}
                                                </span>
                                        @elseif($order['order_status']=='confirmed')
                                            <span class="badge badge-soft-success fz-12">
                                                    {{translate($order['order_status'])}}
                                                </span>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-soft-danger fz-12">
                                                    {{translate('failed_to_deliver')}}
                                                </span>
                                        @elseif($order['order_status']=='delivered')
                                            <span class="badge badge-soft-success fz-12">
                                                    {{translate($order['order_status'])}}
                                                </span>
                                        @else
                                            <span class="badge badge-soft-danger fz-12">
                                                    {{translate($order['order_status'])}}
                                                </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-10">
                                            <a class="btn btn-outline--primary btn-sm edit square-btn"
                                               title="{{translate('view')}}"
                                               href="{{route('admin.orders.details',['id'=>$order['id']])}}"><i
                                                    class="tio-invisible"></i> </a>
                                            <a class="btn btn-outline-info btn-sm square-btn"
                                               title="{{translate('invoice')}}"
                                               target="_blank"
                                               href="{{route('admin.orders.generate-invoice',[$order['id']])}}"><i
                                                    class="tio-download"></i> </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            {!! $orders->links() !!}
                        </div>
                    </div>
                    @if(count($orders)==0)
                        @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path:'public/assets/back-end/js/owl.min.js')}}"></script>
    <script type="text/javascript">
        'use strict';
        $('.order-statistics-slider, .address-slider').owlCarousel({
            margin: 16,
            loop: false,
            autoWidth: true,
        })
    </script>
@endpush
