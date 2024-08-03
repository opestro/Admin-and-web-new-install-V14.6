@extends('layouts.back-end.app')

@section('title', translate('profile_Settings'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <h2 class="col-sm mb-2 mb-sm-0 h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/profile_setting.png')}}" alt="">
                    {{translate('Profile_Information')}}
                </h2>
                <div class="col-sm-auto">
                    <a class="btn btn--primary" href="{{route('admin.dashboard.index')}}">
                        <i class="tio-home mr-1"></i> {{translate('dashboard')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="navbar-vertical navbar-expand-lg mb-3 mb-lg-5">
                    <button type="button" class="navbar-toggler btn btn-block btn-white mb-3"
                            aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu"
                            data-toggle="collapse" data-target="#navbarVerticalNavMenu">
                        <span class="d-flex justify-content-between align-items-center">
                              <span class="h5 mb-0">{{translate('nav_menu')}}</span>
                              <span class="navbar-toggle-default">
                                <i class="tio-menu-hamburger"></i>
                              </span>
                              <span class="navbar-toggle-toggled">
                                <i class="tio-clear"></i>
                              </span>
                        </span>
                    </button>
                    <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                        <ul id="navbarSettings"
                            class="js-sticky-block js-scrollspy navbar-nav navbar-nav-lg nav-tabs card card-navbar-nav p-3">
                            <li class="nav-item">
                                <a class="nav-link active d-flex align-items-center gap-2 m-0 py-3" href="javascript:" id="general-section">
                                    <div class="min-w-30px">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/user-config.svg') }}" alt="">
                                    </div>
                                    <div>{{translate('basic_Information')}}</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 m-0 py-3" href="javascript:" id="password-section">
                                    <div class="min-w-30px">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/password-lock-light.svg') }}" alt="">
                                    </div>
                                    <div>{{translate('password')}}</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <form action="{{route('admin.profile.update',[$admin->id])}}" method="post" enctype="multipart/form-data" id="admin-profile-form">
                @csrf
                    <div class="card mb-3 mb-lg-5" id="general-div">
                        <div class="profile-cover">
                            @php($banner = dynamicAsset(path: 'public/assets/back-end/img/media/admin-profile-bg.png'))
                            <div class="profile-cover-img-wrapper profile-bg" style="background-image: url({{ $banner }})"></div>
                        </div>
                        <div
                            class="avatar avatar-xxl avatar-circle avatar-border-lg avatar-uploader profile-cover-avatar"
                        >
                            <img id="viewer"    class="avatar-img"
                                 src="{{getValidImage(path:'storage/app/public/admin/'.$admin->image, type:'backend-profile')}}"
                                 alt="{{translate('image')}}">
                            <label class="change-profile-image-icon" for="custom-file-upload">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-photo.png') }}" alt="">
                            </label>
                        </div>

                        <div class="card-header">
                            <div class="d-flex align-items-center gap-3">
                                <div><img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/user-1.svg') }}" alt=""></div>
                                <h4 class="card-title m-0 fs-16">{{translate('basic_Information')}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="firstNameLabel" class="input-label mb-0">
                                            {{translate('full_name')}}
                                            <span class="text-danger px-1">*</span>
                                        </label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ translate('this_will_be_displayed_as_your_profile_name') }}">
                                            <img alt="" width="16" src={{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }} alt="">
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <div class="input-group input-group-sm-down-break">
                                            <input type="text" class="form-control" name="name" id="firstNameLabel"
                                                   placeholder="{{ translate('ex') }}: {{ translate('ABC') }}" aria-label=" {{ translate('ABC') }}"
                                                   value="{{ $admin->name }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="phoneLabel" class="input-label mb-0">
                                            {{translate('phone_Number')}}
                                            <span class="input-label-secondary">
                                                ({{translate('optional')}})
                                            </span>
                                        </label>
                                    </div>

                                    <div class="mb-3">
                                        <input class="form-control form-control-user phone-input-with-country-picker"
                                               type="tel" id="exampleInputPhone" value="{{$admin->phone ?? old('phone')}}"
                                               placeholder="{{ translate('ex') }}: {{ translate('123456789') }}" required>
                                        <input type="hidden" class="country-picker-phone-number w-50" value="{{$admin->phone}}" name="phone" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="newEmailLabel" class="input-label mb-0">
                                            {{translate('email')}}
                                            <span class="text-danger px-1">*</span>
                                        </label>

                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ translate('you_can_login_to_your_panel_by_using_this_email') }}">
                                            <img alt="" width="16" src={{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }} alt="">
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" name="email" id="newEmailLabel"
                                               value="{{$admin->email}}"
                                               placeholder="{{ translate('ex') }}: {{ 'admin@admin.com' }}">
                                    </div>
                                </div>

                            </div>

                            <div class="d-none" id="select-img">
                                <input type="file" name="image" id="custom-file-upload" data-image-id="viewer" class="custom-file-input  image-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" data-id="admin-profile-form" data-message="{{translate('want_to_update_admin_info').'?'}}" class="btn btn--primary {{env('APP_MODE')!='demo'?'form-alert':'call-demo'}}">{{translate('save_Changes')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="password-div" class="card mb-3 mb-lg-5">
                    <div class="card-header">
                        <div class="d-flex align-items-center gap-3">
                            <div><img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/password-lock.svg') }}" alt=""></div>
                            <h4 class="card-title m-0 fs-16">{{translate('change_Password')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="change-password-form" action="{{route('admin.profile.update',[$admin->id])}}" method="post" enctype="multipart/form-data">
                        @csrf @method('patch')
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="newPassword" class="input-label mb-0">
                                            {{translate('new_password')}}
                                            <span class="text-danger px-1">*</span>
                                        </label>

                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="" data-original-title="{{translate('The_password_must_be_at_least_8_characters_long_and_contain_at_least_one_uppercase_letter').','.translate('_one_lowercase_letter').','.translate('_one_digit_').','.translate('_one_special_character').','.translate('_and_no_spaces').'.'}}">
                                            <img alt="" width="16" src={{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }} alt="" class="m-1">
                                        </span>
                                    </div>
                                    <div class="">
                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control password-check" id="newPassword"
                                                   autocomplete="off" name="password" required minlength="8" placeholder="{{ translate('enter_new_password') }}"
                                                   data-hs-toggle-password-options='{
                                                         "target": "#changePassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changePassIcon"
                                                }'>
                                            <div id="changePassTarget" class="input-group-append">
                                                <a class="input-group-text" href="javascript:">
                                                    <i id="changePassIcon" class="tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <span class="text-danger pt-1 d-block password-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="confirmNewPasswordLabel" class="input-label mb-1">
                                            {{translate('confirm_password')}}
                                            <span class="text-danger px-1">*</span>
                                        </label>
                                    </div>

                                    <div class="">
                                        <div class="mb-3">
                                            <div class="input-group input-group-merge">
                                                <input type="password" class="js-toggle-password form-control"
                                                       name="confirm_password" required id="confirmNewPasswordLabel"
                                                       placeholder="{{ translate('enter_confirm_password') }}"
                                                       autocomplete="off"
                                                       data-hs-toggle-password-options='{
                                                         "target": "#changeConfirmPassTarget",
                                                        "defaultClass": "tio-hidden-outlined",
                                                        "showClass": "tio-visible-outlined",
                                                        "classChangeTarget": "#changeConfirmPassIcon"
                                                }'>
                                                <div id="changeConfirmPassTarget" class="input-group-append">
                                                    <a class="input-group-text" href="javascript:">
                                                        <i id="changeConfirmPassIcon" class="tio-visible-outlined"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" data-id="change-password-form" data-message="{{translate('want_to_update_admin_password').'?'}}" class="btn btn--primary {{env('APP_MODE')!='demo'?'form-alert':'call-demo'}}">{{translate('save_Changes')}}</button>
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
