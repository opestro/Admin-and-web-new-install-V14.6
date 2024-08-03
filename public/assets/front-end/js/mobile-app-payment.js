"use strict";

setInterval(function () {
    $('.stripe-button-el').hide()
}, 10)

setTimeout(function () {
    $('.stripe-button-el').hide();
    $('.razorpay-payment-button').hide();
}, 10)

function click_if_alone() {
    let messageRedirectingPaymentPage = $('#message-redirecting-payment-page').data('text');
    let total = $('.checkout_details .click-if-alone').length;
    if (Number.parseInt(total) < 2) {
        $('.click-if-alone').click()
        $('.checkout_details').html(`<h1>`+ messageRedirectingPaymentPage + `......</h1>`);
    }
}

click_if_alone();
