@extends('layouts.back-end.app')

@section('title', translate('storage_connection_settings'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/3rd-party.png') }}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-inline-menu')

        <div class="card border-0">
            <div class="card-header shadow-none border-0 pb-0 pt-4">
                <h5 class="card-title align-items-center text-capitalize">
                    {{translate('storage_connection_settings')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                            <span class="title-color text-capitalize">
                                {{translate('local_system')}}
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('enabling_the_option_will_ensure_that_all_uploaded_files_are_saved_to_your_local_storage') }}">
                                    <img width="16" src="{{dynamicAsset('public/assets/back-end/img/info-circle.svg')}}" alt="">
                                </span>
                            </span>

                            <form action="{{ route('admin.storage-connection-settings.update-storage-type') }}" method="post" id="storage-connection-local-form" data-from="storage-connection-type">
                                @csrf
                                <input type="hidden" name="type" value="public">
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input toggle-switch-message" name="status" id="storage-connection-local"
                                           {{ $storageConnectionType == null || $storageConnectionType == 'public' ? 'checked' : '' }}
                                           value="1"
                                           data-modal-id = "toggle-status-modal"
                                           data-toggle-id = "storage-connection-local"
                                           data-on-image = "delivery-available-country-on.png"
                                           data-off-image = "delivery-available-country-off.png"
                                           data-on-title = "{{translate('want_to_Turn_ON_Delivery_Available_Country').'?'}}"
                                           data-off-title = "{{translate('want_to_Turn_OFF_Delivery_Available_Country').'?'}}"
                                           data-on-message = "<p>{{translate('if_enabled_the_admin_or_vendor_can_deliver_orders_to_the_selected_countries')}}</p>"
                                           data-off-message = "<p>{{translate('if_disabled_there_will_be_no_delivery_restrictions_for_admin_or_vendors')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                            <span class="title-color">
                                {{ translate('3rd_Party_Storage') }}
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ translate('enabling_the_option_will_ensure_that_all_uploaded_files_are_saved_to_your_3rd_party_s3_storage.') }}">
                                    <img width="16" src="{{dynamicAsset('public/assets/back-end/img/info-circle.svg')}}" alt="">
                                </span>
                            </span>

                            <form action="{{ route('admin.storage-connection-settings.update-storage-type') }}" method="post" id="storage-connection-s3-form" data-from="storage-connection-type">
                                @csrf
                                <input type="hidden" name="type" value="s3">
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input toggle-switch-message" name="status" id="storage-connection-s3"
                                           {{ $storageConnectionType == 's3' ? 'checked' : '' }}
                                           value="1"
                                           data-modal-id="toggle-status-modal"
                                           data-toggle-id="storage-connection-s3"
                                           data-on-image="delivery-available-country-on.png"
                                           data-off-image="delivery-available-country-off.png"
                                           data-on-title="{{translate('want_to_Turn_ON_Delivery_Available_Country').'?'}}"
                                           data-off-title="{{translate('want_to_Turn_OFF_Delivery_Available_Country').'?'}}"
                                           data-on-message="<p>{{translate('if_enabled_the_admin_or_vendor_can_deliver_orders_to_the_selected_countries')}}</p>"
                                           data-off-message="<p>{{translate('if_disabled_there_will_be_no_delivery_restrictions_for_admin_or_vendors')}}</p>">
                                    <span class="switcher_control"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header p-20 shadow-none">
                <div>
                    <h4 class="card-title align-items-center mb-2">
                        {{translate('S3_Credential')}}
                    </h4>
                    <span>{{ translate('The_Access_Key_ID_is_a_publicly_accessible_identifier_used_to_authenticate_requests_to_S3.') }} <a href="{{ 'https://drive.google.com/file/d/1vlzak2-pBD8zS-tVGRZkAUhlRT_QfRY9/view' }}" target="_blank" class="text--primary text-underline font-weight-semibold">{{ translate('Learn_More') }}</a></span>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.storage-connection-settings.s3-credential') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="border border-D0DBE966 radius-10 p-4">
                                <div class="row align-items-center">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <label class="form-label h4 text-capitalize m-0" for="key-cred">
                                            {{ translate('key_credential').'.' }}
                                        </label>
                                    </div>
                                    <div class="col-xl-9 col-lg-8 col-sm-6">
                                        <div >
                                            <input type="text" class="form-control" placeholder="{{translate('Enter your key')}}" id="key-cred" name="s3_key"
                                                   value="{{ $storageConnectionS3Credential['key'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border border-D0DBE966 radius-10 p-4">
                                <div class="row align-items-center">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <label class="form-label h4 text-capitalize m-0" for="key-secret-cred">
                                            {{ translate('secret_credential') }}
                                        </label>
                                    </div>
                                    <div class="col-xl-9 col-lg-8 col-sm-6">
                                        <div >
                                            <input type="text" class="form-control" placeholder="{{translate('Enter your secret credential')}}" id="key-secret-cred" name="s3_secret"
                                                   value="{{ $storageConnectionS3Credential['secret'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border border-D0DBE966 radius-10 p-4">
                                <div class="row align-items-center">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <label class="form-label h4 text-capitalize m-0" for="key-region">{{translate('region_credential')}}</label>
                                    </div>
                                    <div class="col-xl-9 col-lg-8 col-sm-6">
                                        <div >
                                            <input type="text" class="form-control" placeholder="{{translate('enter_your_region_credential')}}" id="key-region" name="s3_region"
                                                   value="{{ $storageConnectionS3Credential['region'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border border-D0DBE966 radius-10 p-4">
                                <div class="row align-items-center">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <label class="form-label h4 text-capitalize m-0" for="key-bucket">
                                            {{ translate('bucket_credential') }}
                                        </label>
                                    </div>
                                    <div class="col-xl-9 col-lg-8 col-sm-6">
                                        <div >
                                            <input type="text" class="form-control" placeholder="{{translate('Enter your bucket credential')}}" id="key-bucket" name="s3_bucket"
                                                   value="{{ $storageConnectionS3Credential['bucket'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border border-D0DBE966 radius-10 p-4">
                                <div class="row align-items-center">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <label class="form-label h4 m-0" for="key-url">
                                            {{ translate('Url_Credential') }}
                                        </label>
                                    </div>
                                    <div class="col-xl-9 col-lg-8 col-sm-6">
                                        <div >
                                            <input type="text" class="form-control" placeholder="{{translate('enter_your_url')}}" id="key-url" name="s3_url"
                                                   value="{{ $storageConnectionS3Credential['url'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border border-D0DBE966 radius-10 p-4">
                                <div class="row align-items-center">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <label class="form-label h4 m-0" for="key-endpoint">
                                            {{ translate('Endpoint_Credential') }}
                                        </label>
                                    </div>
                                    <div class="col-xl-9 col-lg-8 col-sm-6">
                                        <div>
                                            <input type="text" class="form-control"
                                                   placeholder="{{translate('enter_your_endpoint_credential')}}"
                                                   id="key-endpoint" name="s3_endpoint"
                                                   value="{{ $storageConnectionS3Credential['endpoint'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-5 {{env('APP_MODE')!='demo'? '' : 'call-demo'}}">{{translate('save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
