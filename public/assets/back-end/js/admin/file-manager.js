'use strict';
function readURL(input) {
    $('#files').html("");
    for (let i = 0; i < input.files.length; i++) {
        if (input.files && input.files[i]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#files').append('<div class="col-md-2 col-sm-4 m-1"><img class="__empty-img" id="viewer" src="' + e.target.result + '" alt=""/></div>');
            }
            reader.readAsDataURL(input.files[i]);
        }
    }
}

$("#customFileUpload").change(function () {
    readURL(this);
});

$('#customZipFileUpload').change(function (e) {
    let fileName = e.target.files[0].name;
    $('#zipFileLabel').html(fileName);
});

$('.copy-path').on('click', function () {
    console.log($(this))
    navigator.clipboard.writeText($(this).data('path'));
    toastr.success($('#get-file-copy-success-message').data('success'), {
        CloseButton: true,
        ProgressBar: true
    });
})
