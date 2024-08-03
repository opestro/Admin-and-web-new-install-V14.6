"use strict";

$(".js-example-responsive").select2({
    width: 'resolve'
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#viewer').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#customFileEg1").change(function () {
    readURL(this);
});

$(function () {
    let coba_image = $('#coba-image').data('url');
    let extension_error = $('#extension-error').data('text');
    let size_error = $('#size-error').data('text');
    $("#coba").spartanMultiImagePicker({
        fieldName: 'identity_image[]',
        maxCount: 5,
        rowHeight: '248px',
        groupClassName: 'col-6',
        maxFileSize: '',
        placeholderImage: {
            image: coba_image,
            width: '100%'
        },
        dropFileLabel: "Drop Here",
        onAddRow: function (index, file) {

        },
        onRenderedPreview: function (index) {

        },
        onRemoveRow: function (index) {

        },
        onExtensionErr: function (index, file) {
            toastr.error(extension_error, {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function (index, file) {
            toastr.error(size_error, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
});

$('.deliveryman_status_form').on('submit', function (event){
    event.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url:$(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function (data) {
            toastr.success($('#deliveryman-status-message').data('text'));
        }
    });
});
