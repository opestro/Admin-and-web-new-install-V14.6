@extends('layouts.back-end.app')

@section('title', $seller?->shop->name ?? translate("shop_Name"))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('vendor_details')}}
            </h2>
        </div>
        <div class="page-header border-0 mb-4">
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="{{ route('admin.vendors.view',$seller['id']) }}">{{translate('shop_overview')}}</a>
                    </li>
                    @if ($seller['status']!="pending")
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.vendors.view',['id'=>$seller['id'], 'tab'=>'order']) }}">{{translate('order')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.vendors.view',['id'=>$seller['id'], 'tab'=>'product']) }}">{{translate('product')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.vendors.view',['id'=>$seller['id'], 'tab'=>'setting']) }}">{{translate('setting')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.vendors.view',['id'=>$seller['id'], 'tab'=>'transaction']) }}">{{translate('transaction')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.vendors.view',['id'=>$seller['id'], 'tab'=>'review']) }}">{{translate('review')}}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="card card-top-bg-element mb-5">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 justify-content-between">
                    <div class="media flex-column flex-sm-row gap-3">
                        <img class="avatar avatar-170 rounded-0"
                             src="{{ getValidImage(path: 'storage/app/public/shop/'.$seller?->shop->image, type: 'backend-basic') }}"
                             alt="{{translate('image')}}">
                        <div class="media-body">
                            @if($seller?->shop->temporary_close || ($seller?->shop->vacation_status && $current_date >= date('Y-m-d', strtotime($seller?->shop->vacation_start_date)) && $current_date <= date('Y-m-d', strtotime($seller?->shop->vacation_end_date))))
                                <div class="d-flex justify-content-between gap-2 mb-4">
                                    @if($seller->shop->temporary_close)
                                        <div class="btn btn-soft-danger">{{translate('this_shop_currently_close_now')}} </div>
                                    @elseif($seller->shop->vacation_status && $current_date >= date('Y-m-d', strtotime($seller->shop->vacation_start_date)) && $current_date <= date('Y-m-d', strtotime($seller->shop->vacation_end_date)))
                                        <div class="btn btn-soft-danger">{{translate('this_shop_currently_on_vacation')}} </div>
                                    @endif
                                </div>
                            @endif
                            <div class="d-block">
                                <h2 class="mb-2 pb-1">{{ $seller->shop? $seller->shop->name : translate("shop_Name")." : ".translate("update_Please") }}</h2>
                                <div class="d-flex gap-3 flex-wrap mb-3 lh-1">
                                    <div class="review-hover position-relative cursor-pointer d-flex gap-2 align-items-center">
                                        <i class="tio-star"></i>
                                        <span>{{ round($seller->average_rating, 1) }}</span>
                                        <div class="review-details-popup">
                                            <h6 class="mb-2">{{translate('rating')}}</h6>
                                            <div class="">
                                                <ul class="list-unstyled list-unstyled-py-2 mb-0">
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">{{'5'.' '.translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar width--100" role="progressbar"
                                                                 aria-valuenow="0" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_5}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">{{'4'.' '.translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar width--80" role="progressbar"
                                                                 aria-valuenow="0" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_4}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">{{'3'.' '.translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar width--60" role="progressbar"
                                                                 aria-valuenow="0" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_3}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">{{'2'.' '.translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar width--40" role="progressbar"
                                                                 aria-valuenow="0" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_2}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">{{'2'.' '.translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar width--20" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_1}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="border-left"></span>
                                    <a href="javascript:"
                                       class="text-dark">{{$seller->total_rating}} {{translate('ratings')}}</a>
                                    <span class="border-left"></span>
                                    <a href="{{ $seller['status']!="pending" ? route('admin.vendors.view',['id'=>$seller['id'], 'tab'=>'review']): 'javascript:' }}"
                                       class="text-dark">{{$seller->rating_count}} {{translate('reviews')}}</a>
                                </div>
                                @if ( $seller['status']!="pending" && $seller['status']!="suspended" && $seller['status']!="rejected")
                                    <a href="{{route('shopView',['id'=>$seller['id']])}}"
                                       class="btn btn-outline--primary px-4" target="_blank"><i
                                                class="tio-globe"></i> {{translate('view_live')}}
                                        @endif
                                    </a>
                            </div>
                        </div>
                    </div>
                    @if ($seller['status']=="pending")
                        <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" id="reject-form" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller['id']}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="button" class="btn btn-danger px-5 form-alert" data-message="{{translate('want_to_reject_this_vendor').'?'}}" data-id="reject-form">{{translate('reject')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" id="approve-form" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller['id']}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="button" class="btn btn-success px-5 form-alert" data-message="{{translate('want_to_approve_this_vendor').'?'}}" data-id="approve-form">{{translate('approve')}}</button>
                            </form>
                        </div>
                    @endif
                    @if ($seller['status']=="approved")
                        <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" id="suspend-form" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller['id']}}">
                                <input type="hidden" name="status" value="suspended">
                                <button type="button" class="btn btn-danger px-5 form-alert" data-message="{{translate('want_to_suspend_this_vendor').'?'}}" data-id="suspend-form">{{translate('suspend_this_vendor')}}</button>
                            </form>
                        </div>
                    @endif
                    @if ($seller['status']=="suspended" || $seller['status']=="rejected")
                        <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                            <form class="d-inline-block" action="{{route('admin.vendors.updateStatus')}}" id="active-form" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller['id']}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="button" class="btn btn-success px-5 form-alert" data-message="{{translate('want_to_active_this_vendor').'?'}}" data-id="active-form">{{translate('active')}}</button>
                            </form>
                        </div>
                    @endif
                </div>
                <hr>
                <div class="d-flex gap-3 flex-wrap flex-lg-nowrap">
                    <div class="border p-3 w-170">
                        <div class="d-flex flex-column mb-1">
                            <h6 class="font-weight-normal">{{translate('total_products')}} :</h6>
                            <h3 class="text-primary fs-18">{{$seller->product_count}}</h3>
                        </div>

                        <div class="d-flex flex-column">
                            <h6 class="font-weight-normal">{{translate('total_orders')}} :</h6>
                            <h3 class="text-primary fs-18">{{$seller->orders_count}}</h3>
                        </div>
                    </div>
                    <div class="row gy-3 flex-grow-1 w-100">
                        <div class="col-sm-6 col-xxl-3">
                            <h4 class="mb-3 text-capitalize">{{translate('shop_information')}}</h4>

                            <div class="pair-list">
                                <div>
                                    <span class="key text-nowrap">{{translate('shop_name')}}</span>
                                    <span>:</span>
                                    <span class="value ">{{$seller?->shop->name}}</span>
                                </div>

                                <div>
                                    <span class="key">{{translate('phone')}}</span>
                                    <span>:</span>
                                    <span class="value">{{$seller?->shop->contact}}</span>
                                </div>

                                <div>
                                    <span class="key">{{translate('address')}}</span>
                                    <span>:</span>
                                    <span class="value">{{$seller?->shop->address}}</span>
                                </div>

                                <div>
                                    <span class="key">{{translate('status')}}</span>
                                    <span>:</span>
                                    <span class="value">
                                        <span class="badge badge-{{$seller['status']=='approved'? 'info' :'danger'}}">
                                            {{ $seller['status']=='approved'? translate('active') : translate('inactive') }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xxl-3">
                            <h4 class="mb-3 text-capitalize">{{translate('vendor_information')}}</h4>

                            <div class="pair-list">
                                <div>
                                    <span class="key">{{translate('name')}}</span>
                                    <span>:</span>
                                    <span class="value text-capitalize">{{$seller['f_name'].' '.$seller['l_name']}}</span>
                                </div>

                                <div>
                                    <span class="key">{{translate('email')}}</span>
                                    <span>:</span>
                                    <span class="value">{{$seller['email']}}</span>
                                </div>

                                <div>
                                    <span class="key">{{translate('phone')}}</span>
                                    <span>:</span>
                                    <span class="value">{{$seller['phone']}}</span>
                                </div>
                            </div>
                        </div>
                        @if ($seller['status']!="pending")
                            <div class="col-xxl-6">
                                <div class="bg-light p-3 border border-primary-light rounded">
                                    <h4 class="mb-3 text-capitalize">{{translate('bank_information')}}</h4>

                                    <div class="d-flex gap-5">
                                        <div class="pair-list">
                                            <div>
                                                <span class="key text-nowrap">{{translate('bank_name')}}</span>
                                                <span class="px-2">:</span>
                                                <span class="value ">{{ $seller['bank_name'] ?? translate('no_data_found') }}</span>
                                            </div>

                                            <div>
                                                <span class="key text-nowrap">{{translate('branch')}}</span>
                                                <span class="px-2">:</span>
                                                <span class="value">{{ $seller['branch'] ?? translate('no_data_found') }}</span>
                                            </div>
                                        </div>
                                        <div class="pair-list">
                                            <div>
                                                <span class="key text-nowrap">{{translate('holder_name')}}</span>
                                                <span class="px-2">:</span>
                                                <span class="value">{{ $seller['holder_name'] ?? translate('no_data_found') }}</span>
                                            </div>

                                            <div>
                                                <span class="key text-nowrap">{{translate('A/C_No')}}</span>
                                                <span class="px-2">:</span>
                                                <span class="value">{{ $seller['account_no'] ?? translate('no_data_found') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if ($seller['status']!="pending")
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center g-2 mb-3">
                        <div class="col-sm-6">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                <img width="20" class="mb-1"
                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/admin-wallet.png')}}" alt="">
                                {{translate('vendor_Wallet')}}
                            </h4>
                        </div>
                    </div>

                    <div class="row g-2" id="order_stats">
                        <div class="col-lg-4">
                            <div class="card h-100 d-flex justify-content-center align-items-center">
                                <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
                                    <img width="48" class="mb-2"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw.png')}}" alt="">
                                    <h3 class="for-card-count mb-0 fz-24">{{ $seller->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller->wallet->total_earning)) : 0 }}</h3>
                                    <div class="font-weight-bold text-capitalize mb-30">
                                        {{translate('withdrawable_balance')}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="card card-body h-100 justify-content-center">
                                        <div class="d-flex gap-2 justify-content-between align-items-center">
                                            <div class="d-flex flex-column align-items-start">
                                                <h3 class="mb-1 fz-24">{{ $seller->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller->wallet->pending_withdraw)) : 0}}</h3>
                                                <div class="text-capitalize mb-0">{{translate('pending_Withdraw')}}</div>
                                            </div>
                                            <div>
                                                <img width="40" class="mb-2"
                                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/pw.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-body h-100 justify-content-center">
                                        <div class="d-flex gap-2 justify-content-between align-items-center">
                                            <div class="d-flex flex-column align-items-start">
                                                <h3 class="mb-1 fz-24">{{ $seller->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller->wallet->commission_given)) : 0}}</h3>
                                                <div class="text-capitalize mb-0">{{translate('total_Commission_given')}}</div>
                                            </div>
                                            <div>
                                                <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/tcg.png')}}"
                                                     alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-body h-100 justify-content-center">
                                        <div class="d-flex gap-2 justify-content-between align-items-center">
                                            <div class="d-flex flex-column align-items-start">
                                                <h3 class="mb-1 fz-24">{{$seller->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller->wallet->withdrawn)) : 0}}</h3>
                                                <div class="text-capitalize mb-0">{{translate('aready_Withdrawn')}}</div>
                                            </div>
                                            <div>
                                                <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/aw.png')}}"
                                                     alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-body h-100 justify-content-center">
                                        <div class="d-flex gap-2 justify-content-between align-items-center">
                                            <div class="d-flex flex-column align-items-start">
                                                <h3 class="mb-1 fz-24">{{ $seller->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller->wallet->delivery_charge_earned)) : 0}}</h3>
                                                <div class="text-capitalize mb-0">{{translate('total_delivery_charge_earned')}}</div>
                                            </div>
                                            <div>
                                                <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/tdce.png')}}"
                                                     alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-body h-100 justify-content-center">
                                        <div class="d-flex gap-2 justify-content-between align-items-center">
                                            <div class="d-flex flex-column align-items-start">
                                                <h3 class="mb-1 fz-24">{{ $seller->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller->wallet->total_tax_collected)) : 0}}</h3>
                                                <div class="text-capitalize mb-0">{{translate('total_tax_given')}}</div>
                                            </div>
                                            <div>
                                                <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/ttg.png')}}"
                                                     alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-body h-100 justify-content-center">
                                        <div class="d-flex gap-2 justify-content-between align-items-center">
                                            <div class="d-flex flex-column align-items-start">
                                                <h3 class="mb-1 fz-24">{{ $seller->wallet ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller->wallet->collected_cash)) : 0}}</h3>
                                                <div class="text-capitalize mb-0">{{translate('collected_cash')}}</div>
                                            </div>
                                            <div>
                                                <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/cc.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
