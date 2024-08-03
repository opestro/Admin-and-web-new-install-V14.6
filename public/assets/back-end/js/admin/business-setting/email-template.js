'use strict'
$('.summernote').summernote({
    'height': 150,
    callbacks: {
        onChange: function(contents, $editable) {
            $('.view-mail-body').empty().html(contents);
        }
    }
});
$('input[data-id="mail-title"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('.view-'+dataId).text(value);
});
$('input[data-id="mail-body"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('.view-'+dataId).text(value);
});
$('input[data-id="button-content"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('.view-'+dataId).text(value);
});
$('input[data-id="button-link"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('.view-'+dataId).attr('href', value);
});
$('input[data-id="footer-text"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('.view-'+dataId).text(value);
});
$('input[data-id="copyright-text"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('.view-'+dataId).text(value);
});
$('.form-check-input').change(function() {
    let pageName = $(this).data('id');
    if (this.checked) {
        $('#selected-pages .'+ pageName).removeClass('d-none');
        $('#selected-social-media .'+ pageName).removeClass('d-none');
    } else {
        $('#selected-pages .'+ pageName).addClass('d-none');
        $('#selected-social-media .'+ pageName).addClass('d-none');
    }
});
$('.change-status').change(function() {
    let id = $(this).data('id');
    if (this.checked) {
        $('#'+id).removeClass('d-none');
    } else {
        $('#'+ id).addClass('d-none');
    }
});
