"use strict";

$(document).on('ready', function () {
    // INITIALIZATION OF SHOW PASSWORD
    // =======================================================
    $('.js-toggle-password').each(function () {
        new HSTogglePassword(this).init()
    });

    // INITIALIZATION OF FORM VALIDATION
    // =======================================================
    $('.js-validate').each(function () {
        $.HSCore.components.HSValidation.init($(this));
    });
});

$("#admin-login-form").on('submit', function (e) {
    var response = grecaptcha.getResponse();
    if (response.length === 0) {
        e.preventDefault();
        toastr.error($('#message-please-check-recaptcha').data('text'));
    }
})

$('.get-login-recaptcha-verify').on('click', function () {
    let role = $('#role').val();
    document.getElementById('default_recaptcha_id').src = $(this).data('link') + "/" + Math.random()+"?captcha_session_id=default_recaptcha_id_"+role+"_login";
});

$('#copyLoginInfo').on('click', function () {
    let adminEmail = $('#admin-email').data('email');
    let adminPassword = $('#admin-password').data('password');
    $('#signingAdminEmail').val(adminEmail);
    $('#signingAdminPassword').val(adminPassword);
    toastr.success($('#message-copied_success').data('text'), 'Success!', {
        CloseButton: true,
        ProgressBar: true
    });
});

$('.onerror-logo').on('error', function () {
    let image = $('#onerror-logo').data('onerror-logo');
    $(this).attr('src', image);
});
