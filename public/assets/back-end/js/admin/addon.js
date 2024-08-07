'use strict';

$("#update-error-message").hide();

$("#update-button-message").click(function(){
    $("#update-error-message").slideDown();
});


$('#addon-upload-form').on('submit', function(event){
    event.preventDefault();
    if ($('#input-file').prop('files').length === 0) {
        toastr.error($('#get-file-upload-field-required-message').data('error'));
    } else {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        let formData = new FormData(document.getElementById('addon-upload-form'));
        $.ajax({
            type: 'POST',
            url: $('#get-addon-upload-route').data('action'),
            data: formData,
            processData: false,
            contentType: false,
            xhr: function () {
                let xhr = new window.XMLHttpRequest();
                $('#progress-bar').show();
                xhr.upload.addEventListener("progress", function (e) {
                    if (e.lengthComputable) {
                        let percentage = Math.round((e.loaded * 100) / e.total);
                        $("#uploadProgress").val(percentage);
                        $("#progress-label").text(percentage + "%");
                    }
                }, false);

                return xhr;
            },
            beforeSend: function () {
                $('#upload-theme').attr('disabled');
            },
            success: function (response) {
                if (response.status === 'error') {
                    $('#progress-bar').hide();
                    toastr.error(response.message);
                } else if (response.status === 'success') {
                    toastr.success(response.message);
                    location.reload();
                }
            },
            complete: function () {
                $('#upload-theme').removeAttr('disabled');
            },
            error: function (errors) {
                toastr.error(errors.responseJSON.message);
            },
        });
    }
});

$('#publish-addon').on('click',function (){
    let path = $(this).data('path');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post({
        url: $('#get-addon-publish-route').data('action'),
        data: {
            path
        },
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function (data) {
            if (data.flag === 'inactive') {
                $('#activateData').empty().html(data.view);
                $("#activatedThemeModal").addClass('bg-soft-dark').modal("show");
            } else {
                if (data.errors) {
                    for (let i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    toastr.success($('#get-update-success-message').data('text'), {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function () {
                        location.reload()
                    }, 2000);
                }
            }
        },
        complete: function () {
            $('#loading').fadeOut();
        },
    });
})

$('#theme-delete').on('click',function (){
    let path = $(this).data('path');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post({
        url: $('#get-addon-delete-route').data('action'),
        data: {
            path
        },
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function (data) {
            if (data.status === 'success') {
                setTimeout(function () {
                    location.reload()
                }, 2000);

                toastr.success(data.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }else if(data.status === 'error'){
                toastr.error(data.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        },
        complete: function () {
            $('#loading').fadeOut();
        },
    });
})
