"use strict";

// let selectedImage = [];
$(document).on('ready', function () {
    ajaxFormRenderChattingMessages()

    $("#myInput").on("keyup", function (e) {
        var value = $(this).val().toLowerCase();
        $(".list_filter").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('#chat-search-form').on('submit', function (e) {
        e.preventDefault();
    });
});

$('.get-ajax-message-view').on('click', function () {
    $('.get-ajax-message-view').removeClass('bg-soft-secondary')
    $(this).addClass('bg-soft-secondary')
    let userId = $(this).data('user-id');
    $('.notify-alert-' + userId).remove();
    let actionURL = $('#chatting-post-url').data('url') + userId;

    $.ajaxSetup({
        headers: {'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    $.ajax({
        url: actionURL,
        type: "GET",
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function (response) {
            if (response.userData) {
                $('#chatting-messages-section').html(response.chattingMessages)
                $('#profile_image').attr('src', response.userData.image)
                $('#profile_name').html(response.userData.name)
                $('#profile_phone').html(response.userData.phone)
                $('#current-user-hidden-id').val(userId)
            }
        },
        complete: function () {
            $('#loading').fadeOut();
            $('[data-toggle="tooltip"]').tooltip()
        },
    })
})

function ajaxFormRenderChattingMessages() {
    $('.chatting-messages-ajax-form').on('submit', function (event) {
        event.preventDefault()

        let formData = new FormData(this);
        $.ajaxSetup({
            headers: {'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            type: "POST",
            url: $('#chatting-post-url').data('url'),
            data: formData,
            processData: false,
            contentType: false,
            xhr: function(){
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt){
                    if(evt.lengthComputable){
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $('.circle-progress').show();
                        $('.circle-progress').find('.text').text(`Uploading ${selectedFiles.length} files`);
                        $('.circle-progress').find('#bar').attr('stroke-dashoffset', 100 - percentComplete);
                        if(percentComplete == 100){
                            $('.circle-progress').find('.text').text(`Uploaded ${selectedFiles.length} files`);
                            $('.circle-progress').hide();
                        }
                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {
                $("#msgSendBtn").attr('disabled', true);

            },
            success: function (response) {
                $('#chatting-messages-section').html(response.chattingMessages)
                $("#msgInputValue").val('')
                $(".image-array").empty()
                $(".file-array").empty()
                let container = document.getElementById("selected-files-container");
                let containerImage = document.getElementById("selected-image-container");
                container.innerHTML = "";
                containerImage.innerHTML = "";
                selectedFiles = [];
                selectedImages = [];
            }, complete: function () {
                $('.circle-progress').hide()
                $("#msgSendBtn").removeAttr('disabled');
                $('[data-toggle="tooltip"]').tooltip()
            },
            error: function (error) {
                let errorData = JSON.parse(error.responseText);
                toastr.warning(errorData.message);
            }
        })
    })
}
