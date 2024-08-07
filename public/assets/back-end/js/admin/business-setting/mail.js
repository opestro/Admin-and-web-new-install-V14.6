'use strict';

function ValidateEmail(inputText) {
    let mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    return inputText.match(mailFormat);
}

$('#text-mail-send').on('click',function(){
    const sendMailModal = $('#send-mail-confirmation-modal');
    sendMailModal.modal('hide');
    const sendMail = $('#get-send-mail-route-text');
    if (ValidateEmail($("#test-email").val())) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: sendMail.data('action'),
            method: 'POST',
            data: {
                "email": $('#test-email').val()
            },
            success: function (data) {
                if (data.success === 2) {
                    toastr.error(sendMail.data('error-text'));
                } else if (data.success === 1) {
                    toastr.success(sendMail.data('success-text'));
                } else {
                    toastr.info(sendMail.data('info-text'));
                }
                $('#send-mail-confirmation-modal').modal('hide');
            },
        });
    } else {
        toastr.error(sendMail.data('invalid-text'));
        sendMailModal.modal('hide');
    }
})
