@extends('layouts.back-end.app')

@section('title', translate('order_List'))

@section('content')
    <div class="content container-fluid">

        <div>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
                    {{translate('orders')}}
                </h2>
                <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="pb-3">
                            <div class="row g-2 justify-content-between align-items-center">
                                <div class="col-lg-4">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <h5 class="m-0">{{translate('order_list')}}
                                            <span class="badge badge-soft-dark ml-2">{{$orders->total()}}</span>
                                        </h5>
                                        <h5 class="mb-0 text-black-50">( {{translate('vendor_Name')}}
                                            : {{$seller['f_name'].' '.$seller['l_name']}} , {{translate('vendor_Id')}}
                                            : {{$seller['id']}} )</h5>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-custom input-group-merge">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="searchValue"
                                                   class="form-control"
                                                   placeholder="{{translate('search_by_Order_ID')}}"
                                                   aria-label="Search orders"
                                                   value="{{ request('searchValue') }}">
                                            <button type="submit"
                                                    class="btn btn--primary">{{translate('search')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive datatable-custom">
                            <table
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('order_ID')}}</th>
                                    <th>{{translate('order_Date')}}</th>
                                    <th>{{translate('customer_Info')}}</th>
                                    <th>{{translate('store')}}</th>
                                    <th class="text-right">{{translate('total_Amount')}}</th>
                                    <th class="text-center">{{translate('order_Status')}} </th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($orders as $key=>$order)
                                    <tr class="status-{{$order['order_status']}} class-all">
                                        <td class="">
                                            {{$orders->firstItem()+$key}}
                                        </td>
                                        <td>
                                            <a class="title-color"
                                               href="{{route('admin.orders.details',['id'=>$order['id'],'vendor-order-list'])}}">{{$order['id']}} {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
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
                                                    <a class="text-body text-capitalize"
                                                       href="{{route('admin.customer.view',['user_id'=>$order['customer_id']])}}">
                                                        <strong
                                                            class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                                    </a>
                                                    <a class="d-block title-color"
                                                       href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                                @else
                                                    <label
                                                        class="badge badge-danger fz-12">{{translate('invalid_customer_data')}}</label>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="store-name font-weight-medium">
                                                    @if($order->seller_is == 'seller')
                                                        {{ isset($order->seller->shop) ? $order->seller->shop->name : 'Store not found' }}
                                                    @elseif($order->seller_is == 'admin')
                                                        {{translate('in_House')}}
                                                    @endif
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <div>
                                                @php($discount = 0)
                                                @if($order->order_type == 'default_type' && $order->coupon_discount_bearer == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                                    @php($discount = $order->discount_amount)
                                                @endif

                                                @php($free_shipping = 0)
                                                @if($order->is_shipping_free)
                                                    @php($free_shipping = $order->shipping_cost)
                                                @endif
                                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->order_amount+$discount+$free_shipping))}}
                                            </div>

                                            @if($order->payment_status=='paid')
                                                <span class="badge text-success fz-12 px-0">
                                                    {{translate('paid')}}
                                                </span>
                                            @else
                                                <span class="badge text-danger fz-12 px-0">
                                                    {{translate('unpaid')}}
                                                </span>
                                            @endif
                                        </td>
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
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline--primary square-btn btn-sm mr-1"
                                                   title="{{translate('view')}}"
                                                   href="{{route('admin.vendors.order-details',[$order['id'],$seller['id']])}}">
                                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/eye.svg')}}"
                                                         class="svg" alt="">
                                                </a>
                                                <a class="btn btn-outline-success square-btn btn-sm mr-1"
                                                   target="_blank" title="{{translate('invoice')}}"
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
                    </div>
                </div>
            </div>
        </div>
@endsection
