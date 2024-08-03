@extends('theme-views.layouts.app')

@section('title', translate('Forgot_Password').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
        <div class="container">
            <div class="card">
                <div class="card-body py-5 px-lg-5">
                    <div class="row align-items-center pb-5">
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <h2 class="text-center mb-5 text-capitalize">{{ translate('forget_password') }}</h2>
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
                            <p class="text-muted mx-w mx-auto text-center mb-4 width--18-75rem">
                                @if($verification_by == 'email')
                                    {{ translate('please_enter_your_email_to_send_a_verification_code_for_forget_password') }}
                                @elseif($verification_by=='phone')
                                    {{ translate('please_enter_your_phone_to_send_a_verification_code_for_forget_password') }}
                                @endif
                            </p>
                            <form action="{{route('customer.auth.forgot-password')}}" class="forget-password-form"
                                  method="post">
                                @csrf
                                @if($verification_by=='email')
                                    <div class="form-group">
                                        <label for="recover-email">{{translate('email')}}</label>
                                        <input class="form-control" type="email" name="identity" id="recover-email"
                                               autocomplete="off" required>
                                        <div
                                            class="invalid-feedback">{{translate('please_provide_valid_email_address').'.'}}</div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="recover-email">{{translate('phone')}}</label>
                                        <input class="form-control" type="text" name="identity" id="recover-email"
                                               autocomplete="off" required>
                                        <div
                                            class="invalid-feedback">{{translate('please_provide_valid_phone_number').'.'}}</div>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-center gap-3 mt-5">
                                    <button class="btn btn-outline-primary get-view-by-onclick"
                                            data-link="{{ route('home') }}"
                                            type="button">{{ translate('back_again') }}</button>
                                    <button class="btn btn-primary px-sm-5"
                                            type="submit">{{ translate('verify') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
