"use strict";

$('.action-get-refund-details').on('click', function (){
    let route = $(this).data('route');
    getRefundDetails(route)
})

function getRefundDetails(route) {
    $.get(route, (response) => {
        $("#refund_details_field").html(response);
        $('#refund_details_modal').modal().show();
    })
}

$('.action-digital-product-download').on('click', function (){
    let link = $(this).data('link');
    digitalProductDownload(link);
})

function digitalProductDownload(link) {
    $.ajax({
        type: "GET",
        url: link,
        responseType: 'blob',
        beforeSend: function () {
            $('#loading').show();
        },
        success: function (data) {
            if (data.status == 1 && data.file_path) {
                const a = document.createElement('a');
                a.href = data.file_path;
                a.download = data.file_name;
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(data.file_path);
            }
        },
        error: function () {
        },
        complete: function () {
            $('#loading').hide();
        },
    });
}

let selectedFiles = [];
$(document).on('ready', () => {
    $(".msgfilesValue").on('change', function () {
        for (let i = 0; i < this.files.length; ++i) {
            selectedFiles.push(this.files[i]);
        }
        let pre_container = $(this).closest('.upload_images_area');
        displaySelectedFiles(pre_container);
    });

    function displaySelectedFiles(pre_container = null) {
        let container;
        if (pre_container == null) {
            container = document.getElementsByClassName("selected-files-container");
        } else {
            container = pre_container.find('.selected-files-container');
        }
        container.innerHTML = "";
        selectedFiles.forEach((file, index) => {
            const input = document.createElement("input");
            input.type = "file";
            input.name = `images[${index}]`;
            input.classList.add(`image_index${index}`);
            input.hidden = true;
            container.append(input);
            const blob = new Blob([file], {type: file.type});
            const file_obj = new File([file], file.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file_obj);
            input.files = dataTransfer.files;
        });

        pre_container.find(".filearray").empty();
        for (let i = 0; i < selectedFiles.length; ++i) {
            let filereader = new FileReader();
            let uploadDiv = jQuery.parseHTML("<div class='upload_img_box'><span class='img-clear'><i class='tio-clear'></i></span><img src='' alt=''></div>");

            filereader.onload = function () {
                let imageData = this.result;
                $(uploadDiv).find('img').attr('src', imageData);
            };

            filereader.readAsDataURL(selectedFiles[i]);
            pre_container.find(".filearray").append(uploadDiv);
            $(uploadDiv).find('.img-clear').on('click', function () {
                $(this).closest('.upload_img_box').remove();

                selectedFiles.splice(i, 1);
                $('.image_index' + i).remove();
            });
        }
    }
});


let reviewSelectedFiles = [];
$(document).on('ready', () => {
    $(".reviewFilesValue").on('change', function () {
        for (let i = 0; i < this.files.length; ++i) {
            reviewSelectedFiles.push(this.files[i]);
        }
        let pre_container = $(this).closest('.upload_images_area');
        reviewFilesValueDisplaySelectedFiles(pre_container);
    });

    function reviewFilesValueDisplaySelectedFiles(pre_container = null) {
        let container;
        if (pre_container == null) {
            container = document.getElementsByClassName("selected-files-container");
        } else {
            container = pre_container.find('.selected-files-container');
        }
        container.innerHTML = "";
        reviewSelectedFiles.forEach((file, index) => {
            const input = document.createElement("input");
            input.type = "file";
            input.name = `fileUpload[${index}]`;
            input.classList.add(`image_index${index}`);
            input.hidden = true;
            container.append(input);
            const blob = new Blob([file], {type: file.type});
            const file_obj = new File([file], file.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file_obj);
            input.files = dataTransfer.files;
        });

        pre_container.find(".filearray").empty();
        for (let i = 0; i < reviewSelectedFiles.length; ++i) {
            let filereader = new FileReader();
            let uploadDiv = jQuery.parseHTML("<div class='upload_img_box'><span class='img-clear'><i class='tio-clear'></i></span><img src='' alt=''></div>");

            filereader.onload = function () {
                let imageData = this.result;
                $(uploadDiv).find('img').attr('src', imageData);
            };

            filereader.readAsDataURL(reviewSelectedFiles[i]);
            pre_container.find(".filearray").append(uploadDiv);
            $(uploadDiv).find('.img-clear').on('click', function () {
                $(this).closest('.upload_img_box').remove();
                reviewSelectedFiles.splice(i, 1);
                $('.image_index' + i).remove();
            });
        }
    }
});
