'use strict';
$('.vendor-registration-reason-update-view').on('click',function (){
    $.ajax({
        url: $(this).data('action'),
        type: 'GET',
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function(response) {

            $('.vendor-registration-reason-update-modal').html(response.view);
            $('#update-vendor-registration-reason-modal').modal('show');
        },
        complete: function () {
            $('#loading').fadeOut();
        }
    })
})
$(document).on('click', '.edit', function () {
    let route = $(this).attr("data-id");
    $.ajax({
        url: route,
        type: "GET",
        data: {"_token": "{{ csrf_token() }}"},
        dataType: "json",
        success: function (data) {
            $("#question-filed").val(data.question);
            $("#answer-field").val(data.answer);
            $("#ranking-field").val(data.ranking);
            $("#check-status").attr('checked',(data.status === 1));
            $("#update-form-submit").attr("action", route);
            $('#editModal').modal('show');
        }
    });
});
