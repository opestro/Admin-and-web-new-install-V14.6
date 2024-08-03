"use strict";

let selectedFiles = [];
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

    $("#msgfilesValue").on('change', function () {
        for (let i = 0; i < this.files.length; ++i) {
            selectedFiles.push(this.files[i]);
        }
        displaySelectedFiles();
    });

    function displaySelectedFiles() {
        const container = document.getElementById("selected-files-container");
        container.innerHTML = ""; // Clear previous content
        selectedFiles.forEach((file, index) => {
            const input = document.createElement("input");
            input.type = "file";
            input.name = `image[${index}]`;
            input.classList.add(`image_index${index}`);
            input.hidden = true;
            container.appendChild(input);
            const blob = new Blob([file], {type: file.type});
            const file_obj = new File([file], file.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file_obj);
            input.files = dataTransfer.files;
        });

        $(".filearray").empty(); // Clear previous user input
        for (let i = 0; i < selectedFiles.length; ++i) {
            let filereader = new FileReader();
            let $uploadDiv = jQuery.parseHTML("<div class='upload_img_box'><span class='img-clear'><i class='tio-clear'></i></span><img src='' alt=''></div>");

            filereader.onload = function () {
                $($uploadDiv).find('img').attr('src', this.result);
                let imageData = this.result;
            };

            filereader.readAsDataURL(selectedFiles[i]);
            $(".filearray").append($uploadDiv);
            $($uploadDiv).find('.img-clear').on('click', function () {
                $(this).closest('.upload_img_box').remove();
                selectedFiles.splice(i, 1);
                $('.image_index' + i).remove();
            });
        }
    }
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
            beforeSend: function () {
                $("#msgSendBtn").attr('disabled', true);
            },
            success: function (response) {
                $('#chatting-messages-section').html(response.chattingMessages)
                $("#msgInputValue").val('')
                $(".filearray").empty()
                let container = document.getElementById("selected-files-container");
                container.innerHTML = "";
                selectedFiles = [];
            }, complete: function () {
                $("#msgSendBtn").removeAttr('disabled');
            },
            error: function (error) {
                let errorData = JSON.parse(error.responseText);
                toastr.warning(errorData.message);
            }
        })
    })
}
