'use strict';
$('#submit-create-role').on('submit', function (e) {
    let fields = $("input[name='modules[]']").serializeArray();
    if (fields.length === 0) {
        toastr.warning($('#select-minimum-one-box-message').data('warning'), {
            CloseButton: true,
            ProgressBar: true
        });
        return false;
    } else {
        $(this).submit();
    }
});
$("#select-all").on('change', function () {
    if ($(this).is(":checked") === true) {
        $(".module-permission").prop("checked", true);
    } else {
        $(".module-permission").removeAttr("checked");
    }
});

$(document).ready(function(){
    checkboxSelectionCheck();
})

function checkboxSelectionCheck() {
    let nonEmptyCount = 0;
    $(".module-permission").each(function() {
        if ($(this).is(":checked") !== true) {
            nonEmptyCount++;
        }
    });

    let selectAll = $("#select-all");
    if (nonEmptyCount === 0) {
        selectAll.prop("checked", true);
    }else{
        selectAll.removeAttr("checked");
    }
}

$('.module-permission').click(function(){
    checkboxSelectionCheck();
});
