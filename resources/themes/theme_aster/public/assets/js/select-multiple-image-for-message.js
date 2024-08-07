'use strict';
let selectedImages = [];
$(document).ready(function(){
    $("#select-image").on('change', function () {
        for (let index = 0; index < this.files.length; ++index) {
            selectedImages.push(this.files[index]);
        }
        displaySelectedImages();
        this.value = null;
    });

    function displaySelectedImages() {
        const containerImage = document.getElementById("selected-image-container");
        containerImage.innerHTML = "";
        selectedImages.forEach((file, index) => {
            const input = document.createElement("input");
            input.type = "file";
            input.name = `image[${index}]`;
            input.classList.add(`image-index${index}`);
            input.hidden = true;
            containerImage.appendChild(input);
            const blob = new Blob([file], { type: file.type });
            const file_obj = new File([file],file.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file_obj);
            input.files = dataTransfer.files;
        });
        let imageArray = $('.image-array');
        imageArray.empty();
        for (let index = 0; index < selectedImages.length; ++index) {
            let fileReader = new FileReader();
            let $uploadDiv = jQuery.parseHTML("<div class='upload_img_box'><span class='img-clear'><i class='bi bi-x-circle-fill'></i></span><img src='' alt=''></div>");

            fileReader.onload = function () {
                $($uploadDiv).find('img').attr('src', this.result);
                let imageData = this.result;
            };

            fileReader.readAsDataURL(selectedImages[index]);
            imageArray.append($uploadDiv);
            $($uploadDiv).find('.img-clear').on('click', function () {
                $(this).closest('.upload_img_box').remove();
                $('.image-index'+index).remove();
                selectedImages.splice(selectedImages.indexOf(index), 1);
            });
        }
    }
});
