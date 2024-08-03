@extends('layouts.back-end.app')

@section('title', translate('customer_loyalty_point_report'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/loyalty_point.png')}}" alt="">
                {{translate('customer_loyalty_point_report')}}
            </h2>
        </div>
        <div class="card">
            <div class="card-header text-capitalize">
                <h4 class="mb-0">{{translate('filter_options')}}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 pt-3">
                        <form action="{{route('admin.customer.loyalty.report')}}" method="get">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <input type="date" name="from" id="start-date-time" value="{{request()->get('from')}}" class="form-control" title="{{ucfirst(translate('from_date'))}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <input type="date" name="to" id="end-date-time" value="{{request()->get('to')}}" class="form-control" title="{{ucfirst(translate('to_date'))}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        @php
                                        $transaction_status=request()->get('transaction_type');
                                        @endphp
                                        <select name="transaction_type" id="" class="form-control" title="{{translate('select_transaction_type')}}">
                                            <option value="">{{ translate('all')}}</option>
                                            <option value="point_to_wallet" {{isset($transaction_status) && $transaction_status=='point_to_wallet'?'selected':''}}>{{ translate('point_to_wallet')}}</option>
                                            <option value="order_place" {{isset($transaction_status) && $transaction_status=='order_place'?'selected':''}}>{{ translate('order_place')}}</option>
                                            <option value="refund_order" {{isset($transaction_status) && $transaction_status=='refund_order'?'selected':''}}>{{ translate('refund_order')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <input type="hidden" id='customer-id'  name="customer_id" value="{{request('customer_id') ?? 'all'}}">
                                        <select data-placeholder="
                                                    @if($customer == 'all')
                                                        {{translate('all_customer')}}
                                                    @else
                                                        {{ $customer['name'] ?? $customer['f_name'].' '.$customer['l_name'].' '.'('.$customer['phone'].')'}}
                                                    @endif"
                                                class="get-customer-list-by-ajax-request form-control form-ellipsis set-customer-value">
                                            <option value="all">{{translate('all_customer')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn--primary px-4"><i class="tio-filter-list mr-1"></i>{{translate('filter')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header text-capitalize">
                <h4 class="mb-0">{{translate('summary')}}</h4>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    @php
                        $credit = $data[0]->total_credit??0;
                        $debit = $data[0]->total_debit??0;
                        $balance = $credit - $debit;
                    @endphp
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-atm"></i>
                            <h6 class="order-stats__subtitle">{{translate('debit')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text--primary">
                            {{(int)($debit)}}
                        </span>
                    </div>
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-money"></i>
                            <h6 class="order-stats__subtitle">{{translate('credit')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text-warning">
                            {{(int)$credit}}
                        </span>
                    </div>
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-wallet"></i>
                            <h6 class="order-stats__subtitle">{{translate('balance')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text-success">
                            {{(int)$balance}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header text-capitalize gap-2">
                <h4 class="mb-0 text-nowrap ">
                    {{translate('transactions')}}
                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{$transactions->total()}}</span>
                </h4>
                <div class="d-flex justify-content-end">
                    <div class="dropdown text-nowrap">
                        <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{translate('export')}}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.customer.loyalty.export',['transaction_type'=>$transaction_status,'customer_id'=>request('customer_id'),'to'=>request('to'),'from'=>request('from')])}}">
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
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('transaction_ID')}}</th>
                            <th>{{translate('customer')}}</th>
                            <th>{{translate('credit')}}</th>
                            <th>{{translate('debit')}}</th>
                            <th>{{translate('balance')}}</th>
                            <th>{{translate('transaction_type')}}</th>
                            <th>{{translate('reference')}}</th>
                            <th class="text-center">{{translate('created_at')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $key=>$transaction)
                        <tr scope="row">
                            <td >{{$key+$transactions->firstItem()}}</td>
                            <td>{{$transaction['transaction_id']}}</td>
                            <td><a href="{{route('admin.customer.view',['user_id'=>$transaction['user_id']])}}" class="title-color hover-c1">{{Str::limit($transaction->user?$transaction->user->f_name.' '.$transaction->user->l_name:translate('not_found'),20)}}</a></td>
                            <td>{{$transaction['credit']}}</td>
                            <td>{{$transaction['debit']}}</td>
                            <td>{{$transaction['balance']}}</td>
                            <td>
                                <span class="badge badge-soft-{{$transaction['transaction_type']=='order_refund'
                                    ?'danger'
                                    :($transaction['transaction_type']=='loyalty_point'?'warning'
                                        :($transaction['transaction_type']=='order_place'
                                            ?'info'
                                            :'success'))
                                    }}">
                                    {{translate($transaction['transaction_type'])}}
                                </span>
                            </td>
                            <td>{{$transaction['reference']}}</td>
                            <td class="text-center">{{date('Y/m/d '.config('timeformat'), strtotime($transaction['created_at']))}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!!$transactions->links()!!}
                </div>
            </div>
            @if(count($transactions)==0)
                @include('layouts.back-end._empty-state',['text'=>'no_data_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection
