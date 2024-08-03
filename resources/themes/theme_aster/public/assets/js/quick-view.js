'use strict';
$(document).ready(function () {
    getVariantPrice();
    function stockCheckQuickView() {
        let productQty = $('.product_quantity__qty');
        let minValue = parseInt(productQty.attr('min'));
        let maxValue = parseInt(productQty.attr('max'));
        let valueCurrent = parseInt(productQty.val());

        if (minValue >= valueCurrent) {
            productQty.val(minValue);

            try {
                if (productQty.data('details-page')) {
                    productQty.parent().find(".quantity__minus").html('<i class="bi bi-dash"></i>');
                }else{
                    productQty.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
                }
            }catch (e) {
                productQty.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
            }
        } else {
            productQty.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
        }

        if (valueCurrent > maxValue) {
            toastr.warning('Sorry,stock limit exceeded');
            productQty.val(maxValue);
        }
        getVariantPrice();
    }

    $('#add-to-cart-form input').on('change', function () {
        stockCheckQuickView();
    });
    $('#add-to-cart-form').on('submit', function (e) {
        e.preventDefault();
    });
    $('.single-quantity-plus').on('click', function () {
        let $qty = $(this).parent().find('input');
        let currentVal = parseInt($qty.val());
        if (!isNaN(currentVal)) {
            $qty.val(currentVal + 1);
        }
        if (currentVal >= $qty.attr('max') - 1) {
            $(this).attr('disabled', true);
        }
        stockCheckQuickView();
    });
    $('.single-quantity-minus').on('click', function () {
        let $qty = $(this).parent().find('input');
        let currentVal = parseInt($qty.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            $qty.val(currentVal - 1);
        }
        if (currentVal < $qty.attr('max')) {
            $('.single-quantity-plus').removeAttr('disabled', true);
        }
        stockCheckQuickView();
    });
});
var quickviewSliderThumb2 = new Swiper(".quickviewSliderThumb2", {
    spaceBetween: 10,
    slidesPerView: "auto",
    freeMode: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: ".swiper-quickview-button-next",
        prevEl: ".swiper-quickview-button-prev",
    },
});
var quickviewSlider2 = new Swiper(".quickviewSlider2", {
    // spaceBetween: 10,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    thumbs: {
        swiper: quickviewSliderThumb2,
    },
});
$(".easyzoom").each(function () {
    $(this).easyZoom();
});

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
