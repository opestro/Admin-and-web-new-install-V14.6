'use strict'
$('.add-redirect-link').on('click',function(){
    $('#add-edit-modal input[name=id]').val($(this).data('id'));
    $('#add-edit-modal').modal('show');
})
$('.edit-redirect-link').on('click',function(){
    $('#add-edit-modal input[name=id]').val($(this).data('id'));
    $('#add-edit-modal input[name=redirect_url]').val($(this).data('redirect-url'));
    let logsRedirectStatus301 = $('#logs_redirect_status_301');
    let logsRedirectStatus302 = $('#logs_redirect_status_302');
    if ($(this).data('redirect-status').toString() === '301') {
        logsRedirectStatus301.prop('checked', true);
        logsRedirectStatus302.prop('checked', false);
    } else {
        logsRedirectStatus301.prop('checked', false);
        logsRedirectStatus302.prop('checked', true);
    }
    $('#add-edit-modal').modal('show');
})

let checkedIds = [];
document.getElementById('master-check-box').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.row-check-box');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = document.getElementById('master-check-box').checked;
        updateCheckedIds(checkbox);
    });
    clearLogShowHide();
});
document.querySelectorAll('.row-check-box').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        updateCheckedIds(checkbox);
        updateVisibility();
        clearLogShowHide();
    });
});


function updateVisibility() {
    const anyUnchecked = Array.from(document.querySelectorAll('.row-check-box')).some(checkbox => !checkbox.checked);
    document.getElementById('master-check-box').checked = !anyUnchecked;
}
function clearLogShowHide(){
    if (checkedIds.length === 0) {
        $('#clear-all-log').addClass('d-none')
    }else {
        $('#clear-all-log').removeClass('d-none')
    }
}
function updateCheckedIds(checkbox) {
    if (checkbox.checked) {
        if (!checkedIds.includes(parseInt(checkbox.value))) {
            checkedIds.push(parseInt(checkbox.value));
        }
    } else {
        checkedIds = checkedIds.filter(id => id !== parseInt(checkbox.value));
    }
    $('#selected-ids').empty();
    checkedIds.forEach(function(id) {
        $('#selected-ids').append(`<input name="selected-ids[]" value="${id}" hidden>`);
    })
}
