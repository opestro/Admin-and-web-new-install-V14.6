@extends('theme-views.layouts.app')

@section('title', translate('Reset_Password').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-30">
        <div class="container">
            <div class="card mb-4">
                <div class="card-body py-5 px-lg-5">
                    <div class="row align-items-center pb-5">
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <h2 class="text-center mb-5 text-capitalize">{{ translate('reset_password') }}</h2>
                            <div class="d-flex justify-content-center">
                                <img width="283" class="dark-support" src="{{ theme_asset('assets/img/otp.png') }}"
                                     alt="{{translate('image')}}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="d-flex justify-content-center mb-3">
                                <img width="50" class="dark-support" src="{{ theme_asset('assets/img/otp-lock.png') }}"
                                     alt="">
                            </div>
                            <p class="text-muted mx-w mx-auto text-center mb-4 width--18-75rem">{{translate('please_set_up_a_new_password').'.'}}</p>
                            <form action="{{request('customer.auth.password-recovery')}}" class="reset-password-form"
                                  method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="password2">{{ translate('password') }}</label>
                                    <div class="input-inner-end-ele">
                                        <input type="password" id="password2" name="password" min="8"
                                               class="form-control"
                                               placeholder="{{ translate('minimum_8_characters_long') }}" required>
                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="confirm_password2">{{ translate('confirm_password') }}</label>
                                    <div class="input-inner-end-ele">
                                        <input type="password" id="confirm_password2" class="form-control"
                                               name="confirm_password" min="8"
                                               placeholder="{{ translate('minimum_8_characters_long') }}" required>
                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                    </div>
                                </div>

                                <input type="hidden" name="reset_token" value="{{$token}}" required>
                                <div class="d-flex justify-content-center gap-3 mt-5">
                                    <button class="btn btn-primary px-sm-5"
                                            type="submit">{{ translate('submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
