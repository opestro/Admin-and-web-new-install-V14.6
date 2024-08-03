@php use App\Utils\Helpers; @endphp
@extends('layouts.back-end.app')

@section('content')
    <div class="content container-fluid ">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/order_report.png')}}" alt="">
                {{translate('transaction_table')}}
                <span class="badge badge-soft-dark radius-50 fz-12">{{$transactions->total()}}</span>
            </h2>
        </div>
        <div class="card">
            <div class="px-3 py-4">
                <div class="row gy-2">
                    <div class="col-xl-3">
                        <form action="" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{ translate('search_by_orders_id_or_transaction_id')}}"
                                       aria-label="Search orders"
                                       value="{{ $search }}"
                                       required>
                                <button type="submit"
                                        class="btn btn--primary">{{ translate('search')}}</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-9">
                        <form action="#" id="form-data" method="GET">
                            <div
                                class="row  gy-2 align-items-center text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                <div class="col-md-3">
                                    <div class="">
                                        <select class="js-select2-custom form-control" name="customer_id">
                                            <option class="text-center" value="0">
                                                {{'---'.translate('select_customer').'---'}}
                                            </option>
                                            @foreach($customers as $customer)
                                                <option class="text-left text-capitalize"
                                                        value="{{ $customer->id }}" {{ $customer->id == $customer_id ? 'selected' : '' }}>
                                                    {{ $customer->f_name.' '.$customer->l_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="">
                                        <select class="form-control" name="status">
                                            <option class="text-center" value="0" disabled>
                                                {{'---'.translate('select_status').'---'}}---
                                            </option>
                                            <option class="text-capitalize"
                                                    value="all" {{ $status == 'all'? 'selected' : '' }} >{{translate('all')}} </option>
                                            <option class="text-capitalize"
                                                    value="disburse" {{ $status == 'disburse'? 'selected' : '' }} >{{translate('disburse')}} </option>
                                            <option class="text-capitalize"
                                                    value="hold" {{ $status == 'hold'? 'selected' : '' }}>{{translate('hold')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="">
                                        <input type="date" name="from" value="{{$from}}" id="start-date-time"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="">
                                        <input type="date" value="{{$to}}" name="to" id="end-date-time" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn--primary px-4" id="formUrlChange"
                                            data-action="{{ url()->current() }}">
                                        {{translate('filter')}}
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn--primary text-nowrap btn-block"
                                                data-toggle="dropdown">
                                            <i class="tio-download-to"></i>
                                            {{translate('export')}}
                                            <i class="tio-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a class="dropdown-item"
                                                   href="{{ route('admin.transaction.transaction-export', ['customer_id'=>request('customer_id'), 'status'=>request('status'), 'from'=>request('from'), 'to'=>request('to')]) }}">{{translate('excel')}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="datatable"
                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('vendor_name')}}</th>
                        <th>{{translate('customer_name')}}</th>
                        <th>{{translate('order_id')}}</th>
                        <th>{{translate('transaction_id')}}</th>
                        <th>{{translate('order_amount')}}</th>
                        <th>{{translate('vendor_amount') }}</th>
                        <th>{{translate('admin_commission')}}</th>
                        <th>{{translate('received_by')}}</th>
                        <th>{{translate('delivered_by')}}</th>
                        <th>{{translate('delivery_charge')}}</th>
                        <th>{{translate('payment_method')}}</th>
                        <th>{{translate('tax')}}</th>
                        <th>{{translate('date')}}</th>
                        <th>{{translate('status')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $key=>$transaction)
                        <tr>
                            <td>{{$transactions->firstItem()+$key}}</td>
                            <td>
                                @if($transaction['seller_is'] == 'admin')
                                    {{ Helpers::get_business_settings('company_name') }}
                                @else
                                    @if (isset($transaction->seller))
                                        {{ $transaction->seller->f_name }} {{ $transaction->seller->l_name }}
                                    @else
                                        {{translate('not_found')}}
                                    @endif
                                @endif

                            </td>
                            <td>
                                @if (isset($transaction->customer))
                                    {{ $transaction->customer->f_name}} {{ $transaction->customer->l_name }}
                                @else
                                    {{translate('not_found')}}
                                @endif
                            </td>
                            <td>{{$transaction['order_id']}}</td>
                            <td>{{$transaction['transaction_id']}}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['order_amount']))}}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['seller_amount']))}}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['admin_commission']))}}</td>
                            <td>{{$transaction['received_by']}}</td>
                            <td>{{$transaction['delivered_by']}}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['delivery_charge']))}}</td>
                            <td>{{str_replace('_',' ',$transaction['payment_method'])}}</td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['tax']))}}</td>
                            <td>{{ date('d M Y',strtotime($transaction['created_at'])) }}</td>
                            <td>
                                @if($transaction['status'] == 'disburse')
                                    <span class="badge badge-soft-success">
                                        {{$transaction['status']}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-warning ">
                                        {{$transaction['status']}}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                @if(count($transactions)==0)
                    @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                @endif
            </div>

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {{$transactions->links()}}
                </div>
            </div>

        </div>

    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            $('.js-select2-custom').select2();
        });
    </script>
@endpush
