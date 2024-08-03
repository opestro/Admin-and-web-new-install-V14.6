@extends('layouts.back-end.app')

@section('title', translate('header'))
@section('content')
    <div class="content container-fluid">
        @include('admin-views.business-settings.vendor-registration-setting.partial.inline-menu')
        <form action="{{route('admin.business-settings.vendor-registration-settings.index')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize">{{translate('header_section')}}</h5>
                </div>
                <div class="card-body">
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title-color">{{translate('title')}}</label>
                                        <input type="text" name="title" class="form-control" value="{{$vendorRegistrationHeader?->title}}" placeholder="{{translate('enter_title')}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize">{{translate('sub_title')}}</label>
                                        <input type="text" name="sub_title" class="form-control" value="{{$vendorRegistrationHeader?->sub_title}}" placeholder="{{translate('enter_sub_title')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border shadow-none">
                        <div class="card-body">
                            <div class="mx-auto max-w-400">
                                <div class="mb-3 text-center">
                                    <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{translate('image')}}</label>
                                    <span class="badge badge-soft-info">{{'('.translate('size').' : '.'310px x 240px'.')'}}</span>
                                </div>

                                <div class="custom_upload_input">
                                    <input type="file" name="image"
                                        class="image-input meta-img" data-image-id="view-header-logo"
                                        accept="image/*">
                                    <span class="delete_file_input btn btn-outline-danger btn-sm square-btn d--none">
                                        <i class="tio-delete"></i>
                                    </span>
                                    <div class="img_area_with_preview position-absolute z-index-2">
                                        <img id="view-header-logo" src="{{ getValidImage(path:'storage/app/public/vendor-registration-setting/'.$vendorRegistrationHeader?->image,type: 'backend-banner') }}" class="bg-white {{empty($vendorRegistrationHeader->image) ? '':'h-auto'}}" alt="">
                                    </div>
                                    <div
                                        class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                        <div
                                            class="d-flex flex-column justify-content-center align-items-center">
                                            <img alt="" class="w-75"
                                                src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                            <h3 class="text-muted text-capitalize">{{ translate('upload_image') }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted text-center mt-2">
                                    {{ translate('image_format').' : '.'Jpg, png, jpeg, webp,'}}
                                    <br>
                                    {{ translate('image_size').' : '.translate('max').' ' .'2 MB'}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end gap-3 mt-3 mx-1">
                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn--primary px-5">{{translate('submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
