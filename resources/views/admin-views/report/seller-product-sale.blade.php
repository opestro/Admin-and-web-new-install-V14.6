@extends('layouts.back-end.app')

@section('title', translate('vendor_product_sale_Report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/seller-reports.png')}}" alt="">
                {{translate('vendor_Reports')}}
            </h2>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{translate('filter_Data')}}</h4>
                    <div class="row gx-2 gy-3 align-items-center text-left">
                        <div class="col-sm-6 col-md-3">
                            <select class="js-select2-custom form-control" name="seller_id">
                                <option value="all">{{ translate('all_vendors') }}</option>
                                @foreach($sellers as $seller)
                                    <option
                                        value="{{$seller['id']}}" {{$seller_id==$seller['id']?'selected':''}}>
                                        {{$seller['f_name']}} {{$seller['l_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control __form-control" name="date_type" id="date_type">
                                <option
                                    value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                <option
                                    value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                <option
                                    value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                <option
                                    value="today" {{ $date_type == 'today'? 'selected' : '' }}>{{translate('today')}}</option>
                                <option
                                    value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div class="form-floating">
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                                <label>{{translate('start_date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                <label>{{translate('end_date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 filter-btn">
                            <button type="submit" class="btn btn--primary px-4 px-md-5">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="store-report-content mb-2">
            <div class="left-content">
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/products.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">{{ $total_product }}</h4>
                        <h6 class="subtext">{{translate('products')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/cart.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">{{ $canceled_order+$ongoing_order+$delivered_order }}</h4>
                        <h6 class="subtext">{{translate('total_Orders')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex flex-wrap justify-content-between">
                        <div class="text-center">
                            <strong class="text-danger">{{ $canceled_order }}</strong>
                            <div class="d-flex justify-content-center">
                                <span>{{translate('canceled')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top"
                                      title="{{translate('this_count_is_the_summation_of')}} {{translate('failed_to_deliver')}}, {{translate('canceled')}}, {{translate('and')}} {{translate('returned_orders')}}">
                                    <img class="info-img"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                         alt="img">
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $ongoing_order }}</strong>
                            <div class="d-flex justify-content-center">
                                <span>{{translate('ongoing')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top"
                                      title="{{translate('this_count_is_the_summation_of')}} {{translate('pending')}}, {{translate('confirmed')}}, {{translate('packaging')}}, {{translate('out_for_delivery_orders')}}">
                                    <img class="info-img"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                         alt="img">
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $delivered_order }}</strong>
                            <div class="d-flex justify-content-center">
                                <span>{{translate('completed')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top"
                                      title="{{translate('this_count_is_the_summation_of_delivered_orders')}}">
                                    <img class="info-img"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                         alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/deliveryman.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">
                            {{ $deliveryman }}
                        </h4>
                        <h6 class="subtext">{{translate('total_Deliveryman')}}</h6>
                    </div>
                </div>
            </div>
            @foreach($chart_data['order_amount'] as $amount)
                @php($chartVal[] = usdToDefaultCurrency(amount: $amount))
            @endforeach
            <div class="center-chart-area">
                @include('layouts.back-end._apexcharts',['title'=>'order_statistics','statisticsValue'=>$chartVal,'label'=>array_keys($chart_data['order_amount']),'statisticsTitle'=>'total_order_amount'])
            </div>
            <div class="right-content">
                <div class="card h-100 bg-white payment-statistics-shadow">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="earning-statistics-content">
                            <img class="mb-4" src="{{dynamicAsset(path: 'public/assets/back-end/img/earnings.svg')}}"
                                 alt="back-end/img">
                            <h6 class="subtitle">{{translate('total_Shop_Earnings')}}</h6>
                            <h3 class="title">
                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_store_earning), currencyCode: getCurrencyCode()) }}
                            </h3>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{ translate('total_Vendor') }}
                        <span class="badge badge-soft-dark radius-50 fz-14">{{ $orders->total() }}</span>
                    </h4>
                    <form action="{{ url()->full() }}" method="GET" class="mb-0">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" name="seller_id" value="{{ $seller_id }}">
                            <input type="hidden" name="date_type" value="{{ $date_type }}">
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <input id="datatableSearch_" type="search" value="{{ $search }}" name="search"
                                   class="form-control" placeholder="{{translate('search_by_vendor_info')}}" aria-label="Search orders"
                                   required="">
                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                        </div>
                    </form>
                    <div>
                        <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{translate('export')}}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('admin.report.vendor-report-export', ['date_type'=>request('date_type'), 'seller_id'=>request('seller_id'),'from'=>request('from'), 'to'=>request('to'), 'search'=>request('search')]) }}">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}"
                                         alt="">
                                    {{translate('excel')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                           class="table __table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{ translate('vendor-Info') }}</th>
                            <th>{{translate('total_Order')}}</th>
                            <th>{{translate('commission')}}</th>
                            <th class="text-center">{{translate('refund_Rate')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $key=>$order)
                            <tr>
                                <td>{{ $orders->firstItem()+$key }}</td>
                                <td>
                                    <div>
                                        @if (isset($order->seller->shop))
                                            <a class="title-color"
                                               href="{{ route('admin.vendors.view', ['id' => $order->seller->id]) }}">
                                                <h6 class="mb-1">
                                                    {{ \Str::limit($order->seller->shop->name, 20)}}
                                                </h6>
                                                <span class="mb-1 text-capitalize">
                                                   {{$order->seller->f_name.' '.$order->seller->l_name}}
                                                </span>
                                            </a>
                                        @else
                                            {{translate('not_found')}}
                                        @endif

                                    </div>
                                </td>
                                <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->total_order_amount), currencyCode: getCurrencyCode()) }}</td>
                                <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->total_admin_commission), currencyCode: getCurrencyCode()) }}</td>
                                <td class="text-center">
                                        <?php
                                        $arr = array();
                                        if ($refunds) {
                                            foreach ($refunds as $refund) {
                                                $arr += array(
                                                    $refund['payer_id'] => $refund['total_refund_amount']
                                                );
                                            }
                                        }
                                        if (array_key_exists($order->seller_id, $arr)) {
                                            echo number_format(($arr[$order->seller_id] / $order->total_order_amount) * 100, 2) . '%';
                                        } else {
                                            echo '0%';
                                        }
                                        ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        @if($order->seller_id)
                                            <a href="{{ route('admin.vendors.view', ['id'=>$order->seller_id]) }}"
                                               class="btn btn-outline--primary square-btn btn-sm">
                                                <i class="tio-invisible"></i>
                                            </a>
                                        @else
                                            <span class="btn btn-outline--primary square-btn btn-sm disabled">
                                                <i class="tio-invisible"></i>
                                            </span>
                                        @endif
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
                @if(count($orders) <= 0)
                    @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts-data-show.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/seller-earning-report.js') }}"></script>
@endpush
