@extends('layouts.back-end.app-seller')

@section('title', translate('bank_Info_View'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/my-bank-info.png')}}" alt="">
                {{translate('my_bank_info')}}
            </h2>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card text-start">
                    <div class="border-bottom d-flex gap-3 flex-wrap justify-content-between align-items-center px-4 py-3">
                        <div class="d-flex gap-2 align-items-center">
                            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/bank.png')}}" alt="" />
                            <h3 class="mb-0">{{translate('account_details')}} <span data-toggle="tooltip" data-placement="right" data-title="{{translate('update_your_bank_details_with_correct_information').'.'.translate('it_will_be_used_for_your_withdraw_request_transactions by admin').'.'}}"> <img src="{{ dynamicAsset(path: 'public/assets/installation/assets/img/svg-icons/info.svg') }}" alt="" class="svg ml-1"> </span></h3>
                        </div>
                    </div>
                    <div class="card-body p-30">
                        <div class="row justify-content-center">
                            <div class="col-sm-6 col-md-8 col-lg-6 col-xl-5">
                                <div class="card border bank-info-card bg-bottom text--black bg-contain bg-img" style="background-image: url({{dynamicAsset(path: 'public/assets/back-end/img/wallet-bg.png')}});">
                                    <div class="p-20">
                                        <div class="text-capitalize">
                                            <i class="tio-user"></i> {{translate('holder_name').':'}} <strong class="text-title">{{$vendor->holder_name ?? translate('no_data_found')}}</strong>
                                        </div>
                                    </div>

                                    <a href="{{route('vendor.profile.update-bank-info',[$vendor->id])}}" class="btn btn-sm btn--primary edit-btn">
                                        {{translate('edit')}} <i class="tio-edit"></i>
                                    </a>
                                    <div class="card-body position-relative pt-2">
                                        <ul class="dm-info p-0 m-0">
                                            <li>
                                                <span class="__w-100px">{{translate('bank_Name')}}</span>
                                                <span>:</span>
                                                <strong class="right pl-4">{{$vendor->bank_name ?? translate('no_data_found')}}</strong>
                                            </li>
                                            <li>
                                                <span class="__w-100px">{{translate('branch_Name')}}</span>
                                                <span>:</span>
                                                <strong class="right pl-4">{{$vendor->branch ?? translate('no_data_found')}}</strong>
                                            </li>
                                            <li>
                                                <span class="__w-100px">{{translate('account_Number')}}</span>
                                                <span>:</span>
                                                <strong class="right pl-4">{{$vendor->account_no ?? translate('no_data_found')}}</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
