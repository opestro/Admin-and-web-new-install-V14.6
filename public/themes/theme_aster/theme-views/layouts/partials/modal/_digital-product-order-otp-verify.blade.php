<div class="d-flex justify-content-center">
    <a href="javascript:">
        <img src="{{theme_asset("assets/img/media/otp-2.png")}}" alt="{{translate('logo')}}" class="img-fluid" width="220">
    </a>
</div>
<h3 class="title text-center my-3 text-capitalize text-capitalize">{{ translate('OTP_verification') }}</h3>
<p class="text-center text-muted">
    {{ translate('an_OTP_has_been_sent_to_your_email_and_phone').' '.translate('please_enter_the_OTP_in_the_field_below_to_verify_for_this_download') }}
</p>
<form action="{{route('digital-product-download-otp-verify')}}" method="post" autocomplete="off" class="otp-form submit-digital-product-download-otp">
    @csrf
    <p class="text-center text-primary lead">
        {{ translate('resend_code_within') }}
        <strong><span class="verify-counter" data-second="{{ $time_count }}">{{ $time_count }}</span>{{translate('s')}}</strong>
    </p>

    <div class="d-flex gap-2 gap-sm-3 align-items-end justify-content-center">
        <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
            autocomplete="off">
        <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
            autocomplete="off">
        <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
            autocomplete="off">
        <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
            autocomplete="off">
    </div>
    <input class="otp-value" type="hidden" name="otp">
    <input class="identity" type="hidden" name="order_details_id" value="{{ $orderDetailID }}">
    <p class="text-center verify-message mt-4 mb-0 min-h-30px d-block d-none"></p>
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-2">
        <button type="button"
            class="btn btn-outline-primary w-auto min-w-180 resend-otp-button" id="resend-otp">{{translate('resend_OTP')}}</button>
            <span type="button" class="btn btn-primary w-auto min-w-180 submit-btn" id="verify-otp">{{translate('verify')}}</span>
    </div>
</form>
