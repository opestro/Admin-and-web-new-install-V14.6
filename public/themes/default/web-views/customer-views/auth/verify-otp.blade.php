@extends('layouts.front-end.app')

@section('title',  translate('OTP_verification'))

@section('content')
    <div class="container py-4 py-lg-5 my-4 __inline-8">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <h2 class="h3 mb-4">{{ translate('provide_your_otp_and_proceed')}}?</h2>
                <div class="card py-2 mt-4">
                    <form class="card-body needs-validation" action="{{route('customer.auth.otp-verification')}}"
                          method="post">
                        @csrf
                        <div class="form-group">
                            <div class="resend_otp_custom text-center">
                                <p class="text-primary mb-2 ">{{ translate('resend_code_within') }}</p>
                                <h6 class="text-primary mb-5 verifyTimer">
                                    <span class="verifyCounter" data-second="{{$time_count}}"></span>s
                                </h6>
                            </div>

                            <label>{{ translate('enter_your_OTP')}}</label>
                            <div id="divOuter">
                                <div id="divInner">
                                    <input id="partitioned" class="form-control" name="otp" type="text" maxlength="4" />
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary resend-otp-button" type="button" id="customerOtpVerify"
                                data-identity="{{ request('identity') }}" data-url="{{ route('customer.auth.resend-otp-reset-password') }}">
                            {{ translate('resend_OTP') }}
                        </button>
                        <button class="btn btn--primary" type="submit">{{ translate('proceed')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/verify-otp.js') }}"></script>
@endpush
