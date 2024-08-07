@extends('layouts.back-end.app-seller')

@section('title', translate('bank_Info'))

@section('content')
    <div class="content container-fluid text-start">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/my-bank-info.png')}}" alt="">
                {{translate('edit_Bank_info')}}
            </h2>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0 text-capitalize">{{translate('edit_bank_info')}}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('vendor.profile.update-bank-info',[$vendor->id])}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="title-color">{{translate('bank_Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_name" value="{{$vendor->bank_name}}"
                                               class="form-control" id="name"
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="title-color">{{translate('branch_Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="branch" value="{{$vendor->branch}}" class="form-control"
                                               id="name"
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="account_no" class="title-color">{{translate('holder_Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="holder_name" value="{{$vendor->holder_name}}"
                                               class="form-control" id="account_no"
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="account_no" class="title-color">{{translate('account_No')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="account_no" value="{{$vendor->account_no}}"
                                               class="form-control" id="account_no"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a class="btn btn-danger" href="{{route('vendor.profile.index')}}">{{translate('cancel')}}</a>
                                <button type="submit" class="btn btn--primary" id="btn_update">{{translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
