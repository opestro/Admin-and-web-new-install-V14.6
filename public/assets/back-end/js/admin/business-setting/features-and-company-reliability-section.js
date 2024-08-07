'use strict';
function removeThisFeatureCard(){
    $('.remove-this-features-card').on('click', function() {
        const getText = $('#get-confirm-and-cancel-button-text');
        Swal.fire({
            title: getText.data('sure'),
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: getText.data('cancel'),
            confirmButtonText: getText.data('confirm'),
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $(this).closest('.remove-this-features-card-div').remove();
            }
        })
    });
}
removeThisFeatureCard();

$('#add-this-features-card-middle').on('click',function (){
    const getText =  $('#get-feature-section-append-translate-text');
    let index = Math.floor((Math.random() * 100)+1);
    let html = `<div class="col-sm-12 col-md-3 mb-4 remove-this-features-card-div">
                        <div class="card">
                            <div class="card-header justify-content-end">
                                <div class="cursor-pointer remove-this-features-card">
                                    <span class="btn btn-outline-danger btn-sm square-btn">
                                        <i class="tio-delete"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title">${getText.data('title')}</label>
                                    <input type="text" class="form-control" required
                                        name="features_section_middle[title][]"
                                        placeholder="${getText.data('title-placeholder')}">
                                </div>
                                <div class="mb-3">
                                    <label for="title">${getText.data('sub-title')}</label>
                                    <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                        placeholder="${getText.data('sub-title-placeholder')}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>`;

    $('#features-section-middle-row').append(html);
    removeThisFeatureCard();
})
$('#add-this-features-card-bottom').on('click',function (){
    let index = Math.floor((Math.random() * 100)+1);
    const getText =  $('#get-feature-section-append-translate-text');
    let html = `<div class="col-sm-12 col-md-3 mb-4 remove-this-features-card-div">
                        <div class="card">
                            <div class="card-header align-items-center justify-content-between">
                                <h5 class="m-0 text-muted">${getText.data('icon-box')}</h5>
                                <div class="cursor-pointer remove-this-features-card">
                                    <span class="btn btn-outline-danger btn-sm square-btn">
                                        <i class="tio-delete"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title">${getText.data('title')}</label>
                                    <input type="text" class="form-control" required
                                        name="features_section_bottom[title][]"
                                        placeholder="${getText.data('title-placeholder')}">
                                </div>
                                <div class="mb-3">
                                    <label for="title">${getText.data('sub-title')}</label>
                                    <textarea class="form-control" name="features_section_bottom[subtitle][]" required
                                        placeholder="${getText.data('sub-title-placeholder')}"></textarea>
                                </div>


                                <div class="custom_upload_input">
                                    <input type="file" name="features_section_bottom_icon[]" class="custom-upload-input-file aspect-ratio-3-15 upload-color-image" id="" data-imgpreview="pre_img_header_logo${index}" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <span class="delete_file_input btn btn-outline-danger btn-sm square-btn d-none">
                                        <i class="tio-delete"></i>
                                    </span>

                                    <div class="img_area_with_preview position-absolute z-index-2">
                                        <img id="pre_img_header_logo${index}" class="h-auto aspect-ratio-3-15 bg-white" onerror="this.classList.add('d-none')" src="img" alt="">
                                    </div>
                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50" alt="">
                                            <h3 class="text-muted">${getText.data('upload-icon')}</h3>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>`;

    $('#features-Section-bottom-row').append(html);
    removeThisFeatureCard();

    $(".upload-color-image").on("change", function () {
        uploadColorImage(this);
    });
    deleteInputFile()
    $('.delete_file_input').click(function () {
        let $parentDiv = $(this).parent().parent();
        $parentDiv.find('input[type="file"]').val('');
        $parentDiv.find('.img_area_with_preview img').attr("src", " ");
        $(this).hide();
    });
    customUploadInputFile();
});
$('.remove_icon_box_with_titles').on('click',function(){
    const getText = $('#get-confirm-and-cancel-button-text');
    Swal.fire({
        title: getText.data('sure'),
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: getText.data('cancel'),
        confirmButtonText: getText.data('confirm'),
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: $('#get-feature-section-icon-remove-route').data('action'),
                method: 'POST',
                data: {
                    _token:$('meta[name="_token"]').attr('content'),
                    title:$(this).data('title'),
                    subtitle:$(this).data('subtitle'),
                },
                success: function (data) {
                    if (data.status ==="success") {
                        location.reload();
                    }
                },
            });
        }
    })
})
$(".upload-color-image").on("change", function () {
    uploadColorImage(this);
});
function uploadColorImage(thisData = null){
    if(thisData){
        document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
        document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
    }
}
function deleteInputFile(){
    $('.delete-file-input').click(function () {
        let $parentDiv = $(this).parent().parent();
        $parentDiv.find('input[type="file"]').val('');
        $parentDiv.find('.img_area_with_preview img').attr("src", " ");
        $(this).hide();
    });
}
deleteInputFile();
function customUploadInputFile(){
    $('.custom-upload-input-file').on('change', function(){
        if (parseFloat($(this).prop('files').length) !== 0) {
            let $parentDiv = $(this).closest('div');
            $parentDiv.find('.delete-file-input').fadeIn();
        }
    });
}
customUploadInputFile();
