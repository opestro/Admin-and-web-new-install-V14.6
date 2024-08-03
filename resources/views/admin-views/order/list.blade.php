@extends('layouts.back-end.app')
@section('title', translate('order_List'))

@section('content')
    <div class="content container-fluid">
        <div>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
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
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.orders.list',['status'=>request('status')])}}" id="form-data" method="GET">
                        <div class="row gx-2">
                            <div class="col-12">
                                <h4 class="mb-3 text-capitalize">{{translate('filter_order')}}</h4>
                            </div>
                            @if(request('delivery_man_id'))
                                <input type="hidden" name="delivery_man_id" value="{{ request('delivery_man_id') }}">
                            @endif

                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color text-capitalize" for="filter">{{translate('order_type')}}</label>
                                    <select name="filter" id="filter" class="form-control">
                                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                        <option value="admin" {{ $filter == 'admin' ? 'selected' : '' }}>{{translate('in_House_Order')}}</option>
                                        <option value="seller" {{ $filter == 'seller' ? 'selected' : '' }}>{{translate('vendor_Order')}}</option>
                                        @if(($status == 'all' || $status == 'delivered') && !request()->has('delivery_man_id'))
                                        <option value="POS" {{ $filter == 'POS' ? 'selected' : '' }}>{{translate('POS_Order')}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3" id="seller_id_area" style="{{ $filter && $filter == 'admin'?'display:none':'' }}">
                                <div class="form-group">
                                    <label class="title-color" for="store">{{translate('store')}}</label>
                                    <select name="seller_id" id="seller_id" class="form-control">
                                        <option value="all">{{translate('all_shop')}}</option>
                                        <option value="0" id="seller_id_inhouse" {{request('seller_id') == 0 ? 'selected' :''}}>{{translate('inhouse')}}</option>
                                        @foreach ($sellers as $seller)
                                            @isset($seller->shop)
                                                <option value="{{$seller->id}}"{{request('seller_id') == $seller->id ? 'selected' :''}}>
                                                    {{ $seller->shop->name }}
                                                </option>
                                            @endisset
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color" for="customer">{{translate('customer')}}</label>

                                    <input type="hidden" id='customer_id'  name="customer_id" value="{{request('customer_id') ? request('customer_id') : 'all'}}">
                                    <select  id="customer_id_value"
                                    data-placeholder="@if($customer == 'all')
                                                        {{translate('all_customer')}}
                                                    @else
                                                        {{$customer->name ?? $customer->f_name.' '.$customer->l_name.' '.'('.$customer->phone.')'}}
                                                    @endif"
                                    class="js-data-example-ajax form-control form-ellipsis">
                                        <option value="all">{{translate('all_customer')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <label class="title-color" for="date_type">{{translate('date_type')}}</label>
                                <div class="form-group">
                                    <select class="form-control __form-control" name="date_type" id="date_type">
                                        <option value="" selected disabled>{{translate('select_Date_Type')}}</option>
                                        <option value="this_year" {{ $dateType == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                        <option value="this_month" {{ $dateType == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                        <option value="this_week" {{ $dateType == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                        <option value="custom_date" {{ $dateType == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
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
                                    <a href="{{route('admin.orders.list',['status'=>request('status')])}}" class="btn btn-secondary px-5">
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
            <div class="card mt-3">
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
                                <form action="" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                            placeholder="{{translate('search_by_Order_ID')}}" aria-label="Search by Order ID" value="{{ $searchValue }}">
                                        <button type="submit" class="btn btn--primary input-group-text">{{translate('search')}}</button>
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
                                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.orders.export-excel', ['delivery_man_id' => request('delivery_man_id'), 'status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'searchValue' => $searchValue,'seller_id'=>$vendorId,'customer_id'=>$customerId, 'date_type'=>$dateType]) }}">
                                                <img width="14" src="{{asset('public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('order_ID')}}</th>
                                    <th class="text-capitalize">{{translate('order_date')}}</th>
                                    <th class="text-capitalize">{{translate('customer_info')}}</th>
                                    <th>{{translate('store')}}</th>
                                    <th class="text-capitalize">{{translate('total_amount')}}</th>
                                    @if($status == 'all')
                                        <th class="text-center">{{translate('order_status')}} </th>
                                    @else
                                        <th class="text-capitalize">{{translate('payment_method')}} </th>
                                    @endif
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $key=>$order)

                                <tr class="status-{{$order['order_status']}} class-all">
                                    <td class="">
                                        {{$orders->firstItem()+$key}}
                                    </td>
                                    <td >
                                        <a class="title-color" href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}} {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                                    </td>
                                    <td>
                                        <div>{{date('d M Y',strtotime($order['created_at']))}},</div>
                                        <div>{{ date("h:i A",strtotime($order['created_at'])) }}</div>
                                    </td>
                                    <td>
                                        @if($order->is_guest)
                                            <strong class="title-name">{{translate('guest_customer')}}</strong>
                                        @elseif($order->customer_id == 0)
                                            <strong class="title-name">{{translate('walking_customer')}}</strong>
                                        @else
                                            @if($order->customer)
                                                <a class="text-body text-capitalize" href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                    <strong class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                                </a>
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
                                        @if(isset($order->seller->shop))
                                            <a href="{{$order->seller_is == 'seller' && $order->seller->shop ? route('admin.vendors.view',['id'=>$order->seller->shop->id]) : 'javascript:' }}" class="store-name font-weight-medium">
                                                @if($order->seller_is == 'seller')
                                                    {{ isset($order->seller->shop) ? $order->seller->shop->name : translate('Store_not_found') }}
                                                @elseif($order->seller_is == 'admin')
                                                    {{translate('in_House')}}
                                                @endif
                                            </a>
                                        @else
                                            {{ translate('Store_not_found') }}
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            @php($discount = 0)
                                            @if($order->order_type == 'default_type' && $order->coupon_discount_bearer == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                                @php($discount = $order->discount_amount)
                                            @endif

                                            @php($free_shipping = 0)
                                            @if($order->is_shipping_free)
                                                @php($free_shipping = $order->shipping_cost)
                                            @endif

                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->order_amount), currencyCode: getCurrencyCode())}}
                                        </div>

                                        @if($order->payment_status=='paid')
                                            <span class="badge badge-soft-success">{{translate('paid')}}</span>
                                        @else
                                            <span class="badge badge-soft-danger">{{translate('unpaid')}}</span>
                                        @endif
                                    </td>
                                    @if($status == 'all')
                                        <td class="text-center text-capitalize">
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
                                                <span class="badge badge-danger fz-12">
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
                                    @else
                                        <td class="text-capitalize">
                                            {{str_replace('_',' ',$order['payment_method'])}}
                                        </td>
                                    @endif
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{translate('view')}}"
                                                href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                            </a>
                                            <a class="btn btn-outline-success square-btn btn-sm mr-1" target="_blank" title="{{translate('invoice')}}"
                                                href="{{route('admin.orders.generate-invoice',[$order['id']])}}">
                                                <i class="tio-download-to"></i>
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
                            {!! $orders->links() !!}
                        </div>
                    </div>
                    @if(count($orders) == 0)
                        @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal d-none">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{translate('order_list')}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <span id="message-date-range-text" data-text="{{ translate("invalid_date_range") }}"></span>
    <span id="js-data-example-ajax-url" data-url="{{ route('admin.orders.customers') }}"></span>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/order.js')}}"></script>
@endpush
