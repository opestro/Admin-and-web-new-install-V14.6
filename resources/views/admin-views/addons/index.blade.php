@extends('layouts.back-end.app')

@section('title', translate('system_Addons'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
          href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/system-setup.png')}}" alt="">
                {{translate('system_setup')}}
            </h2>

            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder text-capitalize">
                {{ translate('how_the_setting_works') }}
                <div class="ripple-animation" data-toggle="modal" data-target="#settingModal">
                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/info.svg')}}" class="svg" alt="">
                </div>
            </div>
            <div class="modal fade" id="settingModal" tabindex="-1" aria-labelledby="settingModal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                            <button
                                type="button"
                                class="btn-close border-0"
                                data-dismiss="modal"
                                aria-label="Close"
                            ><i class="tio-clear"></i></button>
                        </div>
                        <div class="modal-body px-4 px-sm-5 pt-0 text-center">
                            <div class="row g-2 g-sm-3 mt-lg-0">
                                <div class="col-12">
                                    <div class="swiper mySwiper pb-3">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <img
                                                    src="{{dynamicAsset(path: 'public/assets/back-end/img/slider-1.png')}}"
                                                    loading="lazy"
                                                    alt="" class="dark-support rounded">
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="d-flex flex-column align-items-center mx-w450 mx-auto">
                                                    <img
                                                        src="{{dynamicAsset(path: 'public/assets/back-end/img/slider-2.png')}}"
                                                        loading="lazy"
                                                        alt="" class="dark-support rounded mb-4">
                                                    <p>
                                                        {{ translate('get_your_zip_file_from_the_purchased_addons_and_upload_it_and_activate_theme_with_your_Codecanyon_username_and_purchase_code').'.' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="d-flex flex-column align-items-center mx-w450 mx-auto">
                                                    <img
                                                        src="{{dynamicAsset(path: 'public/assets/back-end/img/slider-3.png')}}"
                                                        loading="lazy"
                                                        alt="" class="dark-support rounded mb-4">
                                                    <p>
                                                        {{ translate('now_you’ll_be_successfully_able_to_use_the_addons_for_your_6Valley_website') }}
                                                    </p>
                                                    <p>
                                                        {{ translate('N:B you_can_upload_only_6Valley’s_theme_templates').'.' }}
                                                    </p>
                                                    <button class="btn btn-primary px-10 mt-3 text-capitalize"
                                                            data-dismiss="modal">{{ translate('got_it') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin-views.business-settings.theme-and-addon-menu')
        <div class="card mb-5">
            <div class="card-body pl-md-10">
                <h4 class="mb-3 text-capitalize d-flex align-items-center">{{translate('upload_Addons')}}</h4>
                <form enctype="multipart/form-data" id="addon-upload-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-5 col-xl-4 col-xxl-3">
                            <div class="uploadDnD">
                                <div class="form-group inputDnD input_image input_image_edit"
                                     data-title="{{translate('drag_&_drop_file_or_browse_file')}}">
                                    <input type="file" name="file_upload"
                                           class="form-control-file text--primary font-weight-bold image-input"
                                           id="input-file" accept=".zip">
                                </div>
                            </div>
                            <div class="mt-5 card px-3 py-2 d--none" id="progress-bar">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="">
                                        <img width="24"
                                             src="{{dynamicAsset(path: 'public/assets/back-end/img/zip.png')}}" alt="">
                                    </div>
                                    <div class="flex-grow-1 text-start">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                            <span id="name_of_file" class="text-truncate fz-12"></span>
                                            <span class="text-muted fz-12"
                                                  id="progress-label">{{translate('0').'%'}}</span>
                                        </div>
                                        <progress id="uploadProgress" class="w-100" value="0" max="100"></progress>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php($conditionOne=str_replace('MB','',ini_get('upload_max_filesize'))>=20 && str_replace('MB','',ini_get('upload_max_filesize'))>=20)
                        @php($conditionTwo=str_replace('MB','',ini_get('post_max_size'))>=20 && str_replace('MB','',ini_get('post_max_size'))>=20)

                        <div class="col-sm-6 col-lg-5 col-xl-4 col-xxl-9">
                            <div class="pl-sm-5">
                                <h5 class="mb-3 d-flex">{{ translate('instructions') }}</h5>
                                <ul class="pl-3 d-flex flex-column gap-2 instructions-list">
                                    <li>
                                        {{ translate('please_make_sure').','.translate('your_server_php').'"'.translate('upload_max_filesize').'"'.translate('value_is_grater_or_equal_to_20MB').'.'.translate('current_value_is').'-'.ini_get('upload_max_filesize').'B' }}
                                    </li>
                                    <li>
                                        {{ translate('please_make_sure').','.translate('your_server_php').'"'.translate('post_max_size').'"'.translate('value_is_grater_or_equal_to_20MB').'.'.translate('current_value_is') .'-'.ini_get('post_max_size').'B'}}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if($conditionOne && $conditionTwo)
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            class="btn btn--primary px-4 {{env('APP_MODE')!='demo'?'':'call-demo'}}"
                                            id="upload-theme">{{translate('upload')}}</button>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="row" id="update-error-message">
                                    <div class="col-12">
                                        <div class="alert alert-soft-{{($conditionOne)?'danger':'danger'}}"
                                             role="alert">
                                            {{'1.'.' '.translate('please_make_sure').' '.','.' '.translate('your_server_php').' '.','.' '.'"'.translate('upload_max_filesize').'"'.' '.translate('value_is_greater_or_equal_to').' '.'20M'.'.'.translate('current_value_is').'-'.ini_get('upload_max_filesize')}}
                                        </div>
                                        <div class="alert alert-soft-{{($conditionTwo)?'danger':'danger'}}"
                                             role="alert">
                                            {{'2.'.' '.translate('please_make_sure').' '.','.' '.translate('your_server_php').' '.','.' '.'"'.translate('post_max_size').'"'.' '.translate('value_is_greater_or_equal_to').' '.'20M'.'.'.translate('current_value_is').'-'.ini_get('post_max_size')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn--primary px-5"
                                            id="update-button-message">{{translate('upload')}}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <div class="row g-1 g-sm-2">
            @foreach($addons as $key => $addon)
                @php($data = include $addon.'/Addon/info.php')
                <div class="col-6 col-md-4 col-xxl-4">
                    <div class="card theme-card {{ theme_root_path() == $key ? 'theme-active':'' }}">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ ucwords(str_replace('_', ' ', $data['name'])) }}
                            </h3>
                            <div class="d-flex gap-2 gap-sm-3 align-items-center">
                                @if ($data['is_published'] == 0)
                                    <button class="text-danger bg-transparent p-0 border-0" data-toggle="modal"
                                            data-target="#deleteThemeModal_{{ $key }}"><img
                                            src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/delete.svg')}}"
                                            class="svg"
                                            alt="">
                                    </button>
                                    <div class="modal fade" id="deleteThemeModal_{{ $key }}" tabindex="-1"
                                         aria-labelledby="deleteThemeModal_{{ $key }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                                                    <button
                                                        type="button"
                                                        class="btn-close border-0"
                                                        data-dismiss="modal"
                                                        aria-label="Close"
                                                    ><i class="tio-clear"></i></button>
                                                </div>
                                                <div class="modal-body px-4 px-sm-5 text-center">
                                                    <div class="mb-3 text-center">
                                                        <img width="75"
                                                             src="{{dynamicAsset(path: 'public/assets/back-end/img/delete.png')}}"
                                                             alt="">
                                                    </div>
                                                    <h3>{{ translate('are_you_sure_you_want_to_delete_the') }} {{ $data['name'] }}
                                                        ?</h3>
                                                    <p class="mb-5">{{ translate('once_you_delete') .','. translate('you_will_lost_the_this') .' '.$data['name'] }}</p>

                                                    <div class="d-flex justify-content-center gap-3 mb-3">
                                                        <button type="button" class="fs-16 btn btn-secondary px-sm-5"
                                                                data-dismiss="modal">{{ translate('cancel') }}</button>
                                                        <button type="submit" class="fs-16 btn btn-danger px-sm-5"
                                                                data-dismiss="modal"
                                                                data-path="{{$addon}}"
                                                                id="theme-delete">{{ translate('delete') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <button class="text-muted bg-transparent p-0 border-0" data-toggle="modal"
                                        data-target="#shiftThemeModal_{{ $key }}"><img
                                        src="{{dynamicAsset(path: 'public/assets/back-end/img/icons/check.svg')}}"
                                        class="svg {{ $data['is_published'] == 1 ? 'text--primary' : '' }}"
                                        alt="">
                                </button>
                                <div class="modal fade" id="shiftThemeModal_{{ $key }}" tabindex="-1"
                                     aria-labelledby="shiftThemeModalLabel_{{ $key }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                                                <button
                                                    type="button"
                                                    class="btn-close border-0"
                                                    data-dismiss="modal"
                                                    aria-label="Close"
                                                ><i class="tio-clear"></i></button>
                                            </div>
                                            <div class="modal-body px-4 px-sm-5 text-center">
                                                <div class="mb-3 text-center">
                                                    <img width="75"
                                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/shift.png')}}"
                                                         alt="">
                                                </div>
                                                <h3>{{ translate('are_you_sure').'?'}}</h3>
                                                @if ($data['is_published'])
                                                    <p class="mb-5">{{ translate('want_to_inactive_this') .' '. $data['name'] }}</p>
                                                @else
                                                    <p class="mb-5">{{ translate('want_to_activate_this') .' '. $data['name'] }}</p>
                                                @endif
                                                <div class="d-flex justify-content-center gap-3 mb-3">
                                                    <button type="button" class="fs-16 btn btn-secondary px-sm-5"
                                                            data-dismiss="modal">{{ translate('no') }}
                                                    </button>
                                                    <button type="button" class="fs-16 btn btn--primary px-sm-5"
                                                            data-dismiss="modal"
                                                            data-path="{{ $addon }}"
                                                            id="publish-addon">{{ translate('yes') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-2 p-sm-3">
                            <div class="aspect-ration-3:2 border border-color-primary-light radius-10">
                                <img class="img-fit radius-10" alt=""
                                     src="{{ getValidImage(path: $addon.'/public/addon.png', type: 'backend-basic') }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="modal fade" id="activatedThemeModal" tabindex="-1" role="dialog"
                 aria-labelledby="activatedThemeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" id="activateData">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-addon-upload-route" data-action="{{route('admin.addon.upload')}}"></span>
    <span id="get-addon-publish-route" data-action="{{route('admin.addon.publish')}}"></span>
    <span id="get-addon-delete-route" data-action="{{route('admin.addon.delete')}}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/addon.js')}}"></script>
@endpush
