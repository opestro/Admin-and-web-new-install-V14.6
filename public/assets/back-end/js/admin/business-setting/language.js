'use strict';
$(document).ready(function () {
    $('.select-country').select2({
        templateResult: codeSelect,
        templateSelection: codeSelect,
        escapeMarkup: function (mark) {
            return mark;
        }
    });
    function codeSelect(state) {
        let code = state.title;
        if (!code) return state.text;
        return "<img class='image-preview' alt='' src='" + code + "'>" + state.text;
    }
    $(".delete").click(function (e) {
        e.preventDefault();
        const getText = $('#get-confirm-and-cancel-button-text-for-delete');
        Swal.fire({
            title: getText.data('sure'),
            text: getText.data('text'),
            showCancelButton: true,
            confirmButtonColor: 'primary',
            cancelButtonColor: 'secondary',
            confirmButtonText: getText.data('confirm'),
            cancelButtonText: getText.data('cancel'),
        }).then((result) => {
            if (result.value) {
                window.location.href = $(this).attr("id");
            }
        })
    });
});
$('.update-status').on('submit', function (event) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).serialize(),
        success: function () {
            toastr.success($('#get-update-status-message').data('text'));
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    });
});
$('.default-language-delete-alert').on('click',function (){
    toastr.warning($(this).data('text'));
})
