'use strict';
$('#form-submit').on('submit', function (e) {
    if ($('#bonus-type').val() === "fixed") {
        let maximumAmount = parseFloat($('#min_add_money_amount').val());
        let bonusAmount = parseFloat($('#bonus_amount').val());
        if (maximumAmount < bonusAmount) {
            e.preventDefault();
            toastr.error($('#get-minimum-amount-message').data('error'));
        }
    }
});
$('#bonus-type').on('change', function () {
    let inputValue = $(this).val();
    let maxBonusAmountArea = $('#max-bonus-amount-area');
    let bonusTitlePercent = $('#bonus-title-percent');
    if (inputValue === "percentage") {
        maxBonusAmountArea.removeClass('d-none');
        bonusTitlePercent.text("%");
    } else {
        maxBonusAmountArea.addClass('d-none');
        bonusTitlePercent.text($('#get-currency-symbol').data('currency-symbol'));
    }
});
$('#start-date-time,#end-date-time').change(function () {
    let from = $('#start-date-time');
    let to = $('#end-date-time');
    if (from.val() !== '' && to.val() !== '') {
        if (from > to) {
            from.val('');
            to.val('');
            toastr.error($('#get-date-range-message').data('error'), Error, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    }
})

$(document).ready(function () {
    let inputValue = $('#bonus-type').val();
    let maxBonusAmountArea = $('#max-bonus-amount-area');
    let bonusTitlePercent = $('#bonus-title-percent');
    if (inputValue === "percentage") {
        maxBonusAmountArea.removeClass('d-none');
        bonusTitlePercent.text("%");
    } else {
        maxBonusAmountArea.addClass('d-none');
        bonusTitlePercent.text($('#get-currency-symbol').data('currency-symbol'));
    }
});
