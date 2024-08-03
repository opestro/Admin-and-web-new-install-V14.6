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
                                <label>{{ ucwords(translate('start_date'))}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{ $to }}" name="to" id="to_date" class="form-control">
                                <label>{{ ucwords(translate('end_date'))}}</label>
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
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/cart.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: array_sum($earning_data['total_earning_statistics'])), currencyCode: getCurrencyCode()) }}</h4>
                        <h6 class="subtext">{{ translate('total_earnings')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex flex-wrap justify-content-between g-1">
                        <div class="text-center">
                            <strong class="text-danger break-all">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $earning_data['total_commission']), currencyCode: getCurrencyCode()) }}</strong>
                            <div>{{ translate('commission')}}</div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary break-all">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $earning_data['total_inhouse_earning']), currencyCode: getCurrencyCode()) }}</strong>
                            <div>{{ translate('in_House')}}</div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success break-all">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $earning_data['total_shipping_earn']), currencyCode: getCurrencyCode()) }}</strong>
                            <div>
                                {{ translate('shipping')}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/products.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $earning_data['total_in_house_products'] }}</h4>
                        <h6 class="subtext">{{ translate('total_In_House_Products')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/stores.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $earning_data['total_stores'] }}</h4>
                        <h6 class="subtext">{{ translate('total_Shop')}}</h6>
                    </div>
                </div>
            </div>
            @foreach($earning_data['total_earning_statistics'] as $amount)
                @php($earningData[] = usdToDefaultCurrency(amount: $amount))
            @endforeach
            <div class="center-chart-area">
                @include('layouts.back-end._apexcharts',['title'=>'earning_Statistics','statisticsValue'=>$earningData,'label'=>array_keys($earning_data['total_earning_statistics']),'statisticsTitle'=>'total_Earnings'])
            </div>
            <div class="right-content">
                <div class="card h-100 bg-white payment-statistics-shadow">
                    <div class="card-header border-0 ">
                        <h5 class="card-title">
                            <span>{{ translate('payment_Statistics')}}</span>
                        </h5>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="position-relative pie-chart">
                            <div id="dognut-pie" class="label-hide"></div>
                            <div class="total--orders">
                                <h3>{{ getCurrencySymbol(currencyCode: getCurrencyCode()) }}{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['total_payment'])) }}</h3>
                                <span>{{ translate('payments_Amount')}}</span>
                            </div>
                        </div>
                        <div class="apex-legends">
                            <div class="before-bg-004188">
                                <span>{{translate('cash_payments')}} ({{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $payment_data['cash_payment']), currencyCode: getCurrencyCode()) }})</span>
                            </div>
                            <div class="before-bg-0177CD">
                                <span>{{translate('digital_payments')}} ({{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $payment_data['digital_payment']), currencyCode: getCurrencyCode()) }}) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            </div>
                            <div class="before-bg-CDE6F5">
                                <span>{{translate('offline_payments')}} ({{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $payment_data['offline_payment']), currencyCode: getCurrencyCode()) }})</span>
                            </div>
                            <div class="before-bg-A2CEEE">
                                <span>{{translate('wallet')}} ({{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $payment_data['wallet_payment']), currencyCode: getCurrencyCode()) }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Earnings')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ count($inhouse_earn) }}</span>
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
                                   href="{{ route('admin.report.admin-earning-excel-export', ['date_type'=>$date_type, 'from'=>$from, 'to'=>$to]) }}">
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
                        <th>{{translate('duration')}}</th>
                        <th>{{translate('in-House_Earning')}}</th>
                        <th>{{translate('commission_Earning')}}</th>
                        <th>{{translate('earn_From_Shipping')}}</th>
                        <th>{{translate('deliveryman_incentive')}}</th>
                        <th>{{translate('discount_Given')}}</th>
                        <th>{{translate('VAT/TAX')}}</th>
                        <th>{{translate('refund_Given')}}</th>
                        <th>{{translate('total_Earning')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @foreach($inhouse_earn as $key=>$earning)
                        @php($inhouse_earning = $earning-$total_tax[$key])
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $key }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $inhouse_earning), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $admin_commission_earn[$key]), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $shipping_earn[$key]), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $deliveryman_incentive[$key]), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discount_given[$key]), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_tax[$key]), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $refund_given[$key]), currencyCode: getCurrencyCode()) }}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $inhouse_earning+$admin_commission_earn[$key]+$total_tax[$key]+$shipping_earn[$key]-$discount_given[$key]-$refund_given[$key] - $deliveryman_incentive[$key]), currencyCode: getCurrencyCode()) }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('admin.report.admin-earning-duration-download-pdf') }}"
                                          method="post">
                                        @csrf
                                        <input type="hidden" name="duration" value="{{ $key }}">
                                        <input type="hidden" name="inhouse_earning" value="{{ $inhouse_earning }}">
                                        <input type="hidden" name="admin_commission"
                                               value="{{ $admin_commission_earn[$key] }}">
                                        <input type="hidden" name="shipping_earn" value="{{ $shipping_earn[$key] }}">
                                        <input type="hidden" name="discount_given" value="{{ $discount_given[$key] }}">
                                        <input type="hidden" name="total_tax" value="{{ $total_tax[$key] }}">
                                        <input type="hidden" name="refund_given" value="{{ $refund_given[$key] }}">
                                        <input type="hidden" name="deliveryman_incentive" value="{{ $deliveryman_incentive[$key] }}">
                                        <input type="hidden" name="total_earning"
                                               value="{{ $inhouse_earning+$admin_commission_earn[$key]+$shipping_earn[$key]+$total_tax[$key]-$discount_given[$key]-$refund_given[$key] - $deliveryman_incentive[$key] }}">
                                        <button type="submit" class="btn btn-outline-success square-btn btn-sm"><i
                                                    class="tio-download-to"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($inhouse_earn)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
            @endif
        </div>
    </div>

    <span id="currency_symbol" data-text="{{ getCurrencySymbol(currencyCode: getCurrencyCode()) }}"></span>

    <span id="cash_payment" data-text="{{ usdToDefaultCurrency(amount: $payment_data['cash_payment']) }}"></span>
    <span id="digital_payment" data-text="{{ usdToDefaultCurrency(amount: $payment_data['digital_payment']) }}"></span>
    <span id="wallet_payment" data-text="{{ usdToDefaultCurrency(amount: $payment_data['wallet_payment']) }}"></span>
    <span id="offline_payment" data-text="{{ usdToDefaultCurrency(amount: $payment_data['offline_payment']) }}"></span>

    <span id="cash_payment_text" data-text="{{translate('cash_Payments')}}"></span>
    <span id="digital_payment_text" data-text="{{translate('digital_payment')}}"></span>
    <span id="wallet_payment_text" data-text="{{translate('wallet_payment')}}"></span>
    <span id="offline_payment_text" data-text="{{translate('offline_payment')}}"></span>

    <span id="cash_payment_format" data-text="{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['cash_payment'])) }}"></span>
    <span id="digital_payment_format" data-text="{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['offline_payment'])) }}"></span>
    <span id="wallet_payment_format" data-text="{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['wallet_payment'])) }}"></span>
    <span id="offline_payment_format" data-text="{{getFormatCurrency(amount: usdToDefaultCurrency(amount: $payment_data['offline_payment'])) }}"></span>

@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts-data-show.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/admin-earning-report.js') }}"></script>
@endpush

