"use strict";
function updateCartQuantityListCartData()
{
    $('.update-cart-quantity-list-cart-data').on('click', function () {
        let minOrder = $(this).data('min-order');
        let cart = $(this).data('cart');
        let value = $(this).data('value');
        let action = $(this).data('action');
        updateCartQuantityList(minOrder, cart, value, action);
    });
    $('.update-cart-quantity-list-cart-data-input').on('change', function () {
        let minOrder = $(this).data('min-order');
        let cart = $(this).data('cart');
        let value = $(this).data('value');
        let action = $(this).data('action');
        updateCartQuantityList(minOrder, cart, value, action);
    });
}

updateCartQuantityListCartData();

function updateCartQuantityListMobileCartData()
{
    $('.update-cart-quantity-list-mobile-cart-data').on('click change', function () {
        let minOrder = $(this).data('min-order');
        let cart = $(this).data('cart');
        let value = $(this).data('value');
        let action = $(this).data('action');
        updateCartQuantityListMobile(minOrder, cart, value, action);
    });
    $('.update-cart-quantity-list-mobile-cart-data-input').on('change', function () {
        let minOrder = $(this).data('min-order');
        let cart = $(this).data('cart');
        let value = $(this).data('value');
        let action = $(this).data('action');
        updateCartQuantityListMobile(minOrder, cart, value, action);
    });
}
updateCartQuantityListMobileCartData();

function updateCartQuantityList(minimum_order_qty, key, incr, e) {
    let quantity =parseInt($("#cartQuantityWeb" + key).val())+parseInt(incr);
    let ex_quantity = $("#cartQuantityWeb" + key);
    updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity);
}

function updateCartQuantityListMobile(minimum_order_qty, key, incr, e) {
    let quantity = parseInt($("#cartQuantityMobile" + key).val())+parseInt(incr);
    let ex_quantity = $("#cartQuantityMobile" + key);
    updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity);
}
function updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity) {

    console.log(minimum_order_qty)
    console.log(key)
    console.log(e)
    console.log(quantity)
    console.log(ex_quantity)
    console.log(ex_quantity.data('min'))
    console.log(ex_quantity.html())

    if (ex_quantity.val() > ex_quantity.data('current-stock') && e == 'minus') {
        removeProductFromCartList(key)
        return false;
    }

    if(minimum_order_qty > quantity && e !== 'delete' ) {
        toastr.error($('.minimum_order_quantity_msg').data('text')+' '+ minimum_order_qty);
        $(".cartQuantity" + key).val(minimum_order_qty);
        location.reload();
        return false;
    }
    if (parseInt(ex_quantity.val()) === parseInt(ex_quantity.data('min')) && e === 'delete') {
        removeProductFromCartList(key)
    }else{
        let updateQuantityBasicUrl = $('#update-quantity-basic-url').data('url');
        $.post(updateQuantityBasicUrl, {
            _token: $('meta[name="_token"]').attr('content'),
            key,
            quantity
        }, function (response) {
            if (response.status === 0) {
                toastr.error(response.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
                $(".cartQuantity" + key).val(response['qty']);
            } else {
                if (response['qty'] === ex_quantity.data('min')) {
                    ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
                } else {
                    ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
                }
                updateNavCart();
                $('#cart-summary').empty().html(response);
            }
            initTooltip();
            proceedToNextAction();
            setShippingIdFunction()
            updateCartQuantityListCartData();
            updateCartQuantityListMobileCartData();
            renderCouponCodeApply()
            multipleCheckBoxFunctionsInit()
        });
    }
}

function removeProductFromCartList(key) {
    let remove_from_cart_url = $('#remove_from_cart_url').data('url');
    $.post(remove_from_cart_url, {
            _token: $('meta[name="_token"]').attr('content'),
            key: key
        },
        function (response) {
            updateNavCart();
            toastr.info(response.message, {
                CloseButton: true,
                ProgressBar: true
            });
            let segment_array = window.location.pathname.split('/');
            let segment = segment_array[segment_array.length - 1];
            if (segment === 'checkout-payment' || segment === 'checkout-details') {
                location.reload();
            }
            $('#cart-summary').empty().html(response.data);
            initTooltip();
            proceedToNextAction();
            updateCartQuantityListCartData();
            setShippingIdFunction();
            updateCartQuantityListMobileCartData();
            renderCouponCodeApply()
            multipleCheckBoxFunctionsInit()
        }
    );
}

function setShippingIdFunction(){

    $('.set-shipping-onchange').on('change', function(){
        let Id = $(this).val();
        setShippingId(Id, 'all_cart_group');
    })
    $('.set-shipping-id').on('click', function(){
        let Id = $(this).data('id');
        let cartGroupId = $(this).data('cart-group');
        setShippingId(Id, cartGroupId);
    })
    function setShippingId(Id, cartGroupId) {
        $.get({
            url: $('#set-shipping-url').data('url'),
            dataType: 'json',
            data: {
                id: Id,
                cart_group_id: cartGroupId
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function () {
                location.reload();
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }
}
setShippingIdFunction();


