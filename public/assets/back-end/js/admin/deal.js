'use strict';
$(".lang-link").click(function (e) {
    e.preventDefault();
    $('.lang-link').removeClass('active');
    $(".lang-form").addClass('d-none');
    $(this).addClass('active');
    let formId = this.id;
    let lang = formId.split("-")[0];
    $("#" + lang + "-form").removeClass('d-none');
});
