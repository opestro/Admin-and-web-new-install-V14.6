"use strict";

let getYesWord = $('#message-yes-word').data('text');
let getCancelWord = $('#message-cancel-word').data('text');
let messageAreYouSureDeleteThis = $('#message-are-you-sure-delete-this').data('text');
let messageYouWillNotAbleRevertThis = $('#message-you-will-not-be-able-to-revert-this').data('text');

$('.attribute-delete-button').on('click', function () {
    let attributeId = $(this).attr("id");
    Swal.fire({
        title: messageAreYouSureDeleteThis,
        text: messageYouWillNotAbleRevertThis,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: getYesWord,
        cancelButtonText: getCancelWord,
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
                url: $('#route-admin-attribute-delete').data('url'),
                method: 'POST',
                data: {id: attributeId},
                success: function (response) {
                    toastr.success(response.message);
                    location.reload();
                }
            });
        }
    })
})

$('.delete-brand').on('click', function () {
    let brandId = $(this).attr("id");
    Swal.fire({
        title: messageAreYouSureDeleteThis,
        text: messageYouWillNotAbleRevertThis,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: getYesWord,
        cancelButtonText: getCancelWord,
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            let brands =$('#get-brands').data('brands').data
            brands = brands.filter(brands =>brands.id !== parseInt(brandId));
            let selectDropdown = $('.brand-option').empty();
            brands.forEach(brand => {
                let option = $('<option></option>').attr('value', brand.id).text(brand.name);
                selectDropdown.append(option);
            });
            $('input[name=id]').val(brandId)
            $('.brand-title-message').html($(this).data('text'))
            if($(this).data('product-count') > 0){
                $('#select-brand-modal').modal('show');
            }else{
                $('.product-brand-update-form-submit').submit();
            }
        }
    })
});

$('.delete-category').on('click', function () {
    let categoryId = $(this).attr("id");
    Swal.fire({
        title: messageAreYouSureDeleteThis,
        text: messageYouWillNotAbleRevertThis,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: getYesWord,
        cancelButtonText: getCancelWord,
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            let categories =$('#get-categories').data('categories').data
            categories = categories.filter(category =>category.id !== parseInt(categoryId));
            let selectDropdown = $('.category-option').empty();
            categories.forEach(category => {
                let option = $('<option></option>').attr('value', category.id).text(category.name);
                selectDropdown.append(option);
            });
            $('input[name=id]').val(categoryId)

            $('.category-title-message').html($(this).data('text'))
            if($(this).data('product-count') > 0){
                $('#select-category-modal').modal('show');
            }else{
                $('.product-category-update-form-submit').submit();
            }

        }
    })
});

$('.category-delete-button').on('click', function () {
    let categoryId = $(this).attr("id");
    Swal.fire({
        title: messageAreYouSureDeleteThis,
        text: messageYouWillNotAbleRevertThis,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: getYesWord,
        cancelButtonText: getCancelWord,
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $('#route-admin-category-delete').data('url'),
                method: 'POST',
                data: {id: categoryId},
                success: function (response) {
                    toastr.success(response.message);
                    location.reload();
                }
            });
        }
    })
});

$('.action-get-sub-category-onchange').on('change', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: $(this).data('route'),
        data: {
            id: $(this).val()
        },
        success: function (response) {
            $("#parent_id").html(response.html);
        }
    });
});
