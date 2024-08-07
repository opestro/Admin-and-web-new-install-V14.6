"use strict";

$(document).ready(function() {
    $('.delete-ticket-by-modal').on('click', function (){
        let link = $(this).data('link');
        $('#delete-ticket-link').attr('href', link);
        $('#delete-ticket-modal').modal('show');
    });

    const stickyElement = $('.bottom-sticky_ele');
    const offsetElement = $('.bottom-sticky_offset');

    if(stickyElement.length !== 0){
        $(window).on('scroll', function() {
            const elementOffset = offsetElement.offset().top;
            const scrollTop = $(window).scrollTop();
            if (scrollTop >= elementOffset) {
                stickyElement.addClass('stick');
            } else {
                stickyElement.removeClass('stick');
            }
        });
    }
});

let reviewSelectedFiles = [];
$(document).on('ready', () => {
    $(".attachmentfiles").on('change', function () {
        for (let i = 0; i < this.files.length; ++i) {
            reviewSelectedFiles.push(this.files[i]);
        }
        let pre_container = $(this).closest('.upload_images_area');
        displaySelectedFiles(pre_container);
    });

    function displaySelectedFiles(pre_container = null) {
        let container;
        if(pre_container == null) {
            container = document.getElementsByClassName("selected-files-container");
        }else {
            container = pre_container.find('.selected-files-container');
        }
        container.innerHTML = "";
        reviewSelectedFiles.forEach((file, index) => {
            const input = document.createElement("input");
            input.type = "file";
            input.name = `image[${index}]`;
            input.classList.add(`image_index${index}`);
            input.hidden = true;
            container.append(input);
            const blob = new Blob([file], { type: file.type });
            const file_obj = new File([file],file.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file_obj);
            input.files = dataTransfer.files;
        });

        pre_container.find(".filearray").empty();
        for (let i = 0; i < reviewSelectedFiles.length; ++i) {
            let filereader = new FileReader();
            let uploadDiv = jQuery.parseHTML("<div class='upload_img_box mb-2'><span class='img-clear'><i class='tio-clear'></i></span><img src='' alt=''></div>");

            filereader.onload = function () {
                let imageData = this.result;
                $(uploadDiv).find('img').attr('src', imageData);
            };

            filereader.readAsDataURL(reviewSelectedFiles[i]);
            pre_container.find(".filearray").append(uploadDiv);
            $(uploadDiv).find('.img-clear').on('click', function () {
                $(this).closest('.upload_img_box').remove();
                reviewSelectedFiles.splice(i, 1);
                $('.image_index'+i).remove();
            });
        }
    }
});
