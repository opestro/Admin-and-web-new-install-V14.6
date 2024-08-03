"use strict";

let getYesWord = $('#message-yes-word').data('text');
let getNoWord = $('#message-no-word').data('text');
let messageAreYouSureDeleteThis = $('#message-are-you-sure-delete-this').data('text');
let messageYouWillNotAbleRevertThis = $('#message-you-will-not-be-able-to-revert-this').data('text');

$('#banner_type_select').on('change', function () {
    let inputValue = $(this).val().toString();
    if (inputValue === "Main Banner") {
        $('.input-field-for-main-banner').removeClass('d-none');
    } else {
        $('.input-field-for-main-banner').addClass('d-none');
    }
});

$(".js-example-theme-single").select2({theme: "classic"});
$(".js-example-responsive").select2({width: 'resolve'});

$('#main-banner-add').on('click', function () {
    $('#main-banner').slideToggle();
});

$('.cancel').on('click', function () {
    $('.banner_form').attr('action', $('#route-admin-banner-store').data('url'));
    $('#main-banner').slideToggle();
});

$('.action-display-data').on('change', function () {
    let data = $(this).val();
    let elementResourceProduct = $('#resource-product');
    let elementResourceBrand = $('#resource-brand');
    let elementResourceCategory = $('#resource-category');
    let elementResourceShop = $('#resource-shop');

    elementResourceProduct.hide()
    elementResourceBrand.hide()
    elementResourceCategory.hide()
    elementResourceShop.hide()

    if (data === 'product') {
        elementResourceProduct.show()
    } else if (data === 'brand') {
        elementResourceBrand.show()
    } else if (data === 'category') {
        elementResourceCategory.show()
    } else if (data === 'shop') {
        elementResourceShop.show()
    }
})

$('#banner').on('change', function(){
    var input = this;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        let inputImage = $('.input_image');
        reader.onload = (e) => {
            let imageData = e.target.result;
            input.setAttribute("data-title", "");
            let img = new Image();
            img.onload = function () {
                inputImage.css({
                    "background-image": `url('${imageData}')`,
                    "width": "100%",
                    "height": "auto",
                    backgroundPosition: "center",
                    backgroundSize: "contain",
                    backgroundRepeat: "no-repeat",
                });
                inputImage.addClass('hide-before-content')
            };
            img.src = imageData;
        }
        reader.readAsDataURL(input.files[0]);
    }
});

$('.banner-delete-button').on('click', function () {
    let bannerId = $(this).attr("id");
    Swal.fire({
        title: messageAreYouSureDeleteThis,
        text: messageYouWillNotAbleRevertThis,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: getYesWord,
        cancelButtonText: getNoWord,
        type: 'warning',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $('#route-admin-banner-delete').data('url'),
                method: 'POST',
                data: {id: bannerId},
                success: function (response) {
                    toastr.success(response.message);
                    location.reload();
                }
            });
        }
    })
});

var backgroundImage = $("[data-bg-img]");
backgroundImage.css("background-image", function () {
    return 'url("' + $(this).data("bg-img") + '")';
}).removeAttr("data-bg-img").addClass("bg-img");

$('.most-demanded-product-delete-button').on('click', function () {
    let productId = $(this).attr("id");
    Swal.fire({
        title: "{{ translate('are_you_sure_delete_this_most_demanded_product') }}?",
        text: "{{ translate('you_will_not_be_able_to_revert_this') }}!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ translate("yes_delete_it") }}!',
        type: 'warning',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $('#route-admin-most-demanded-delete').data('url'),
                method: 'POST',
                data: {id: productId},
                success: function (response) {
                    toastr.success(response.message);
                    location.reload()
                }
            });
        }
    })
})

$('.most-demanded-status-form').on('submit', function(event){
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            toastr.success(response.message);
            setTimeout(function (){
                location.reload()
            },1000);
        }
    });
});
