@extends('layouts.front-end.app')

@section('title', translate('sign_in'))

@section('content')
    <div class="container py-4 py-lg-5 my-4 text-align-direction">
         <div class="login-card">
            <div class="mx-auto __max-w-360">
                <h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">{{ translate('sign_in')}}</h2>
                <form class="needs-validation mt-2" autocomplete="off" action="{{route('customer.auth.login')}}"
                        method="post" id="customer-login-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label font-semibold">
                            {{ translate('email') }} / {{ translate('phone')}}
                        </label>
                        <input class="form-control text-align-direction" type="text" name="user_id" id="si-email"
                                value="{{old('user_id')}}" placeholder="{{ translate('enter_email_address_or_phone_number') }}"
                                required>
                        <div class="invalid-feedback">{{ translate('please_provide_valid_email_or_phone_number') }} .</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-semibold">{{ translate('password') }}</label>
                        <div class="password-toggle rtl">
                            <input class="form-control text-align-direction" name="password" type="password" id="si-password" placeholder="{{ translate('password_must_be_7+_Character')}}" required>
                            <label class="password-toggle-btn">
                                <input class="custom-control-input" type="checkbox">
                                    <i class="tio-hidden password-toggle-indicator"></i>
                                    <span class="sr-only">{{ translate('show_password') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-between">
                        <div class="rtl">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="remember"
                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label text-primary" for="remember">{{ translate('remember_me') }}</label>
                            </div>
                        </div>
                        <a class="font-size-sm text-primary text-underline" href="{{route('customer.auth.recover-password')}}">
                            {{ translate('forgot_password') }}?
                        </a>
                    </div>
                    @php($recaptcha = getWebConfig(name: 'recaptcha'))
                    @if(isset($recaptcha) && $recaptcha['status'] == 1)
                        <div id="recaptcha_element" class="w-100" data-type="image"></div>
                        <br/>
                    @else
                        <div class="row py-2">
                            <div class="col-6 pr-2">
                                <input type="text" class="form-control border __h-40" name="default_recaptcha_id_customer_login" value=""
                                    placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off">
                            </div>
                            <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                <a href="javascript:" class="d-flex align-items-center align-items-center get-login-recaptcha-verify" data-link="{{ URL('/customer/auth/code/captcha') }}">
                                    <img src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_login') }}" class="input-field rounded __h-40" id="customer_login_recaptcha_id" alt="">
                                    <i class="tio-refresh icon cursor-pointer p-2"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    <button class="btn btn--primary btn-block btn-shadow" type="submit">{{ translate('sign_in') }}</button>
                </form>
                @if($web_config['social_login_text'])
                <div class="text-center m-3 text-black-50">
                    <small>{{ translate('or_continue_with') }}</small>
                </div>
                @endif
                <div class="d-flex justify-content-center my-3 gap-2">
                @foreach (getWebConfig(name: 'social_login') as $socialLoginService)
                    @if (isset($socialLoginService) && $socialLoginService['status'])
                        <div>
                            <a class="d-block" href="{{ route('customer.auth.service-login', $socialLoginService['login_medium']) }}">
                                <img src="{{theme_asset(path: 'public/assets/front-end/img/icons/'.$socialLoginService['login_medium'].'.png') }}" alt="">
                            </a>
                        </div>
                    @endif
                @endforeach
                </div>
                <div class="text-black-50 text-center">
                    <small>
                        {{  translate('Enjoy_New_experience') }}
                        <a class="text-primary text-underline" href="{{route('customer.auth.sign-up')}}">
                            {{ translate('sign_up') }}
                        </a>
                    </small>
                </div>
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
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
                async defer></script>
    @endif
@endpush
