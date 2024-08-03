"use strict";

$(".order-status-history").on('click', function () {
    let url = $('#order-status-url').data('url');
    let id = $(this).data('id');
    url = url.replace(":id", id)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {
            $(".load-with-ajax").empty();
            $(".load-with-ajax").append(data);
        }
    });
});
