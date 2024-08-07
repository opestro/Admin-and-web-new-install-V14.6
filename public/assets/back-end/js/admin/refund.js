"use strict";

$(document).ready(function () {

    $('#approved').hide();
    $("#approved_note").prop("required", false);
    $('#rejected').hide();
    $("#rejected_note").prop("required", false);
    $('#payment_option').hide();
    $("#payment_method").prop("required", false);
    $('#refunded').hide();
    $("#payment_info").prop("required", false);
});

$("#refund_status_change").on('change', function () {
    let value = $(this).val();
    if (value === 'approved') {
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);
        $('#refunded').hide();
        $("#payment_info").prop("required", false);
        $('#payment_option').hide();
        $("#payment_method").prop("required", false);

        $('#approved').show();
        $("#approved_note").prop("required", true);

    } else if (value === 'rejected') {
        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#refunded').hide();
        $("#payment_info").prop("required", false);
        $('#payment_option').hide();
        $("#payment_method").prop("required", false);

        $('#rejected').show();
        $("#rejected_note").prop("required", true);

    } else if (value === 'refunded') {
        Swal.fire({
            title: $('#message-alert-title').data('text'),
            type: 'warning',
        });

        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);

        $('#refunded').show();
        $("#payment_info").prop("required", true);
        $('#payment_option').show();
        $("#payment_method").prop("required", true);
    } else {
        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);

        $('#refunded').hide();
        $("#payment_info").prop("required", false);
        $('#payment_option').hide();
        $("#payment_method").prop("required", false);
    }
});
