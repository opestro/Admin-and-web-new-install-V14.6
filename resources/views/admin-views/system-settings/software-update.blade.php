@extends('layouts.back-end.app')

@section('title', translate('software_update'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('system_setup')}}
            </h2>
        </div>
        @include('admin-views.business-settings.system-settings-inline-menu')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="border-bottom px-4 py-3">
                        <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/environment.png')}}" alt="">
                            {{translate('upload_the_updated_file')}}
                            <span class="ml-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{translate('this_module_will_run_for_updates_after_version_13.1')}}">
                                <img class="info-img w-200" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="img">
                            </span>
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.system-settings.software-update')}}" method="post"
                              enctype="multipart/form-data" id="software-update-form_">
                            @csrf
                            <div class="progress mb-5 d-none height-30px">
                                <div class="progress-bar progress-bar-animated">{{translate('0').'%'}}</div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="purchase_code">{{translate('codecanyon_username')}}</label>
                                        <input type="text" class="form-control" id="username" value="{{env('BUYER_USERNAME')}}"
                                               name="username" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="purchase_code">{{translate('purchase_code')}}</label>
                                        <input type="text" class="form-control" id="purchase_key"
                                               value="{{env('PURCHASE_CODE')}}" name="purchase_key" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="custom-file text-left">
                                        <input type="file" name="update_file" class="custom-file-input form-control"
                                               accept=".zip" required>
                                        <label class="custom-file-label"
                                               for="customFileUpload">{{translate('choose_updated_file')}}</label>
                                    </div>
                                </div>
                            </div>
                            @php($conditionOne=str_replace('M','',ini_get('upload_max_filesize'))>=180 && str_replace('M','',ini_get('upload_max_filesize'))>=180)
                            @php($conditionTwo=str_replace('M','',ini_get('post_max_size'))>=200 && str_replace('M','',ini_get('post_max_size'))>=200)
                            @if($conditionOne && $conditionTwo)
                                <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                    <button type="submit" class="btn btn--primary px-4">
                                        {{translate('upload_&_update')}}
                                    </button>
                                </div>
                            @else
                                <div class="row" id="update-error-message">
                                    <div class="col-12">
                                        <div class="alert alert-soft-{{($conditionOne)?'success':'danger'}}" role="alert">
                                            {{'1.'.' '.translate('please_make_sure').' '.','.' '.translate('your_server_php').' '.','.' '.'"'.translate('upload_max_filesize').'"'.' '.translate('value_is_greater_or_equal_to').' '.'180M'.'.'.translate('current_value_is').'-'.ini_get('upload_max_filesize')}}
                                        </div>
                                        <div class="alert alert-soft-{{($conditionTwo)?'success':'danger'}}" role="alert">
                                            {{'2.'.' '.translate('please_make_sure').' '.','.' '.translate('your_server_php').' '.','.' '.'"'.translate('post_max_size').'"'.' '.translate('value_is_greater_or_equal_to').' '.'200M'.'.'.translate('current_value_is').'-'.ini_get('post_max_size')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                    <button type="button" class="btn btn--primary px-4" id="update-button-message">
                                        {{translate('upload_&_update')}}
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-software-update-route" data-action="{{route('admin.system-settings.software-update')}}" data-redirect-route="{{route('home')}}"></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush
