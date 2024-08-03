"use strict";

$('.digital-product-download').on('click',function (){
    let action = $('.get-digital-product-download-url').data('action');
    $.ajax({
        type: "GET",
        url: action,
        responseType: 'blob',
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (parseInt(data.status) === 1 && data.file_path) {
                const a = document.createElement('a');
                a.href = data.file_path;
                a.download = data.file_name;
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(data.file_path);

            } else if (parseInt(data.status) === 2) {
                $('#order_details').modal('hide');
                $('#digital-product-order-otp-verify-modal .modal-body').empty().html(data.view);
                $('#digital-product-order-otp-verify-modal').modal('show');
                newTick(data.data.new_time);
                otpVerifyEvents();
            } else if (parseInt(data.status) === 0) {
                toastr.error(data.message);
                $('#digital-product-order-otp-verify-modal').modal('hide');
            }
        },
        error: function () {
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});
function otpVerifyEvents() {
    $(".otp-form .submit-btn").attr("disabled", true).addClass("disabled");
    $(".otp-form *:input[type!=hidden]:first").focus();
    let otp_fields = $(".otp-form .otp-field"),
        otp_value_field = $(".otp-form .otp-value");
    otp_fields.on("input", function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ""));
        let otp_value = "";
        otp_fields.each(function () {
            let field_value = $(this).val();
            if (field_value !== "") otp_value += field_value;
        });
        otp_value_field.val(otp_value);
        if (otp_value.length === 4) {
            $(".otp-form .submit-btn").attr("disabled", false).removeClass("disabled");
        } else {
            $(".otp-form .submit-btn").attr("disabled", true).addClass("disabled");
        }
    })
    .on("keyup", function (e) {
        let key = e.keyCode || e.charCode;
        if (key === 8 || key === 46 || key === 37 || key === 40) {
            $(this).prev().focus();
        } else if (key === 38 || key === 39 || $(this).val() !== "") {
            $(this).next().focus();
        }
    })
    .on("paste", function (e) {
        let paste_data = e.originalEvent.clipboardData.getData("text");
        let paste_data_splitted = paste_data.split("");
        $.each(paste_data_splitted, function (index, value) {
            otp_fields.eq(index).val(value);
        });
    });
}
$('#verify-otp').on('click',function (){
    let formData = $('.submit-digital-product-download-otp');
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
    $.ajax({
        type: "POST",
        url: formData.attr('action'),
        data: formData.serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status === 1) {
                $('.verify-message').addClass('text-success').removeClass('text-danger');
                if(data.file_path){
                    const a = document.createElement('a');
                    a.href = data.file_path;
                    a.download = data.file_name;
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(data.file_path);
                }
                $('#digital_product_order_otp_verify').modal('hide');
            }else{
                $('.verify-message').addClass('text-danger').removeClass('text-success');
            }
            $('.verify-message').html(data.message).fadeIn();
        },
        error: function (error) {
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});

$('#resend-otp').on('click',function (){
    $('input.otp-field').val('');
    $('.verify-message').fadeOut(300).empty();
    let formData = $('.submit-digital-product-download-otp');
    let action = $('#digital-product-download-otp-reset').data('route');
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
    $.ajax({
        url: action,
        method: 'POST',
        data: formData.serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status === 1) {
                newTick(data.data.new_time);
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

function newTick(newSeconds) {
    let newCounter = $('.verify-counter');
    let m = Math.floor(newSeconds / 60);
    let s = newSeconds % 60;
    newSeconds--;
    newCounter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
    if (newSeconds > 0) {
        setTimeout(newTick, 1000);
        $('.resend-otp-button').attr('disabled', true);
        $(".resend_otp_custom").slideDown();
    }
    else {
        $('.resend-otp-button').removeAttr('disabled');
        $(".verify-counter").html("0:00");
        $(".resend_otp_custom").slideUp();
    }
}
