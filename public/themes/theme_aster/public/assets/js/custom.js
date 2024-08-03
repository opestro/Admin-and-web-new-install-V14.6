'use strict';
toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop: false,
    progressBar: false,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

$('.currency-change').on('click',function (){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#currency-route").data("currency-route"),
        data: {
            currency_code: $(this).data("currency-code"),
        },
        success: function (data) {
            toastr.success(data.message);
            location.reload();
        },
    });
})
$('.change-language').on('click',function (){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $(this).data('action'),
        data: {
            language_code: $(this).data('language-code'),
        },
        success: function (data) {
            toastr.success(data.message);
            location.reload();
        },
    });
})

$('#global-search').on('keyup', function() {
    $(".search-card").css("display", "block");
    const name = $(".search-bar-input").val();
    const category_id = $("#search_category_value").val();
    const base_url = $('meta[name="base-url"]').attr("content");
    if (name.length > 0) {
        $.get({
            url: base_url + "/searched-products",
            dataType: "json",
            data: {
                name,
                category_id,
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                $(".search-result-box").show().empty().html(data.result);
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    } else {
        $(".search-result-box").empty();
    }
});

$(".search-bar-input-mobile").keyup(function () {
    $(".search-card").css("display", "block");
    const name = $(".search-bar-input-mobile").val();
    const base_url = $('meta[name="base-url"]').attr("content");
    if (name.length > 0) {
        $.get({
            url: base_url + "/searched-products",
            dataType: "json",
            data: {
                name,
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                $(".search-result-box").empty().html(data.result);
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    } else {
        $(".search-result-box").empty();
    }
});
function cartQuantityInitialize() {
    $(".btn-number").click(function (e) {
        e.preventDefault();

        const fieldName = $(this).attr("data-field");
        const type = $(this).attr("data-type");
        const productType = $(this).attr("product-type");
        let input = $("input[name='" + fieldName + "']");
        let currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (type === "minus") {
                if (currentVal > input.attr("min")) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) === parseInt(input.attr("min"))) {
                    $(this).attr("disabled", true);
                }
            } else if (type === "plus") {
                if (
                    currentVal < input.attr("max") ||
                    productType === "digital"
                ) {
                    input.val(currentVal + 1).change();
                }

                if (
                    parseInt(input.val()) === parseInt(input.attr("max")) &&
                    productType === "physical"
                ) {
                    $(this).attr("disabled", true);
                }
            }
        } else {
            input.val(0);
        }
    });
    const inputNumber = $(".input-number");
    inputNumber.focusin(function () {
        $(this).data("oldValue", $(this).val());
    });

    inputNumber.change(function () {
        const productType = $(this).attr("product-type");
        const minValue = parseInt($(this).attr("min"));
        const maxValue = parseInt($(this).attr("max"));
        const valueCurrent = parseInt($(this).val());

        const name = $(this).attr("name");
        if (valueCurrent >= minValue) {
            $(
                ".btn-number[data-type='minus'][data-field='" + name + "']"
            ).removeAttr("disabled");
        } else {
            Swal.fire({
                icon: "error",
                title: "Cart",
                text: "Sorry, the minimum order quantity does not match",
            });
            $(this).val($(this).data("oldValue"));
        }
        if (productType === "digital" || valueCurrent <= maxValue) {
            $(
                ".btn-number[data-type='plus'][data-field='" + name + "']"
            ).removeAttr("disabled");
        } else {
            Swal.fire({
                icon: "error",
                title: "Cart",
                text: "Sorry, stock limit exceeded.",
            });
            $(this).val($(this).data("oldValue"));
        }
    });
    inputNumber.keydown(function (e) {
        if (
            $.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)
        ) {
            return;
        }
        if (
            (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
            (e.keyCode < 96 || e.keyCode > 105)
        ) {
            e.preventDefault();
        }
    });
}
$(window).on('load',function (){
    $('.get-quick-view').on('click',function (){
        let productId = $(this).data('product-id')
        $.get({
            url: $(this).data('action'),
            dataType: "json",
            data: {
                product_id: productId,
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                $("#quickViewModal_content").empty().html(data.view);
                $("#quickViewModal").modal("show");
                quickViewDefaultFunctionality();
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    });
})

function focusPreviewImageByColor(){
    $('.focus-preview-image-by-color').on('click',function (){
        let slideId = $(this).data('slide-id');
        let swiper_slide = new Swiper(".quickviewSlider2 .swiper-wrapper", {
            spaceBetween: 0,
        });
        let slides = swiper_slide.$el.children();
        let slideIndex = -1;
        slides.each(function (index, slide) {
            if (index.getAttribute("id") === slideId) {
                slideIndex = slide;
                return false;
            }
        });
        if (slideIndex !== -1) {
            swiper_slide = new Swiper(".quickviewSlider2", { spaceBetween: 0 });
            swiper_slide.slideTo(slideIndex, 200, false);
        }

        $(".color_variants").removeClass("color_variant_active");
        $(`#color_variants_${slideId}`).addClass("color_variant_active");
    })
}
focusPreviewImageByColor();

$(".easyzoom").each(function () {
    $(this).easyZoom();
});

$('.slider-thumb-img-preview').on('click',function (){
    let thumbKey = $(this).data('thumb-key');
    let mySwiper = new Swiper(".quickviewSlider2", {
        pagination: {
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    let targetSlide = $(`.${thumbKey}`);
    let slideIndex = targetSlide.index();
    mySwiper.slideToLoop(slideIndex, 300, false);
})
function addToWishlist(){

    $('.add-to-wishlist').on('click',function (){
        let productId = $(this).data('product-id')
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });

        $.ajax({
            url: $(this).data('action'),
            method: "POST",
            data: {
                product_id:productId,
            },
            success: function (data) {
                let wishlistCountStatus= $(".wishlist_count_status");
                if (data.value === 1) {
                    toastr.success(data.success);
                    $(`.wishlist-${productId}`).addClass("wishlist_icon_active");
                    wishlistCountStatus.html(
                        parseInt(wishlistCountStatus.html()) + 1
                    );
                } else if (data.value === 2) {
                    $(`.wishlist-${productId}`).removeClass(
                        "wishlist_icon_active"
                    );
                    wishlistCountStatus.html(
                        parseInt(wishlistCountStatus.html()) - 1
                    );
                    toastr.success(data.error);
                } else {
                    toastr.error(data.error);
                    $("#quickViewModal").modal("hide");
                    $("#loginModal").modal("show");
                }
            },
        });
    })
}
function addToCompare(){
    $('.add-to-compare').on('click',function (){
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        let productId = $(this).data('product-id');
        $.ajax({
            url: $(this).data('action'),
            method: "POST",
            data: {
                product_id:productId
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                if (data.value === 1) {
                    toastr.success(data.success);
                    $(`.compare_list_icon_active`)
                        .removeClass("compare_list_icon_active")
                        .blur();
                    $(".compare_list_count_status").html(data.count);

                    $.each(data.compare_product_ids, function (key, id) {
                        $(`.compare_list-${id}`)
                            .addClass("compare_list_icon_active")
                            .blur();
                    });
                } else if (data.value === 2) {
                    $(`.compare_list_icon_active`)
                        .removeClass("compare_list_icon_active")
                        .blur();
                    $.each(data.compare_product_ids, function (key, id) {
                        $(`.compare_list-${id}`)
                            .addClass("compare_list_icon_active")
                            .blur();
                    });
                    $(".compare_list_count_status").html(data.count);
                    toastr.success(data.error);
                } else {
                    toastr.error(data.error);
                    $("#quickViewModal").modal("hide");
                    $("#loginModal").modal("show");
                }
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    });
}
function addCompareList(product_id, action_url) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });

    $.ajax({
        url: action_url,
        method: "POST",
        data: {
            product_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (parseInt(data.value) === 1) {
                toastr.success(data.success);
                $(`.compare_list_icon_active`)
                    .removeClass("compare_list_icon_active")
                    .focusout();
                $(".compare_list_count_status").html(data.count);

                $.each(data.compare_product_ids, function (key, id) {
                    $(`.compare_list-${id}`)
                        .addClass("compare_list_icon_active")
                        .focusout();
                });
            } else if (parseInt(data.value) === 2) {
                $(`.compare_list_icon_active`)
                    .removeClass("compare_list_icon_active")
                    .focusout();
                $.each(data.compare_product_ids, function (key, id) {
                    $(`.compare_list-${id}`)
                        .addClass("compare_list_icon_active")
                        .focusout();
                });
                $(".compare_list_count_status").html(data.count);
                toastr.success(data.error);
            } else {
                toastr.error(data.error);
                $("#quickViewModal").modal("hide");
                $("#loginModal").modal("show");
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}
function shareOnSocialMedia(){
    $('.share-on-social-media').on('click',function (){
        let social = $(this).data('social-media-name');
        let url = $(this).data('action');
        let width = 600,
            height = 400,
            left = (screen.width - width) / 2,
            top = (screen.height - height) / 2;
        window.open(
            "https://" + social + encodeURIComponent(url),
            "Popup",
            "toolbar=0,status=0,width=" +
            width +
            ",height=" +
            height +
            ",left=" +
            left +
            ",top=" +
            top
        );
    })
}
shareOnSocialMedia();

function checkAddToCartValidity(form_id) {
    let names = {};
    $("." + form_id + " input:radio").each(function () {
        names[$(this).attr("name")] = true;
    });
    let count = 0;
    $.each(names, function () {
        count++;
    });
    return parseInt($("." + form_id + " input:radio:checked").length) === count;
}
function buyNow(){
    $('.buy-now').on('click',function (){
        let formId = $(this).data('form-id');
        let redirectStatus = $(this).data('redirect-status');
        let url = $(this).data('action');
        addToCart(formId, redirectStatus, url);
        if (redirectStatus === false) {
            $("#quickViewModal").modal("hide");
            $("#loginModal").modal("show");
        }
    })
}
buyNow();
$("#add-to-cart-form input").on("change", function () {
    stock_check();
});

function stock_check() {
    const productQty = $(".product_quantity__qty");
    const minValue = parseInt(productQty.attr("min"));
    const maxValue = parseInt(productQty.attr("max"));
    const valueCurrent = parseInt(productQty.val());

    if (minValue >= valueCurrent) {
        productQty.val(minValue);
        try {
            if (productQty.data('details-page')) {
                productQty.parent().find(".quantity__minus").html('<i class="bi bi-dash"></i>');
            }else{
                productQty.parent().find(".quantity__minus").html('<i class="bi bi-trash3-fill text-danger fs-10"></i>');
            }
        }catch (e) {
            productQty.parent().find(".quantity__minus").html('<i class="bi bi-trash3-fill text-danger fs-10"></i>');
        }
    } else {
        productQty
            .parent()
            .find(".quantity__minus")
            .html('<i class="bi bi-dash"></i>');
    }

    if (valueCurrent > maxValue) {
        toastr.warning("Sorry, stock limit exceeded");
        productQty.val(maxValue);
    }
    getVariantPrice();
}

/* Increase */
$(".quantity__plus").on("click", function () {
    if($(this).data('prevent') !== true){
        let $qty = $(this).parent().find("input");
        let currentVal = parseInt($qty.val());
        if (!isNaN(currentVal)) {
            $qty.val(currentVal + 1);
        }
        if (currentVal >= $qty.attr("max") - 1) {
            $(this).attr("disabled", true);
        }
    }
    stock_check();
});
/* Decrease */
$(".quantity__minus").on("click", function () {
    if($(this).data('prevent') !== true){
        let $qty = $(this).parent().find("input");
        let currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            $qty.val(currentVal - 1);
        }
        if (currentVal < $qty.attr("max")) {
            $(".quantity__plus").removeAttr("disabled", true);
        }
    }
    stock_check();
});
$("#add-to-cart-form").on("submit", function (e) {
    e.preventDefault();
});
function quickViewDefaultFunctionality()
{
    addToCartOnclick();
}
quickViewDefaultFunctionality();
function addToCartOnclick()
{
    $('.add-to-cart').on('click',function (){
        addToCart($(this).data('form-id'))

    });
}
function addToCart(form_id, redirect_to_checkout = false, url = null) {
    let getQuantity = $("#"+form_id+"input[name=quantity]");
    if (
        checkAddToCartValidity(form_id) &&
        getQuantity.val() !== 0
    ) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });

        let existCartItem = $('.in_cart_key[name="key"]').val();
        let formActionUrl = $(`#` + form_id).attr("action");
        if (existCartItem !== '' && !(redirect_to_checkout)) {
            formActionUrl = $('#update_quantity_url').data('url');
        }

        $.post({
            url: formActionUrl,
            data: $("#" + form_id).serializeArray().concat({name: 'buy_now', value: (redirect_to_checkout ? 1 : 0)}),
            beforeSend: function () {},
            success: function (response) {

                console.log(response);
                if (response.status === 2) {
                    $("#buyNowModal-body").html(response.shippingMethodHtmlView);
                    $("#quickViewModal").modal("hide");
                    $("#buyNowModal").modal("show");
                    return false;
                }

                if (response.status === 1) {
                    updateNavCart();
                    toastr.success(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 3000,
                    });

                    if (redirect_to_checkout == true && response.redirect_to_url) {
                        setTimeout(function () {
                            location.href = response.redirect_to_url;
                        }, 100);
                    } else if (redirect_to_checkout === true) {
                        setTimeout(function () {
                            location.href = url;
                        }, 100);
                    }
                    $("#quickViewModal").modal("hide");
                    return false;
                } else if (response.status === 0) {
                    toastr.warning(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000,
                    });
                    return false;
                }
            },
            complete: function () {},
        });
    } else if (parseInt(getQuantity.val()) === 0) {
        toastr.warning($(`#` + form_id).data("outofstock"), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000,
        });
    } else {
        toastr.info($(`#`+form_id).data("errormessage"), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000,
        });
    }
}

function updateNavCart() {
    let url = $("#update_nav_cart_url").data("url");
    $.post(
        url,
        {
            _token: $('meta[name="_token"]').attr("content"),
        },
        function (response) {
            $("#cart_items").html(response.data);
            $("#mobile_app_bar").html(response.mobile_nav);
            updateCart();
        }
    );
}

function removeFromCart(key) {
    let cart_quantity_of = $(`#cart_quantity_of_${key}`).val();
    let url = $("#remove_from_cart_url").data("url");
    if (cart_quantity_of === 1) {
        $.post(
            url,
            {
                _token: $('meta[name="_token"]').attr("content"),
                key: key,
            },
            function (response) {
                updateNavCart();
                toastr.info(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                });
                let segment_array = window.location.pathname.split("/");
                let segment = segment_array[segment_array.length - 1];
                if (
                    segment === "checkout-payment" ||
                    segment === "checkout-details"
                ) {
                    location.reload();
                }
            }
        );
    } else {
        let $qty = $(this).parent().find("input");
        let currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            $qty.val(currentVal - 1);
        }
        if (currentVal < $qty.attr("max")) {
            $(".quantity__plus").removeAttr("disabled", true);
        }
        let qty = $(this);
        if (qty.val() === 1) {
            qty.siblings(".quantity__minus").html(
                '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
            );
        } else {
            qty.siblings(".quantity__minus").html('<i class="bi bi-dash"></i>');
        }
    }
}
function updateCart(){
    $('.cart-quantity-update').on('click',function (){
        let cartId = $(this).data('cart-id');
        let productId = $(this).data('product-id');
        let value = $(this).data('value')
        let event = $(this).data('event')
        updateCartQuantity(cartId,productId,value,event)
    });
    $('.cart-quantity-update-input').on('change',function (){
        let cartId = $(this).data('cart-id');
        let productId = $(this).data('product-id');
        let value = $(this).data('value')
        updateCartQuantity(cartId,productId,value)
    });
}
updateCart();
function updateCartQuantity(cartId, productId, action, event) {
    let removeUrl = $("#remove_from_cart_url").data("url");
    let updateQuantityQrl = $("#update_quantity_url").data("url");
    let token = $('meta[name="_token"]').attr("content");
    let cartQuantity = $(`.cart_quantity_of_${cartId}`);
    let productQyt = parseInt(cartQuantity.val())+parseInt(action);
    let segmentArray = window.location.pathname.split("/");
    let segment = segmentArray[segmentArray.length - 1];

    if (cartQuantity.val() > cartQuantity.data('current-stock')) {
        cartItemRemoveFunction(removeUrl, token, cartId, segment)
        return false
    }

    if ((parseInt(cartQuantity.val()) === parseInt(cartQuantity.data("min")) && event === 'minus')) {
        cartItemRemoveFunction(removeUrl, token, cartId, segment)
    } else {
        if (cartQuantity.val() < cartQuantity.data("min")) {
            let minValue = cartQuantity.data("min");
            toastr.error(
                "Minimum order quantity cannot be less than" + minValue
            );
            cartQuantity.val(minValue);
            updateCartQuantity(cartId, productId, action, event);
        } else {
            $(`.cart_quantity_${cartId}`).html(productQyt);
            cartQuantity.empty().val(productQyt);
            $.post(
                updateQuantityQrl,
                {
                    _token: token,
                    key: cartId,
                    product_id: productId,
                    quantity: productQyt,
                },
                function (response) {
                    if (response["status"] === 0) {
                        toastr.error(response["message"]);
                    } else {
                        toastr.success(response["message"]);
                    }
                    let cartQuantityMinus =  $(`.cart_quantity__minus${cartId}`);
                    parseInt(response["qty"]) <= 1  ?
                        cartQuantityMinus.html(
                              '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
                          )
                        : cartQuantityMinus.html(
                              '<i class="bi bi-dash"></i>'
                          );

                    cartQuantityMinus.val(response["qty"]);
                    $(`.cart_quantity_${cartId}`).html(response["qty"]);
                    $(".cart_total_amount").html(response.total_price);
                    $(`.discount_price_of_${cartId}`).html(
                        response["discount_price"]
                    );
                    $(`.quantity_price_of_${cartId}`).html(
                        response["quantity_price"]
                    );
                    if (parseInt(response["qty"]) === cartQuantity.data("min")) {
                        cartQuantity
                            .parent()
                            .find(".quantity__minus")
                            .html(
                                '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
                            );
                    } else {
                        cartQuantity
                            .parent()
                            .find(".quantity__minus")
                            .html('<i class="bi bi-dash"></i>');
                    }
                    if (
                        segment === "shop-cart" ||
                        segment === "checkout-payment" ||
                        segment === "checkout-details"
                    ) {
                        location.reload();
                    }
                }
            );
        }
    }
}

function cartItemRemoveFunction(removeUrl, token, cartId, segment) {
    $.post(
        removeUrl,
        {
            _token: token,
            key: cartId,
        },
        function (response) {
            updateNavCart();
            toastr.info(response.message, {
                CloseButton: true,
                ProgressBar: true,
            });
            if (
                segment === "shop-cart" ||
                segment === "checkout-payment" ||
                segment === "checkout-details"
            ) {
                location.reload();
            }
        }
    );
}

function getVariantPrice() {
    let addToCartForm = $("#add-to-cart-form");
    if (
        $("#add-to-cart-form input[name=quantity]").val() > 0 &&
        checkAddToCartValidity("add-to-cart-form")
    ) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });

        let qty_val = $(".product_quantity__qty").val();
        $.ajax({
            type: "POST",
            url: addToCartForm.data("varianturl"),
            data: addToCartForm.serializeArray(),
            success: function (data) {
                $('.product-generated-variation-code').val(data.variation_code);
                $("#add-to-cart-form .total_price").html(data.price);
                $(".product_vat").html(data.update_tax);
                $(".in_stock_status").html(data.quantity);

                if (data.quantity > qty_val) {
                    $(".single-quantity-plus").removeAttr("disabled", true);
                    $(".single-quantity-minus").removeAttr("disabled", true);
                    $(".product_quantity__qty").attr("max", data.quantity);
                } else {
                    if (data.quantity < qty_val) {
                        $(".single-quantity-plus").attr("disabled", true);
                        $(".single-quantity-minus").attr("disabled", true);
                    } else if (data.quantity <= 0) {
                        const productQty = $(".product_quantity__qty");
                        productQty.val(
                            parseInt(productQty.attr("min"))
                        );
                    } else {
                        $(".product_quantity__qty").attr("max", data.quantity);
                    }
                }

                $('.discounted_unit_price').html(data.discounted_unit_price)
                $('.total_unit_price').html(data.discount_amount > 0 ? data.total_unit_price : '')

                let actionAddToCartBtn = $('.add-to-cart');
                if (data.in_cart_status === 1) {
                    $('.in_cart_key[name="key"]').val(data.in_cart_key);
                    actionAddToCartBtn.html(actionAddToCartBtn.data('update-text'))
                }else {
                    $('.in_cart_key[name="key"]').val(data.in_cart_key);
                    actionAddToCartBtn.html(actionAddToCartBtn.data('add-text'))
                }
            },
        });
    }
}

$("#contact_with_seller_form").on("submit", function (e) {
    e.preventDefault();
    let messages_form = $(this);
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });

    $.ajax({
        type: "post",
        url: messages_form.attr("action"),
        data: messages_form.serialize(),
        success: function () {
            const contactWithSellerFrom = $("#contact_with_seller_form");
            toastr.success(
                contactWithSellerFrom.data("success-message"),
                {
                    CloseButton: true,
                    ProgressBar: true,
                }
            );
            contactWithSellerFrom.trigger("reset");
            $("#contact_sellerModal").modal("hide");
        },
    });
});

let loadReviewCount = 1;
let checkAllReviewData = 0;
let seeMoreReviewInProductDetails = 0;

function loadReview() {
    let seeMoreDetails = $(".see-more-details-review");
    let productId = seeMoreDetails.data("product-id");
    let onerror = seeMoreDetails.data("onerror");
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "post",
        url: seeMoreDetails.data("action"),
        data: {
            product_id: productId,
            offset: loadReviewCount,
        },
        success: function (data) {
            $("#product-review-list").append(data.productReview);
            renderCustomImagePopup()
            if (data.not_empty === 0 && checkAllReviewData === 0) {
                checkAllReviewData = 1;
                toastr.info(onerror, {
                    CloseButton: true,
                    ProgressBar: true,
                });
            }

            if (data.checkReviews === 0) {
                if (data.not_empty === 0 && !seeMoreDetails.parent().siblings(".details-content-wrap").hasClass("custom-height")) {
                    seeMoreDetails.html($("#all-msg-container").data("afterextend"));
                    seeMoreDetails.addClass("view_checked");
                    $(".show-more--content").removeClass("active");
                    seeMoreReviewInProductDetails = 2;
                }
                // else {
                //     seeMoreDetails.html(
                //         $("#all-msg-container").data("seemore")
                //     );
                //     $(".show-more--content").addClass("active");
                // }
            }
        },
    });
    loadReviewCount++;
}

$('#see-more').on('click',function (){
    let seeMoreDetails = $(".see-more-details-review");
    if(seeMoreReviewInProductDetails === 0 || seeMoreReviewInProductDetails === 2) {
        seeMoreReviewInProductDetails = seeMoreReviewInProductDetails === 0 ? 1 : 2;
        let showMoreContent = $(".show-more--content");
        if(seeMoreDetails.parent().siblings(".details-content-wrap").hasClass("custom-height")) {
            seeMoreDetails.parent().siblings(".details-content-wrap").removeClass("custom-height");
            showMoreContent.removeClass("active");
            seeMoreReviewInProductDetails === 2 ? seeMoreDetails.html($("#all-msg-container").data("afterextend")) : console.log(seeMoreReviewInProductDetails);
        }else {
            seeMoreDetails.parent().siblings(".details-content-wrap").addClass("custom-height");
            seeMoreReviewInProductDetails === 2 ? seeMoreDetails.html($("#all-msg-container").data("seemore")) : console.log(seeMoreReviewInProductDetails);
        }
    }else {
        loadReview()
    }
})

$(".product-view-sort-by").on("click", "ul li", function () {

    let value = $(this).data("value");
    let text = $(this).text();
    $(".product-view-sort-by ul li").removeClass("selected");
    $(this).addClass("selected");
    $(".product-view-sort-by button").html(text);
    sortByfilterBy(value);
});
$('.sort-filter-by').on('click',function (){
    sortByfilterBy();
})
function sortByfilterBy(value = null, ratings = null) {


    let sort_by_value = value;
    if (value === null) {
        sort_by_value = $("#sort-by-list li.selected").first().data("value");
    }
    $.get({
        url: $("#filter-url").data("url"),
        data: {
            id: $("#data_id").val(),
            name: $("#data_name").val(),
            data_from: $("#data_from").val(),
            min_price: $("#price_rangeMin").val(),
            max_price: $("#price_rangeMax").val(),
            sort_by: sort_by_value,
            ratings: ratings,
        },
        dataType: "json",
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            $("#ajax-products-view").html(response.view);
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

$('.filter-by-rating').on('click',function (){
    let rating = $(this).data('rating')
    $.get({
        url: $("#filter-url").data("url"),
        data: {
            id: $("#data_id").val(),
            name: $("#data_name").val(),
            data_from: $("#data_from").val(),
            ratings: rating,
        },
        dataType: "json",
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            $("#ajax-products-view").html(response.view);
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
})

$(".product-view-option input[name=product_view]").on("change", function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#product-view-style-url").data("url"),
        data: {
            value: $(this).val(),
        },
        success: function (response) {
        },
    });
});

$("#max_price, #min_price").on("keyup", function () {
    let filter_rangeOne = $('input[name="rangeOne"]'),
        filter_rangeTwo = $('input[name="rangeTwo"]'),
        inclRange = $(".incl-range");
    const minPrice = $("#min_price");
    const maxPrice = $("#max_price");
    const priceRangeMax = $("#price_rangeMax");
    $("#price_rangeMin").val(minPrice.val());
    priceRangeMax.val(maxPrice.val());
    $("#data_min_price").val(minPrice.val());
    $("#data_max_price").val(maxPrice.val());
    if (parseInt(filter_rangeOne.val()) > parseInt(filter_rangeTwo.val())) {
        inclRange.css({
            "inline-size":
                ((filter_rangeOne.val() - filter_rangeTwo.val()) /
                    priceRangeMax.attr("max")) *
                    100 +
                "%",
            "inset-inline-start":
                (filter_rangeTwo.val() / priceRangeMax.attr("max")) *
                    100 +
                "%",
        });
    } else {
        inclRange.css({
            "inline-size":
                ((filter_rangeTwo.val() - filter_rangeOne.val()) /
                    priceRangeMax.attr("max")) *
                    100 +
                "%",
            "inset-inline-start":
                (filter_rangeOne.val() / priceRangeMax.attr("max")) *
                    100 +
                "%",
        });
    }
});

$(".custom_common_nav")
    .find(".has-sub-item div span")
    .on("click", function (event) {
        event.preventDefault();
        $(this).parent().parent(".has-sub-item").toggleClass("sub-menu-opened");
        if ($(this).parent().siblings("ul").hasClass("open")) {
            $(this).parent().siblings("ul").removeClass("open").slideUp("200");
            $(this).removeClass("rotateicon");
        } else {
            $(this).parent().siblings("ul").addClass("open").slideDown("200");
            $(this).addClass("rotateicon");
        }
    });

$(".btn_products_aside_categories").on("click", function () {
    $(".products_aside_categories").css("overflow", "auto");
    $(".btn_products_aside_categories").hide();
});
$(".btn_products_aside_brands").on("click", function () {
    $(".products_aside_brands").css("overflow", "auto");
    $(".btn_products_aside_brands").hide();
});
function removeWishlist(){

    $('.remove-wishlist').on('click', function() {
        let confirmText = $(this).data('confirm-text');
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        let product_id = $(this).data('product-id')
        $.ajax({
            url: $(this).data('action'),
            method: "POST",
            data: {
                id: product_id,
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                Swal.fire({
                    type: "success",
                    title: $(this).data("wishlist"),
                    text: data.success,
                    confirmButtonText: confirmText,
                });
                $(".wishlist_count_status").html(data.count);
                $("#set-wish-list").html(data.wishlist);
                removeWishlist();
                addToCompare();
                $(".tooltip").html("");
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    })
}
removeWishlist();
$('.order-again').on('click',function (){
    let orderId = $(this).data('order-id')
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $('#order_again_url').data('action'),
        data: {
            order_id : orderId,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            if (response.status === 1) {
                updateNavCart();
                toastr.success(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 3000, // duration
                });
                $("#quickViewModal").modal("hide");
                location.href = response.redirect_url;
                return false;
            } else if (response.status === 0) {
                toastr.warning(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 2000,
                });
                return false;
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
})

function shopFollowAction(shop_id) {
    const followButton = $(".follow_button");
    let status = followButton.data("status");
    if (parseInt(status) === 1) {
        Swal.fire({
            title: followButton.data("titletext"),
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: followButton.data("titletext2"),
            cancelButtonText: followButton.data("cancelbuttontext"),
        }).then((result) => {
            if (result.isConfirmed) {
                shopFollow(shop_id);
            }
        });
    } else {
        shopFollow(shop_id);
    }
}
function shopFollow(shop_id) {
    let followButton =$(".follow_button");
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        url: $("#shop-follow-url").data("url"),
        method: "POST",
        data: {
            shop_id: shop_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.value === 1) {
                toastr.success(data.message);
                $(".follower_count").html(data.followers);
                followButton.html(data.text);
                followButton.data("status", "1");
            } else if (data.value === 2) {
                toastr.success(data.message);
                $(".follower_count").html(data.followers);
                followButton.html(data.text);
                followButton.data("status", "0");
            } else {
                toastr.error(data.message);
                $("#loginModal").modal("show");
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

//coupon copy
$('.coupon-copy').on('click',function (){
    $("<textarea/>")
        .appendTo("body")
        .val($(this).data('copy-coupon'))
        .select()
        .each(function () {
            document.execCommand("copy");
        })
        .remove();
    toastr.success($('#successfully-copied').data('text'));
})

function getViewByOnclick(){
    $('.get-view-by-onclick').on('click',function (){
        location.href = $(this).data('link');
    });

}
getViewByOnclick();

function initTooltip()
{
    let tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
     tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    $('.minimum_Order_Amount_message').on('click', function(){
        let message = $(this).data('bs-title');
        toastr.warning(message, {
            CloseButton: true,
            ProgressBar: true
        });
    });
}

function checkPasswordMatch() {
    let password = $("#password").val();
    let confirmPassword = $("#confirm-password").val();
    let message = $("#message");
    let getText = $('#get-check-password-msg');
    message.removeAttr("style");
    message.html("");
    if (confirmPassword === "") {
        message.attr("style", "color:black");
        message.html(getText.data('retype'));

    } else if (password === "") {
        message.removeAttr("style");
        message.html("");

    } else if (password !== confirmPassword) {
        message.html(getText.data('not-match'));
        message.attr("style", "color:red");
    } else if (confirmPassword.length <= 7) {
        message.html(getText.data('character-limit'));
        message.attr("style", "color:red");
    } else {

        message.html(getText.data('match'));
        message.attr("style", "color:green");
    }
}
$('#confirm-password').on('keyup',function (){
    checkPasswordMatch();
});
$('#password').on("keyup", function() {
    if ($("#confirm-password").val() !== '') {
        checkPasswordMatch();
    }
});
$('.cancel-message').on('click',function (){
    toastr.info($('#get-cancel-message').data('text'), {
        CloseButton: true,
        ProgressBar: true
    });
});
$('.click-to-copy-code').on('click',function (){
    let copyCode = $(this).data('copy-code')
    navigator.clipboard.writeText(copyCode)
    .then(function () {
        if($('.click-to-copy-code-div').find('.coupon-id').length)
        {
            $('.coupon-id-hide').addClass("d-none");
            $('.coupon-id').removeClass("d-none");
            $('.coupon-id-' + copyCode).addClass("d-none");
            $('.coupon-hide-id-' + copyCode).removeClass("d-none");
        }
        toastr.success($('#successfully-copied').data('text'));
    }).catch(function () {
        toastr.error($('#copied-failed').data('text'));
    });
});
$('#add-fund-to-wallet-form-btn').on('click', function () {
    if (!$("input[name='payment_method']:checked").val()) {
        toastr.error($('#get-select-payment-method-message').data('text'));
    }
});

$('#add-fund-amount-input').on('keyup', function () {
    if ($(this).val() === '') {
        $('#add-fund-list-area').slideUp();
    } else {
        if (!isNaN($(this).val()) && $(this).val() < 0) {
            $(this).val(0);
            toastr.error($('#get-minus_value-message').data('text'));
        } else {
            $('#add-fund-list-area').slideDown();
        }
    }
})
$('.remove-mask-img').on('click', function () {
    $('.show-more--content').removeClass('active')
})

$(window).on('load',function (){
    addToWishlist();
    getViewByOnclick();
    addToCompare();
})

$('.digital-product-download').on('click',function (){
    $.ajax({
        type: "GET",
        url: $(this).data('action'),
        responseType: 'blob',
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status === 1 && data.file_path) {
                const a = document.createElement('a');
                a.href = data.file_path;
                a.download = data.file_name;
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(data.file_path);
            } else if (data.status === 0) {
                toastr.error(data.message);
            }
        },
        error: function () {

        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
})

function renderCustomImagePopup() {
    $('.custom-image-popup-init').each(function () {
        $('.custom-image-popup').magnificPopup({
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: false,
            mainClass: 'mfp-with-zoom mfp-img-mobile',
            image: {
                verticalFit: true,
                titleSrc: function(item) {
                    return item.el.attr('title') + ' &middot; <a class="image-source-link" href="'+item.el.attr('data-source')+'" target="_blank">image source</a>';
                }
            },
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true,
                duration: 300,
                opener: function(element) {
                    return element.find('img');
                }
            }
        });
    });
}
renderCustomImagePopup()

function renderCouponCodeApply() {
    $('#coupon-code-apply').on('click',function (e){
        e.preventDefault();
        let submitCouponCode = $('#submit-coupon-code');
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        $.ajax({
            type: submitCouponCode.attr('method'),
            url: submitCouponCode.attr('action'),
            data: submitCouponCode.serializeArray(),
            success: function (data) {
                if (parseInt(data.status) === 1) {
                    toastr.success(data.messages, {
                        CloseButton: true,
                        ProgressBar: true,
                    });
                } else {
                    toastr.error(data.messages, {
                        CloseButton: true,
                        ProgressBar: true,
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
        });
    })
}
renderCouponCodeApply()

$('.close-element-onclick-by-data').on('click', function (){
    $($(this).data('selector')).slideUp('slow').fadeOut('slow');
})
function playAudio() {
    document.getElementById("myAudio").play();
}

$('#profile-reset-button').on('click', function () {

})
