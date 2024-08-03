@extends('layouts.back-end.app')

@section('title', translate('business_Process'))
@section('content')
    <div class="content container-fluid">
        @include('admin-views.business-settings.vendor-registration-setting.partial.inline-menu')

        <form action="{{route('admin.business-settings.vendor-registration-settings.business-process')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize">{{translate('business_process')}}</h5>
                </div>
                <div class="card-body">
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title-color">{{translate('title')}}</label>
                                        <input type="text" name="title" class="form-control" value="{{$businessProcess?->title}}"  placeholder="{{translate('enter_title')}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title-color">{{translate('sub_title')}}</label>
                                        <input type="text" name="sub_title" class="form-control" value="{{$businessProcess?->sub_title}}" placeholder="{{translate('enter_sub_title')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @for($index = 1 ;$index <=3 ;$index++)
                    <div class="card border shadow-none mb-2">
                        <div class="card-body">
                            <h5 class="mb-4">{{translate('section').' '.$index}}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color">{{translate('title')}}</label>
                                        <input type="text" name="section_{{$index}}_title" class="form-control" value="{{isset($businessProcessStep[$index-1]) ? $businessProcessStep[$index-1]->title : null}}" placeholder="{{translate('enter_title')}}">
                                    </div>

                                    <div class="form-group">
                                        <label class="title-color text-capitalize">{{translate('short_description')}}</label>
                                        <textarea name="section_{{$index}}_description" class="form-control" rows="4" placeholder="{{translate('write_description').'...'}}">{{isset($businessProcessStep[$index-1]) ? $businessProcessStep[$index-1]->description : null}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mx-auto max-w-150">
                                        <div class="mb-3 text-center">
                                            <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{translate('image')}}</label>
                                            <span class="badge badge-soft-info">{{'('.translate('size').': 1:1'.')'}}</span>
                                        </div>
                                        <div class="custom_upload_input">
                                            <input type="file" name="section_{{$index}}_image"
                                                class="image-input" data-image-id="view-bp-logo-{{$index}}"
                                                accept="image/*">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn d--none">
                                                <i class="tio-delete"></i>
                                            </span>
                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="view-bp-logo-{{$index}}"
                                                     src="{{getValidImage(path:'storage/app/public/vendor-registration-setting/'.(isset($businessProcessStep[$index-1])? $businessProcessStep[$index-1]->image : ''),type:'backend-basic')}}" class="bg-white {{isset($businessProcessStep[$index-1]) &&  $businessProcessStep[$index-1]->image ? '':'h-auto'}}" alt="">
                                            </div>
                                            <div
                                                class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                    <img alt="" class="w-50"
                                                        src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                                    <h5 class="text-muted">{{ translate('Upload_Image') }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted text-center fz-12 mt-2">
                                            {{ translate('image_format').' : Jpg, png, jpeg, webp,'}}
                                            <br>
                                            {{ translate('image_size').' : '.translate('max'). '2MB' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                    <div class="row justify-content-end gap-3 mt-3 mx-1">
                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn--primary px-5">{{translate('submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
