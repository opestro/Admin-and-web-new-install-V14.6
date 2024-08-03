@extends('layouts.back-end.app')

@section('title', translate('edit').' - '.translate('all_Pages_Banner'))

@section('content')

    <div class="content container-fluid">

        <div class="pb-2 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('all_Pages_Banner')}}
            </h2>
        </div>

        <div class="row pb-4 text-start" id="main-banner">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0 text-capitalize">{{ translate('banner_form')}}</h5>
                        </div>
                        <div>
                            <a class="btn btn--primary text-white" href="{{ route('admin.business-settings.all-pages-banner') }}">
                                <i class="tio-chevron-left"></i> {{ translate('Back') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.all-pages-banner-update') }}" method="post" enctype="multipart/form-data"
                              class="banner_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id" name="id" value="{{ $banner->id }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="title-color text-capitalize">{{ translate('banner_type') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="type" required>

                                            @if (theme_root_path() == 'theme_fashion')
                                                <option value="banner_product_list_page" {{ $banner->type == "banner_product_list_page"?"selected":"" }}>{{ translate('Product_List_Page')}}</option>
                                            @endif

                                            <option value="banner_terms_conditions" {{ $banner->type == "banner_terms_conditions"?"selected":"" }}>{{ translate('Terms_and_Conditions')}}</option>
                                            <option value="banner_privacy_policy" {{ $banner->type == "banner_privacy_policy"?"selected":"" }}>{{ translate('Privacy_Policy')}}</option>
                                            <option value="banner_refund_policy" {{ $banner->type == "banner_refund_policy"?"selected":"" }}>{{ translate('Refund_Policy')}}</option>
                                            <option value="banner_return_policy" {{ $banner->type == "banner_return_policy"?"selected":"" }}>{{ translate('Return_Policy')}}</option>
                                            <option value="banner_cancellation_policy" {{ $banner->type == "banner_cancellation_policy"?"selected":"" }}>{{ translate('Cancellation_Policy')}}</option>
                                            <option value="banner_about_us" {{ $banner->type == "banner_about_us"?"selected":"" }}>{{ translate('About_us')}}</option>
                                            <option value="banner_faq_page" {{ $banner->type == "banner_faq_page"?"selected":"" }}>{{ translate('FAQ')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ translate('image')}}</label>
                                        <span class="text-info">( {{ translate('ratio')}} 6:1 )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="banner-image" data-preview="#banner-image-view"
                                                class="custom-file-input image-preview-before-upload"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label title-color"
                                                for="banner-image">{{ translate('choose')}} {{ translate('file')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-end">
                                    <div>
                                        <div class="mb-30 mx-auto ratio-6:1 overflow-hidden d-flex justify-content-center align-items-center">
                                            <img
                                                class="ratio-6:1" id="banner-image-view"
                                                src="{{ getValidImage(path:'storage/app/public/banner/'.json_decode($banner['value'])->image,type: 'backend-basic')}}"
                                                alt=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4" type="reset">{{ translate('reset')}}</button>
                                    <button id="update" type="submit" class="btn btn--primary text-white">{{ translate('update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/banner.js') }}"></script>
@endpush
