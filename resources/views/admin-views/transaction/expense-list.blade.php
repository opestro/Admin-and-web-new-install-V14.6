@extends('layouts.back-end.app')
@section('title', translate('expense_transaction'))

@section('content')
    <div class="content container-fluid ">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/order_report.png')}}" alt="">
                {{translate('transaction_report')}}
            </h2>
        </div>
        @include('admin-views.report.transaction-report-inline-menu')
        <div class="card mb-2">
            <div class="card-body">
                <form action="#" id="form-data" method="GET">
                    <h4 class="mb-3">{{translate('filter_Data')}}</h4>
                    <div class="row  gy-2 align-items-center text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control __form-control" name="date_type" id="date_type">
                                <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_year')}}</option>
                                <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_month')}}</option>
                                <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_week')}}</option>
                                <option value="today" {{ $date_type == 'today'? 'selected' : '' }}>{{translate('today')}}</option>
                                <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_date')}}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div class="form-floating">
                                <input type="date" name="from" value="{{$from}}" id="from_date"
                                       class="form-control __form-control">
                                <label>{{translate('start Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{$to}}" name="to" id="to_date"
                                       class="form-control __form-control">
                                <label>{{translate('end Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn--primary px-4 w-100" id="formUrlChange"
                                    data-action="{{ url()->current() }}">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="store-report-content mb-2">
            <div class="left-content expense--content">
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/expense.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_expense), currencyCode: getCurrencyCode()) }}</h4>
                        <h6 class="subtext">
                            <span>{{translate('total_Expense')}}</span>
                            <span class="ml-2" data-toggle="tooltip" data-placement="top"
                                  title="{{translate('free_delivery')}}, {{translate('coupon_discount_will_be_shown_here')}}">
                                <img class="info-img" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                     alt="img">
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/free-delivery.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $free_delivery), currencyCode: getCurrencyCode()) }}</h4>
                        <h6 class="subtext">{{translate('free_Delivery')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/coupon-discount.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $coupon_discount), currencyCode: getCurrencyCode()) }}</h4>
                        <h6 class="subtext">
                            <span>{{translate('coupon_Discount')}}</span>
                            <span class="ml-2" data-toggle="tooltip" data-placement="top"
                                  title="{{translate('discount_on_purchase_and_first_delivery_coupon_amount_will_be_shown_here')}}">
                                <img class="info-img" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                     alt="img">
                            </span>
                        </h6>
                    </div>
                </div>
            </div>
            @foreach($expense_transaction_chart['discount_amount'] as $amount)
                @php($amountArray[] = usdToDefaultCurrency(amount: $amount))
            @endforeach
            <div class="center-chart-area">
                @include('layouts.back-end._apexcharts',['title'=>'expense_Statistics','statisticsValue'=>$amountArray,'label'=>array_keys($expense_transaction_chart['discount_amount']),'statisticsTitle'=>'total_expense_amount'])
            </div>
        </div>

        <div class="card">
            <div class="px-3 py-4">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Transactions')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $expense_transactions_table->total() }}</span>
                    </h4>
                    <form action="" method="GET" class="mb-0">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" name="date_type" value="{{ $date_type }}">
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{ translate('search_by_Order_ID_or_Transaction_ID')}}"
                                   aria-label="Search orders"
                                   value="{{ $search }}"
                                   required>
                            <button type="submit"
                                    class="btn btn--primary">{{ translate('search')}}</button>
                        </div>
                    </form>
                    <div>
                        <a href="{{ route('admin.transaction.expense-transaction-summary-pdf', ['search'=>request('search'),'date_type'=>request('date_type'), 'from'=>request('from'), 'to'=>request('to')]) }}"
                           class="btn btn-outline--primary text-nowrap btn-block">
                            <i class="tio-file-text"></i>
                            {{translate('download_PDF')}}
                        </a>
                    </div>
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
                                   href="{{ route('admin.transaction.expense-transaction-export-excel', ['search'=>request('search'), 'date_type'=>request('date_type'), 'from'=>request('from'), 'to'=>request('to')]) }}">
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
                        <th>{{translate('XID')}}</th>
                        <th>{{translate('transaction_Date')}}</th>
                        <th>{{translate('order_ID')}}</th>
                        <th>{{translate('expense_Amount')}}</th>
                        <th>{{translate('expense_Type')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expense_transactions_table as $key=>$transaction)
                        <tr>
                            <td>{{ $expense_transactions_table->firstItem()+$key }}</td>
                            <td>{{ $transaction->orderTransaction->transaction_id }}</td>
                            <td>{{ date_format($transaction->updated_at, 'd F Y h:i:s a') }}</td>
                            <td>
                                <a class="title-color" href="{{route('admin.orders.details',['id'=>$transaction->id])}}">
                                    {{$transaction->id}}
                                </a>
                            </td>
                            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($transaction->coupon_discount_bearer == 'inhouse'?$transaction->discount_amount:0) + ($transaction->free_delivery_bearer=='admin'?$transaction->extra_discount:0)), currencyCode: getCurrencyCode()) }}</td>
                            <td>
                                {{ $transaction->coupon_discount_bearer == 'inhouse'?(isset($transaction->coupon->coupon_type) ? ($transaction->coupon->coupon_type == 'free_delivery' ? 'Free Delivery Promotion':ucwords(str_replace('_', ' ', $transaction->coupon->coupon_type))) : ''):'' }}
                                <br>
                                {{ $transaction->free_delivery_bearer == 'admin' ? ucwords(str_replace('_', ' ', $transaction->extra_discount_type)):'' }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.transaction.pdf-order-wise-expense-transaction', ['id'=>$transaction->id]) }}"
                                       class="btn btn-outline-success square-btn btn-sm">
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
                <div class="px-4 d-flex justify-content-lg-end">
                    {{$expense_transactions_table->links()}}
                </div>
            </div>
            @if(count($expense_transactions_table)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts-data-show.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/expense-report.js') }}"></script>
@endpush
