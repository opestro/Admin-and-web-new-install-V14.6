@extends('layouts.back-end.app')
@section('title', translate('analytics_script'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: '/public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        @include('admin-views.business-settings.third-party-inline-menu')
        <div class="row gy-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        @php($pixel_analytics = getWebConfig(name: 'pixel_analytics'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.analytics-update'):'javascript:'}}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('pixel_analytics_your_pixel_id')}}</label>
                                <input type="hidden" name="type" value="pixel_analytics">
                                <textarea type="text" placeholder="{{translate('pixel_analytics_your_pixel_id_from_facebook')}}" class="form-control" name="value" >{{env('APP_MODE')!='demo'?$pixel_analytics??'':''}}</textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-5 {{env('APP_MODE') !='demo'?'' : 'call-demo'}}">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        @php($google_tag_manager_id = getWebConfig(name: 'google_tag_manager_id'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.analytics-update'):'javascript:'}}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('google_tag_manager_id')}}</label>
                                <input type="hidden" name="type" value="google_tag_manager_id">
                                <textarea type="text" placeholder="{{translate('google_tag_manager_script_id_from_google')}}" class="form-control" name="value" >{{env('APP_MODE')!='demo'?$google_tag_manager_id??'':''}}</textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"  class="btn btn--primary px-5 {{env('APP_MODE')!='demo'?'':'call-demo'}}">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
