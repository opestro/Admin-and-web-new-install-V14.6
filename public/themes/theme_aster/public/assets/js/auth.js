"use strict";
$('#customer-verify').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        dataType: "json",
        data: $(this).serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status === 'success') {
                $('#otp_form_section').addClass('d-none');
                $('#success_message').removeClass('d-none');
                $('#loginModal').modal('show');
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});
$('#resend-otp').click(function () {
    $('input.otp-field').val('');
    let userId = $(this).data('field') === 'identity' ? $('input[name="identity"]').val(): $('input[name="id"]').val();
    let url = $(this).data('route') ;
    if ($(this).data('field') === 'identity') {
        sendAjaxRequest(url,{identity: userId });
    } else {
        sendAjaxRequest(url,{user_id: userId });
    }
});

function sendAjaxRequest(url,responseData)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: responseData,
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (parseInt(data.status) === 1) {
                let newCounter = $('.verifyCounter');
                let newSeconds = data.new_time;
                function newTick() {
                    let m = Math.floor(newSeconds / 60);
                    let s = newSeconds % 60;
                    newSeconds--;
                    newCounter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
                    if (newSeconds > 0) {
                        setTimeout(newTick, 1000);
                        $('.resend-otp-button').attr('disabled', true);
                        $(".resend-otp-custom").slideDown();
                    } else {
                        $('.resend-otp-button').removeAttr('disabled');
                        newCounter.html("0:00");
                        $(".resend-otp-custom").slideUp();
                    }
                }
                newTick();
                toastr.success($('#get-resend-otp-text').data('success'));
            } else {
                toastr.error($('#get-resend-otp-text').data('error'));
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}
