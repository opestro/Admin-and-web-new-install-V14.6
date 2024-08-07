@extends('layouts.back-end.app-seller')

@section('title', translate('withdraw_Request'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                {{translate('withdraw_Request')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <h5>
                                    {{ translate('withdraw_Request_Table')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1" id="withdraw-requests-count">{{ $withdrawRequests->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-lg-8 mt-3 mt-lg-0 d-flex gap-3 justify-content-lg-end flex-wrap">
                                <div class="">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input type="search" name="searchValue" class="form-control"
                                                   placeholder="{{translate('search_by_name')}}"
                                                   value="{{ request('searchValue') }}">
                                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                        </div>
                                    </form>
                                </div>
                                <button type="button" class="btn btn-outline--primary text-nowrap" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{ translate('export') }}
                                        <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item withdraw-request-file-export" href="javascript:" data-action="{{route('vendor.delivery-man.withdraw.export',['searchValue'=> request('searchValue')??''])}}">
                                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                            {{translate('Excel')}}
                                        </a>
                                    </li>
                                </ul>
                                <select name="delivery_withdraw_status_filter" class="custom-select min-w-120 max-w-200 status-filter">
                                    <option value="all" >{{translate('all')}}</option>
                                    <option value="approved" >{{translate('approved')}}</option>
                                    <option value="denied" >{{translate('denied')}}</option>
                                    <option value="pending" >{{translate('pending')}}</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div id="status-wise-view">
                        @include('vendor-views.delivery-man.withdraw._table')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-status-filter-route" data-action="{{ route('vendor.delivery-man.withdraw.index',['searchValue'=> request('searchValue')]) }}"></span>
    <div class="withdraw-info-sidebar-wrap withdraw-details-view">
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/withdraw.js')}}"></script>
@endpush
