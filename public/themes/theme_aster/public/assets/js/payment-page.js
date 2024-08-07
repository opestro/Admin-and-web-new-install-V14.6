"use strict";
$('#digital-payment-btn').on('click', function () {
    $('.digital-payment').slideToggle('slow');
});

$('#pay-offline-method').on('change', function () {
    payOfflineMethodField(this.value);
});
function payOfflineMethodField(methodId) {
    $.get($('.get-payment-method-list').data('action'), {method_id: methodId}, (response) => {
        $("#method-filed-div").html(response.methodHtml);
    })
}
