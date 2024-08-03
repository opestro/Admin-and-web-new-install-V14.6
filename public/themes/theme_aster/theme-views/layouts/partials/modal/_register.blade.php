<div class="modal fade"
     id="registerModal"
     tabindex="-1"
     aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body px-4 px-lg-5">
                <div class="mb-4 text-center">
                    <img width="200" alt="" class="dark-support"
                        src="{{ getValidImage(path: 'storage/app/public/company/'.($web_config['web_logo']->value), type:'logo') }}">
                </div>
                <div class="mb-4">
                    <h2 class="mb-2">{{ translate('sign_up') }}</h2>
                    <p class="text-muted">
                        {{ translate('login_to_your_account.') }} {{ translate('Don’t_have_account') }}?
                        <span
                            class="text-primary fw-bold"
                            data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            {{ translate('login') }}
                        </span>
                    </p>
                </div>
                <form action="{{ route('customer.auth.sign-up') }}" method="POST" id="customer-form"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="custom-scrollbar height-45vh">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="text-capitalize" for="f_name"> {{ translate('first_name') }}</label>
                                    <input
                                        type="text"
                                        id="f_name"
                                        name="f_name"
                                        class="form-control"
                                        placeholder="{{ translate('ex').':'.translate('Jhone') }}"
                                        value="{{old('f_name')}}"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="text-capitalize" for="l_name">{{ translate('last_name') }}</label>
                                    <input
                                        type="text"
                                        id="l_name"
                                        name="l_name"
                                        value="{{old('l_name')}}"
                                        class="form-control"
                                        placeholder="{{ translate('ex').':'.translate('doe') }}"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label for="r_email">{{ translate('email') }}</label>
                                    <input
                                        type="text"
                                        id="r_email"
                                        value="{{old('email')}}"
                                        name="email"
                                        class="form-control"
                                        placeholder="{{ translate('enter_email_or_phone_number') }}"
                                        autocomplete="off"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-4">
                                    <label for="phone">{{ translate('phone') }}</label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        value="{{old('phone')}}"
                                        class="form-control phone-input-with-country-picker"
                                        placeholder="{{ translate('enter_phone_number') }}"
                                        required
                                    />
                                    <input type="hidden" class="country-picker-phone-number w-50" name="phone" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <label for="password">{{ translate('password') }}
                                        <span class="text-danger mx-1 password-error"></span>
                                    </label>
                                    <div class="input-inner-end-ele">
                                        <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            class="form-control"
                                            placeholder="{{ translate('minimum_8_characters_long') }}"
                                            autocomplete="off"
                                            required
                                        />
                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4">
                                    <label class="text-capitalize"
                                           for="confirm_password">{{ translate('confirm_password') }}</label>
                                    <div class="input-inner-end-ele">
                                        <input
                                            type="password"
                                            id="confirm_password"
                                            class="form-control"
                                            name="con_password"
                                            placeholder="{{ translate('minimum_8_characters_long') }}"
                                            autocomplete="off"
                                            required
                                        />
                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                    </div>
                                </div>
                            </div>
                            @if ($web_config['ref_earning_status'])
                                <div class="col-sm-12">
                                    <div class="mb-4">
                                        <div class="form-group">
                                            <label class="form-label form--label text-capitalize"
                                                   for="referral_code">{{ translate('refer_code') }} <small
                                                    class="text-muted">({{ translate('optional') }})</small></label>
                                            <input type="text" id="referral_code" class="form-control"
                                                   name="referral_code"
                                                   placeholder="{{ translate('use_referral_code') }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($web_config['recaptcha']['status'] == 1)
                            <div class="d-flex justify-content-center">
                                <div id="recaptcha-element-customer-register" class="w-100 mt-2" data-type="image"></div>
                            </div>
                        @else
                            <div class="d-flex gap-3 justify-content-center py-2 mt-4 mb-3">
                                <div class="">
                                    <input type="text" class="form-control border __h-40"
                                           name="default_recaptcha_value_customer_regi" value=""
                                           placeholder="{{ translate('Enter_captcha_value') }}" autocomplete="off">
                                </div>
                                <div class="input-icons rounded bg-white">
                                    <a id="re-captcha-customer-register"
                                       class="d-flex align-items-center align-items-center">
                                        <img
                                            src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_regi') }}"
                                            alt="" class="input-field rounded __h-40" id="customer-regi-recaptcha-id">
                                        <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center mt-4">
                            <label for="input-checked" class="d-flex gap-1 align-items-center mb-0 user-select-none">
                                <input type="checkbox" id="input-checked" required/>
                                {{translate('i_agree_with_the')}} <a href="{{route('terms')}}"
                                                                     class="text-info text-capitalize">{{ translate('terms_&_conditions') }}</a>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4 mb-3">
                        <button type="submit" id="sign-up" class="btn btn-primary px-5 text-capitalize"
                                disabled>{{ translate('sign_up') }}</button>
                    </div>
                </form>

                @if($web_config['social_login_text'])
                    <p class="text-center text-muted">{{ translate('or_continue_with') }}</p>
                @endif
                <div class="d-flex justify-content-center gap-3 align-items-center flex-wrap pb-3">
                    @foreach ($web_config['socials_login'] as $socialLoginService)
                        @if (isset($socialLoginService))
                            <a href="{{route('customer.auth.service-login', $socialLoginService['login_medium'])}}">
                                <img
                                    width="35"
                                    src="{{ theme_asset('assets/img/svg/'.$socialLoginService['login_medium'].'.svg') }}"
                                    alt=""
                                    class="dark-support"/>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallbackCustomerRegi&render=explicit" async
            defer></script>
    <script>
        'use strict';
        initializePhoneInput(".phone-input-with-country-picker", ".country-picker-phone-number");
        $('#input-checked').change(function () {
            if ($(this).is(':checked')) {
                $('#sign-up').removeAttr('disabled');
            } else {
                $('#sign-up').attr('disabled', 'disabled');
            }
        });
        @if($web_config['recaptcha']['status'] == '1')
        var onloadCallbackCustomerRegi = function () {
            let reg_id = grecaptcha.render('recaptcha-element-customer-register', {
                'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
            });
            $('#recaptcha-element-customer-register').attr('data-reg-id', reg_id);
        };
        function recaptcha_f() {
            let response = grecaptcha.getResponse($('#recaptcha-element-customer-register').attr('data-reg-id'));
            return response.length !== 0;
        }
        @else
            function reCaptchaCustomerRegister()
            {
                $('#re-captcha-customer-register').on('click', function () {
                    let url = "{{ URL('/customer/auth/code/captcha') }}";
                    url = url + "/" + Math.random() + '?captcha_session_id=default_recaptcha_id_customer_regi';
                    document.getElementById('customer-regi-recaptcha-id').src = url;
                    console.log('url: ' + url);
                })
            }
            reCaptchaCustomerRegister();
        @endif
        $('#customer-form').submit(function (event) {
            event.preventDefault();
            let formData = $(this).serialize()
            let recaptcha = true;
            @if($web_config['recaptcha']['status'] == '1')
                recaptcha = recaptcha_f();
            @endif
            if (recaptcha === true) {
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    beforeSend: function () {
                        $("#loading").addClass("d-grid");
                    },
                    success: function (data) {
                        if (data.errors) {
                            for (let index = 0; index < data.errors.length; index++) {
                                toastr.error(data.errors[index], {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success(
                                '{{translate("Customer_Added_Successfully")}}!', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            if (data.redirect_url !== '') {
                                window.location.href = data.redirect_url;
                            } else {
                                $('#registerModal').modal('hide');
                                $('#loginModal').modal('show');
                            }
                        }
                    },
                    complete: function () {
                        $("#loading").removeClass("d-grid");
                    },
                });
            } else {
                toastr.error("{{translate('please_check_the_recaptcha')}}");
            }
        });
    </script>
    <script src="{{theme_asset('assets/js/password-strength.js')}}"></script>
@endpush
