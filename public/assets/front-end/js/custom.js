"use strict";

(function ($) {
    $(".profile-aside-btn").on("click", function () {
        $("#shop-sidebar, .profile-aside-overlay").toggleClass("active");
    });
    $(".profile-aside-close-btn, .profile-aside-overlay").on(
        "click",
        function () {
            $("#shop-sidebar, .profile-aside-overlay").removeClass("active");
        }
    );

    $(".stopPropagation").on("click", function (e) {
        e.stopPropagation();
    });
    $('[data-toggle="tooltip"]').tooltip();
})(jQuery);

$(document).ready(function () {
    const stickyElement = $(".bottom-sticky_ele");
    const offsetElement = $(".bottom-sticky_offset");

    if (stickyElement.length !== 0) {
        $(window).on("scroll", function () {
            const elementOffset =
                offsetElement.offset().top - $(window).height() / 1.2;
            const scrollTop = $(window).scrollTop();

            if (scrollTop >= elementOffset) {
                stickyElement.addClass("stick");
            } else {
                stickyElement.removeClass("stick");
            }
        });
    }

    getReferralCodeFromURL();
});

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

$(".get-currency-change-function").on("click", function () {
    let code = $(this).data("code");
    currencyChangeFunction(code);
});

function currencyChangeFunction(currency_code) {
    $.ajaxSetup({
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
    });
    $.ajax({
        type: "POST",
        url: $("#route-currency-change").data("url"),
        data: { currency_code: currency_code },
        success: function (data) {
            toastr.success(data.message);
            location.reload();
        },
    });
}

$(".change-language").on("click", function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $(this).data("action"),
        data: {
            language_code: $(this).data("language-code"),
        },
        success: function (data) {
            toastr.success(data.message);
            location.reload();
        },
    });
});

function getReferralCodeFromURL() {
    let getAuthStatus = $("#is-customer-auth-active").data("value");
    if (getAuthStatus == 0) {
        const currentUrl = new URL(window.location.href);
        const referralCodeParameter = new URLSearchParams(
            currentUrl.search
        ).get("referral_code");
        if (referralCodeParameter) {
            let referralCodeInput = $("#referral_code");
            if ($("#is-request-customer-auth-sign-up").data("value"))
                console.log(referralCodeInput.length);
            if (referralCodeInput.length) {
                referralCodeInput.val(referralCodeParameter);
            } else {
                let redirectLink = $("#route-customer-auth-sign-up").data(
                    "url"
                );
                window.location.href =
                    redirectLink + "?referral_code=" + referralCodeParameter;
            }
        }
    }
}

$(".web-announcement-slideUp").on("click", function () {
    $("#announcement").slideUp(300);
});

$(".category-menu-toggle-btn").on("click", function () {
    $(".megamenu-wrap").toggleClass("show");
});

$(".navbar-tool-icon-box").on("click", function () {
    $(".megamenu-wrap").removeClass("show");
});

$(window).on("scroll", function () {
    $(".megamenu-wrap").removeClass("show");
});

$(".close-search-form-mobile").on("click", function () {
    $(".search-form-mobile").removeClass("active");
});
$(".open-search-form-mobile").on("click", function () {
    $(".search-form-mobile").addClass("active");
});

$(".get-view-by-onclick").on("click", function () {
    location.href = $(this).data("link");
});

$(".get-login-recaptcha-verify").on("click", function () {
    let url = $(this).data("link");
    customerLoginRecaptcha(url);
});

function customerLoginRecaptcha(url) {
    url =
        url +
        "/" +
        Math.random() +
        "?captcha_session_id=default_recaptcha_id_customer_login";
    document.getElementById("customer_login_recaptcha_id").src = url;
}

$(".get-regi-recaptcha-verify").on("click", function () {
    let url = $(this).data("link");
    customerRegiRecaptcha(url);
});

function customerRegiRecaptcha(url) {
    url =
        url +
        "/" +
        Math.random() +
        "?captcha_session_id=default_recaptcha_id_customer_regi";
    document.getElementById("default_recaptcha_id").src = url;
}

$(".get-vendor-regi-recaptcha-verify").on("click", function () {
    let genUrl = $(this).data("link");
    genUrl = genUrl.replace(":dummy-id", Math.random());
    vendorRegiRecaptcha(genUrl);
});

function vendorRegiRecaptcha(url) {
    let genUrl = url + "?captcha_session_id=default_recaptcha_id_seller_regi";
    document.getElementById("default_recaptcha_id").src = genUrl;
    console.log("url: " + genUrl);
}

$("#inputChecked").change(function () {
    if ($(this).is(":checked")) {
        $("#sign-up").removeAttr("disabled");
    } else {
        $("#sign-up").attr("disabled", "disabled");
    }
});

$("#customerResendVerifyOtp").click(function () {
    $("input.otp-field").val("");
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        url: $(this).data("url"),
        method: "POST",
        dataType: "json",
        data: {
            user_id: $(this).data("userid"),
        },
        beforeSend: function () {
            $("#loading").show();
        },
        success: function (data) {
            if (data.status == 1) {
                let new_counter = $(".verifyCounter");
                let new_seconds = data.new_time;

                function new_tick() {
                    let minutes = Math.floor(new_seconds / 60);
                    let seconds = new_seconds % 60;
                    new_seconds--;
                    new_counter.html(
                        minutes +
                            ":" +
                            (seconds < 10 ? "0" : "") +
                            String(seconds)
                    );
                    if (new_seconds > 0) {
                        setTimeout(new_tick, 1000);
                        $(".resend-otp-button").attr("disabled", true);
                        $(".resend_otp_custom").slideDown();
                    } else {
                        $(".resend-otp-button").removeAttr("disabled");
                        new_counter.html("0:00");
                        $(".resend_otp_custom").slideUp();
                    }
                }

                new_tick();

                toastr.success($("#message-otp-sent-again").data("text"));
            } else {
                toastr.error($("#message-wait-for-new-code").data("text"));
            }
        },
        complete: function () {
            $("#loading").hide();
        },
    });
});

$("#customerOtpVerify").click(function () {
    $("input.otp-field").val("");
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        url: $(this).data("url"),
        method: "POST",
        dataType: "json",
        data: {
            identity: $(this).data("identity"),
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status == 1) {
                let newCounter = $(".verifyCounter");
                let newSeconds = data.new_time;

                function new_tick() {
                    let minutes = Math.floor(newSeconds / 60);
                    let seconds = newSeconds % 60;
                    newSeconds--;
                    newCounter.html(
                        minutes +
                            ":" +
                            (seconds < 10 ? "0" : "") +
                            String(seconds)
                    );
                    if (newSeconds > 0) {
                        setTimeout(new_tick, 1000);
                        $(".resend-otp-button").attr("disabled", true);
                        $(".resend_otp_custom").slideDown();
                    } else {
                        $(".resend-otp-button").removeAttr("disabled");
                        newCounter.html("0:00");
                        $(".resend_otp_custom").slideUp();
                    }
                }

                new_tick();
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});

$(".get-contact-recaptcha-verify").on("click", function () {
    let url = $(this).data("link");
    url = url + "/" + Math.random();
    document.getElementById("default_recaptcha_id").src = url;
});

$(".check-password-match").on("change", function () {
    checkPasswordMatch();
});

function checkPasswordMatch() {
    let password = $("#password").val();
    let confirmPassword = $("#confirm_password").val();
    let messageElement = $("#message");
    messageElement.removeAttr("style");
    messageElement.html("");
    if (confirmPassword === "") {
        messageElement.attr("style", "color:black");
        messageElement.html($("#message-please-retype-password").data("text"));
    } else if (password === "") {
        messageElement.removeAttr("style");
        messageElement.html("");
    } else if (password !== confirmPassword) {
        messageElement.html($("#message-password-not-match").data("text"));
        messageElement.attr("style", "color:red");
    } else if (confirmPassword.length <= 6) {
        messageElement.html($("#message-password-need-longest").data("text"));
        messageElement.attr("style", "color:red");
    } else {
        messageElement.html($("#message-password-match").data("text"));
        messageElement.attr("style", "color:green");
    }
}

$(document).ready(function () {
    $("#confirm_password").keyup(checkPasswordMatch);
});

function accountImageReadURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#blah").attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$("#files").change(function () {
    accountImageReadURL(this);
});

$(".update-account-info").on("click", function () {
    $("#profile_form").submit();
});

$(".call-route-alert").on("click", function () {
    let route = $(this).data("route");
    let message = $(this).data("message");
    route_alert(route, message);
});

$(".filter-ico-button").on("click", function () {
    $(".__shop-page-sidebar").toggleClass("active");
});

$(".shop-page-sidebar-close").on("click", function () {
    $(".__shop-page-sidebar").removeClass("active");
});

$(".action-sort-shop-products-by-data").on("change", function () {
    sortShopProductsByData($(this).val());
});

function sortShopProductsByData(value) {
    $.get({
        url: $("#shop-sort-by-filter-url").data("url"),
        data: {
            sort_by: value,
            category_id: $("#store-request-data-category-id").data("value"),
            sub_category_id: $("#store-request-data-sub-category-id").data(
                "value"
            ),
            sub_sub_category_id: $(
                "#store-request-data-sub-sub-category-id"
            ).data("value"),
            product_name: $("#store-request-data-product-name").data("value"),
        },
        dataType: "json",
        beforeSend: function () {
            $("#loading").show();
        },
        success: function (response) {
            $("#ajax-products").html(response.view);
            renderQuickViewFunction();
        },
        complete: function () {
            $("#loading").hide();
        },
    });
}

$("#shop-view-chat-form").on("submit", function (e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "post",
        url: $("#route-messages-store").data("url"),
        data: $("#shop-view-chat-form").serialize(),
        success: function (response) {
            toastr.success($("#message-send-successfully").data("text"), {
                CloseButton: true,
                ProgressBar: true,
            });
            $("#shop-view-chat-form").trigger("reset");
        },
    });
});

$(".menu--caret").on("click", function (e) {
    var element = $(this).closest(".menu--caret-accordion");
    if (element.hasClass("open")) {
        element.removeClass("open");
        element.find(".menu--caret-accordion").removeClass("open");
        element.find(".card-body").slideUp(300, "swing");
    } else {
        element.addClass("open");
        element.children(".card-body").slideDown(300, "swing");
        element
            .siblings(".menu--caret-accordion")
            .children(".card-body")
            .slideUp(300, "swing");
        element.siblings(".menu--caret-accordion").removeClass("open");
        element
            .siblings(".menu--caret-accordion")
            .find(".menu--caret-accordion")
            .removeClass("open");
        element
            .siblings(".menu--caret-accordion")
            .find(".card-body")
            .slideUp(300, "swing");
    }
});

$("#seller-chat-form").on("submit", function (e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "post",
        url: $("#route-messages-store").data("url"),
        data: $(this).serialize(),
        success: function (response) {
            toastr.success($("#message-send-successfully").data("text"), {
                CloseButton: true,
                ProgressBar: true,
            });
            $("#seller-chat-form").trigger("reset");
        },
    });
});

$("#deliveryman-chat-form").on("submit", function (e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "post",
        url: $("#route-messages-store").data("url"),
        data: $(this).serialize(),
        success: function (response) {
            toastr.success($("#message-send-successfully").data("text"), {
                CloseButton: true,
                ProgressBar: true,
            });
            $("#deliveryman-chat-form").trigger("reset");
        },
    });
});

$(".rating-label-wrap input[type=range]").on("change", function () {
    let ratingVal = $(this).val();
    let ratingContent = $(".rating_content_delivery_man");
    let rattingTextAreaClass = $(".text-area-class");
    let rattingMessages = $("#message-ratingContent");
    let htmlDir = $("#system-session-direction").data("value");
    let position = "left";
    if (htmlDir === "rtl") {
        position = "right";
    }
    switch (ratingVal) {
        case "1":
            ratingContent
                .text(rattingMessages.data("poor"))
                .css(position, "5px");
            rattingTextAreaClass.attr("placeholder", "");
            break;
        case "2":
            ratingContent
                .text(rattingMessages.data("average"))
                .css(position, "36px");
            rattingTextAreaClass.attr("placeholder", "");
            break;
        case "3":
            ratingContent
                .text(rattingMessages.data("good"))
                .css(position, "85px");
            rattingTextAreaClass.attr(
                "placeholder",
                rattingMessages.data("good-message")
            );
            break;
        case "4":
            ratingContent
                .text(rattingMessages.data("good2"))
                .css(position, "112px");
            rattingTextAreaClass.attr(
                "placeholder",
                rattingMessages.data("good2-message")
            );
            break;
        case "5":
            ratingContent
                .text(rattingMessages.data("excellent"))
                .css(position, "155px");
            rattingTextAreaClass.attr(
                "placeholder",
                rattingMessages.data("excellent-message")
            );
            break;
        default:
            break;
    }
});

$("#add_fund_to_wallet_form_btn").on("click", function () {
    if (!$("input[name='payment_method']:checked").val()) {
        toastr.error($("#message-select-payment-method").data("text"));
    }
});

$("#add-fund-amount-input").on("keyup", function () {
    if (!isNaN($(this).val()) && $(this).val() < 0) {
        $(this).val(0);
        toastr.error($("#message-cannot-input-minus-value").data("text"));
    }
});

$(".click-to-copy-coupon").on("click", function () {
    let value = $(this).data("value");
    click_to_copy_coupon(value);
});

function click_to_copy_coupon(copied_text) {
    navigator.clipboard
        .writeText(copied_text)
        .then(function () {
            toastr.success($("#message-successfully-copied").data("text"));
            $(".couponid-hide").addClass("d-none");
            $(".couponid").removeClass("d-none");
            $(".couponid-" + copied_text).addClass("d-none");
            $(".couponhideid-" + copied_text).removeClass("d-none");
        })
        .catch(function (error) {
            toastr.error($("#message-copied-failed").data("text"));
        });
}

$(".click-to-copy-data-value").on("click", function () {
    let copiedText = $(this).data("value");
    let tempTextarea = $("<textarea>");
    $("body").append(tempTextarea);
    tempTextarea.val(copiedText).select();
    document.execCommand("copy");
    tempTextarea.remove();
    toastr.success($("#message-successfully-copied").data("text"));
});

$("#customer-login-form").on("submit", function (e) {
    var response = grecaptcha.getResponse();
    if (response.length === 0) {
        e.preventDefault();
        toastr.error($("#message-please-check-recaptcha").data("text"));
    }
});

$("#customer-register-form").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).data("action"),
        data: $(this).serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            if (response.errors) {
                for (let index = 0; index < response.errors.length; index++) {
                    toastr.error(response.errors[index].message);
                }
            } else if (response.error) {
                toastr.error(response.error);
            } else if (response.status === 1) {
                toastr.success(response.message);
                window.location.href = response.redirect_url;
            } else if (response.redirect_url !== "") {
                window.location.href = response.redirect_url;
            }
        },
        error: function () {},
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});

$(".remove-img-row-by-key").on("click", function () {
    let reviewId = $(this).data("review-id");
    let getPhoto = $(this).data("photo");
    let key = $(this).data("key");

    $.ajaxSetup({
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
    });
    $.ajax({
        type: "POST",
        url: $(this).data("route"),
        data: {
            id: reviewId,
            name: getPhoto,
        },
        success: function (response) {
            if (response.message) {
                toastr.success(response.message);
            }
            $(".img_row" + key).remove();
        },
    });
});

$(".customer-account-delete-by-route").on("click", function () {
    $("#shop-sidebar").removeClass("active");
    $(".profile-aside-overlay ").removeClass("active");
    let route = $(this).data("route");
    let message = $(this).data("message");
    route_alert(route, message);
});

$(".show-instant-image").on("click", function () {
    let link = $(this).data("link");
    showInstantImage(link);
});

function showInstantImage(link) {
    $("#attachment-view").attr("src", link);
    $("#show-modal-view").modal("toggle");
}

function couponCode() {
    $("#apply-coupon-code").on("click", function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: $("#route-coupon-apply").data("url"),
            data: $("#coupon-code-ajax").serializeArray(),
            success: function (data) {
                let messages = data.messages;
                if (data.status) {
                    messages.forEach(function (message, index) {
                        toastr.success(message, index, {
                            CloseButton: true,
                            ProgressBar: true,
                        });
                    });
                } else {
                    messages.forEach(function (message, index) {
                        toastr.error(message, index, {
                            CloseButton: true,
                            ProgressBar: true,
                        });
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
        });
    });
}

couponCode();

$(".password-toggle-btn").on("click", function () {
    let checkbox = $(this).find("input[type=checkbox]");
    let eyeIcon = $(this).find("i");
    checkbox.change(function () {
        if (checkbox.is(":checked")) {
            eyeIcon.removeClass("tio-hidden").addClass("tio-invisible");
        } else {
            eyeIcon.removeClass("tio-invisible").addClass("tio-hidden");
        }
    });
});

$(".filter-show-btn").on("click", function () {
    $("#shop-sidebar").toggleClass("show active");
});

$(".cz-sidebar-header .close").on("click", function () {
    $("#shop-sidebar").removeClass("show active");
});

$(".remove-address-by-modal").on("click", function () {
    let link = $(this).data("link");
    $("#remove-address-link").attr("href", link);
    $("#remove-address").modal("show");
});

var backgroundImage = $("[data-bg-img]");
backgroundImage
    .css("background-image", function () {
        return 'url("' + $(this).data("bg-img") + '")';
    })
    .removeAttr("data-bg-img")
    .addClass("bg-img");

$(".get-order-again-function").on("click", function () {
    let orderId = $(this).data("id");
    order_again(orderId);
});

function order_again(orderId) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#route-cart-order-again").data("url"),
        data: { order_id: orderId },
        beforeSend: function () {
            $("#loading").show();
        },
        success: function (response) {
            if (response.status === 1) {
                updateNavCart();
                toastr.success(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 3000,
                });
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
            $("#loading").hide();
        },
    });
}

$(".search-bar-input-mobile").keyup(function () {
    $(".search-card").css("display", "block");
    let name = $(".search-bar-input-mobile").val();
    if (name.length > 0) {
        $.get({
            url: $("#route-searched-products").data("url"),
            dataType: "json",
            data: {
                name: name,
            },
            beforeSend: function () {
                $("#loading").show();
            },
            success: function (data) {
                $(".search-result-box").empty().html(data.result);
                $(".search-result-product").on("mouseover", function () {
                    $(".search-bar-input-mobile").val(
                        $(this).data("product-name")
                    );
                });
            },
            complete: function () {
                $("#loading").hide();
            },
        });
    } else {
        $(".search-result-box").empty();
    }
});

$(".search-bar-input").keyup(function () {
    let searchBarInputElement = $(".search-bar-input");
    $(".search-card").css("display", "block");
    let name = searchBarInputElement.val();
    searchBarInputElement.data("given-value", name);
    if (name.length > 0) {
        $.get({
            url: $("#route-searched-products").data("url"),
            dataType: "json",
            data: {
                name: name,
            },
            beforeSend: function () {
                $("#loading").show();
            },
            success: function (data) {
                $(".search-result-box").empty().html(data.result);
                $(".search-result-product").on("mouseover", function () {
                    searchBarInputElement.val($(this).data("product-name"));
                });
                $(".search-result-product").on("mouseleave", function () {
                    searchBarInputElement.val(
                        searchBarInputElement.data("given-value")
                    );
                });
            },
            complete: function () {
                $("#loading").hide();
            },
        });
    } else {
        $(".search-result-box").empty();
    }
});

$(".clickable").click(function () {
    window.location = $(this).find("a").attr("href");
    return false;
});

function addWishlist(product_id, modalId) {
    $.ajaxSetup({
        headers: { "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content") },
    });
    $.ajax({
        url: $("#route-store-wishlist").data("url"),
        method: "POST",
        data: { product_id: product_id },
        success: function (data) {
            if (data.value == 1) {
                $(".countWishlist").html(data.count);
                $(".countWishlist-" + product_id).text(data.product_count);
                $(".tooltip").html("");
                $(`.wishlist_icon_${product_id}`)
                    .removeClass("fa fa-heart-o")
                    .addClass("fa fa-heart");
                $("#add-wishlist-modal").modal("show");
                $(`#${modalId}`).modal("show");
            } else if (data.value == 2) {
                $("#remove-wishlist-modal").modal("show");
                $(".countWishlist").html(data.count);
                $(".countWishlist-" + product_id).text(data.product_count);
                $(`.wishlist_icon_${product_id}`)
                    .removeClass("fa fa-heart")
                    .addClass("fa fa-heart-o");
            } else {
                $("#login-alert-modal").modal("show");
            }
        },
    });
}

$(".function-remove-wishList").on("click", function () {
    let productId = $(this).data("id");
    let modalId = $(this).data("modal");
    removeWishlist(productId, modalId);
});

function removeWishlist(productId, modalId) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        url: $("#route-delete-wishlist").data("url"),
        method: "POST",
        data: { id: productId },
        beforeSend: function () {
            $("#loading").show();
        },
        success: function (data) {
            $(`#${modalId}`).modal("show");
            let countWishlistElement = $(".countWishlist");
            let messageNoDataFound = $("#message-no-data-found").data("text");
            countWishlistElement.html(
                parseInt(countWishlistElement.html()) - 1
            );
            $("#row_id" + productId).hide();
            $(".tooltip").html("");
            if (parseInt(countWishlistElement.html()) % 15 === 0) {
                let wishlistPaginatedPage = $("#wishlist_paginated_page");
                if (wishlistPaginatedPage.val() == 1) {
                    $("#set-wish-list")
                        .empty()
                        .append(
                            `<h6 class="text-muted text-center">` +
                                messageNoDataFound +
                                `</h6>`
                        );
                } else {
                    let pageValue = wishlistPaginatedPage.val();
                    window.location.href =
                        $("#route-wishlists").data("url") +
                        "?page=" +
                        (pageValue - 1);
                }
            }
        },
        complete: function () {
            $("#loading").hide();
        },
    });
}

function renderQuickViewFunction() {
    $(".action-product-quick-view").on("click", function () {
        let productId = $(this).data("product-id");
        productQuickView(productId);
    });
}

$(window).on("load", function () {
    renderQuickViewFunction();
});

function productQuickView(product_id) {
    $.get({
        url: $("#route-quick-view").data("url"),
        dataType: "json",
        data: {
            product_id: product_id,
        },
        beforeSend: function () {
            $("#loading").show();
        },
        success: function (data) {
            $("#quick-view-modal").empty().html(data.view);
            renderOwlCarouselSilder();
            commonFunctionalityForProductView();
            $("#quick-view").modal("show");
        },
        complete: function () {
            $("#loading").hide();
        },
    });
}

$(".action-hide-billing-address").on("click", function () {
    hideBillingAddressFunction();
});

function hideBillingAddressFunction() {
    let checkSameAsShipping = $("#same_as_shipping_address").is(":checked");
    console.log(checkSameAsShipping);
    if (checkSameAsShipping) {
        $("#hide_billing_address").hide();
    } else {
        $("#hide_billing_address").show();
    }
}

$(".action-billing-address-hide").on("click", function () {
    billingAddressFunction();
});

function billingAddressFunction() {
    $("#bh-0").prop("checked", true);
    $("#billing_model").collapse();
}

$(".minimum-order-amount-message").on("click", function () {
    toastr.error($(this).data("title"), {
        CloseButton: true,
        ProgressBar: true,
    });
});

function productQuickViewFunctionalityInitialize() {
    cartQuantityInitialize();
    console.log("Called from 875");
    getVariantPrice();
    $("#add-to-cart-form input").on("change", function () {
        console.log("Called from 878");
        getVariantPrice();
    });

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    });

    $(".quick-view-preview-image-by-color").on("click", function () {
        let id = $("#preview-img" + $(this).data("key"));
        $(id).click();
    });
}

function addToCart(form_id = "add-to-cart-form", redirect_to_checkout = false) {
    if (checkAddToCartValidity()) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });

        let existCartItem = $('.in_cart_key[name="key"]').val();

        let formActionUrl = $("#route-cart-add").data("url");
        if (existCartItem !== ""  && !(redirect_to_checkout)) {
            formActionUrl = $("#route-cart-updateQuantity-guest").data("url");
        }

        $.post({
            url: formActionUrl,
            data: $("#" + form_id).serializeArray().concat({name: 'buy_now', value: (redirect_to_checkout ? 1 : 0)}),
            beforeSend: function () {
                $("#loading").show();
            },
            success: function (response) {
                console.log(response);
                if (response.status === 2) {
                    $("#buyNowModal-body").html(response.shippingMethodHtmlView);
                    $("#quick-view").modal("hide");
                    $("#buyNowModal").modal("show");
                    return false;
                }
                if (response.status == 1) {
                    updateNavCart();
                    toastr.success(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                    });

                    let actionAddToCartBtn = $(".action-add-to-cart-form");
                    $('.in_cart_key[name="key"]').val(response.in_cart_key);
                    actionAddToCartBtn.html(
                        actionAddToCartBtn.data("update-text")
                    );

                    $(".call-when-done").click();

                    if (redirect_to_checkout == true && response.redirect_to_url) {
                        setTimeout(function () {
                            location.href = response.redirect_to_url;
                        }, 100);
                    } else if (redirect_to_checkout) {
                        location.href = $("#route-checkout-details").data("url");
                    }
                    return false;
                } else if (response.status == 0) {
                    $("#out-of-stock-modal-message").html(response.message);
                    $("#out-of-stock-modal").modal("show");
                    return false;
                }
            },
            complete: function () {
                $("#loading").hide();
            },
        });
    } else {
        Swal.fire({
            type: "info",
            title: "Cart",
            text: $("#message-please-choose-all-options").data("text"),
        });
    }
}

function commonFunctionalityForProductView() {
    $(".action-buy-now-this-product").on("click", function () {
        addToCart("add-to-cart-form", true);
    });

    $(".action-add-to-cart-form").on("click", function () {
        addToCart("add-to-cart-form");
    });

    $(".product-action-add-wishlist").on("click", function () {
        let id = $(this).data("product-id");
        addWishlist(id);
    });
}
commonFunctionalityForProductView();

function checkoutFromCartList() {
    let orderNote = $("#order_note").val();
    $.post({
        url: $("#route-order-note").data("url"),
        data: {
            _token: $('meta[name="_token"]').attr("content"),
            order_note: orderNote,
        },
        beforeSend: function () {
            $("#loading").show();
        },
        success: function (response) {
            if (response.status === 0) {
                response.message.map(function (message) {
                    toastr.error(message);
                });
            } else {
                location.href = response.redirect
                    ? response.redirect
                    : $("#route-checkout-details").data("url");
            }
        },
        complete: function () {
            $("#loading").hide();
        },
    });
}

function orderSummaryStickyFunction() {
    const stickyElement = $(".bottom-sticky3");
    const offsetElement = $(".__cart-total_sticky");
    $(window).on("scroll", function () {
        const elementOffset = offsetElement.offset().top;
        const scrollTop = $(window).scrollTop();
        if (scrollTop >= elementOffset) {
            stickyElement.addClass("stick");
        } else {
            stickyElement.removeClass("stick");
        }
    });
}

function cartListQuantityUpdateInit() {
    $(".action-update-cart-quantity").on("click", function () {
        let cartId = $(this).data("cart-id");
        let productId = $(this).data("product-id");
        let action = $(this).data("action");
        let event = $(this).data("event");
        updateCartQuantity(cartId, productId, action, event);
    });

    $(".action-update-cart-quantity-list").on("click", function () {
        let minimumOrderQuantity = $(this).data("minimum-order");
        let key = $(this).data("cart-id");
        let increment = $(this).data("increment");
        let event = $(this).data("event");
        updateCartQuantityList(minimumOrderQuantity, key, increment, event);
    });

    $(".action-change-update-cart-quantity-list").on("change", function () {
        let minimumOrderQuantity = $(this).data("minimum-order");
        let key = $(this).data("cart-id");
        let increment = $(this).data("increment");
        let event = $(this).data("event");
        updateCartQuantityList(minimumOrderQuantity, key, increment, event);
    });

    $(".action-update-cart-quantity-list-mobile").on("click", function () {
        let minimumOrderQuantity = $(this).data("minimum-order");
        let key = $(this).data("cart-id");
        let increment = $(this).data("increment");
        let event = $(this).data("event");
        updateCartQuantityListMobile(
            minimumOrderQuantity,
            key,
            increment,
            event
        );
    });

    $(".action-change-update-cart-quantity-list-mobile").on(
        "change",
        function () {
            let minimumOrderQuantity = $(this).data("minimum-order");
            let key = $(this).data("cart-id");
            let increment = $(this).data("increment");
            let event = $(this).data("event");
            updateCartQuantityListMobile(
                minimumOrderQuantity,
                key,
                increment,
                event
            );
        }
    );

    $(document).ready(function() {
        $('.cart_information').each(function() {
            let allShopItemsInChecked = true;
            $(this).find('.shop-item-check').each(function() {
                if (!$(this).prop('checked')) {
                    allShopItemsInChecked = false;
                    return false;
                }
            });
            $(this).find('.shop-head-check').prop('checked', allShopItemsInChecked);
        });
    });



    $('.shop-head-check').on('change', function () {
        $(this).parents('.cart_information').find('.shop-item-check').prop('checked', this.checked);
    });

    $('.shop-item-check').on('change', function () {
        var allChecked = true;
        $(this).parents('.cart_information').find('.shop-item-check').each(function () {
            if (!$(this).prop('checked')) {
                allChecked = false;
                return false;
            }
        });
        $(this).parents('.cart_information').find('.shop-head-check').prop('checked', allChecked);
    });

    $('.shop-head-check-desktop').on('change', function () {
        getCartSelectCartItemsCheckedValues('.cart_information input[type="checkbox"].shop-item-check-desktop')
    })

    $('.shop-head-check-mobile').on('change', function () {
        getCartSelectCartItemsCheckedValues('.cart_information input[type="checkbox"].shop-item-check-mobile')
    })

    $('.shop-item-check-desktop').on('change', function () {
        getCartSelectCartItemsCheckedValues('.cart_information input[type="checkbox"].shop-item-check-desktop')
    })

    $('.shop-item-check-mobile').on('change', function () {
        getCartSelectCartItemsCheckedValues('.cart_information input[type="checkbox"].shop-item-check-mobile')
    })

    function getCartSelectCartItemsCheckedValues(elementSelector)
    {
        let checkedValues = [];
        $(elementSelector).each(function() {
            if ($(this).prop('checked')) {
                checkedValues.push($(this).val());
            }
        });
        getCartSelectCartItemsRequest(checkedValues)
    }

    function getCartSelectCartItemsRequest(checkedValues)
    {
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        $.ajax({
            url: $('#get-cart-select-cart-items').data('route'),
            type: "POST",
            data: {
                ids: checkedValues
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (response) {
                $('#cart-summary').empty().html(response.htmlView);
                toastr.success(response.message);
                cartListQuantityUpdateInit()
                setShippingIdFunctionCartDetails()
                $('[data-toggle="tooltip"]').tooltip()
                actionCheckoutFunctionInit()
                couponCode()
            },
            complete: function () {
                $('#loading').hide();
            }
        });
    }
}

cartListQuantityUpdateInit();

function actionCheckoutFunctionInit() {
    $(".action-checkout-function").on("click", function () {
        let getRoute = $("#route-action-checkout-function").data("route");
        if (getRoute && getRoute.toString() === "shop-cart") {
            checkoutFromCartList();
        } else if (getRoute && getRoute.toString() === "checkout-details") {
            checkoutFromShipping();
        } else if (getRoute && getRoute.toString() === "checkout-payment") {
            checkoutFromPayment();
        }
    });

    $(".action-set-shipping-id").on("change", function () {
        let cartGroupId = $(this).data("product-id");
        let id = $(this).val();
        setShippingId(id, cartGroupId);
    });
}

actionCheckoutFunctionInit();

function removeFromCart(key) {
    $.post(
        $("#route-cart-remove").data("url"),
        {
            _token: $('meta[name="_token"]').attr("content"),
            key: key,
        },
        function (response) {
            $("#cod-for-cart").hide();
            updateNavCart();
            $("#cart-summary").empty().html(response.data);
            couponCode();
            toastr.info(
                $("#message-item-has-been-removed-from-cart").data("text"),
                {
                    CloseButton: true,
                    ProgressBar: true,
                }
            );
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
}

function updateNavCart() {
    $.post(
        $("#route-cart-nav-cart").data("url"),
        {
            _token: $('meta[name="_token"]').attr("content"),
        },
        function (response) {
            $("#cart_items").html(response.data);
            cartListQuantityUpdateInit();
        }
    );
}

$("#add-to-cart-form").on("submit", function (e) {
    e.preventDefault();
});

function cartQuantityInitialize() {
    $(".btn-number").click(function (e) {
        e.preventDefault();
        let fieldName = $(this).attr("data-field");
        let type = $(this).attr("data-type");
        let productType = $(this).data("producttype");
        let input = $("input[name='" + fieldName + "']");
        let currentVal = parseInt($(".input-number").val());
        if (!isNaN(currentVal)) {
            if (type == "minus") {
                if (currentVal > $(".input-number").attr("min")) {
                    $(".input-number")
                        .val(currentVal - 1)
                        .change();
                }
                if (
                    parseInt($(".input-number").val()) ==
                    $(".input-number").attr("min")
                ) {
                    $(this).attr("disabled", true);
                }
            } else if (type == "plus") {
                if (
                    currentVal < $(".input-number").attr("max") ||
                    productType === "digital"
                ) {
                    $(".input-number")
                        .val(currentVal + 1)
                        .change();
                }

                if (
                    parseInt(input.val()) == $(".input-number").attr("max") &&
                    productType === "physical"
                ) {
                    $(this).attr("disabled", true);
                }
            }
        } else {
            $(".input-number").val(0);
        }
    });

    $(".input-number").focusin(function () {
        $(this).data("oldValue", $(this).val());
    });

    $(".input-number").change(function () {
        let productType = $(this).data("producttype");
        let minValue = parseInt($(this).attr("min"));
        let maxValue = parseInt($(this).attr("max"));
        let valueCurrent = parseInt($(this).val());
        let name = $(this).attr("name");
        if (valueCurrent >= minValue) {
            $(
                ".btn-number[data-type='minus'][data-field='" + name + "']"
            ).removeAttr("disabled");
        } else {
            Swal.fire({
                icon: "error",
                title: $("#message-cart").data("text"),
                text: $(
                    "#message-sorry-the-minimum-order-quantity-not-match"
                ).data("text"),
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
                title: $("#message-cart").data("text"),
                text: $("#message-sorry-stock-limit-exceeded").data("text"),
            });
            $(this).val($(this).data("oldValue"));
        }
    });

    $(".input-number").keydown(function (e) {
        if (
            $.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
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

function updateQuantity(key, element) {
    $.post(
        $("#route-cart-updateQuantity").data("url"),
        {
            _token: "<?php echo e(csrf_token()); ?>",
            key: key,
            quantity: element.value,
        },
        function (data) {
            updateNavCart();
            $("#cart-summary").empty().html(data);
            couponCode();
        }
    );
}

function updateCartQuantity(cart_id, product_id, action, event) {
    let remove_url = $("#route-cart-remove").data("url");
    let update_quantity_url = $("#route-cart-updateQuantity-guest").data("url");
    let token = $('meta[name="_token"]').attr("content");
    let product_qyt =
        parseInt($(`.cartQuantity${cart_id}`).val()) + parseInt(action);
    let cart_quantity_of = $(`.cartQuantity${cart_id}`);
    let segment_array = window.location.pathname.split("/");
    let segment = segment_array[segment_array.length - 1];

    if (cart_quantity_of.val() > cart_quantity_of.data('current-stock')) {
        cartItemRemoveFunction(remove_url, token, cart_id, segment)
        return false
    }

    if (cart_quantity_of.val() == 0) {
        toastr.info($(".cannot_use_zero").data("text"), {
            CloseButton: true,
            ProgressBar: true,
        });
        cart_quantity_of.val(cart_quantity_of.data("min"));
    } else if (cart_quantity_of.val() == cart_quantity_of.data("min") && event == "minus") {
        cartItemRemoveFunction(remove_url, token, cart_id, segment)
    } else {
        if (cart_quantity_of.val() < cart_quantity_of.data("min")) {
            let min_value = cart_quantity_of.data("min");
            toastr.error(
                $("#message-minimum-order-quantity-cannot-less-than").data(
                    "text"
                ) +
                    " " +
                    min_value
            );
            cart_quantity_of.val(min_value);
            updateCartQuantity(cart_id, product_id, action, event);
        } else {
            $(`.cartQuantity${cart_id}`).html(product_qyt);
            $.post(
                update_quantity_url,
                {
                    _token: token,
                    key: cart_id,
                    product_id: product_id,
                    quantity: product_qyt,
                },
                function (response) {
                    if (response["status"] == 0) {
                        toastr.error(response["message"]);
                    } else {
                        toastr.success(response["message"]);
                    }
                    $('.in_cart_key[name="key"]').val(response.in_cart_key);
                    response["qty"] <= 1
                        ? $(`.quantity__minus${cart_id}`).html(
                              '<i class="tio-delete-outlined text-danger fs-10"></i>'
                          )
                        : $(`.quantity__minus${cart_id}`).html(
                              '<i class="tio-remove fs-10"></i>'
                          );

                    $(`.cartQuantity${cart_id}`).val(response["qty"]);
                    $(`.cartQuantity${cart_id}`).html(response["qty"]);
                    $(`.cart_quantity_multiply${cart_id}`).html(
                        response["qty"]
                    );
                    $(".cart_total_amount").html(response.total_price);
                    $(".cart-total-price").html(response.total_price);
                    $(`.discount_price_of_${cart_id}`).html(
                        response["discount_price"]
                    );
                    $(`.quantity_price_of_${cart_id}`).html(
                        response["quantity_price"]
                    );
                    $(`.total_discount`).html(response["total_discount_price"]);
                    $(`.free_delivery_amount_need`).html(
                        response.free_delivery_status.amount_need
                    );
                    if (response.free_delivery_status.amount_need <= 0) {
                        $(".amount_fullfill").removeClass("d-none");
                        $(".amount_need_to_fullfill").addClass("d-none");
                    } else {
                        $(".amount_fullfill").addClass("d-none");
                        $(".amount_need_to_fullfill").removeClass("d-none");
                    }
                    const progressBar = document.querySelector(".progress-bar");
                    if (progressBar) {
                        progressBar.style.width =
                            response.free_delivery_status.percentage + "%";
                    }
                    if (response["qty"] == cart_quantity_of.data("min")) {
                        cart_quantity_of
                            .parent()
                            .find(".quantity__minus")
                            .html(
                                '<i class="tio-delete-outlined text-danger fs-10"></i>'
                            );
                    } else {
                        cart_quantity_of
                            .parent()
                            .find(".quantity__minus")
                            .html('<i class="tio-remove fs-10"></i>');
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

function cartItemRemoveFunction(remove_url, token, cart_id, segment) {
    $.post(
        remove_url,
        {
            _token: token,
            key: cart_id,
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

$("#add-to-cart-form input").on("change", function () {
    getVariantPrice();
});

function getVariantPrice() {
    if (
        ($("#add-to-cart-form input[name=quantity]").val() > 0 &&
            checkAddToCartValidity()) ||
        ($("#add-to-cart-form input[name=quantity]").val() > 0 &&
            checkAddToCartValidity())
    ) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: $("#route-cart-variant-price").data("url"),
            data: $("#add-to-cart-form").serializeArray(),
            success: function (data) {
                $("#add-to-cart-form #chosen_price_div").removeClass("d-none");
                $("#add-to-cart-form #chosen_price_div #chosen_price").html(
                    data.price
                );
                $("#chosen_price_mobile").html(data.price);
                $("#set-tax-amount-mobile").html(data.update_tax);
                $("#set-tax-amount").html(data.update_tax);
                $("#set-discount-amount").html(data.discount);
                $("#available-quantity").html(data.quantity);
                $(".cart-qty-field").attr("max", data.quantity);

                if (data.quantity <= 0) {
                    $(".out-of-stock-element").fadeIn();
                } else {
                    $(".out-of-stock-element").fadeOut();
                }

                $(".cart-qty-field").val(data.in_cart_quantity);
                $(".product-generated-variation-code").val(data.variation_code);
                if (
                    $(".cart-qty-field").attr("min") < parseFloat($(".cart-qty-field").val())
                ) {
                    $(".btn-number[data-type='minus'][data-field='quantity']").removeAttr("disabled");
                } else {
                    $(".btn-number[data-type='minus'][data-field='quantity']").attr("disabled", true);
                }

                $(".discounted_unit_price").html(data.discounted_unit_price);
                $(".total_unit_price").html(data.discount_amount > 0 ? data.total_unit_price : "");

                let actionAddToCartBtn = $(".action-add-to-cart-form");
                if (data.in_cart_status === 1) {
                    $('.in_cart_key[name="key"]').val(data.in_cart_key);
                    actionAddToCartBtn.html(actionAddToCartBtn.data("update-text"));
                } else {
                    $('.in_cart_key[name="key"]').val(data.in_cart_key);
                    actionAddToCartBtn.html(actionAddToCartBtn.data("add-text"));
                }
            },
        });
    }
}

function checkAddToCartValidity() {
    var names = {};
    $("#add-to-cart-form input:radio").each(function () {
        names[$(this).attr("name")] = true;
    });
    var count = 0;
    $.each(names, function () {
        count++;
    });

    if ($("#add-to-cart-form input:radio:checked").length == count) {
        return true;
    }
    return false;
}

function updateFlashDealProgressBar() {
    let getFlashDealsTime = $("#storage-flash-deals").data("value");
    const current_time_stamp = new Date().getTime();
    const start_date = new Date(getFlashDealsTime).getTime();
    const countdownElement = document.querySelector(".cz-countdown");
    try {
        const getEndTime = countdownElement.data("countdown");
        const endTime = new Date(getEndTime).getTime();
        let time_progress =
            ((current_time_stamp - start_date) / (endTime - start_date)) * 100;
        const progress_bar = document.querySelector(".flash-deal-progress-bar");
        progress_bar.style.width = time_progress + "%";
    } catch (e) {}
}

$(".image-preview-before-upload").on("change", function () {
    let getElementId = $(this).data("preview");
    $(getElementId).attr("src", window.URL.createObjectURL(this.files[0]));
});

$("#vendor-remember-input-checked").on("change", function () {
    if ($(this).is(":checked")) {
        $("#apply").removeAttr("disabled");
    } else {
        $("#apply").attr("disabled", "disabled");
    }
});

$("#exampleInputPassword, #exampleRepeatPassword").on("keyup", function () {
    let password = $("#exampleInputPassword").val();
    let passwordRepeat = $("#exampleRepeatPassword").val();
    if (password != "" && passwordRepeat != "") {
        if (password.toString() === passwordRepeat.toString()) {
            $(".pass").hide();
        } else {
            $(".pass").show();
        }
    } else {
        $(".pass").hide();
    }
});

$(".alert-insufficient-loyalty-point").on("click", function () {
    let message = $(this).data("insufficient-point");
    toastr.error(message);
});

function shareOnSocialMedia() {
    $(".share-on-social-media").on("click", function () {
        let social = $(this).data("social-media-name");
        let url = $(this).data("action");
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
    });
}
shareOnSocialMedia();

var directionFromSession = $("#direction-from-session").data("value");
directionFromSession = directionFromSession ? directionFromSession : "ltr";

const themeDirection = $("html").attr("dir");

function renderOwlCarouselSilder() {
    var sync1 = $("#sync1");
    var sync2 = $("#sync2");
    var thumbnailItemClass = ".owl-item";
    var slides = sync1
        .owlCarousel({
            startPosition: 12,
            items: 1,
            loop: false,
            margin: 0,
            mouseDrag: true,
            touchDrag: true,
            pullDrag: false,
            scrollPerPage: true,
            autoplayHoverPause: false,
            nav: false,
            dots: false,
            rtl: themeDirection && themeDirection.toString() === "rtl",
        })
        .on("changed.owl.carousel", syncPosition);

    function syncPosition(el) {
        var owl_slider = $(this).data("owl.carousel");
        var loop = owl_slider.options.loop;

        if (loop) {
            var count = el.item.count - 1;
            var current = Math.round(el.item.index - el.item.count / 2 - 0.5);
            if (current < 0) {
                current = count;
            }
            if (current > count) {
                current = 0;
            }
        } else {
            var current = el.item.index;
        }

        var owl_thumbnail = sync2.data("owl.carousel");
        var itemClass = "." + owl_thumbnail.options.itemClass;

        var thumbnailCurrentItem = sync2
            .find(itemClass)
            .removeClass("synced")
            .eq(current);
        thumbnailCurrentItem.addClass("synced");

        if (!thumbnailCurrentItem.hasClass("active")) {
            var duration = 500;
            sync2.trigger("to.owl.carousel", [current, duration, true]);
        }
    }
    var thumbs = sync2
        .owlCarousel({
            startPosition: 12,
            items: 4,
            loop: false,
            margin: 10,
            autoplay: false,
            nav: false,
            dots: false,
            rtl: themeDirection && themeDirection.toString() === "rtl",
            responsive: {
                576: {
                    items: 4,
                },
                768: {
                    items: 4,
                },
                992: {
                    items: 4,
                },
                1200: {
                    items: 5,
                },
                1400: {
                    items: 5,
                },
            },
            onInitialized: function (e) {
                var thumbnailCurrentItem = $(e.target)
                    .find(thumbnailItemClass)
                    .eq(this._current);
                thumbnailCurrentItem.addClass("synced");
            },
        })
        .on("click", thumbnailItemClass, function (e) {
            e.preventDefault();
            var duration = 500;
            var itemIndex = $(e.target).parents(thumbnailItemClass).index();
            sync1.trigger("to.owl.carousel", [itemIndex, duration, true]);
        })
        .on("changed.owl.carousel", function (el) {
            var number = el.item.index;
            var owl_slider = sync1.data("owl.carousel");
            owl_slider.to(number, 500, true);
        });
    sync1.owlCarousel();
}

renderOwlCarouselSilder();

function findCountryObject(data) {
    return data.find((obj) => obj.types && obj.types.includes("country"));
}

$(".close-element-onclick-by-data").on("click", function () {
    $($(this).data("selector")).slideUp("slow").fadeOut("slow");
});
$(".password-check").on("keyup keypress change click", function () {
    let password = $(this).val();
    let passwordError = $(".password-error");
    let passwordErrorMessage = $("#password-error-message");
    switch (true) {
        case password.length < 8:
            passwordError
                .html(passwordErrorMessage.data("max-character"))
                .removeClass("d-none");
            break;
        case !/[a-z]/.test(password):
            passwordError
                .html(passwordErrorMessage.data("lowercase-character"))
                .removeClass("d-none");
            break;
        case !/[A-Z]/.test(password):
            passwordError
                .html(passwordErrorMessage.data("uppercase-character"))
                .removeClass("d-none");
            break;
        case !/\d/.test(password):
            passwordError
                .html(passwordErrorMessage.data("number"))
                .removeClass("d-none");
            break;
        case !/[@.#$!%*?&]/.test(password):
            passwordError
                .html(passwordErrorMessage.data("symbol"))
                .removeClass("d-none");
            break;
        default:
            passwordError.addClass("d-none").empty();
    }
});

$(".footer-slider").owlCarousel({
    margin: 10,
    items: 3,
    responsiveClass: true,
    nav: false,
    dots: false,
    loop: false,
    autoplay: false,
    rtl: themeDirection && themeDirection.toString() === "rtl",
    responsive: {
        500: {
            items: 3,
            nav: false,
            dots: false,
        },
    },
});

$(".footer-top-slider").owlCarousel({
    margin: 20,
    items: 2,
    responsiveClass: true,
    nav: false,
    dots: false,
    loop: false,
    autoplay: false,
    rtl: themeDirection && themeDirection.toString() === "rtl",
    responsive: {
        500: {
            items: 3,
            nav: false,
            dots: false,
        },
        768: {
            items: 4,
            nav: false,
            dots: false,
        },
    },
});

function playAudio() {
    document.getElementById("myAudio").play();
}

$(window).on("load", function () {
    $(".upload-file__input").on("change", function () {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            let img = $(this)
                .siblings(".upload-file__img")
                .find("img")
                .removeAttr("hidden");
            $(this)
                .siblings(".upload-file__img")
                .find(".temp-img-box")
                .remove();

            reader.onload = function (e) {
                img.attr("src", e.target.result);
            };

            reader.readAsDataURL(this.files[0]);
        }
    });
});
