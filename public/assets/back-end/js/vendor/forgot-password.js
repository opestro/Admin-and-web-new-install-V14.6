"use strict";
$(".js-example-responsive").select2({
    width: 'resolve'
});
$(document).on('ready', function () {
    $('.js-toggle-password').each(function () {
        new HSTogglePassword(this).init()
    });
    $('.js-validate').each(function () {
        $.HSCore.components.HSValidation.init($(this));
    });
});
$('.forget-password-form').on('click',function (){
    $.ajaxSetup({
        headers: {
            'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post({
        url: $(this).closest('form').attr('action'),
        data: $(this).closest('form').serialize(),
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
            }
            else if(data.verificationBy === 'mail'){
                $('.password-reset-successfully-modal').modal('show');
                $('.forget-password-form').attr('disabled',true);
            }else if(data.verificationBy === 'phone' || data.passwordUpdate){
                location.href=data.redirectRoute;
                toastr.success(data.success)
            }
        },complete: function () {
            $('#loading').fadeOut();
        },
    })
})
var backgroundImage = $("[data-bg-img]");
backgroundImage.css("background-image", function () {
    return 'url("' + $(this).data("bg-img") + '")';
}).removeAttr("data-bg-img").addClass("bg-img");
$('.password-check').on('keyup keypress change click', function () {
    let password = $(this).val();
    let passwordError = $('.password-error');
    let passwordErrorMessage = $('#password-error-message');
    switch (true) {
        case password.length < 8:
            passwordError.html(passwordErrorMessage.data('max-character')).removeClass('d-none');
            break;
        case !(/[a-z]/.test(password)):
            passwordError.html(passwordErrorMessage.data('lowercase-character')).removeClass('d-none');
            break;
        case !(/[A-Z]/.test(password)):
            passwordError.html(passwordErrorMessage.data('uppercase-character')).removeClass('d-none');
            break;
        case !(/\d/.test(password)):
            passwordError.html(passwordErrorMessage.data('number')).removeClass('d-none');
            break;
        case !(/[@.#$!%*?&]/.test(password)):
            passwordError.html(passwordErrorMessage.data('symbol')).removeClass('d-none');
            break;
        default:
            passwordError.addClass('d-none').empty();
    }
});
