@extends('theme-views.layouts.app')
@section('title', translate('vendor_Apply').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'assets/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
        <form id="seller-registration" action="{{route('vendor.auth.registration.index')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="first-el">
                <section>
                    <div class="container">
                        <div class="create-an-account p-3 p-sm-4">
                            <img src="{{theme_asset('assets/img/media/form-bg.png')}}" alt="" class="create-an-accout-bg-img">
                            <div class="row">
                                @include('theme-views.seller-views.auth.partial.header')
                                <div class="col-lg-8">
                                    <div class="bg-white p-3 p-sm-4 rounded">
                                        <h4 class="mb-4">{{translate('Create_an_Account')}}</h4>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group mb-4">
                                                    <label for="email">
                                                        {{translate('email')}}
                                                        <span class="text-danger">*</span>
                                                        <span class="text-danger fs-12 mail-error"></span>
                                                    </label>
                                                    <input class="form-control" type="email" id="email"  name="email" placeholder="{{translate('Ex: example@gmail.com')}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-4">
                                                    <label for="tel">
                                                        {{translate('phone')}}
                                                        <span class="text-danger">*</span>
                                                        <span class="text-danger fs-12 phone-error"></span>
                                                    </label>
                                                    <div>
                                                        <input class="form-control form-control-user phone-input-with-country-picker"
                                                                type="tel"
                                                                placeholder="{{ translate('enter_phone_number') }}" required>
                                                        <input type="hidden" class="country-picker-phone-number w-50" name="phone" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-4">
                                                    <label for="password">
                                                        {{translate('password')}}
                                                        <span class="text-danger">*</span>
                                                        <span class="text-danger fs-12 password-error"></span>
                                                    </label>
                                                    <div class="input-inner-end-ele">
                                                        <input class="form-control password-check" type="password" id="password"  name="password" value="{{old('password')}}" placeholder="{{translate('enter_password')}}" required>
                                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-4">
                                                    <label for="confirm_password">
                                                        {{translate('confirm_password')}}
                                                        <span class="text-danger">*</span>
                                                        <span class="text-danger fs-12 confirm_password-error"></span>
                                                    </label>
                                                    <div class="input-inner-end-ele">
                                                        <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="{{translate('confirm_password')}}" required>
                                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-primary proceed-to-next-btn">{{translate('Proceed_to_next')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                @include('theme-views.seller-views.auth.partial.why-with-us')
                @include('theme-views.seller-views.auth.partial.business-process')
                @include('theme-views.seller-views.auth.partial.download-app')
                @include('theme-views.seller-views.auth.partial.faq')
            </div>
            @include('theme-views.seller-views.auth.partial.vendor-information-form')
        </form>

        <div class="modal fade registration-success-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                        <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                    </div>
                    <div class="modal-body px-4 px-sm-5 pt-0">
                        <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                            <img src="{{theme_asset('assets/img/congratulations.png')}}" width="70" class="mb-3 mb-20" alt="">
                            <h5 class="modal-title">{{translate('congratulations')}}</h5>
                            <div class="text-center">{{translate('your_registration_is_successful').', '.translate('please-wait_for_admin_approval').'.'.translate(' you’ll_get_a_mail_soon')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span id="get-confirm-and-cancel-button-text" data-sure ="{{translate('are_you_sure').'?'}}"
              data-message="{{translate('want_to_apply_as_a_vendor').'?'}}"
              data-confirm="{{translate('yes')}}" data-cancel="{{translate('no')}}"></span>
        <span id="proceed-to-next-validation-message"
              data-mail-error="{{translate('please_enter_your_email').'.'}}"
              data-phone-error="{{translate('please_enter_your_phone_number').'.'}}"
              data-valid-mail="{{translate('please_enter_a_valid_email_address').'.'}}"
              data-enter-password="{{translate('please_enter_your_password').'.'}}"
              data-enter-confirm-password="{{translate('please_enter_your_confirm_password').'.'}}"
              data-password-not-match="{{translate('passwords_do_not_match').'.'}}"
        >

    </span>
    </main>
@endsection
@push('script')
    @if($web_config['recaptcha']['status'] == '1')
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script>
            "use strict";
            var onloadCallback = function () {
                let reg_id = grecaptcha.render('recaptcha-element-vendor-register', {'sitekey': '{{ $web_config['recaptcha']['site_key'] }}'});
                let login_id = grecaptcha.render('recaptcha_element_seller_login', {'sitekey': '{{ $web_config['recaptcha']['site_key'] }}'});

                $('#recaptcha-element-vendor-register').attr('data-reg-id', reg_id);
                $('#recaptcha_element_seller_login').attr('data-login-id', login_id);
            };
        </script>
    @else
        <script>
            "use strict";
            $('#re-captcha-vendor-register').on('click',function () {
                let genUrl = "{{ route('vendor.auth.recaptcha', ['tmp'=>':dummy-id']) }}";
                genUrl = genUrl.replace(":dummy-id", Math.random());
                genUrl = genUrl + '?captcha_session_id=sellerRecaptchaSessionKey';
                document.getElementById('default_recaptcha_id_regi').src = genUrl;
            })
        </script>
    @endif
    <script src="{{ theme_asset('assets/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset('assets/js/country-picker-init.js') }}"></script>
    <script src="{{theme_asset('assets/js/vendor-registration.js')}}"></script>
    <script>
        $('#vendor-apply-submit').on('click', function(){
            @if($web_config['recaptcha']['status'] == '1')
            var response = grecaptcha.getResponse($('#recaptcha-element-vendor-register').attr('data-reg-id'));
            if (response.length === 0) {
                toastr.error("{{translate('please_check_the_recaptcha')}}");
            }else{
                submitRegistration();
            }
            @else
            if ($('#default-recaptcha-id-vendor-register').val() != '') {
                submitRegistration();
            } else {
                toastr.error("{{translate('please_check_the_recaptcha')}}");
            }
            @endif
        });
        initializePhoneInput(".phone-input-with-country-picker", ".country-picker-phone-number");
    </script>
    <script src="{{theme_asset('assets/js/password-strength.js')}}"></script>
@endpush
