<div class="d-flex justify-content-center">
    <a href="javascript:">
        <img src="{{theme_asset("assets/img/media/OTP-Verification-fail.png")}}" alt="{{translate('logo')}}" class="img-fluid" width="220">
    </a>
</div>
<h3 class="title text-center my-3 text-capitalize">{{ translate('something_went_wrong').'!' }} </h3>
<p class="text-center text-muted">
    {{ translate('sorry_the_number_and_email_you_provided_during_order_is_incorrect').' '.translate('we_cannot_send_any_verification_code').' '.translate('please_contact_with_admin_or_your_vendor') }}
</p>
<div class="d-flex flex-column justify-content-center align-items-center gap-3 mt-5">
    <button type="button" class="btn btn-outline-primary w-auto min-w-180" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('support_ticket')}}</button>
    <button type="button" class="btn btn-primary w-auto min-w-180 text-capitalize" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('open_support_ticket')}}</button>
</div>
