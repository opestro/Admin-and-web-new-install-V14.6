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

$('textarea[name=denied_note]').on('keyup', function() {
    let text = $(this).val().trim();
    let wordCount = countWords($(this).val());
    if (wordCount >= 99) {
        wordCount = 100;
        const words = text.split(/\s+/);
        $(this).val(words.slice(0, wordCount).join(' '));
    }
    $('#denied-note-word-count').text(wordCount + '/100');
});
