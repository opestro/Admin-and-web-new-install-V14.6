"use strict";

let elementProductTypeByID = $('#product_type');
let elementAdditionalImageColumn = $('.additional_image_column');
let elementCustomUploadInputFileByID = $('.custom-upload-input-file');
let elementDigitalProductTypeByID = $('#digital_product_type');
let elementProductColorSwitcherByID = $('#product-color-switcher');
let elementImagePathOfProductUploadIconByID = $('#image-path-of-product-upload-icon').data('path');
let messageEnterChoiceValues = $('#message-enter-choice-values').data('text');
let messageUploadImage = $('#message-upload-image').data('text');
let messageFileSizeTooBig = $('#message-file-size-too-big').data('text');
let messagePleaseOnlyInputPNGOrJPG = $('#message-please-only-input-png-or-jpg').data('text');
let messageAreYouSure = $('#message-are-you-sure').data('text');
let messageYesWord = $('#message-yes-word').data('text');
let messageNoWord = $('#message-no-word').data('text');
let messageWantAddOrUpdateThisProduct = $('#message-want-to-add-or-update-this-product').data('text');
let getSystemCurrencyCode = $('#system-currency-code').data('value');

$(document).on('ready', function () {
    $('.summernote').summernote({
        'height': 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

    getProductTypeFunctionality();
    getDigitalProductTypeFunctionality();

    if ($('#product-color-switcher').prop('checked')) {
        $('#color-wise-image-area').show();
        colorWiseImageFunctionality($('#colors-selector'));
    } else {
        $('#color-wise-image-area').hide();
    }

    $('.color-var-select').select2({
        templateResult: colorCodeSelect,
        templateSelection: colorCodeSelect,
        escapeMarkup: function (m) {return m;}
    })

    function colorCodeSelect(state) {
        let colorCode = $(state.element).val();
        if (!colorCode) return state.text;
        return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state
            .text;
    }
});

function getProductTypeFunctionality() {
    let productType = elementProductTypeByID.val();
    if (productType && productType.toString() === 'physical') {
        $('#digital_product_type_show').hide();
        $('#digital_file_ready_show').hide();
        $('.physical_product_show').show();
        elementDigitalProductTypeByID.val($('#digital_product_type option:first').val());
        $('#digital_file_ready').val('');
    } else if (productType && productType.toString() === 'digital') {
        $('#digital_product_type_show').show();
        $('.physical_product_show').hide();
    }
}

function getDigitalProductTypeFunctionality() {
    let digitalProductType = elementDigitalProductTypeByID.val();
    if (digitalProductType && digitalProductType.toString() === 'ready_product') {
        $('#digital_file_ready_show').show();
    } else if (digitalProductType && digitalProductType.toString() === 'ready_after_sell') {
        $('#digital_file_ready_show').hide();
        $("#digital_file_ready").val('');
    }
}

elementProductTypeByID.on('change', () => getProductTypeFunctionality())
elementDigitalProductTypeByID.on('change', () => getDigitalProductTypeFunctionality())

elementProductColorSwitcherByID.on('click', function () {
    if (elementProductColorSwitcherByID.prop('checked')) {
        $('.color_image_column').removeClass('d-none');
        elementAdditionalImageColumn.removeClass('col-md-9');
        elementAdditionalImageColumn.addClass('col-md-12');
        $('#color-wise-image-area').show();
        $('#additional_Image_Section .col-md-4').addClass('col-lg-2');
    } else {
        $('.color_image_column').addClass('d-none');
        elementAdditionalImageColumn.addClass('col-md-9');
        elementAdditionalImageColumn.removeClass('col-md-12');
        $('#color-wise-image-area').hide();
        $('#additional_Image_Section .col-md-4').removeClass('col-lg-2');
    }
});

$(document).on('ready', function () {
    if (elementProductColorSwitcherByID.prop('checked')) {
        $('.color_image_column').removeClass('d-none');
        elementAdditionalImageColumn.removeClass('col-md-9');
        elementAdditionalImageColumn.addClass('col-md-12');
        $('#additional_Image_Section .col-md-4').addClass('col-lg-2');
    } else {
        $('.color_image_column').addClass('d-none');
        elementAdditionalImageColumn.addClass('col-md-9');
        elementAdditionalImageColumn.removeClass('col-md-12');
        $('#additional_Image_Section .col-md-4').removeClass('col-lg-2');
    }
});

$('input[name="colors_active"]').on('change', function () {
    if (!$('input[name="colors_active"]').is(':checked')) {
        $('#colors-selector').prop('disabled', true);
    } else {
        $('#colors-selector').prop('disabled', false);
    }
});

$('#choice_attributes').on('change', function () {

    let colors = $('#colors-selector').val();
    let choiceAttributes = $('#choice_attributes').val();
    if (colors.length === 0 && choiceAttributes.length === 0 || (!$('#product-color-switcher').prop('checked') && choiceAttributes.length === 0)) {
        $('#sku_combination').empty().html('');
    }

    $('#customer_choice_options').empty().html('');
    $.each($("#choice_attributes option:selected"), function () {
        addMoreCustomerChoiceOption($(this).val(), $(this).text());
    });
})

$('#colors-selector').on('change', function () {
    getUpdateSKUFunctionality();
    if (elementProductColorSwitcherByID.prop('checked')) {
        colorWiseImageFunctionality($('#colors-selector'));
        $('#color-wise-image-area').show();
    }else {
        $('#color-wise-image-area').hide();
    }
})

$('input[name="unit_price"]').on('keyup', function () {
    let productType = elementProductTypeByID.val();
    if (productType && productType.toString() === 'physical') {
        getUpdateSKUFunctionality();
    }
})

function getUpdateSKUFunctionality() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: $('#route-admin-products-sku-combination').data('url'),
        data: $('#product_form').serialize(),
        success: function (data) {
            $('#sku_combination').html(data.view);
            updateProductQuantity();
            updateProductQuantityByKeyUp()
            if (data.length > 1) {
                $('#quantity').hide();
            } else {
                $('#quantity').show();
            }
            generateSKUPlaceHolder();
            removeSymbol();
        }
    });
}

$('#discount_type').on('change', function () {
    if ($(this).val().toString() === 'flat') {
        $('.discount_amount_symbol').html(`(`+ getSystemCurrencyCode +`)`).fadeIn();
    } else {
        $('.discount_amount_symbol').html("(%)").fadeIn();
    }
})

$('.action-add-more-image').on('change', function () {
    let parentDiv = $(this).closest('div');
    parentDiv.find('.delete_file_input').removeClass('d-none');
    parentDiv.find('.delete_file_input').fadeIn();
    addMoreImage(this, $(this).data('target-section'))
})

function addMoreImage(thisData, targetSection) {
    let $fileInputs = $(targetSection + " input[type='file']");
    let nonEmptyCount = 0;
    $fileInputs.each(function () {
        if (parseFloat($(this).prop('files').length) === 0) {
            nonEmptyCount++;
        }
    });

    uploadColorImage(thisData)

    if (nonEmptyCount === 0) {

        let datasetIndex = thisData.dataset.index + 1;

        let newHtmlData = `<div class="col-sm-12 col-md-4">
                        <div class="custom_upload_input position-relative border-dashed-2">
                            <input type="file" name="${thisData.name}" class="custom-upload-input-file action-add-more-image" data-index="${datasetIndex}" data-imgpreview="additional_Image_${datasetIndex}"
                                accept=".jpg, .webp, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" data-target-section="${targetSection}">

                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn d-none">
                                <i class="tio-delete"></i>
                            </span>

                            <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                <img alt="" id="additional_Image_${datasetIndex}" class="h-auto aspect-1 bg-white d-none" src="img">
                            </div>
                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="`+ elementImagePathOfProductUploadIconByID +`" class="w-50" alt="">
                                    <h3 class="text-muted">`+ messageUploadImage +`</h3>
                                </div>
                            </div>
                        </div>
                    </div>`;

        $(targetSection).append(newHtmlData);
    }

    elementCustomUploadInputFileByID.on('change', function () {
        if (parseFloat($(this).prop('files').length) !== 0) {
            let parentDiv = $(this).closest('div');
            parentDiv.find('.delete_file_input').fadeIn();
        }
    })

    $('.delete_file_input_section').click(function () {
        $(this).closest('div').parent().remove();
    });

    if (elementProductColorSwitcherByID.prop('checked')) {
        $('#additional_Image_Section .col-md-4').addClass('col-lg-2');
    } else {
        $('#additional_Image_Section .col-md-4').removeClass('col-lg-2');
    }

    $('.action-add-more-image').on('change', function () {
        let parentDiv = $(this).closest('div');
        parentDiv.find('.delete_file_input').removeClass('d-none');
        parentDiv.find('.delete_file_input').fadeIn();
        addMoreImage(this, $(this).data('target-section'))
    })

    $('.onerror-add-class-d-none').on('error', function () {
        $(this).addClass('d-none')
    })

    onErrorImage()
}

$(function () {
    $("#coba").spartanMultiImagePicker({
        fieldName: 'images[]',
        maxCount: 15,
        rowHeight: 'auto',
        groupClassName: 'col-6 col-md-4 col-lg-3 col-xl-2',
        maxFileSize: '',
        placeholderImage: {
            image: $('#image-path-of-product-upload-icon-two').data('path'),
            width: '100%',
        },
        dropFileLabel: "Drop Here",
        onAddRow: function (index, file) {

        },
        onRenderedPreview: function (index) {

        },
        onRemoveRow: function (index) {

        },
        onExtensionErr: function () {
            toastr.error(messagePleaseOnlyInputPNGOrJPG, {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function () {
            toastr.error(messageFileSizeTooBig, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
});


function addMoreCustomerChoiceOption(index, name) {
    let nameSplit = name.split(' ').join('');
    let genHtml = `<div class="col-md-6"><div class="form-group">
                <input type="hidden" name="choice_no[]" value="${index}">
                    <label class="title-color">${nameSplit}</label>
                    <input type="text" name="choice[]" value="${nameSplit}" hidden>
                    <div class="">
                        <input type="text" class="form-control" name="choice_options_${index}[]"
                        placeholder="`+ messageEnterChoiceValues +`" data-role="tagsinput" onchange="getUpdateSKUFunctionality()">
                    </div>
                </div>
        </div>`;
    $('#customer_choice_options').append(genHtml);
    $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
}

$('.delete_file_input').on('click', function () {
    let $parentDiv = $(this).parent().parent();
    $parentDiv.find('input[type="file"]').val('');
    $parentDiv.find('.img_area_with_preview img').addClass("d-none");
    $(this).removeClass('d-flex');
    $(this).hide();
});

$('.onerror-add-class-d-none').on('error', function () {
    $(this).addClass('d-none')
})

function uploadColorImage(thisData = null) {
    if (thisData) {
        document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
        document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
    }
}

$('.action-upload-color-image').on('change', function () {
    uploadColorImage(this)
})

$('.delete_file_input').click(function () {
    let $parentDiv = $(this).closest('div');
    $parentDiv.find('input[type="file"]').val('');
    $parentDiv.find('.img_area_with_preview img').addClass("d-none");
    $(this).hide();
});

elementCustomUploadInputFileByID.on('change', function () {
    if (parseFloat($(this).prop('files').length) !== 0) {
        let $parentDiv = $(this).closest('div');
        $parentDiv.find('.delete_file_input').fadeIn();
    }
})

$('.product-add-requirements-check').on('click', function () {
    getProductAddRequirementsCheck()
})

$('.action-onclick-generate-number').on('click', function () {
    let getElement = $(this).data('input');
    $(getElement).val(generateRandomString(6));
    generateSKUPlaceHolder();
})
function generateRandomString(length) {
    let result = '';
    let characters = '012345ABCDEFGHIJKLMNOPQRSTUVWXYZ3456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function getProductAddRequirementsCheck() {
    Swal.fire({
        title: messageAreYouSure,
        text: messageWantAddOrUpdateThisProduct,
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: 'default',
        confirmButtonColor: '#377dff',
        cancelButtonText: messageNoWord,
        confirmButtonText: messageYesWord,
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            let discountValue = parseFloat($('#discount').val());
            let submitStatus = 1;
            $(".variation-price-input").each(function () {
                let variationPrice = parseFloat($(this).val());
                if (variationPrice < discountValue) {
                    toastr.error($('#message-discount-will-not-larger-then-variant-price').data('text'));
                    submitStatus = 0;
                    return false;
                }
            });

            if (submitStatus === 1) {
                let formData = new FormData(document.getElementById('product_form'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: $('#product_form').attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.errors) {
                            for (let i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success($('#message-product-added-successfully').data('text'), {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('#product_form').submit();
                        }
                    }
                });
            }

        }
    })
}

$('#generate_number').on('keyup', function() {
    generateSKUPlaceHolder();
});
 function generateSKUPlaceHolder(){
     let newPlaceholderValue = $('#get-example-text').data('example')+' : '+$('input[name=code]').val()+'-MCU-47-V593-M';
     $('.store-keeping-unit').attr('placeholder', newPlaceholderValue);
 }
$(window).on('load',function (){
    generateSKUPlaceHolder();
})
