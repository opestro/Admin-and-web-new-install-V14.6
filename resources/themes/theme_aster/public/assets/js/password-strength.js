'use strict'
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
