'use strict';

$("#update-error-message").hide();

$("#update-button-message").click(function(){
    $("#update-error-message").slideDown();
});

$('#theme-form').on('submit', function(event){
    event.preventDefault();
    if ($('#input-file').prop('files').length === 0) {
        toastr.error($('#get-input-file-text').data('error'));
    } else {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        let formData = new FormData(document.getElementById('theme-form'));
        $.ajax({
            type: 'POST',
            url: $('#get-theme-install-route').data('action'),
            data: formData,
            processData: false,
            contentType: false,
            xhr: function () {
                let XMLHttpRequest = new window.XMLHttpRequest();
                $('#progress-bar').show();
                XMLHttpRequest.upload.addEventListener("progress", function (e) {
                    if (e.lengthComputable) {
                        let percentage = Math.round((e.loaded * 100) / e.total);
                        $("#upload-progress").val(percentage);
                        $("#progress-label").text(percentage + "%");
                    }
                }, false);
                return XMLHttpRequest;
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
        });
    }
});

$('.theme-publish').on('click',function (){
    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let theme = $(this).data('key');
    $.post({
        url: $('#get-theme-publish-route').data('action'),
        data: {
            theme
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

                    toastr.success($('#get-success-text').data('success'), {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    if(parseInt(data.reload_action) === 1){
                        setTimeout(function () {
                            location.reload()
                        }, 1500);
                    }else{
                        $("#informationModalContent").empty().html(data.informationModal);
                        $("#InformationThemeModal").addClass('bg-soft-dark').modal("show");
                    }
                }
            }
        },
        complete: function () {
            $('#loading').fadeOut();
        },
    });
})

$('.theme-delete').on('click',function (){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let theme = $(this).data('key');
    $.post({
        url: $('#get-theme-delete-route').data('action'),
        data: {
            theme
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
            } else if (data.status === 'error') {
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
$(document).on('load',function (){
    notifyAllTheSellers()
})
function notifyAllTheSellers(){
    $('.notify-all-the-sellers').on('click',function (){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let notifyAllVendor = $('#get-notify-all-vendor-route-and-img-src');
        $.post({
            url: notifyAllVendor.data('action'),
            _token:notifyAllVendor.data('csrf'),
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                let message_html = `<img src="${notifyAllVendor.data('src')}" alt="" width="50" class="mb-2">
                                            <h5 class="`+(data.status === 1? 'text-success':'text-danger')+`">${data.message}</h5>`;

                $('.notify-all-the-sellers-area').empty().html(message_html).fadeIn();
                setTimeout(function () {
                    location.reload()
                }, 10000);
            },
            complete: function () {
                $('#loading').fadeOut();
            },
        });
    })
}
