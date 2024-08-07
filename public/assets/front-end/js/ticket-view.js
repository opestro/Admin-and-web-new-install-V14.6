"use strict";

$(document).on('ready', () => {
    let selectedFiles = [];

    $("#f_p_v_up1").on('change', function () {
        for (let i = 0; i < this.files.length; ++i) {
            selectedFiles.push(this.files[i]);
        }
        displaySelectedFiles();
    });

    function displaySelectedFiles() {
        const container = document.getElementById("selected-files-container");
        container.innerHTML = "";
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
        $(".filearray").empty();

        for (let i = 0; i < selectedFiles.length; ++i) {
            let filereader = new FileReader();
            let $uploadDiv = jQuery.parseHTML("<div class='upload_img_box'><span class='img-clear'><i class='tio-clear'></i></span><img src='' width='40' alt=''></div>");

            filereader.onload = function () {
                $($uploadDiv).find('img').attr('src', this.result);
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
