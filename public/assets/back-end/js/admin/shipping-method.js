'use strict';
$(document).ready(function () {
    let shippingTypeValue = $('#get-shipping-type-value').data('value');
    shippingType(shippingTypeValue);
});
function shippingType(shippingTypeValue){
    if (shippingTypeValue === 'category_wise') {
        $('#product_wise_note').hide();
        $('#order_wise_shipping').hide();
        $('#update_category_shipping_cost').show();

    } else if (shippingTypeValue === 'order_wise') {
        $('#product_wise_note').hide();
        $('#update_category_shipping_cost').hide();
        $('#order_wise_shipping').show();
    } else {
        $('#update_category_shipping_cost').hide();
        $('#order_wise_shipping').hide();
        $('#product_wise_note').show();
    }
}
$('.shipping-type').on('change',function (){
    let shippingTypeValue = $(this).val();
    shippingType(shippingTypeValue);
    let shippingTypeData = $('#get-shipping-type-data');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: shippingTypeData.data('action'),
        method: 'POST',
        data: {
            shippingType: shippingTypeValue
        },
        success: function () {
            toastr.success(shippingTypeData.data('success'));
        }
    });
})
