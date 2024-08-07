@extends('layouts.back-end.app')

@section('title', translate('app_settings'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('system_setup')}}
            </h2>
            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder text-capitalize">
                {{translate('read_instructions')}}
                <div class="ripple-animation" data-toggle="modal" data-target="#readInstructionsModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                         class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                              fill="currentColor"></path>
                    </svg>
                </div>
            </div>
        </div>
        @include('admin-views.business-settings.system-settings-inline-menu')
        <div class="d-flex gap-2 mb-3">
            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/settings.png')}}" alt="">
            <h5 class="mb-0">{{translate('user_app_version_control')}}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.web-config.app-settings') }}" method="post">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/android.png')}}" alt="">
                                <h5 class="mb-0 text-capitalize">{{translate('for_android')}}</h5>
                            </div>
                            <input type="hidden" name="type" value="user_app_version_control">
                            <div class="bg-light p-3 rounded">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0 text-capitalize">{{translate('minimum_customer_app_version')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('define_the_minimum_Android_app_version_for_best_user_experience').'.'.translate('if_a_user_still_don’t_have_it,_they’ll_be_requested_a_force_app_update_when_they_opens_the_app').'.'}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="hidden" name="for_android[status]" value="1">
                                    <input type="text" class="form-control" name="for_android[version]"
                                           placeholder="{{translate('ex').':'.'2.1'}}" required
                                           value="{{ $userAppVersionControl['for_android']['version'] ?? '' }}">
                                </div>
                                <div class="">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0 text-capitalize">{{translate('download_URL_for_customer_app')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('add_the_Android_app_download_URL_that_will_redirect_users_when_they_agree_to_update_the_app').'.' }}">
                                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="url" class="form-control" name="for_android[link]"
                                           placeholder="{{translate('ex').':'.'https://play.google.com/store/apps'}}" required
                                           value="{{ $userAppVersionControl['for_android']['link'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/apple.png')}}" alt="">
                                <h5 class="mb-0">{{translate('for_iOS')}}</h5>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0 text-capitalize">{{translate('minimum_customer_app_version')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right"
                                              title="{{translate('define_the_minimum_iOS_app_version_for_best_user_experience').'.'. translate('if_a_user_still_don’t _have_it,_they’ll_be_requested_a_force_app_update_when_they_opens_the_app').'.' }}">
                                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="hidden" name="for_ios[status]" value="1">
                                    <input type="text" class="form-control" name="for_ios[version]"
                                           placeholder="{{translate('ex').':'.'2.1'}}" required
                                           value="{{ $userAppVersionControl['for_ios']['version'] ?? '' }}">
                                </div>

                                <div class="">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('download_URL_For_Customer_App')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('add_the_iOS_app_download_URL_that_will_redirect_users_when_they_agree_to_update_the_app').'.'}}">
                                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="url" class="form-control" name="for_ios[link]"
                                           placeholder="{{translate('ex').':'.'https://www.apple.com/app-store/'}}" required
                                           value="{{ $userAppVersionControl['for_ios']['link'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-5">{{translate('save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex gap-2 mb-3 mt-5">
            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/settings.png')}}" alt="">
            <h5 class="mb-0">{{translate('vendor_app_version_control')}}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.web-config.app-settings') }}" method="post">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/android.png')}}" alt="">
                                <h5 class="mb-0 text-capitalize">{{translate('for_android')}}</h5>
                            </div>

                            <input type="hidden" name="type" value="seller_app_version_control">

                            <div class="bg-light p-3 rounded">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('minimum_Vendor_app_version')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('define_the_minimum_Android_app_version_for_best_user_experience').'.'.translate('if_a_user_still_don’t_have_it,_they’ll_be_requested_a_force_app_update_when_they_opens_the_app').'.'}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="hidden" name="for_android[status]" value="1">
                                    <input type="text" class="form-control" name="for_android[version]"
                                           placeholder="{{translate('ex: 2.1')}}" required
                                           value="{{ $sellerAppVersionControl['for_android']['version'] ?? '' }}">
                                </div>

                                <div class="">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('download_URL_For_Vendor_App')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('add_the_Android_app_download_URL_that_will_redirect_users_when_they_agree_to_update_the_app').'.' }}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="url" class="form-control" name="for_android[link]"
                                           placeholder="{{translate('ex').'https://play.google.com/store/apps'}}"
                                           required
                                           value="{{ $sellerAppVersionControl['for_android']['link'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/apple.png')}}" alt="">
                                <h5 class="mb-0">{{translate('for_iOS')}}</h5>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('minimum_Vendor_app_version')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('define_the_minimum_iOS_app_version_for_best_user_experience').'.'. translate('if_a_user_still_don’t _have_it,_they’ll_be_requested_a_force_app_update_when_they_opens_the_app').'.' }}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="hidden" name="for_ios[status]" value="1">
                                    <input type="text" class="form-control" name="for_ios[version]"
                                           placeholder="{{translate('ex').':'.'2.1'}}" required
                                           value="{{ $sellerAppVersionControl['for_ios']['version'] ?? '' }}">
                                </div>

                                <div class="">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('download_URL_For_Vendor_App')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('add_the_iOS_app_download_URL_that_will_redirect_users_when_they_agree_to_update_the_app').'.'}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="url" class="form-control" name="for_ios[link]"
                                           placeholder="{{translate('ex').':'.' https://www.apple.com/app-store/'}}" required
                                           value="{{ $sellerAppVersionControl['for_ios']['link'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-5">{{translate('save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex gap-2 mb-3 mt-5">
            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/settings.png')}}" alt="">
            <h5 class="mb-0">{{translate('delivery_man_app_version_control')}}</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.web-config.app-settings') }}" method="post">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/android.png')}}" alt="">
                                <h5 class="mb-0 text-capitalize">{{translate('for_android')}}</h5>
                            </div>
                            <input type="hidden" name="type" value="delivery_man_app_version_control">
                            <div class="bg-light p-3 rounded">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('minimum_Deliveryman_App_Version')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('define_the_minimum_Android_app_version_for_best_user_experience').'.'.translate('if_a_user_still_don’t_have_it,_they’ll_be_requested_a_force_app_update_when_they_opens_the_app').'.'}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="hidden" name="for_android[status]" value="1">
                                    <input type="text" class="form-control" name="for_android[version]"
                                           placeholder="{{translate('ex').':'.'2.1'}}" required
                                           value="{{ $deliverymanAppVersionControl['for_android']['version'] ?? '' }}">
                                </div>
                                <div class="">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('download_URL_For_Deliveryman_App')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('add_the_Android_app_download_URL_that_will_redirect_users_when_they_agree_to_update_the_app').'.' }}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="url" class="form-control" name="for_android[link]"
                                           placeholder="{{translate('ex').':'.'https://play.google.com/store/apps'}}" required
                                           value="{{ $deliverymanAppVersionControl['for_android']['link'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/apple.png')}}" alt="">
                                <h5 class="mb-0">{{translate('for_iOS')}}</h5>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0 text-capitalize">{{translate('minimum_deliveryman_app_version')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right"
                                                  title="{{translate('define_the_minimum_iOS_app_version_for_best_user_experience').'.'. translate('if_a_user_still_don’t _have_it,_they’ll_be_requested_a_force_app_update_when_they_opens_the_app').'.'}}">
                                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="hidden" name="for_android[status]" value="1">
                                    <input type="text" class="form-control" name="for_ios[version]"
                                           placeholder="{{translate('ex').':'.'2.1'}}" required
                                           value="{{ $deliverymanAppVersionControl['for_ios']['version'] ?? '' }}">
                                </div>

                                <div class="">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label class="title-color mb-0">{{translate('download_URL_For_Deliveryman_App')}}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('add_the_iOS_app_download_URL_that_will_redirect_users_when_they_agree_to_update_the_app').'.'}}">
                                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <input type="url" class="form-control" name="for_ios[link]"
                                           placeholder="{{translate('ex').':'.'https://www.apple.com/app-store/'}}" required
                                           value="{{ $deliverymanAppVersionControl['for_ios']['link'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-5">{{translate('save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="readInstructionsModal" tabindex="-1" aria-labelledby="readInstructionsModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                                class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="swiper mySwiper pb-3">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/what_app_version.png')}}"
                                         loading="lazy" alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{ translate('what_is_app_version').'?' }}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{{ translate('this_app_version_means_the_minimum_version_of_Vendor_Deliveryman_and_Customer_apps_that_are_required_for_the_update') }}</li>
                                        <li>{{ translate('it_does_not_represent_the_Play_Store_or_App_Store_version') }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3"
                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/what_app_version.png')}}"
                                         loading="lazy" alt="">
                                    <h4 class="lh-md mb-3 text-capitalize">{{ translate('app_download_link') }}</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{{ translate('the_app_download_link_is_the_URL_that_allows_users_to_update_the_app_by_clicking_the_Update_App_button_within_the_app_itself') }} </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination mb-4"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>
@endpush
