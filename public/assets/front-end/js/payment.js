"use strict";

setTimeout(function () {
    $('.stripe-button-el').hide();
    $('.razorpay-payment-button').hide();
}, 10)
$(function() {
    $('.proceed_to_next_button').addClass('disabled');
});
const radioButtons = document.querySelectorAll('input[type="radio"]');
radioButtons.forEach(radioButton => {
    radioButton.addEventListener('change', function() {
        if (this.checked) {
            $('.proceed_to_next_button').removeClass('disabled');

            radioButtons.forEach(otherRadioButton => {
                if (otherRadioButton !== this) {
                    otherRadioButton.checked = false;
                }
            });
            this.setAttribute('checked', 'true');
            const field_id = this.id;
            if(field_id == "pay_offline"){
                $('.pay_offline_card').removeClass('d-none')
                $('.proceed_to_next_button').addClass('disabled');

            }else{
                $('.pay_offline_card').addClass('d-none');
                $('.proceed_to_next_button').removeClass('disabled');

            }
        }else{
        }
    });
});

function checkoutFromPayment(){
    let checked_button_id = $('input[type="radio"]:checked').attr('id');
    $('#' + checked_button_id + '_form').submit();
}

const buttons = document.querySelectorAll('.offline_payment_button');
const selectElement = document.getElementById('pay_offline_method');
buttons.forEach(button => {
    button.addEventListener('click', function() {
        const buttonId = this.id;
        pay_offline_method_field(buttonId);
        selectElement.value = buttonId;
    });
});

$('#pay_offline_method').on('change', function () {
    pay_offline_method_field(this.value);
});
function pay_offline_method_field(method_id){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: $('#route-pay-offline-method-list').data('url') + "?method_id=" + method_id,
        data: {},
        processData: false,
        contentType: false,
        type: 'get',
        success: function (response) {
            $("#payment_method_field").html(response.methodHtml);
            $('#selectPaymentMethod').modal().show();
        },
        error: function () {
        }
    });
}
