@extends('layouts.back-end.app')

@section('title', translate('download_app'))
@section('content')
    <div class="content container-fluid">
        @include('admin-views.business-settings.vendor-registration-setting.partial.inline-menu')

        <form action="{{route('admin.business-settings.vendor-registration-settings.download-app')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize">{{translate('download_app_section')}}</h5>
                </div>
                <div class="card-body">
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color">{{translate('title')}}</label>
                                        <input type="text" name="title" class="form-control" value="{{$downloadVendorApp?->title}}" placeholder="{{translate('enter_title')}}">
                                    </div>

                                    <div class="form-group">
                                        <label class="title-color text-capitalize">{{translate('sub_title')}}</label>
                                        <input type="text" name="sub_title" class="form-control" value="{{$downloadVendorApp?->sub_title}}" placeholder="{{translate('enter_title')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mx-auto max-w-150">
                                        <div class="mb-3 text-center">
                                            <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{translate('image')}}</label>
                                            <span class="badge badge-soft-info">{{'('.translate('size').' 1:1'.')'}}</span>
                                        </div>

                                        <div class="custom_upload_input">
                                            <input type="file" name="image"
                                                class="image-input" data-image-id="view-bp-logo"
                                                accept="image/*">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn d--none">
                                                <i class="tio-delete"></i>
                                            </span>
                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="view-bp-logo"
                                                     src="{{getValidImage(path:'storage/app/public/vendor-registration-setting/'.$downloadVendorApp?->image ,type:'backend-basic')}}" class="bg-white {{$downloadVendorApp?->image ? '':'h-auto'}}" alt="">
                                            </div>
                                            <div
                                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                    <img alt="" class="w-50"
                                                        src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                                    <h5 class="text-muted text-capitalize">{{ translate('upload_image') }}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-muted text-center fz-12 mt-2">
                                            {{ translate('image_format').' : '.'Jpg, png, jpeg, webp,'}}
                                            <br>
                                            {{ translate('image_size').' : '.translate('max').' ' .'2 MB'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-lg-6">
                                    <div class="d-flex gap-2 align-items-center text-capitalize mb-3 font-weight-bold text-capitalize">
                                        <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/play_store.png')}}" alt="">
                                        {{translate('play_store_button')}}
                                    </div>

                                    <div class="bg-aliceblue p-3 rounded">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <span class="title-color text-capitalize">
                                                {{translate('download_link')}}
                                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                      data-placement="right"
                                                      title="{{translate('if_enabled_the_Google_Play_Store_will_be_visible_in_the_website_footer_section')}}">
                                                    <img width="16"
                                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                                </span>
                                            </span>

                                            <label class="switcher">
                                                <input type="checkbox" name="download_google_app_status" value="1"  class="switcher_input" {{isset($downloadVendorApp?->download_google_app_status) && $downloadVendorApp?->download_google_app_status == 1  ? 'checked' : ''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                        <input type="url" name="download_google_app" class="form-control"
                                               value="{{ $downloadVendorApp?->download_google_app}}"
                                               placeholder="{{translate('Ex: https://play.google.com/store/apps')}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex gap-2 align-items-center text-capitalize mb-3 font-weight-bold text-capitalize">
                                        <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/apple.png')}}" alt="">
                                        {{translate('app_store_button')}}
                                    </div>

                                    <div class="bg-aliceblue p-3 rounded">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <span class="title-color text-capitalize">
                                                {{translate('download_link')}}
                                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                      data-placement="right"
                                                      title="{{translate('if_enabled_the_download_button_from_the_App_Store_will_be_visible_in_the_Footer_section')}}">
                                                    <img width="16"
                                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                                </span>
                                            </span>

                                            <label class="switcher">
                                                <input type="checkbox" name="download_apple_app_status" value="1"  class="switcher_input" {{isset($downloadVendorApp?->download_apple_app_status) && $downloadVendorApp?->download_apple_app_status == 1  ? 'checked' : ''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>
                                        <input type="url" name="download_apple_app" class="form-control"
                                               value="{{ $downloadVendorApp?->download_apple_app }}"
                                               placeholder="{{translate('ex').':'.'https://www.apple.com/app-store/'}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end gap-3 mt-3 mx-1">
                        <button type="reset" class="btn btn-secondary px-5">{{('reset')}}</button>
                        <button type="submit" class="btn btn--primary px-5">{{('submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
