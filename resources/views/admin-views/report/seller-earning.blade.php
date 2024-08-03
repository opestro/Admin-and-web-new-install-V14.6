@extends('layouts.back-end.app')
@section('title', translate('earning_Reports'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/earning_report.png')}}" alt="">
                {{translate('earning_Reports')}}
            </h2>
        </div>
        @include('admin-views.report.earning-report-inline-menu')
        <div class="card mb-2">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{ translate('filter_Data')}}</h4>
                    <div class="row gy-3 gx-2 align-items-center text-left">
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control __form-control" name="date_type" id="date_type">
                                <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                <option value="today" {{ $date_type == 'today'? 'selected' : '' }}>{{translate('today')}}</option>
                                <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div class="form-floating">
                                <input type="date" name="from" value="{{ $from }}" id="from_date" class="form-control">
                                <label>{{ translate('start Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{ $to }}" name="to" id="to_date" class="form-control">
                                <label>{{ translate('end Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn--primary px-4 w-100">
                                {{ translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="store-report-content mb-2">
            <div class="left-content">
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/stores.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $data['total_seller'] }}</h4>
                        <h6 class="subtext">{{ translate('total_Vendor')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/cart.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $data['all_product'] }}</h4>
                        <h6 class="subtext">{{ translate('total_Vendor_Products')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex flex-wrap justify-content-between g-1">
                        <div class="text-center">
                            <strong class="text-danger">{{ $data['rejected_product'] }}</strong>
                            <div>{{ translate('denied')}}</div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $data['pending_product'] }}</strong>
                            <div>{{ translate('pending_Request')}}</div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $data['active_product'] }}</strong>
                            <div>{{ translate('approved')}}</div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/total-earning.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_earning), currencyCode: getCurrencyCode()) }}</h4>
                        <h6 class="subtext">{{ translate('total_Earning')}}</h6>
                    </div>
                </div>
            </div>
            @foreach($chart_earning_statistics as $amount)
                @php($chartEarningStatistics[] = usdToDefaultCurrency(amount: $amount))
            @endforeach
            <div class="center-chart-area">
                @include('layouts.back-end._apexcharts',['title'=>'earning_Statistics','statisticsValue'=>$chartEarningStatistics,'label'=>array_keys($chart_earning_statistics),'statisticsTitle'=>'total_Earnings','average'=>(array_sum($chartEarningStatistics)/count($chartEarningStatistics))])
            </div>
            <div class="right-content">
                <div class="card h-100 bg-white payment-statistics-shadow">
                    <div class="card-header border-0 ">
                        <h5 class="card-title">
                            <span>{{ translate('vendor_Wallet_Status')}}</span>
                        </h5>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="position-relative pie-chart">
                            <div id="dognut-pie" class="label-hide"></div>
                            <div class="total--orders">
                                <h3>{{ getCurrencySymbol(currencyCode: getCurrencyCode()) }}{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['wallet_amount'] ?? 0)) }}</h3>
                                <span>{{ translate('wallet_Amount')}}</span>
                            </div>
                        </div>
                        <div class="apex-legends">
                            <div class="before-bg-004188">
                                <span>{{translate('withdrawble_Balance')}} ({{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $payment_data['withdrawable_balance'] ?? 0), currencyCode: getCurrencyCode()) }})</span>
                            </div>
                            <div class="before-bg-0177CD">
                                <span>{{translate('pending_Withdraws')}} ({{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $payment_data['pending_withdraw'] ?? 0), currencyCode: getCurrencyCode()) }})</span>
                            </div>
                            <div class="before-bg-A2CEEE">
                                <span>{{translate('already_Withdrawn')}} ({{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $payment_data['already_withdrawn'] ?? 0), currencyCode: getCurrencyCode()) }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Vendor')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ count($table_earning['seller_earn_table']) }}</span>
                    </h4>
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
                                   href="{{ route('admin.report.vendor-earning-excel-export', ['date_type'=>$date_type, 'from'=>$from, 'to'=>$to]) }}">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                    {{translate('excel')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="datatable"
                       class="table __table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('vendor_Info')}}</th>
                        <th>{{translate('earn_From_Order')}}</th>
                        <th>{{translate('earn_From_Shipping')}}</th>
                        <th>{{translate('deliveryman_incentive')}}</th>
                        <th>{{translate('commission_Given')}}</th>
                        <th>{{translate('discount_Given')}}</th>
                        <th>{{translate('tax_Collected')}}</th>
                        <th>{{translate('refund_Given')}}</th>
                        <th>{{translate('total_Earning')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php($i=0)
                    @foreach($table_earning['seller_earn_table'] as $key=>$seller_earn)
                        @php($shipping_earn_table = isset($table_earning['shipping_earn_table'][$key]['amount']) ? $table_earning['shipping_earn_table'][$key]['amount'] : 0)
                        @php($deliveryman_incentive_table = isset($table_earning['deliveryman_incentive_table'][$key]['amount']) ? $table_earning['deliveryman_incentive_table'][$key]['amount'] : 0)
                        @php($commission_given_table = isset($table_earning['commission_given_table'][$key]['amount']) ? $table_earning['commission_given_table'][$key]['amount'] : 0)
                        @php($discount_given_table = isset($table_earning['discount_given_table'][$key]['amount']) ? $table_earning['discount_given_table'][$key]['amount'] : 0)
                        @php($discount_given_bearer_admin_table = isset($table_earning['discount_given_bearer_admin_table'][$key]['amount']) ? $table_earning['discount_given_bearer_admin_table'][$key]['amount'] : 0)
                        @php($total_tax_table = isset($table_earning['total_tax_table'][$key]['amount']) ? $table_earning['total_tax_table'][$key]['amount'] : 0)
                        @php($total_refund_table = isset($table_earning['total_refund_table'][$key]['amount']) ? $table_earning['total_refund_table'][$key]['amount'] : 0)
                        @php($total_earn_from_order=$seller_earn['amount']+$discount_given_table-$total_tax_table)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>
                                <div>
                                    <h6 class="mb-1">
                                        @if(isset($seller_earn['seller_id']) && isset($seller_earn['name']))
                                            <a class="title-color" href="{{ route('admin.vendors.view', ['id' => $seller_earn['seller_id']]) }}">{{ $seller_earn['name'] }}</a>
                                        @else
                                            <span class="title-color">{{ translate('vendor_not_found') }}</span>
                                        @endif
                                    </h6>
                                </div>
                            </td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_earn_from_order), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $shipping_earn_table), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $deliveryman_incentive_table), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $commission_given_table), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discount_given_table), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_tax_table), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_refund_table), currencyCode: getCurrencyCode()) }}</td>
                            <td>
                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_earn_from_order+$shipping_earn_table+$total_tax_table-$discount_given_table-$total_refund_table-$commission_given_table-$deliveryman_incentive_table), currencyCode: getCurrencyCode()) }}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            @if(count($table_earning['seller_earn_table']) <= 0)
                @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
            @endif
        </div>
    </div>

    <span id="currency_symbol" data-text="{{ getCurrencySymbol(currencyCode: getCurrencyCode()) }}"></span>

    <span id="withdrawable_balance" data-text="{{ usdToDefaultCurrency(amount: $payment_data['withdrawable_balance'] ?? 0) }}"></span>
    <span id="pending_withdraw" data-text="{{ usdToDefaultCurrency(amount: $payment_data['pending_withdraw'] ?? 0) }}"></span>
    <span id="already_withdrawn" data-text="{{ usdToDefaultCurrency(amount: $payment_data['already_withdrawn'] ?? 0) }}"></span>

    <span id="withdrawable_balance_text" data-text="{{translate('withdrawble_Balance')}}"></span>
    <span id="pending_withdraw_text" data-text="{{translate('pending_Withdraws')}}"></span>
    <span id="already_withdrawn_text" data-text="{{translate('already_Withdrawn')}}"></span>

    <span id="withdrawable_balance_format" data-text="{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['withdrawable_balance'] ?? 0)) }}"></span>
    <span id="pending_withdraw_format" data-text="{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['pending_withdraw'] ?? 0)) }}"></span>
    <span id="already_withdrawn_format" data-text="{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['already_withdrawn'] ?? 0)) }}"></span>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts-data-show.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/seller-earning-report.js') }}"></script>
@endpush
