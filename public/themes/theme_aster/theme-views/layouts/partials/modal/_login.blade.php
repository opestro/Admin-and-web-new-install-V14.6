<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body px-4 px-sm-5">
                <div class="mb-4 text-center">
                    <img alt="" class="dark-support"
                        width="200" src="{{ getValidImage(path: 'storage/app/public/company/'.$web_config['web_logo']->value, type:'logo') }}">
                </div>
                <div class="mb-4">
                    <h2 class="mb-2">{{ translate('login') }}</h2>
                    <p class="text-muted">
                        {{ translate('login_to_your_account').'.'.translate('don’t_have_account').'?' }}
                        <span class="text-primary fw-bold text-capitalize" data-bs-toggle="modal"
                              data-bs-target="#registerModal">
                            {{translate('sign_up')}}
                        </span>
                    </p>
                </div>
                <form action="{{route('customer.auth.login')}}" method="post" id="customer-login-modal"
                      autocomplete="off">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="email">{{ translate('email') }} / {{ translate('phone') }}</label>
                        <input
                            name="user_id" id="si-email"
                            class="form-control" value="{{old('user_id')}}"
                            placeholder="{{translate('enter_email_or_phone_number')}}" required
                        />
                    </div>

                    <div class="mb-4">
                        <label for="password">{{ translate('password') }}</label>
                        <div class="input-inner-end-ele">
                            <input name="password" type="password" id="si-password" class="form-control"
                                   placeholder="{{ translate('ex:').':'.'6+'.' '.translate('character') }}" required/>
                            <i class="bi bi-eye-slash-fill togglePassword"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between gap-3 align-items-center">
                        <label for="remember" class="d-flex gap-1 align-items-center mb-0">
                            <input type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}/>
                            {{ translate('remember_me') }}
                        </label>

                        <a href="{{route('customer.auth.recover-password')}}"
                           class="text-capitalize">{{ translate('forgot_password').'?' }} </a>
                    </div>

                    @if($web_config['recaptcha']['status'] == 1)
                        <div class="d-flex justify-content-center mb-3">
                            <div id="recaptcha-element-customer-login" class="w-100 mt-4" data-type="image"></div>
                        </div>
                    @else
                        <div class="d-flex justify-content-center align-items-center gap-3 py-2 mt-4 mb-3">
                            <div>
                                <input type="text" class="form-control border __h-40"
                                       name="default_recaptcha_id_customer_login" value=""
                                       placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off">
                            </div>
                            <div class="input-icons rounded bg-white">
                                <a id="re-captcha-customer-login" class="d-flex align-items-center align-items-center">
                                    <img
                                        src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_login') }}"
                                        alt="" class="input-field rounded __h-40" id="customer_login_recaptcha_id">
                                    <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex justify-content-center mb-3">
                        <button type="submit" class="fs-16 btn btn-primary px-5">{{ translate('login') }}</button>
                    </div>
                </form>

                @if($web_config['social_login_text'])
                    <p class="text-center text-muted">{{ translate('or_continue_with') }}</p>
                @endif

                <div class="d-flex justify-content-center gap-3 align-items-center flex-wrap pb-3">
                    @foreach ($web_config['socials_login'] as $socialLoginService)
                        @if (isset($socialLoginService) && $socialLoginService['status']==true)
                            <a href="{{route('customer.auth.service-login', $socialLoginService['login_medium'])}}">
                                <img width="35"
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
    @if($web_config['recaptcha']['status'] == 1)
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallbackCustomerLogin&render=explicit" async
                defer></script>
        <script type="text/javascript">
            'use strict';
            var onloadCallbackCustomerLogin = function () {
                let loginId = grecaptcha.render('recaptcha-element-customer-login', {
                    'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
                });
                $('#recaptcha-element-customer-login').attr('data-login-id', loginId);
            };
        </script>
    @else
        <script type="text/javascript">
            'use strict';
            $('#re-captcha-customer-login').on('click', function () {
                let url = "{{ URL('/customer/auth/code/captcha') }}";
                url = url + "/" + Math.random() + '?captcha_session_id=default_recaptcha_id_customer_login';
                document.getElementById('customer_login_recaptcha_id').src = url;
                console.log('url: ' + url);
            })
        </script>
    @endif
    <script>
        'use strict';
        $('#customer-login-modal').submit(function (e) {
            e.preventDefault();
            let customer_recaptcha = true;
            @if($web_config['recaptcha']['status'] == 1)
            let response_customer_login = grecaptcha.getResponse($('#recaptcha-element-customer-login').attr('data-login-id'));
            if (response_customer_login.length === 0) {
                e.preventDefault();
                toastr.error("{{ translate('Please_check_the_recaptcha') }}");
                customer_recaptcha = false;
            }
            @endif
            if (customer_recaptcha === true) {
                let form = $(this);
                $.ajax({
                    type: 'POST',
                    url: `{{route('customer.auth.login')}}`,
                    data: form.serialize(),
                    success: function (data) {
                        console.log(data)
                        if (data.status === 'success') {
                            toastr.success(`{{translate('Login_successful')}}`);
                            data.redirect_url !== '' ? window.location.href = data.redirect_url : location.reload();
                        } else if (data.status === 'error') {
                            toastr.error(data.message);
                            if (data.redirect_url && data.redirect_url !== '') {
                                window.location.href = data.redirect_url
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
