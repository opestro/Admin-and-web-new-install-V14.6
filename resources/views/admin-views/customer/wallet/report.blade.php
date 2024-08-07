@extends('layouts.back-end.app')

@section('title',translate('customer_wallet'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-flex justify-content-between flex-wrap gap-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/admin-wallet.png')}}" alt="">
                {{translate('wallet')}}
            </h2>
            @if($customerStatus == 1)
                <button type="button" class="btn btn--primary text-capitalize" data-toggle="modal" data-target="#add-fund-modal">
                    {{translate('add_fund')}}
                </button>
            @endif
        </div>
        <div class="modal fade" id="add-fund-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title text-capitalize" id="exampleModalLongTitle">{{translate('add_fund')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.customer.wallet.add-fund')}}" method="post" enctype="multipart/form-data" id="add-fund">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="form-group">
                                        <label class="input-label d-flex" for="customer">{{translate('customer')}}</label>
                                        <select id='form-customer' name="customer_id" data-placeholder="{{translate('select_customer')}}" class="get-customer-list-without-all-customer" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="form-group">
                                        <label class="input-label d-flex" for="amount">{{translate('amount')}}</label>
                                        <input type="number" class="form-control" name="amount" id="amount" step=".01" placeholder="{{translate('ex').':'.'500'}}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label d-flex align-items-center gap-1" for="reference">{{translate('reference')}} <small>({{translate('optional')}})</small></label>
                                        <input type="text" class="form-control" name="reference" placeholder="{{translate('ex').':'.'abc990'}}" id="reference">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" id="submit" class="btn btn--primary px-4">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card  mt-3">
            <div class="card-header text-capitalize">
                <h4 class="mb-0">{{translate('filter_options')}}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 pt-3">
                        <form action="{{route('admin.customer.wallet.report')}}" method="get">
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
                                        <select name="transaction_type" class="form-control" title="{{translate('select_transaction_type')}}">
                                            <option value="">{{translate('all')}}</option>
                                            <option value="add_fund_by_admin" {{isset($transaction_status) && $transaction_status=='add_fund_by_admin'?'selected':''}} >{{translate('add_fund_by_admin')}}</option>
                                            <option value="add_fund" {{isset($transaction_status) && $transaction_status=='add_fund'?'selected':''}} >{{translate('add_fund')}}</option>
                                            <option value="order_refund" {{isset($transaction_status) && $transaction_status=='order_refund'?'selected':''}}>{{translate('refund_order')}}</option>
                                            <option value="loyalty_point" {{isset($transaction_status) && $transaction_status=='loyalty_point'?'selected':''}}>{{translate('customer_loyalty_point')}}</option>
                                            <option value="order_place" {{isset($transaction_status) && $transaction_status=='order_place'?'selected':''}}>{{translate('order_place')}}</option>
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
                            <div class="d-flex justify-content-end">
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
                        $credit = $data[0]->total_credit;
                        $debit = $data[0]->total_debit;
                        $balance = $credit - $debit;
                    @endphp
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-atm"></i>
                            <h6 class="order-stats__subtitle">{{translate('debit')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text--primary">
                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $debit??0))}}
                        </span>
                    </div>
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-money"></i>
                            <h6 class="order-stats__subtitle">{{translate('credit')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text-warning">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$credit??0))}}
                        </span>
                    </div>
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-wallet"></i>
                            <h6 class="order-stats__subtitle">{{translate('balance')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text-success">
                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$balance??0))}}
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <div class="card mt-3">
            <div class="card-header text-capitalize gap-2">
                <h4 class="mb-0 text-nowrap ">
                    {{translate('transactions')}}
                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $transactions->total() }}</span>
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
                                <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.customer.wallet.export',['transaction_type'=>$transaction_status,'customer_id'=>request('customer_id'),'to'=>request('to'),'from'=>request('from')])}}">
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
                            <th>{{translate('Customer')}}</th>
                            <th>{{translate('credit')}}</th>
                            <th>{{translate('debit')}}</th>
                            <th>{{translate('balance')}}</th>
                            <th>{{translate('transaction_type')}}</th>
                            <th>{{translate('reference')}}</th>
                            <th class="text-center">{{translate('created_at')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $key => $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem()+($key) }}</td>
                            <td>{{$transaction['transaction_id']}}</td>
                            <td>
                                <a href="{{route('admin.customer.view',['user_id'=>$transaction['user_id']])}}" class="title-color hover-c1">{{Str::limit($transaction['user'] ? $transaction?->user->f_name.' '.$transaction?->user->l_name : translate('not_found'),20)}}</a>
                            </td>
                            <td>
                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['credit']))}}
                                @if($transaction['transaction_type'] == 'add_fund' && $transaction['admin_bonus'] > 0)
                                    <span class="text-sm badge badge-soft-success">
                                        + {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['admin_bonus'])) }} {{ translate('admin_bonus') }}
                                    </span>
                                @endif
                            </td>
                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['debit']))}}</td>

                            <td>
                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$transaction['balance']))}}
                            </td>

                            <td>
                                <span class="badge badge-soft-{{$transaction['transaction_type']=='order_refund' ?'danger'
                                    :($transaction['transaction_type']=='loyalty_point'?'warning'
                                        :($transaction['transaction_type']=='order_place'
                                            ?'info'
                                            :'success'))
                                    }}">
                                    {{translate($transaction['transaction_type'])}}
                                </span>
                            </td>
                            <td>{{translate(str_replace('_',' ',$transaction['reference'])) }}</td>
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

@push('script')
    <script>
        'use strict' ;
        $('#add-fund').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            Swal.fire({
                title: "{{translate('are_you_sure').'?'}} ",
                text: '{{translate("you_want_to_add_fund")}} '+$('#amount').val()+' {{getCurrencyCode(type: 'default').' '.translate("to")}} '+$('#form-customer option:selected').text()+'{{translate("to_wallet")}}',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{translate("no")}}',
                confirmButtonText: '{{translate("add")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{route('admin.customer.wallet.add-fund')}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.errors) {
                                for (let i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                toastr.success('{{translate("fund_added_successfully")}}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                location.reload();
                            }
                        }
                    });
                }
            })
        })
    </script>
@endpush
