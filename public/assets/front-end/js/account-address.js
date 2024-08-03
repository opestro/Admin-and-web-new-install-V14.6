"use strict";

$(document).ready(function () {
    $('.address_type_li').on('click', function (e) {
        let addressTypeList = $('.address_type_li');
        addressTypeList.find('.address_type').removeAttr('checked', false);
        addressTypeList.find('.component').removeClass('active_address_type');
        $(this).find('.address_type').attr('checked', true);
        $(this).find('.address_type').removeClass('add_type');
        $('#defaultValue').removeClass('add_type');
        $(this).find('.address_type').addClass('add_type');
        $(this).find('.component').addClass('active_address_type');
    });
})

$('#addressUpdate').on('click', function (e) {
    e.preventDefault();
    let addressAs, address, name, zip, city, state, country, phone;
    addressAs = $('.add_type').val();
    address = $('#own_address').val();
    name = $('#person_name').val();
    zip = $('#zip_code').val();
    city = $('#city').val();
    state = $('#own_state').val();
    country = $('#own_country').val();
    phone = $('#own_phone').val();

    let id = $(this).attr('data-id');
    if (addressAs != '' && address != '' && name != '' && zip != '' && city != '' && state != '' && country != '' && phone != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: $('#route-address-update').data('url'),
            method: 'POST',
            data: {
                id: id,
                addressAs: addressAs,
                address: address,
                name: name,
                zip: zip,
                city: city,
                state: state,
                country: country,
                phone: phone
            },
            success: function () {
                toastr.success($('#message-update-successfully').data('text'));
                location.reload();
            }
        });
    } else {
        toastr.error($('#message-all-input-field-required').data('text'));
    }
});
