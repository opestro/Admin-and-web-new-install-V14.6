@php use App\Utils\Helpers; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('my_Order_List').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5 class="text-capitalize">{{translate('my_order_list')}}</h5>
                                <div class="border rounded  custom-ps-3 py-2">
                                    <div class="d-flex gap-2">
                                        <div class="flex-middle gap-2">
                                            <i class="bi bi-sort-up-alt"></i>
                                            <span
                                                class="d-none d-sm-inline-block text-capitalize">{{translate('show_order').':'}}</span>
                                        </div>
                                        <div class="dropdown">
                                            <button type="button"
                                                    class="border-0 bg-transparent dropdown-toggle text-dark p-0 custom-pe-3"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                {{translate($order_by=='asc'?'old':'latest')}}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="d-flex" href="{{route('account-oder')}}/?order_by=desc">
                                                        {{translate('latest')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="d-flex" href="{{route('account-oder')}}/?order_by=asc">
                                                        {{translate('old')}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                @if($orders->count() > 0)
                                    <div class="table-responsive d-none d-sm-block">
                                        <table class="table align-middle table-striped">
                                            <thead class="text-primary">
                                            <tr>
                                                <th>{{translate('SL')}}</th>
                                                <th class="text-capitalize">{{translate('order_details')}}</th>
                                                <th class="text-center">{{translate('status')}}</th>
                                                <th>{{translate('amount')}}</th>
                                                <th class="text-center">{{translate('action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $key=>$order)
                                                <tr>
                                                    <td> {{$orders->firstItem()+$key}}</td>
                                                    <td>
                                                        <div class="media gap-3 align-items-center mn-w200">
                                                            <div class="avatar rounded size-3-75rem aspect-1 overflow-hidden d-flex align-items-center">
                                                                @if($order->seller_is == 'seller')
                                                                    <img class="img-fit dark-support rounded" alt=""
                                                                        src="{{ getValidImage(path: 'storage/app/public/shop/'.($order?->seller?->shop->image), type:'shop') }}">
                                                                @elseif($order->seller_is == 'admin')
                                                                    <img class="img-fit dark-support rounded" alt=""
                                                                        src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['fav_icon']->value), type:'shop') }}">
                                                                @endif
                                                            </div>
                                                            <div class="media-body">
                                                                <h6>
                                                                    <a href="{{ route('account-order-details', ['id'=>$order->id]) }}">{{translate('order')}}
                                                                        #{{$order['id']}}</a>
                                                                </h6>
                                                                <div
                                                                    class="text-dark fs-12">{{count($order->details)}} {{translate('items')}}</div>
                                                                <p class="text-muted fs-12">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                            <span class="text-center badge bg-danger rounded-pill">
                                                            {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                                                        </span>
                                                        @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                            <span class="text-center badge bg-success rounded-pill">
                                                            {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                        </span>
                                                        @else
                                                            <span class="text-center badge bg-info rounded-pill">
                                                            {{translate($order['order_status'])}}
                                                        </span>
                                                        @endif

                                                        <div
                                                            class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }} mt-1"> {{ translate($order['payment_status']) }}</div>
                                                    </td>
                                                    <td>{{Helpers::currency_converter($order['order_amount'])}}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2 align-items-center">
                                                            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
                                                               class="btn btn-outline-info btn-action">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                            <a href="{{route('generate-invoice',[$order->id])}}"
                                                               class="btn btn-outline-success btn-action">
                                                                <img src="{{theme_asset('assets/img/svg/download.svg')}}"
                                                                     alt="" class="svg">
                                                            </a>
                                                            @if($order['order_status']=='pending')
                                                                <a href="javascript:" title="{{translate('cancel')}}"
                                                                   data-action="{{route('order-cancel',[$order->id])}}"
                                                                   data-text="{{translate('want_to_cancel_this_order').'?'}}"
                                                                   class="btn btn-danger btn-action delete-action">
                                                                    <i class="bi bi-trash"></i>
                                                                </a>
                                                            @else
                                                                <button class="btn btn-danger btn-action cancel-message"
                                                                        title="{{ translate('cancel')}}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex flex-column">
                                    @foreach($orders as $key=>$order)
                                        <div class="d-flex gap-2 justify-content-between py-2 border-bottom d-sm-none">
                                            <div class="media gap-2 mn-w200 get-view-by-onclick"
                                                 data-link="{{ route('account-order-details', ['id'=>$order->id]) }}">
                                                <div class="avatar rounded size-3-75rem">
                                                    @if($order->seller_is == 'seller')
                                                        <img class="img-fit dark-support rounded" alt=""
                                                            src="{{ getValidImage(path: 'storage/app/public/shop/'.($order?->seller?->shop->image), type:'shop') }}">
                                                    @elseif($order->seller_is == 'admin')
                                                        <img class="img-fit dark-support rounded" alt=""
                                                            src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['fav_icon']->value), type:'shop') }}">
                                                    @endif
                                                </div>
                                                <div class="media-body">
                                                    <h6>{{translate('order').'#'}}{{$order['id']}}</h6>
                                                    <div
                                                        class="text-dark fs-12">{{count($order->details)}} {{translate('items')}}</div>
                                                    <div
                                                        class="text-muted fs-12">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</div>
                                                    <div class="d-flex gap-2 align-items-center fs-12">
                                                        <div class="text-muted">{{ translate('price').':' }}</div>
                                                        <div class="text-dark"> {{Helpers::currency_converter($order['order_amount'])}}</div>
                                                    </div>
                                                    <div class="d-flex gap-2 align-items-center fs-12">
                                                        <div class="text-muted">{{ translate('status') }} :</div>
                                                        @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                            <span class="text-center badge bg-danger rounded-pill">
                                                                {{translate($order['order_status'] =='failed' ? 'failed_to_Deliver' : $order['order_status'])}}
                                                            </span>
                                                                @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                                    <span class="text-center badge bg-success rounded-pill">
                                                                {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                            </span>
                                                        @else
                                                            <span class="text-center badge bg-info rounded-pill">
                                                                {{translate($order['order_status'])}}
                                                            </span>
                                                        @endif
                                                        <div class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}"> {{ translate($order['payment_status']) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <a href="{{route('generate-invoice',[$order->id])}}"
                                                   class="btn btn-outline-success btn-action mb-1">
                                                    <img src="{{theme_asset('assets/img/svg/download.svg')}}" alt=""
                                                         class="svg">
                                                </a>
                                                @if($order['payment_method']=='cash_on_delivery' && $order['order_status']=='pending')
                                                    <a href="javascript:" title="{{translate('cancel')}}"
                                                       data-action="{{route('order-cancel',[$order->id])}}"
                                                       data-text="{{translate('want_to_cancel_this_order').'?'}}"
                                                       class="btn btn-danger btn-action mb-1 delete-action">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-danger btn-action mb-1 cancel-message"
                                                            title="{{ translate('cancel')}}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif

                                @if($orders->count()==0)
                                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 mt-5 w-100">
                                        <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-order.svg') }}" alt="">
                                        <h5 class="text-center text-muted">
                                            {{ translate('You_have_not_any_order_yet') }}!
                                        </h5>
                                    </div>
                                @endif

                                @if($orders->count()>0)
                                    <div class="card-footer border-0">
                                        {{$orders->links() }}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
