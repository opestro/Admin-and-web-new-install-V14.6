@php
use Illuminate\Support\Facades\Session;
@endphp
@extends('layouts.back-end.app')

@section('title',$seller?->shop->name ?? translate("shop_name_not_found"))

@section('content')
    @php($direction = Session::get('direction'))
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('vendor_Details')}}
            </h2>
        </div>
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                @if ($seller->status=="pending")
                    <div class="mt-4 pr-2 float-{{$direction === "rtl" ? 'left' : 'right'}}">
                        <div class="flex-start">
                            <div class="mx-1"><h4><i class="tio-shop-outlined"></i></h4></div>
                            <div>{{translate('vendor_request_for_open_a_shop.')}}</div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                        class="btn btn--primary btn-sm">{{translate('approve')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                        class="btn btn-danger btn-sm">{{translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="page-header">
            <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title">{{ $seller?->shop->name ?? translate("Shop_Name").' : '. translate("Update_Please") }}</h1>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link "
                           href="{{ route('admin.vendors.view',$seller->id) }}">{{translate('shop')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{translate('order')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'product']) }}">{{translate('product')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'setting']) }}">{{translate('setting')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'transaction']) }}">{{translate('transaction')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.vendors.view',['id'=>$seller->id, 'tab'=>'review']) }}">{{translate('review')}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="order">
                <div class="row pt-2">
                    <div class="col-md-12">
                        <div class="card w-100">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 w-100">
                                    <h5 class="mb-0">{{translate('order_info')}}</h5>
                                    <div class="dropdown text-nowrap">
                                        <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                            <i class="tio-download-to"></i>
                                            {{translate('export')}}
                                            <i class="tio-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.vendors.order-list-export',[$seller['id'],'searchValue' => request('searchValue')])}}">
                                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                                    {{translate('excel')}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="order-stats order-stats_pending">
                                            <div class="order-stats__content"
                                                 style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};">
                                                <i class="tio-airdrop"></i>
                                                <h6 class="order-stats__subtitle">{{translate('pending')}}</h6>
                                            </div>
                                            <div class="order-stats__title">
                                                {{ $pendingOrder }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="order-stats order-stats_delivered">
                                            <div class="order-stats__content"
                                                 style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};">
                                                <i class="tio-checkmark-circle"></i>
                                                <h6 class="order-stats__subtitle">{{translate('delivered')}}</h6>
                                            </div>
                                            <div class="order-stats__title">
                                                {{ $deliveredOrder }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="order-stats order-stats_all">
                                            <div class="order-stats__content"
                                                 style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};">
                                                <i class="tio-table"></i>
                                                <h6 class="order-stats__subtitle">{{translate('all')}}</h6>
                                            </div>
                                            <div class="order-stats__title">
                                                {{ $orders->total() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive datatable-custom">
                                <table id="datatable"
                                       style="text-align: {{$direction === "rtl" ? 'right' : 'left'}};"
                                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('order')}}</th>
                                        <th>{{translate('date')}}</th>
                                        <th>{{translate('customer')}}</th>
                                        <th>{{translate('payment_status')}}</th>
                                        <th>{{translate('total')}}</th>
                                        <th>{{translate('order_status')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="set-rows">
                                    @foreach($orders as $key=>$order)
                                        <tr class="status class-all">
                                            <td>
                                                {{$orders->firstItem()+$key}}
                                            </td>
                                            <td>
                                                <a href="{{route('admin.vendors.order-details',['order_id'=>$order['id'],'vendor_id'=>$order['seller_id']])}}"
                                                   class="title-color hover-c1">{{$order['id']}}</a>
                                            </td>
                                            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                                            <td>
                                                @if($order->is_guest)
                                                    {{translate('guest_customer')}}
                                                @else
                                                    @if($order->customer)
                                                        <a class="text-body text-capitalize"
                                                           href="{{route('admin.customer.view',['user_id'=>$order->customer['id']])}}">
                                                            {{$order?->customer['f_name'] ?? ''}} {{$order?->customer['l_name']??''}}
                                                        </a>
                                                    @else
                                                        <label
                                                                class="badge badge-soft-danger fz-12">{{translate('removed')}}</label>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->payment_status=='paid')
                                                    <span
                                                            class="badge badge-soft-info fz-12">{{translate('paid')}}</span>
                                                @else
                                                    <span class="badge badge-soft-danger fz-12">{{translate('unpaid')}}
                                                </span>
                                                @endif
                                            </td>
                                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order['order_amount']))}}</td>
                                            <td class="text-capitalize">
                                                @if($order['order_status']=='pending')
                                                    <span
                                                            class="badge badge-soft-info fz-12">{{translate('pending')}}</span>
                                                @elseif($order['order_status']=='confirmed')
                                                    <span
                                                            class="badge badge-soft-info fz-12">{{translate('confirmed')}}</span>
                                                @elseif($order['order_status']=='processing')
                                                    <span
                                                            class="badge badge-soft-warning fz-12">{{translate('processing')}}</span>
                                                @elseif($order['order_status']=='out_for_delivery')
                                                    <span
                                                            class="badge badge-soft-warning fz-12">{{translate('out_for_delivery')}}</span>
                                                @elseif($order['order_status']=='delivered')
                                                    <span
                                                            class="badge badge-soft-success fz-12">{{translate('delivered')}}</span>
                                                @else
                                                    <span
                                                            class="badge badge-soft-danger fz-12">{{translate(str_replace('_',' ',$order['order_status']))}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a title="{{translate('view')}}"
                                                       class="btn btn-outline-info btn-sm square-btn"
                                                       href="{{route('admin.vendors.order-details',['order_id'=>$order['id'],'vendor_id'=>$order['seller_id']])}}"><i
                                                                class="tio-invisible"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-4">
                                <div class="px-4 d-flex justify-content-lg-end">
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
        </div>
    </div>
@endsection
