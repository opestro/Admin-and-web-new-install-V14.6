@extends('layouts.front-end.app')

@section('title',  translate('register'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
    <div class="container py-4 __inline-7 text-align-direction">
        <div class="login-card">
            <div class="mx-auto __max-w-760">
                <h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">{{ translate('sign_up')}}</h2>
                <form class="needs-validation_" id="customer-register-form" action="{{ route('customer.auth.sign-up')}}"
                        method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('first_name')}}</label>
                                <input class="form-control text-align-direction" value="{{ old('f_name')}}" type="text" name="f_name"
                                        placeholder="{{ translate('Ex') }}: {{ translate('Jhone') }}"
                                        required >
                                <div class="invalid-feedback">{{ translate('please_enter_your_first_name')}}!</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('last_name') }}</label>
                                <input class="form-control text-align-direction" type="text" value="{{old('l_name') }}" name="l_name"
                                        placeholder="{{ translate('ex') }}: {{ translate('Doe') }}" required>
                                <div class="invalid-feedback">{{ translate('please_enter_your_last_name') }}!</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('email_address') }}</label>
                                <input class="form-control text-align-direction" type="email" value="{{old('email') }}" name="email"
                                     placeholder="{{ translate('enter_email_address') }}" autocomplete="off"
                                        required>
                                <div class="invalid-feedback">{{ translate('please_enter_valid_email_address') }}!</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('phone_number') }}
                                    <small class="text-primary">( * {{ translate('country_code_is_must_like_for_BD') }} 880 )</small></label>
                                <input class="form-control text-align-direction phone-input-with-country-picker"
                                       type="tel"  value="{{ old('phone') }}"
                                       placeholder="{{ translate('enter_phone_number') }}" required>

                                <input type="hidden" class="country-picker-phone-number w-50" name="phone" readonly>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('password') }} <small class="text-danger mx-1 password-error"></small></label>
                                <div class="password-toggle rtl">
                                    <input class="form-control text-align-direction" name="password" type="password" id="si-password"
                                            placeholder="{{ translate('minimum_8_characters_long') }}" required>
                                    <label class="password-toggle-btn">
                                        <input class="custom-control-input" type="checkbox"><i
                                            class="tio-hidden password-toggle-indicator"></i><span
                                            class="sr-only">{{ translate('show_password') }} </span>
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('confirm_password') }}</label>
                                <div class="password-toggle rtl">
                                    <input class="form-control text-align-direction" name="con_password" type="password"
                                            placeholder="{{ translate('minimum_8_characters_long') }}"
                                            id="si-password" required>
                                    <label class="password-toggle-btn">
                                        <input class="custom-control-input text-align-direction" type="checkbox">
                                        <i class="tio-hidden password-toggle-indicator"></i>
                                        <span class="sr-only">{{ translate('show_password') }}</span>
                                    </label>
                                </div>
                            </div>

                        </div>

                        @if ($web_config['ref_earning_status'])
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label font-semibold">{{ translate('refer_code') }} <small class="text-muted">({{ translate('optional') }})</small></label>
                                <input type="text" id="referral_code" class="form-control"
                                name="referral_code" placeholder="{{ translate('use_referral_code') }}">
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="rtl">
                                    <label class="custom-control custom-checkbox m-0 d-flex">
                                        <input type="checkbox" class="custom-control-input" name="remember" id="inputChecked">
                                        <span class="custom-control-label">
                                            <span>{{ translate('i_agree_to_Your') }}</span> <a class="font-size-sm text-primary text-force-underline" target="_blank" href="{{ route('terms') }}">{{ translate('terms_and_condition') }}</a>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @php($recaptcha = getWebConfig(name: 'recaptcha'))
                                @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                    <div id="recaptcha_element" class="w-100" data-type="image"></div>
                                @else
                                <div class="row">
                                    <div class="col-6 pr-2">
                                        <input type="text" class="form-control border __h-40" name="default_recaptcha_value_customer_regi" value=""
                                                placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off">
                                    </div>
                                    <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                        <a href="javascript:" class="d-flex align-items-center align-items-center get-regi-recaptcha-verify" data-link="{{ URL('/customer/auth/code/captcha') }}">
                                            <img alt="" src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_regi') }}" class="input-field rounded __h-40" id="default_recaptcha_id">
                                            <i class="tio-refresh icon cursor-pointer p-2"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="web-direction">
                        <div class="mx-auto mt-4 __max-w-356">
                            <button class="w-100 btn btn--primary" id="sign-up" type="submit" disabled>
                                {{ translate('sign_up') }}
                            </button>
                        </div>

                        <div class="text-black-50 mt-3 text-center">
                            <small>
                                {{  translate('Already_have_account ') }}?
                                <a class="text-primary text-underline" href="{{ route('customer.auth.login') }}">
                                    {{ translate('sign_in') }}
                                </a>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @if(isset($recaptcha) && $recaptcha['status'] == 1)
        <script type="text/javascript">
            "use strict";
            var onloadCallback = function () {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    @endif

    <script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
@endpush
