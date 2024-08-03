"use strict";

$('.status-filter').on('change',function (){
    let status = $(this).val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post({
        url: $('#get-status-filter-route').data('action'),
        data: {
            status: status
        },
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function (data) {
            $('#status-wise-view').html(data.view);
            $('#withdraw-requests-count').empty().html(data.count);
            closeRequest();
            withdrawInfoHide();
            openNote();
            formSubmit();
            withdrawInfoShow();
        },
        complete: function () {
            $('#loading').fadeOut();
        }
    });
})
function closeRequest(){
    $('.close-request').on('click',function (){
        let getText = $('#get-confirm-and-cancel-button-text');
        swal({
            title: getText.data('sure'),
            text: getText.data('delete-text'),
            icon: 'warning',
            buttons: true,
            dangerMode: true,
            confirmButtonText: getText.data('confirm'),
        })
            .then((willDelete) => {
                if (willDelete.value) {
                    window.location.href = ($(this).data('action'));
                }
            });
    })
}
closeRequest();

function withdrawInfoHide(){
    $('.withdraw-info-hide, .withdraw-info-sidebar-overlay').on('click', function () {
        $('.withdraw-info-sidebar, .withdraw-info-sidebar-overlay').removeClass('show');
    });
}
function withdrawInfoShow(){
    $('.withdraw-info-show').on('click', function () {
        getWithdrawDetailsView($(this))
    })
}
withdrawInfoShow();
function getWithdrawDetailsView(value){
    $.get({
        url:value.data('action') ,
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function (response) {
            $('.withdraw-details-view').empty().html(response.view);
            $('.withdraw-info-sidebar, .withdraw-info-sidebar-overlay').addClass('show');
            withdrawInfoHide();
            openNote();
            formSubmit();
        },
        complete: function () {
            $('#loading').fadeOut();
        }
    });
}
function openNote(){
    $('.open-note').on('click', function () {
        $('.note-area').addClass('d-none');
        $('.note-section').removeClass('d-none');
        $('.note-area-button').removeClass('d-none');
        $('#'+$(this).data('id')).removeClass('d-none');
        $('.withdraw-details').addClass('d-none');
        let button = document.querySelector('.form-submit');
        button.setAttribute('data-form-id', $(this).data('id')+'-form');
        button.setAttribute('data-message', $(this).data('message'));

    });
    $('.back-to-details').on('click', function () {
        $('.note-area').removeClass('d-none');
        $('.note-section').addClass('d-none');
        $('.withdraw-details').removeClass('d-none');
    });
}
function formSubmit(){
    $('.form-submit').on('click',function (){
        let getText = $('#get-confirm-and-cancel-button-text');
        const getFormId =  $(this).data('form-id');
        Swal.fire({
            title: getText.data('sure'),
            text:  $(this).data('message'),
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: getText.data('cancel'),
            confirmButtonText: getText.data('confirm'),
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: $('#'+getFormId).attr('action'),
                    data: $('#'+getFormId).serialize(),
                    beforeSend: function () {
                        $('#loading').fadeIn();
                    },
                    success: function (data) {
                        if (data.errors) {
                            for (let index = 0; index < data.errors.length; index++) {
                                toastr.error(data.errors[index].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else if(data.error){
                            toastr.error(data.error, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }else {
                            toastr.success(data.message);
                            location.reload();
                        }
                    },complete: function () {
                        $('#loading').fadeOut();
                    },
                })
            }
        })
    });
}
$('.withdraw-request-file-export').on('click',function (){
    let checkedStatusValuesArray = $('.status-filter').val();
    let queryParams = '&status=' +checkedStatusValuesArray;
    window.location.href = $(this).data('action')+queryParams;
});
