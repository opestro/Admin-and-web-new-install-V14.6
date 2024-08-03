@extends('layouts.front-end.app')

@section('title', translate('verify'))

@section('content')
    <div class="container py-4 py-lg-5 my-4 __inline-7">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 box-shadow">
                    @if ($user_verify == 0)
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="h4 mb-1">{{ translate('one_step_ahead')}}</h2>
                                <p class="font-size-sm text-muted mb-4">
                                    {{ translate('verify_information_to_continue')}}.</p>
                                <div class="resend_otp_custom">
                                    <p class="text-primary mb-2 ">{{ translate('resend_code_within') }}</p>
                                    <h6 class="text-primary mb-5 verifyTimer">
                                        <span class="verifyCounter" data-second="{{$get_time}}"></span>s
                                    </h6>
                                </div>
                            </div>
                            <form class="needs-validation_" id="sign-up-form"
                                  action="{{ route('customer.auth.verify') }}"
                                  method="post">
                                @csrf
                                <div class="col-sm-12">
                                    @php($email_verify_status = getWebConfig(name: 'email_verification'))
                                    @php($phone_verify_status = getWebConfig(name: 'phone_verification'))
                                    <div class="form-group">
                                        @if(getWebConfig(name: 'email_verification'))
                                            <label for="reg-phone" class="text-primary">
                                                * {{ translate('please_provide_verification_token_sent_in_your_email') }}
                                            </label>
                                        @elseif(getWebConfig(name: 'phone_verification'))
                                            <label for="reg-phone" class="text-primary">
                                                * {{ translate('please_provide_OTP_sent_in_your_phone') }}
                                            </label>
                                        @else
                                            <label for="reg-phone" class="text-primary">
                                                * {{ translate('verification_code') }} / {{ translate('OTP')}}
                                            </label>
                                        @endif
                                        <input class="form-control" type="text" name="token" required>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$user->id}}" name="id">
                                <button class="btn btn-outline-primary resend-otp-button" type="button"
                                        id="customerResendVerifyOtp" data-url="{{ route('customer.auth.resend_otp') }}"
                                        data-userid="{{$user->id}}">
                                    {{ translate('resend_OTP') }}
                                </button>
                                <button type="submit" class="btn btn-outline-primary">{{ translate('verify')}}</button>
                            </form>
                        </div>
                    @else
                        <div class=" p-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <i class="fa fa-check-circle __text-100px __color-0f9d58"></i>
                                    </div>

                                    <span class="font-weight-bold d-block mt-4 __text-17px text-center">{{ translate('hello')}}, {{$user->f_name}}</span>
                                    <h5 class="font-black __text-20px text-center my-2">
                                        {{ translate('verification_Successfully_Done!')}}!
                                    </h5>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{route('customer.auth.login')}}" class="btn btn-sm btn--primary">
                                    {{ translate('sign_in')}}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/verify-otp.js') }}"></script>
@endpush
