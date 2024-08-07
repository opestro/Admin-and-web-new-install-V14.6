'use strict'
let selectedFiles = [];
$(document).ready(function(){
    $("#select-file").on('change', function () {
        for (let index = 0; index < this.files.length; ++index) {
            selectedFiles.push(this.files[index]);
        }
        displaySelectedFiles();
        this.value = null;
    });

    function displaySelectedFiles() {
        const container = document.getElementById("selected-files-container");
        container.innerHTML = "";
        selectedFiles.forEach((file, index) => {
            const input = document.createElement("input");
            input.type = "file";
            input.name = `file[${index}]`;
            input.classList.add(`file-index${index}`);
            input.hidden = true;
            container.appendChild(input);
            const blob = new Blob([file], { type: file.type });
            const file_obj = new File([file],file.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file_obj);
            input.files = dataTransfer.files;
        });
        let fileArray = $('.file-array');
        fileArray.empty();
        for (let index = 0; index < selectedFiles.length; ++index) {
            let filereader = new FileReader();
            let fileName = selectedFiles[index].name;
            let fileSize = formatBytes(selectedFiles[index].size);
            let fileIcon = getFileIcon(fileName);
            let fileDesign = '<div class="uploaded-file-item">' +
                '<img src="' + fileIcon + '" class="file-icon" alt="">' +
                '<div class="upload-file-item-content">'+
                '<div>' + fileName + '</div>' +
                '<small>'+fileSize+'</small>' +
                '</div>' +
                '<button type="button" class="remove-file px-0"><i class="bi bi-x-lg"></i></button>' +
                '</div>';
            let $uploadDiv = jQuery.parseHTML(fileDesign);

            filereader.readAsDataURL(selectedFiles[index]);
            fileArray.append($uploadDiv);
            $($uploadDiv).find('.remove-file').on('click', function () {
                $(this).closest('.uploaded-file-item').remove();
                $('.file-index'+index).remove();
                selectedFiles.splice(selectedFiles.indexOf(index), 1);
            });
        }
    }
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
    function getFileIcon(fileName) {
        let extension = fileName.split('.').pop().toLowerCase();
        let iconPath = $('#get-file-icon');
        switch(extension) {
            case 'doc':
            case 'docx':
                return iconPath.data('word-icon');
            case 'pdf':
                return iconPath.data('word-icon');
            case 'zip':
                return iconPath.data('default-icon');
            default:
                return iconPath.data('default-icon');
        }
    }
});
