"use strict";

$(document).ready(function () {
    $('.card-header').click(function () {
        $('.card-header').removeClass('active');
        $(this).addClass('active');
    });
});

$('.action-get-categories-function').on('click', function () {
    let route = $(this).data('route');
    getCategories(route);
})

function getCategories(route) {
    $.get({
        url: route,
        dataType: 'json',
        beforeSend: function () {
            $('#loading').show();
        },
        success: function (response) {
            $('html,body').animate({scrollTop: $("#ajax-categories").offset().top}, 'slow');
            $('#ajax-categories').html(response.view);

            $('.get-view-by-onclick').on('click', function () {
                location.href = $(this).data('link');
            });
        },
        complete: function () {
            $('#loading').hide();
        },
    });
}
