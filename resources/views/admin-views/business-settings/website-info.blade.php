@extends('layouts.back-end.app')

@section('title', translate('general_Settings'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business-setup.png')}}" alt="">
                {{ translate('business_Setup') }}
            </h2>
            <div class="btn-group">
                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                         class="svg replaced-svg">
                        <path
                            d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <div
                    class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/note.png')}}" alt="">
                        <h5 class="text-primary mb-0">{{translate('note')}}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('please_click_save_information_button_below_to_save_all_the_changes') }}</p>
                </div>
            </div>
        </div>
        @include('admin-views.business-settings.business-setup-inline-menu')
        <div class="alert alert-danger d-none mb-3" role="alert">
            {{translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.business-settings.maintenance-mode')}}" method="post" id="maintenance-mode-form" data-from="maintenance-mode">
                    @csrf
                    <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                        <h5 class="mb-0 d-flex gap-1 c1">
                            {{translate('maintenance_mode')}}
                        </h5>
                        <div class="position-relative">
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input toggle-switch-message" id="maintenance-mode" name="value"
                                   value="1" {{isset($businessSetting['maintenance_mode']) && $businessSetting['maintenance_mode']==1?'checked':''}}
                                   data-modal-id="toggle-status-modal"
                                   data-toggle-id="maintenance-mode"
                                   data-on-image="maintenance_mode-on.png"
                                   data-off-image="maintenance_mode-off.png"
                                   data-on-title="{{translate('Want_to_enable_the_Maintenance_Mode')}}"
                                   data-off-title="{{translate('Want_to_disable_the_Maintenance_Mode')}}"
                                   data-on-message="<p>{{translate('if_enabled_all_your_apps_and_customer_website_will_be_temporarily_off')}}</p>"
                                   data-off-message="<p>{{translate('if_disabled_all_your_apps_and_customer_website_will_be_functional')}}</p>">
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                    </div>
                </form>
                <p>{{'*'.translate('by_turning_the').', "'. translate('Maintenance_Mode').'"'.translate('ON').' '.translate('all_your_apps_and_customer_website_will_be_disabled_until_you_turn_this_mode_OFF').' '.translate('only_the_Admin_Panel_&_Vendor_Panel_will_be_functional')}}
                </p>
            </div>
        </div>
        <form action="{{ route('admin.business-settings.web-config.update') }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-1">
                        <i class="tio-user-big"></i>
                        {{translate('company_information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{translate('company_Name')}}</label>
                                <input class="form-control" type="text" name="company_name"
                                       value="{{ $businessSetting['company_name'] }}"
                                       placeholder="{{translate('new_business')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('phone')}}</label>
                                <input class="form-control" type="text" name="company_phone"
                                       value="{{ $businessSetting['company_phone'] }}"
                                       placeholder="{{translate('01xxxxxxxx')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{translate('email')}}</label>
                                <input class="form-control" type="text" name="company_email"
                                       value="{{ $businessSetting['company_email'] }}"
                                       placeholder="{{translate('company@gmail.com')}}">
                            </div>
                        </div>

                        @php($countryCode = getWebConfig(name: 'country_code'))
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('country')}} </label>
                                <select id="country" name="country_code" class="form-control js-select2-custom">
                                    @foreach(COUNTRIES as $country)
                                        <option value="{{$country['code']}}" {{ $countryCode?($countryCode==$country['code']?'selected':''):'' }} >
                                            {{$country['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @php($timeZone = getWebConfig(name: 'timezone'))
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('time_zone')}}</label>
                                <select name="timezone" class="form-control js-select2-custom">
                                    @foreach(App\Enums\GlobalConstant::TIMEZONE_ARRAY as $timeZoneArray)
                                        <option value="{{$timeZoneArray['value']}}" {{$timeZone?($timeZone==$timeZoneArray['value'] ? 'selected':''):''}}>
                                            {{$timeZoneArray['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex" for="language">{{translate('language')}}</label>
                                <select name="language" class="form-control js-select2-custom">
                                    @if (isset($businessSetting['language']))
                                        @foreach (json_decode($businessSetting['language']) as $item)
                                            <option
                                                value="{{ $item->code }}" {{ $item->default == 1?'selected':'' }}>{{ ucwords($item->name).' ('.ucwords($item->code).')' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('company_address')}}</label>
                                <input type="text" value="{{ $businessSetting['shop_address'] }}"
                                       name="shop_address" class="form-control" id="shop-address"
                                       placeholder="{{translate('your_shop_address')}}"
                                       required>
                            </div>
                        </div>
                        @php($default_location = getWebConfig(name: 'default_location'))
                        @if(getWebConfig('map_api_status') ==1 )
                            <div class="col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label class="title-color d-flex">
                                        {{translate('latitude')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('copy_the_latitude_of_your_business_location_from_Google_Maps_and_paste_it_here')}}">
                                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                                 alt="">
                                        </span>
                                    </label>
                                    <input class="form-control latitude disabled-input" type="text" name="latitude" id="latitude"
                                           value="{{ $default_location['lat']?? '-33.8688' }}"
                                           placeholder="{{translate('latitude')}}" readonly >

                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label class="title-color d-flex">
                                        {{translate('longitude')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('copy_the_longitude_of_your_business_location_from_Google_Maps_and_paste_it_here')}}">
                                            <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                                 alt="">
                                        </span>
                                    </label>
                                    <input class="form-control longitude disabled-input" type="text" name="longitude" id="longitude"
                                           value="{{ $default_location['lng']??'151.2195' }}"
                                           placeholder="{{translate('longitude')}}"readonly>
                                </div>
                            </div>
                            <div class="col-12">
                            <div class="form-group">
                                <label class="title-color d-flex justify-content-end">
                                    <span class="badge badge--primary-2">
                                       {{translate('latitude').' : '}}
                                        <span  id="showLatitude">
                                            {{($default_location['lat']??'-33.8688')}}
                                        </span>
                                    </span>
                                    <span class="mx-1 badge badge--primary-2" id="showLongitude">
                                       {{translate('longitude').' : '}}
                                        <span  id="showLongitude">
                                            {{($default_location['lng']??'151.2195')}}
                                        </span>
                                    </span>
                                </label>
                                <input id="map-pac-input" class="form-control rounded __map-input mt-1"
                                       title="{{translate('search_your_location_here')}}" type="text"
                                       placeholder="{{translate('search_here')}}"/>
                                <div class="rounded w-100 __h-200px mb-5"
                                     id="location-map-canvas"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-1">
                        <i class="tio-briefcase"></i>
                        {{translate('business_information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex" for="currency">{{translate('currency')}} </label>
                                <select name="currency_id" class="form-control js-select2-custom">
                                    @foreach ($CurrencyList as $item)
                                        <option
                                            value="{{ $item->id }}" {{ $item->id == $businessSetting['system_default_currency'] ?'selected':'' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <label class="title-color d-flex">{{translate('currency_Position')}}</label>
                            <div class="form-control form-group d-flex gap-2">
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="left"
                                           name="currency_symbol_position"
                                           id="currency_position_left" {{ $businessSetting['currency_symbol_position'] == 'left' ? 'checked':'' }}>
                                    <label class="custom-control-label"
                                           for="currency_position_left">({{ getCurrencySymbol(currencyCode: getCurrencyCode(type: 'default')) }}
                                        ) {{translate('left')}}</label>
                                </div>
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="right"
                                           name="currency_symbol_position"
                                           id="currency_position_right" {{ $businessSetting['currency_symbol_position'] == 'right' ? 'checked':'' }}>
                                    <label class="custom-control-label"
                                           for="currency_position_right">{{translate('right')}}
                                        ({{ getCurrencySymbol(currencyCode: getCurrencyCode(type: 'default')) }}
                                        )</label>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <label class="title-color d-flex">
                                {{translate('forgot_password_verification_by')}}
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                      data-placement="right"
                                      title="{{translate('set_how_users_of_recover_their_forgotten_password')}}">
                                    <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                         alt="">
                                </span>
                            </label>
                            <div class="form-control form-group d-flex gap-2">
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="email"
                                           name="forgot_password_verification"
                                           id="verification_by_email" {{ $businessSetting['forgot_password_verification'] == 'email' ? 'checked':'' }}>
                                    <label class="custom-control-label"
                                           for="verification_by_email">{{translate('email')}}</label>
                                </div>
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="phone"
                                           name="forgot_password_verification"
                                           id="verification_by_phone" {{ $businessSetting['forgot_password_verification'] == 'phone' ? 'checked':'' }}>
                                    <label class="custom-control-label"
                                           for="verification_by_phone">{{translate('phone').' '.'('.translate('OTP').')'}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <label class="title-color d-flex">{{translate('business_model')}}</label>
                            <div class="form-control form-group d-flex gap-2">
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="single" name="business_mode"
                                           id="single_vendor" {{ $businessSetting['business_mode'] == 'single' ? 'checked':'' }}>
                                    <label class="custom-control-label"
                                           for="single_vendor">{{translate('single_vendor')}}</label>
                                </div>
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="multi" name="business_mode"
                                           id="multi_vendor" {{ $businessSetting['business_mode'] == 'multi' ? 'checked':'' }}>
                                    <label class="custom-control-label"
                                           for="multi_vendor">{{translate('multi_vendor')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color text-capitalize">
                                        {{translate('email_verification')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('if_enabled_users_can_receive_verification_codes_on_their_registered_email_addresses')}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="email-verification">
                                        <input type="checkbox" class="switcher_input toggle-switch-message"
                                               name="email_verification"
                                               id="email-verification"
                                               value="1"
                                               {{ $businessSetting['email_verification'] == 1 ? 'checked':'' }}
                                               data-modal-id="toggle-modal"
                                               data-toggle-id="email-verification"
                                               data-on-image="email-verification-on.png"
                                               data-off-image="email-verification-off.png"
                                               data-on-title="{{translate('want_to_Turn_OFF_the_Email_Verification')}}"
                                               data-off-title="{{translate('want_to_Turn_ON_the_Email_Verification')}}"
                                               data-on-message="<p>{{translate('if_disabled_users_would_not_receive_verification_codes_on_their_registered_email_addresses')}}</p>"
                                               data-off-message="<p>{{translate('if_enabled_users_will_receive_verification_codes_on_their_registered_email_addresses')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            @php($phoneVerification = getWebConfig(name: 'phone_verification'))
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('OTP_Verification')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                              data-placement="right"
                                              title="{{translate('if_enabled_users_can_receive_verification_codes_via_OTP_messages_on_their_registered_phone_numbers')}}">
                                            <img width="16"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="otp-verification">
                                        <input type="checkbox" class="switcher_input toggle-switch-message"
                                               name="phone_verification"
                                               id="otp-verification"
                                               value="1" {{ $phoneVerification == 1 ? 'checked':'' }}
                                               data-modal-id="toggle-modal"
                                               data-toggle-id="otp-verification"
                                               data-on-image="otp-verification-on.png"
                                               data-off-image="otp-verification-off.png"
                                               data-on-title="{{translate('want_to_Turn_OFF_the_OTP_Verification')}}"
                                               data-off-title="{{translate('want_to_Turn_ON_the_OTP_Verification')}}"
                                               data-on-message="<p>{{translate('if_disabled_users_would_not_receive_verification_codes_on_their_registered_phone_numbers')}}</p>"
                                               data-off-message="<p>{{translate('if_enabled_users_will_receive_verification_codes_on_their_registered_phone_numbers')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">
                                    {{translate('pagination')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                          data-placement="right"
                                          title="{{translate('this_number_indicates_how_much_data_will_be_shown_in_the_list_or_table')}}">
                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                             alt="">
                                    </span>
                                </label>
                                <input type="number" value="{{ $businessSetting['pagination_limit'] }}"
                                       name="pagination_limit" class="form-control" placeholder="25">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('Company_Copyright_Text')}}</label>
                                <input class="form-control" type="text" name="company_copyright_text"
                                       value="{{ $businessSetting['company_copyright_text'] }}"
                                       placeholder="{{translate('company_copyright_text')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label
                                    class="input-label text-capitalize">{{translate('digit_after_decimal_point')}}
                                    ( {{translate('ex').':'. '0.00'}})</label>
                                <input type="number" value="{{ $businessSetting['decimal_point_settings'] }}"
                                       name="decimal_point_settings" class="form-control" min="0"
                                       placeholder="{{translate('4')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-2">
                        <i class="tio-briefcase"></i>
                        {{translate('app_download_info')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <div class="d-flex gap-2 align-items-center text-capitalize mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/apple.png')}}" alt="">
                                {{translate('apple_store')}}:
                            </div>

                            @php($appStoreDownload = getWebConfig('download_app_apple_stroe'))

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

                                    <label class="switcher" for="app-store-download-status">
                                        <input type="checkbox" value="1" class="switcher_input toggle-switch-message"
                                               name="app_store_download_status"
                                               id="app-store-download-status"
                                               {{ $appStoreDownload['status'] == 1 ? 'checked':''  }}
                                               data-modal-id="toggle-modal"
                                               data-toggle-id="app-store-download-status"
                                               data-on-image="app-store-download-on.png"
                                               data-off-image="app-store-download-off.png"
                                               data-on-title="{{translate('want_to_Turn_OFF_the_App_Store_button')}}"
                                               data-off-title="{{translate('want_to_Turn_ON_the_App_Store_button')}}"
                                               data-on-message="<p>{{translate('if_disabled_the_App_Store_button_will_be_hidden_from_the_website_footer')}}</p>"
                                               data-off-message="<p>{{translate('if_enabled_everyone_can_see_the_App_Store_button_in_the_website_footer')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <input type="url" name="app_store_download_url" class="form-control"
                                       value="{{ $appStoreDownload['link'] ?? '' }}"
                                       placeholder="{{translate('ex').':'.'https://www.apple.com/app-store/'}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex gap-2 align-items-center text-capitalize mb-3">
                                <img width="22" src="{{dynamicAsset(path: 'public/assets/back-end/img/play_store.png')}}" alt="">
                                {{translate('google_play_store').':'}}
                            </div>

                            @php($playStoreDownload = getWebConfig('download_app_google_stroe'))
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

                                    <label class="switcher" for="play-store-download-status">
                                        <input type="checkbox" value="1" class="switcher_input toggle-switch-message"
                                               name="play_store_download_status"
                                               id="play-store-download-status"
                                               {{ $playStoreDownload['status'] == 1 ? 'checked':'' }}
                                               data-modal-id="toggle-modal"
                                               data-toggle-id="play-store-download-status"
                                               data-on-image="play-store-download-on.png"
                                               data-off-image="play-store-download-off.png"
                                               data-on-title="{{translate('want_to_Turn_OFF_the_Google_Play_Store_button')}}"
                                               data-off-title="{{translate('want_to_Turn_ON_the_Google_Play_Store_button')}}"
                                               data-on-message="<p>{{translate('if_disabled_the_Google_Play_Store_button_will_be_hidden_from_the_website_footer')}}</p>"
                                               data-off-message="<p>{{translate('if_enabled_everyone_can_see_the_Google_Play_Store_button_in_the_website_footer')}}</p>">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <input type="url" name="play_store_download_url" class="form-control"
                                       value="{{ $playStoreDownload['link'] ?? '' }}"
                                       placeholder="{{translate('Ex: https://play.google.com/store/apps')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center gap-2">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/website-color.png')}}" alt="">
                                {{translate('website_Color')}}
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-wrap gap-4 justify-content-around">
                            <div class="form-group">
                                <input type="color" name="primary" value="{{ $businessSetting['primary_color'] }}"
                                       class="form-control form-control_color">
                                <div class="text-center">
                                    <div
                                        class="title-color mb-4 mt-3">{{ strtoupper($businessSetting['primary_color']) }}</div>
                                    <label class="title-color text-capitalize">{{translate('primary_Color')}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="color" name="secondary" value="{{ $businessSetting['secondary_color'] }}"
                                       class="form-control form-control_color">
                                <div class="text-center">
                                    <div
                                        class="title-color mb-4 mt-3">{{ strtoupper($businessSetting['secondary_color']) }}</div>
                                    <label class="title-color text-capitalize">
                                        {{translate('secondary_Color')}}
                                    </label>
                                </div>
                            </div>
                            @if(theme_root_path() == 'theme_aster')
                                <div class="form-group">
                                    <input type="color" name="primary_light"
                                           value="{{ $businessSetting['primary_color_light'] ?? '#CFDFFB' }}"
                                           class="form-control form-control_color">
                                    <div class="text-center">
                                        <div
                                            class="title-color mb-4 mt-3">{{ $businessSetting['primary_color_light'] ?? '#CFDFFB' }}</div>
                                        <label
                                            class="title-color text-capitalize">{{translate('primary_Light_Color')}}</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/header-logo.png')}}" alt="">
                                {{translate('website_header_logo')}}
                            </h5>
                            <span
                                class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Main website Logo'] }}</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <div class="d-flex justify-content-center">
                                <img height="60" id="view-website-logo" alt=""
                                     src="{{ getValidImage(path: 'storage/app/public/company/'. $businessSetting['web_logo'] , type: 'backend-basic') }}">
                            </div>
                            <div class="mt-4 position-relative">
                                <input type="file" name="company_web_logo" id="website-logo"
                                       class="custom-file-input image-input" data-image-id="view-website-logo"
                                       accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label text-capitalize"
                                       for="website-logo">{{translate('choose_file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('website_footer_logo')}}
                            </h5>
                            <span
                                class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Main website Logo'] }}</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <div class="d-flex justify-content-center">
                                <img height="60" id="view-website-footer-logo"
                                     src="{{ getValidImage(path: 'storage/app/public/company/'. $businessSetting['footer_logo'] , type: 'backend-basic') }}"alt="">
                            </div>
                            <div class="position-relative mt-4">
                                <input type="file" name="company_footer_logo" id="website-footer-logo"
                                       class="custom-file-input image-input" data-image-id="view-website-footer-logo"
                                       accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label text-capitalize"
                                       for="website-footer-logo">{{translate('choose_file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('website_Favicon')}}
                            </h5>
                            <span class="badge badge-soft-info">( {{translate('ratio').'1:1'}} )</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <div class="d-flex justify-content-center">
                                <img height="60" id="view-website-fav-icon"
                                     src="{{ getValidImage(path: 'storage/app/public/company/'. $businessSetting['fav_icon'] , type: 'backend-basic') }}" alt="">
                            </div>
                            <div class="position-relative mt-4">
                                <input type="file" name="company_fav_icon" id="website-fav-icon"
                                       class="custom-file-input image-input" data-image-id="view-website-fav-icon"
                                       accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                       for="website-fav-icon">{{translate('choose_File')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('loading_gif')}}
                            </h5>
                            <span class="badge badge-soft-info">( {{translate('ratio').'1:1'}})</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <div class="d-flex justify-content-center">
                                <img height="60" id="view-loader-icon"
                                     src="{{ getValidImage(path: 'storage/app/public/company/'. $businessSetting['loader_gif'] , type: 'backend-basic') }}" alt="">
                            </div>
                            <div class="position-relative mt-4">
                                <input type="file" name="loader_gif" id="loader-icon"
                                       class="custom-file-input image-input" data-image-id="view-loader-icon"
                                       accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label text-capitalize"
                                       for="loader-icon">{{translate('choose_file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('App_Logo')}}
                            </h5>
                            <span class="badge badge-soft-info">{{'('.'100X60'.'px'.')'}}</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <div class="d-flex justify-content-center">
                                <img height="60" id="view-app-logo"
                                     src="{{ getValidImage(path: 'storage/app/public/company/'. $businessSetting['mob_logo'] , type: 'backend-basic') }}" alt="">
                            </div>
                            <div class="mt-4 position-relative">
                                <input type="file" name="company_mobile_logo" id="app-logo"
                                       class="custom-file-input image-input" data-image-id="view-app-logo"
                                       accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label text-capitalize"
                                       for="app-logo">{{translate('choose_file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn--primary text-capitalize px-4">{{translate('save_information')}}</button>
            </div>
        </form>
    </div>
    <span id="get-default-latitude" data-latitude="{{$default_location['lat']??'-33.8688'}}"></span>
    <span id="get-default-longitude" data-longitude="{{$default_location['lng']??'151.2195'}}"></span>

@endsection

@push('script')
    @if(getWebConfig('map_api_status') ==1 )
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{getWebConfig('map_api_key')}}&callback=initAutocomplete&loading=async&libraries=places&v=3.56"
        defer>
    </script>
    @endif
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush
