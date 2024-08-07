"use strict";

function updateCartQuantityList(minimum_order_qty, key, incr, e) {
    let quantity_id = 'cart_quantity_web';
    updateCartCommon(minimum_order_qty, key, incr, e, quantity_id);
}

function updateCartQuantityListMobile(minimum_order_qty, key, incr, e) {
    let quantityId = 'cart_quantity_mobile';
    updateCartCommon(minimum_order_qty, key, incr, e, quantityId);
}

function updateCartCommon(minimum_order_qty, key, incr, e, quantity_id) {

    let quantity = parseInt($("#" + quantity_id + key).val()) + parseInt(incr);
    let exQuantity = $("#" + quantity_id + key);

    if (exQuantity.val() > exQuantity.data('current-stock') && e == 'minus') {
        removeProductFromCartList(key)
        return false;
    }

    if (minimum_order_qty > quantity && e != 'delete') {
        toastr.error($('#message-minimum-order-quantity-cannot-less-than').data('text') + minimum_order_qty);
        $(".cartQuantity" + key).val(minimum_order_qty);
        return false;
    }
    if (exQuantity.val() == exQuantity.data('min') && e == 'delete') {
        removeProductFromCartList(key)
    } else {
        $.post($('#route-cart-updateQuantity').data('url'), {
            _token: $('meta[name="_token"]').attr('content'),
            key,
            quantity
        }, function (response) {
            if (response.status == 0) {
                toastr.error(response.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
                $(".cartQuantity" + key).val(response['qty']);
            } else {
                updateNavCart();
                $('#cart-summary').empty().html(response);
                $('[data-toggle="tooltip"]').tooltip()
                actionCheckoutFunctionInit()
                couponCode()
                setShippingIdFunctionCartDetails()
            }
        });
    }
}

function removeProductFromCartList(key) {
    $.post($('#route-cart-remove').data('url'), {
            _token: $('meta[name="_token"]').attr('content'),
            key: key
        },
        function (response) {
            updateNavCart();
            toastr.info($('#message-item-has-been-removed-from-cart').data('text'), {
                CloseButton: true,
                ProgressBar: true
            });
            let segmentArray = window.location.pathname.split('/');
            let segment = segmentArray[segmentArray.length - 1];
            if (segment === 'checkout-payment' || segment === 'checkout-details') {
                location.reload();
            }
            $('#cart-summary').empty().html(response.data)
            $('[data-toggle="tooltip"]').tooltip()
            actionCheckoutFunctionInit()
            couponCode()
            setShippingIdFunctionCartDetails();
        });
}

$('.qty_plus').on('click', function () {
    var $qty = $(this).parent().find('input');
    var currentVal = parseInt($qty.val());
    if (!isNaN(currentVal)) {
        $qty.val(currentVal + 1);
    }
    quantityListener();
});


$('.qty_minus').on('click', function () {
    var $qty = $(this).parent().find('input');
    var currentVal = parseInt($qty.val());
    if (!isNaN(currentVal) && currentVal > 1) {
        $qty.val(currentVal - 1);
    }
    quantityListener();
});


function quantityListener() {
    $('.qty_input').each(function () {
        var qty = $(this);
        var minimumOrderQuantity = $(this).data('minimum-order') ?? 1;
        var currentStockQuantity = $(this).data('current-stock') ?? 1000;
        if (qty.val() == 1 || qty.val() == minimumOrderQuantity ) {
            qty.siblings('.qty_minus').html('<i class="tio-delete text-danger fs-12"></i>')
        } else {
            qty.siblings('.qty_minus').html('<i class="tio-remove"></i>')
        }

        try {
            if (qty.val() > currentStockQuantity) {
                qty.siblings('.qty_minus').html('<i class="tio-delete text-danger fs-12"></i>')
            }
        }catch (e) {
        }

    });
}

quantityListener();

cartQuantityInitialize();


function setShippingId(id, cartGroupId) {
    $.get({
        url: $('#route-customer-set-shipping-method').data('url'),
        dataType: 'json',
        data: {
            id: id,
            cart_group_id: cartGroupId
        },
        beforeSend: function () {
            $('#loading').show();
        },
        success: function () {
            location.reload();
        },
        complete: function () {
            $('#loading').hide();
        },
    });
}

function setShippingIdFunctionCartDetails() {
    $('.setShippingIdFunctionCartDetails').on('click', function(){
        let id = $(this).data('id');
        let cartGroupId = $(this).data('cart-group');
        setShippingIdCartDetails(id, cartGroupId);
    })

    $('.set_shipping_onchange').on('change', function(){
        let id = $(this).val();
        setShippingIdCartDetails(id, 'all_cart_group');
    })

    function setShippingIdCartDetails(id, cart_group_id) {
        $.get({
            url: $('#route-set-shipping-id').data('url'),
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }
}

setShippingIdFunctionCartDetails();
