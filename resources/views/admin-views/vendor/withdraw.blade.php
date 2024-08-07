@extends('layouts.back-end.app')

@section('title', translate('withdraw_request'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                {{translate('withdraw')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5 class="text-capitalize">
                                {{ translate('withdraw_request_table')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $withdrawRequests->total() }}</span>
                                </h5>
                            </div>
                            <div class="d-flex col-auto gap-3">
                                <select name="withdraw_status_filter" data-action="{{url()->current()}}"
                                        class="custom-select min-w-120 withdraw-status-filter">
                                    <option value="all" {{request('approved') == 'all' ? 'selected' : ''}}>{{translate('all')}}</option>
                                    <option value="approved" {{request('approved') == 'approved' ? 'selected' : ''}}>{{translate('approved')}}</option>
                                    <option value="denied" {{request('approved') == 'denied' ? 'selected' : ''}}>{{translate('denied')}}</option>
                                    <option value="pending" {{request('approved') == 'pending' ? 'selected' : ''}}>{{translate('pending')}}</option>
                                </select>
                                <div>
                                    <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                            data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{translate('export')}}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.vendors.withdraw-list-export-excel') }}?approved={{request('approved')}}">
                                                <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
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
                                <th>{{translate('amount')}}</th>
                                <th>{{ translate('name') }}</th>
                                <th>{{translate('request_time')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdrawRequests as $key => $withdrawRequest)
                                <tr>
                                    <td>{{$withdrawRequests->firstItem() + $key }}</td>
                                    <td>{{setCurrencySymbol(currencyConverter($withdrawRequest['amount']), currencyCode: getCurrencyCode(type: 'default'))}}</td>

                                    <td>
                                        @if (isset($withdrawRequest->seller))
                                            <a href="{{route('admin.vendors.view', $withdrawRequest->seller_id)}}" class="title-color hover-c1">{{ $withdrawRequest->seller->f_name . ' ' . $withdrawRequest->seller->l_name }}</a>
                                        @else
                                            <span>{{translate('not_found')}}</span>
                                        @endif
                                    </td>
                                    <td>{{$withdrawRequest->created_at}}</td>
                                    <td class="text-center">
                                        @if($withdrawRequest->approved == 0)
                                            <label class="badge badge-soft-primary">{{translate('pending')}}</label>
                                        @elseif($withdrawRequest->approved == 1)
                                            <label class="badge badge-soft-success">{{translate('approved')}}</label>
                                        @elseif($withdrawRequest->approved == 2)
                                            <label class="badge badge-soft-danger">{{translate('denied')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            @if (isset($withdrawRequest->seller))
                                            <a href="{{route('admin.vendors.withdraw_view', ['withdrawId'=>$withdrawRequest['id'], 'vendorId'=>$withdrawRequest->seller['id']])}}"
                                                class="btn btn-outline-info btn-sm square-btn"
                                                title="{{translate('view')}}">
                                                <i class="tio-invisible"></i>
                                                </a>
                                            @else
                                            <a href="javascript:">
                                                {{translate('action_disabled')}}
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            {{ $withdrawRequests->links() }}
                        </div>
                    </div>
                    @if(count($withdrawRequests) == 0)
                        @include('layouts.back-end._empty-state',['text'=>'no_withdraw_request_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
