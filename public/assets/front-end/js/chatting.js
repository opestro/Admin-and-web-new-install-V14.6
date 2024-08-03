"use strict";

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
    $('.get-ajax-message-view').removeClass('active')
    $('.get-ajax-message-view .chat_ib h5').addClass('active-text');
    $(this).addClass('active')
    $(this).find('.chat_ib h5').removeClass('active-text');
    let userId = $(this).data('user-id');
    let actionURL = $('#chatting-post-url').data('url') + userId;
    console.log(userId)
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
                $('.profile-image').attr('src', response.userData.image)
                $('.profile-name').html(response.userData.name)
                $('#profile_phone').html(response.userData.phone)
                if(parseInt(response.userData['temporary-close-status']) === 1){
                    $('.temporarily-closed-sticky-alert').removeClass('d-none').css({
                        'display': '',
                    });
                }else{
                    $('.temporarily-closed-sticky-alert').addClass('d-none').css({
                        'display': 'none',
                    })
                }
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
    $('.chatting-messages-form').on('submit', function (event) {
        event.preventDefault()
        let userId = $('.get-ajax-message-view.active').data('user-id');
        let actionURL = $('#chatting-post-url').data('url') + userId;
        let formData = new FormData(this);
        $.ajaxSetup({
            headers: {'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
            type: "POST",
            url: actionURL,
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
