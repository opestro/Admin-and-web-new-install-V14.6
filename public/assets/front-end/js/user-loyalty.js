"use strict";

$(document).ready(function () {
    const $stickyElement = $('.bottom-sticky4');
    const $offsetElement = $('.bottom-sticky4_js');
    $(window).on('scroll', function () {
        const elementOffset = $offsetElement.offset().top - $(window).height();
        const scrollTop = $(window).scrollTop();

        if (scrollTop >= elementOffset) {
            $stickyElement.addClass('stick');
        } else {
            $stickyElement.removeClass('stick');
        }
    });
})
