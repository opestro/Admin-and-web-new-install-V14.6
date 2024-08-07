"use strict";

$(document).on('ready', function () {
    $('.js-toggle-password').each(function () {
        new HSTogglePassword(this).init()
    });
    $('.js-validate').each(function () {
        $.HSCore.components.HSValidation.init($(this));
    });
});

$('.submit-login-form').on('click',function (){
    var response = 1;
    try{
        response = grecaptcha.getResponse();
    }catch (e) {

    }
    if (response.length === 0) {
        e.preventDefault();
        toastr.error($('#message-please-check-recaptcha').data('text'));
    }else {
        $.ajaxSetup({
            headers: {
                'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: $('#vendor-login-form').attr('action'),
            data: $('#vendor-login-form').serialize(),
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                if (data.errors) {
                    for (let index = 0; index < data.errors.length; index++) {
                        toastr.error(data.errors[index].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else if(data.error){
                    toastr.error(data.error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }else if(data.status){
                    $('.'+data.status+'-message').removeClass('d-none')
                }else {
                    location.href = data.redirectRoute;
                    toastr.success(data.success)
                }
            },complete: function () {
                $('#loading').fadeOut();
            },
        })
    }
})
$('.clear-alter-message').on('click',function (){
    $('.vendor-suspend').addClass('d-none')
})
$('.get-login-recaptcha-verify').on('click', function () {
    document.getElementById('default_recaptcha_id').src = $(this).data('link') + "/" + Math.random()+'?captcha_session_id=vendorRecaptchaSessionKey';
});

$('#copyLoginInfo').on('click', function () {
    let vendorEmail = $('#vendor-email').data('email');
    let vendorPassword = $('#vendor-password').data('password');
    $('#signingVendorEmail').val(vendorEmail);
    $('#signingVendorPassword').val(vendorPassword);
    toastr.success($('#message-copied_success').data('text'), 'Success!', {
        CloseButton: true,
        ProgressBar: true
    });
});

$('.onerror-logo').on('error', function () {
    let image = $('#onerror-logo').data('onerror-logo');
    $(this).attr('src', image);
});
