@extends('layouts.back-end.app-seller')

@section('title',translate('earning_statement'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('earning_Statement')}}
            </h2>
        </div>
        @include('vendor-views.delivery-man.pages-inline-menu')
        <div class="card mb-3">
            <div class="card-body">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-6 mb-2 mb-md-0">

                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                {{ translate('order_list') }}
                                <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{  $orders->total() }}</span>
                            </h4>
                        </div>
                        <div class="col-md-8 col-lg-6">
                            <div class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                <form action="" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('search_by_order_no') }}" aria-label="Search orders" value="{{ $searchValue?? '' }}">
                                        <button type="submit" class="btn btn--primary">
                                            {{ translate('search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-sm-12 mb-3">
                        <div class="card">
                            <div class="table-responsive datatable-custom">
                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table text-left">
                                    <thead class="thead-light thead-50 text-capitalize table-nowrap">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('order_no') }}</th>
                                        <th class="text-center">{{ translate('current_status') }}</th>
                                        <th>{{ translate('history') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody id="set-rows">
                                    @foreach($orders as $key=>$order)
                                        <tr>
                                            <td>{{ $orders->firstItem()+$key }}</td>
                                            <td>
                                                <div class="media align-items-center gap-10 flex-wrap">
                                                    <div class="media-body">
                                                        <a  class="title-color hover-c1" href="{{route('vendor.orders.details',$order['id'])}}">{{$order['id']}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-capitalize">
                                                @if($order['order_status']=='pending')
                                                    <span class="badge badge-soft-info fz-12">
                                                    {{$order['order_status']}}
                                            </span>

                                                @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                                    <span class="badge badge-soft-warning fz-12">
                                                {{str_replace('_',' ',$order['order_status'] == 'processing' ? 'packaging':$order['order_status'])}}
                                            </span>
                                                @elseif($order['order_status']=='confirmed')
                                                    <span class="badge badge-soft-success fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                                @elseif($order['order_status']=='failed')
                                                    <span class="badge badge-danger fz-12">
                                                {{translate('Failed_To_Deliver')}}
                                            </span>
                                                @elseif($order['order_status']=='delivered')
                                                    <span class="badge badge-soft-success fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                                @else
                                                    <span class="badge badge-soft-danger fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="media align-items-center gap-10 flex-wrap">
                                                    <button class="btn btn-info order-status-history" data-id="{{ $order->id }}" data-toggle="modal" data-target="#exampleModalLong"><i class="tio-history"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-4">
                                <div class="px-4 d-flex justify-content-lg-end">
                                    {{ $orders->links() }}
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
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content load-with-ajax">

                </div>
            </div>
        </div>
        <span id="order-status-url" data-url="{{ route('vendor.delivery-man.wallet.order-status-history', ['order' => ':id'] ) }}"></span>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/wallet.js')}}"></script>
@endpush
