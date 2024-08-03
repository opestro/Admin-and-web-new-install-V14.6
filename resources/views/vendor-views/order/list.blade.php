@extends('layouts.back-end.app-seller')
@section('title', translate('order_List'))

@push('css_or_js')
    <link href="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{dynamicAsset(path: 'assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
                <span class="page-header-title">
                    @if($status =='processing')
                        {{translate('packaging')}}
                    @elseif($status =='failed')
                        {{translate('failed_to_Deliver')}}
                    @elseif($status == 'all')
                        {{translate('all')}}
                    @else
                        {{translate(str_replace('_',' ',$status))}}
                    @endif
                </span>
                {{translate('orders')}}
            </h2>
            <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('vendor.orders.list',['status'=>request('status')])}}" id="form-data"
                      method="GET">
                    <div class="row gx-2">
                        <div class="col-12">
                            <h4 class="mb-3 text-capitalize">{{translate('filter_order')}}</h4>
                        </div>
                        @if(request('delivery_man_id'))
                            <input type="hidden" name="delivery_man_id" value="{{ request('delivery_man_id') }}">
                        @endif

                        @if (request('status')=='all' || request('status')=='delivered')
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color" for="filter">{{translate('order_Type')}}</label>
                                    <select name="filter" id="filter" class="form-control select2-selection__arrow">
                                        <option
                                            value="all" {{ $filter == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                        <option
                                            value="default_type" {{ $filter == 'default_type' ? 'selected' : '' }}>{{translate('website_Order')}}</option>
                                        @if(($status == 'all' || $status == 'delivered') && $sellerPos == 1 && !request()->has('deliveryManId'))
                                            <option
                                                value="POS" {{ $filter == 'POS' ? 'selected' : '' }}>{{translate('POS_Order')}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color" for="customer">{{translate('customer')}}</label>

                                <input type="hidden" id='customer_id' name="customer_id"
                                       value="{{request('customer_id') ? request('customer_id') : 'all'}}">
                                <select
                                        id="customer_id_value"
                                        data-placeholder="
                                        @if($customer == 'all')
                                            {{translate('all_customer')}}
                                        @else
                                            {{$customer['name'] ?? $customer['f_name'].' '.$customer['l_name'].' '.'('.$customer['phone'].')'}}
                                        @endif"
                                        class="js-data-example-ajax form-control form-ellipsis"
                                >
                                    <option value="all">{{translate('all_customer')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <label class="title-color" for="date_type">{{translate('date_type')}}</label>
                            <div class="form-group">
                                <select class="form-control __form-control" name="date_type" id="date_type">
                                    <option
                                        value="this_year" {{ $dateType == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                    <option
                                        value="this_month" {{ $dateType == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                    <option
                                        value="this_week" {{ $dateType == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                    <option
                                        value="custom_date" {{ $dateType == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3" id="from_div">
                            <label class="title-color" for="customer">{{translate('start_date')}}</label>
                            <div class="form-group">
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl-3" id="to_div">
                            <label class="title-color" for="customer">{{translate('end_date')}}</label>
                            <div class="form-group">
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{route('vendor.orders.list',['status'=>request('status')])}}"
                                   class="btn btn-secondary px-5">
                                    {{translate('reset')}}
                                </a>
                                <button type="submit" class="btn btn--primary px-5" id="formUrlChange" data-action="{{ url()->current() }}">
                                    {{translate('show_data')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="px-3 py-4 light-bg">
                    <div class="row g-2 align-items-center flex-grow-1">
                        <div class="col-md-4">
                            <h5 class="text-capitalize d-flex gap-1">
                                {{translate('order_list')}}
                                <span class="badge badge-soft-dark radius-50 fz-12">{{$orders->total()}}</span>
                            </h5>
                        </div>
                        <div class="col-md-8 d-flex gap-3 flex-wrap flex-sm-nowrap justify-content-md-end">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                           placeholder="{{translate('search_orders')}}" aria-label="Search orders"
                                           value="{{ $searchValue }}" required>
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </div>
                            </form>

                            <div class="dropdown">
                                <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('vendor.orders.export-excel', ['delivery_man_id' => request('delivery_man_id'), 'status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'searchValue' => $searchValue,'seller_id'=>$vendorId,'customer_id'=>$customerId, 'date_type'=>$dateType]) }}">
                                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                            {{translate('excel')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable"
                           class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th class="text-capitalize">{{translate('SL')}}</th>
                            <th class="text-capitalize">{{translate('order_ID')}}</th>
                            <th class="text-capitalize">{{translate('order_Date')}}</th>
                            <th class="text-capitalize">{{translate('customer_info')}}</th>
                            <th class="text-capitalize">{{translate('total_amount')}}</th>
                            @if($status == 'all')
                                <th class="text-capitalize">{{translate('order_Status')}} </th>
                            @else
                                <th class="text-capitalize">{{translate('payment_method')}} </th>
                            @endif
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $key=>$order)
                            <tr>
                                <td>
                                    {{$orders->firstItem()+$key}}
                                </td>
                                <td>
                                    <a class="title-color hover-c1"
                                       href="{{route('vendor.orders.details',$order['id'])}}">{{$order['id']}} {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                                </td>
                                <td>
                                    <div>{{date('d M Y',strtotime($order['created_at']))}}</div>
                                    <div>{{date('H:i A',strtotime($order['created_at']))}}</div>
                                </td>
                                <td>
                                    @if($order->is_guest)
                                        <strong class="title-name">{{translate('guest_customer')}}</strong>
                                    @elseif($order->customer_id == 0)
                                        <strong class="title-name">{{translate('walking_customer')}}</strong>
                                    @else
                                        @if($order->customer)
                                            <span class="text-body text-capitalize" >
                                                <strong class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                            </span>
                                            @if($order->customer['phone'])
                                                <a class="d-block title-color" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                            @else
                                                <a class="d-block title-color" href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                            @endif
                                        @else
                                            <label class="badge badge-danger fz-12">{{translate('invalid_customer_data')}}</label>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        @php($discount = 0)
                                        @if($order->coupon_discount_bearer == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                            @php($discount = $order->discount_amount)
                                        @endif

                                        @php($free_shipping = 0)
                                        @if($order->is_shipping_free)
                                            @php($free_shipping = $order->shipping_cost)
                                        @endif

                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->order_amount+$discount+$free_shipping), currencyCode: getCurrencyCode())}}
                                    </div>

                                    @if($order->payment_status=='paid')
                                        <span class="badge badge-soft-success">{{translate('paid')}}</span>
                                    @else
                                        <span class="badge badge-soft-danger">{{translate('unpaid')}}</span>
                                    @endif
                                </td>
                                @if($status == 'all')
                                    <td class="text-capitalize">
                                        @if($order->order_status=='pending')
                                            <label
                                                class="badge badge-soft-primary">{{$order['order_status']}}</label>
                                        @elseif($order->order_status=='processing' || $order->order_status=='out_for_delivery')
                                            <label
                                                class="badge badge-soft-warning">{{str_replace('_',' ',$order['order_status'] == 'processing' ? 'packaging' : $order['order_status'])}}</label>
                                        @elseif($order->order_status=='delivered' || $order->order_status=='confirmed')
                                            <label
                                                class="badge badge-soft-success">{{$order['order_status']}}</label>
                                        @elseif($order->order_status=='returned')
                                            <label
                                                class="badge badge-soft-danger">{{$order['order_status']}}</label>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-danger fz-12">
                                                    {{translate('failed_to_deliver')}}
                                            </span>
                                        @else
                                            <label
                                                class="badge badge-soft-danger">{{$order['order_status']}}</label>
                                        @endif
                                    </td>
                                @else
                                    <td class="text-capitalize">
                                        {{str_replace('_',' ',$order['payment_method'])}}
                                    </td>
                                @endif
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="btn btn-outline--primary btn-sm square-btn"
                                           title="{{translate('view')}}"
                                           href="{{route('vendor.orders.details',[$order['id']])}}">
                                            <i class="tio-invisible"></i>

                                        </a>
                                        <a class="btn btn-outline-info btn-sm square-btn" target="_blank"
                                           title="{{translate('invoice')}}"
                                           href="{{route('vendor.orders.generate-invoice',[$order['id']])}}">
                                            <i class="tio-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="d-flex justify-content-lg-end">
                        {{$orders->links()}}
                    </div>
                </div>

                @if(count($orders)==0)
                    @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>

    <span id="message-date-range-text" data-text="{{ translate("invalid_date_range") }}"></span>
    <span id="js-data-example-ajax-url" data-url="{{ route('vendor.orders.customers') }}"></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/order.js')}}"></script>
@endpush
