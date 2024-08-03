@extends('layouts.back-end.app-seller')

@section('title', translate('shop_Edit'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush
@section('content')
    <div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/shop-info.png')}}" alt="">
            {{translate('edit_shop_info')}}
        </h2>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize">{{translate('edit_shop_info')}}</h5>
                    <a href="{{route('vendor.shop.index')}}" class="btn btn--primary __inline-70 px-4 text-white">{{ translate('back') }}</a>
                </div>
                <div class="card-body">
                    <form action="{{route('vendor.shop.update',[$shop->id])}}" method="post" class="text-start"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('shop_name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{$shop->name}}" class="form-control" id="name"
                                            required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('contact')}}</label>
                                    <div class="mb-3">
                                        <input class="form-control form-control-user phone-input-with-country-picker"
                                               type="tel" id="exampleInputPhone" value="{{$shop->contact ?? old('phone')}}"
                                               placeholder="{{ translate('enter_phone_number') }}" required>
                                        <div class="">
                                            <input type="text" class="country-picker-phone-number w-50" value="{{$shop->contact}}" name="contact" hidden  readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="title-color">{{translate('address')}} <span class="text-danger">*</span></label>
                                    <textarea type="text" rows="4" name="address" class="form-control" id="address"
                                            required>{{$shop->address}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('upload_image')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="custom-file-upload" class="custom-file-input image-input"
                                               data-image-id="viewer"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label text-capitalize" for="custom-file-upload">{{translate('choose_file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                    src="{{getValidImage(path: 'storage/app/public/shop/'.$shop->image,type: 'backend-basic')}}" alt="{{translate('image')}}"/>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <label for="name" class="title-color text-capitalize">{{translate('upload_banner')}} </label>
                                        <div class="mx-1">
                                            <span class="text-info">{{ THEME_RATIO[theme_root_path()]['Store cover Image'] }}</span>
                                        </div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="banner" id="banner-upload" class="custom-file-input image-input"
                                               data-image-id="viewer-banner"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label text-capitalize" for="banner-upload">{{translate('choose_file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <img class="upload-img-view upload-img-view__banner" id="viewer-banner"
                                             src="{{getValidImage(path: 'storage/app/public/shop/banner/'.$shop->banner,type: 'backend-banner')}}" alt="{{translate('banner_image')}}"/>
                                    </div>
                                </div>
                            </div>
                            @if(theme_root_path() == "theme_aster")
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <label for="name" class="title-color text-capitalize">{{translate('upload_secondary_banner')}}</label>
                                        <div class="mx-1">
                                            <span class="text-info">{{translate('ratio').' '.'( 6:1 )'}}</span>
                                        </div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="bottom_banner" id="bottom-banner-upload" class="custom-file-input image-input"
                                               data-image-id="viewer-bottom-banner"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="bottom-banner-upload">{{translate('choose_file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <img class="upload-img-view upload-img-view__banner" id="viewer-bottom-banner" src="{{getValidImage(path: 'storage/app/public/shop/banner/'.$shop->bottom_banner, type: 'backend-banner')}}" alt="{{translate('banner_image')}}"/>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(theme_root_path() == "theme_fashion")
                                <div class="col-md-6 mb-4 mt-2">
                                    <div class="form-group">
                                        <div class="flex-start">
                                            <label for="name" class="title-color text-capitalize">{{translate('upload_offer_banner')}}</label>
                                            <div class="mx-1">
                                                <span class="text-info">{{translate('ratio').' '.'( 7:1 )'}}</span>
                                            </div>
                                        </div>
                                        <div class="custom-file text-left">
                                            <input type="file" name="offer_banner" id="offer-banner-upload" class="custom-file-input image-input"
                                                data-image-id="viewer-offer-banner"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label text-capitalize" for="offer-banner-upload">{{translate('choose_file')}}</label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="d-flex">
                                            <img class="upload-img-view upload-img-view__banner" id="viewer-offer-banner"
                                                src="{{getValidImage(path: 'storage/app/public/shop/banner/'.$shop->offer_banner,type: 'backend-banner')}}" alt="{{translate('banner_image')}}"/>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-danger" href="{{route('vendor.shop.index')}}">{{translate('cancel')}}</a>
                            <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
@endpush
