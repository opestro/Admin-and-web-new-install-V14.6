'use strict';
$(document).ready(function() {
    $('.proceed-to-next-btn').click(function() {
        let email = $('#email').val();
        let phone = $('.phone-input-with-country-picker').val();
        let password = $('#password').val();
        let confirmPassword = $('#confirm_password').val();
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let getErrorMessages = $('#proceed-to-next-validation-message');
        if (email === '') {
            $('.mail-error').html(getErrorMessages.data('mail-error'));
            return;
        } else {
            $('.mail-error').html('');
        }
        if (!emailPattern.test(email)) {
            $('.mail-error').html(getErrorMessages.data('valid-mail'));
            return;
        } else {
            $('.mail-error').html('');
        }
        if (phone === '') {
            $('.phone-error').html(getErrorMessages.data('phone-error'));
            return;
        } else {
            $('.phone-error').html('');
        }
        if (password === '') {
            $('.password-error').html(getErrorMessages.data('enter-password'));
            return;
        } else {
            $('.password-error').html('');
        }
        if (confirmPassword === '') {
            $('.confirm-password-error').html(getErrorMessages.data('enter-confirm-password'));
            return;
        } else {
            $('.confirm-password-error').html('');
        }
        if (password.trim() !== confirmPassword.trim()) {
            $('.confirm-password-error').html(getErrorMessages.data('password-not-match'));
            return;
        } else {
            $('.confirm-password-error').html('');
        }
        $('.first-el').fadeOut(300);
        $('.second-el').fadeIn(300);
    });
});
$('.back-to-main-page').on('click',function (){
    $('.first-el').fadeIn(300);
    $('.second-el').fadeOut(300);
});

function submitRegistration(){
    let getText = $('#get-confirm-and-cancel-button-text');
    const getFormId =  'seller-registration';
    Swal.fire({
        title: getText.data('sure'),
        text:  getText.data('message'),
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: getText.data('cancel'),
        confirmButtonText: getText.data('confirm'),
        reverseButtons: true
    }).then((result) => {
        if (result.value) {

            let formData = new FormData(document.getElementById(getFormId));
            $.ajaxSetup({
                headers: {
                    'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: $('#'+getFormId).attr('action'),
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#loading").addClass("d-grid");
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
                    }else {
                        $('.registration-success-modal').modal('show');
                        setTimeout(function () {
                            location.href = data.redirectRoute;
                        }, 4000);
                    }
                },complete: function () {
                    $("#loading").removeClass("d-grid");
                },
            })
        }
    })
}
$('#terms-checkbox').on('click',function (){
    if($(this).is(':checked')){
        $('#vendor-apply-submit').removeClass('disabled')
    }else{
        $('#vendor-apply-submit').addClass('disabled')
    }
})
