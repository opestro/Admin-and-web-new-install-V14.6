"use strict";
$(function () {
    $(".coba").spartanMultiImagePicker({
        fieldName: 'fileUpload[]',
        maxCount: 5,
        rowHeight: '150px',
        groupClassName: 'col-md-4',
        placeholderImage: {
            image: $('#get-place-holder-image').data('src'),
            width: '100%'
        },
        dropFileLabel: "Drop Here",
        onAddRow: function (index, file) {

        },
        onRenderedPreview: function (index) {

        },
        onRemoveRow: function (index) {

        },
        onExtensionErr: function () {
            toastr.error('input_png_or_jpg', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function () {
            toastr.error('file_size_too_big', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    })
});
$(function () {
    $(".coba_refund").spartanMultiImagePicker({
            fieldName: 'images[]',
            maxCount: 5,
            rowHeight: '150px',
            groupClassName: 'col-md-4',
            maxFileSize: '',
            placeholderImage: {
                image: $('#get-place-holder-image').data('src'),
            width: '100%'
        },
        dropFileLabel: "{{translate('drop_here')}}",
        onAddRow: function (index, file) {

    },
    onRenderedPreview: function (index) {

    },
    onRemoveRow: function (index) {

    },
    onExtensionErr: function () {
        toastr.error('input_png_or_jpg', {
            CloseButton: true,
            ProgressBar: true
        });
    },
    onSizeErr: function () {
        toastr.error('file_size_too_big', {
            CloseButton: true,
            ProgressBar: true
        });
    }
    });
});

$('.remove-mask-img').on('click', function(){
    $('.show-more--content').removeClass('active')
})
