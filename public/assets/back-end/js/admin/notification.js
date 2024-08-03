'use strict';
$('.resend-notification').on('click',function (){
    let id = $(this).data('id');
    let resendNotification=$('#get-resend-notification-route-and-text');
    let getText = $('#get-confirm-and-cancel-button-text');
    Swal.fire({
        title: getText.data('sure'),
        text: resendNotification.data('text'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: 'default',
        confirmButtonColor: '#161853',
        cancelButtonText: getText.data('cancel'),
        confirmButtonText: getText.data('confirm'),
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: resendNotification.data('action'),
                type: 'POST',
                data: {
                    id: id
                },
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (response) {
                    let toasterMessage = response.success ? toastr.success : toastr.info;
                    toasterMessage(response.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    let countId = $('#count-' + id) ;
                    countId.text(parseInt(countId.text()) + 1);
                },
                complete: function () {
                    $('#loading').fadeOut();
                }
            });
        }
    })
})
$(".lang-link").click(function (e) {
    e.preventDefault();
    $(".lang-link").removeClass('active');
    $(".lang-form").addClass('d-none');
    $(this).addClass('active');

    let formId = this.id;
    let lang = formId.split("-")[0];
    $("." + lang + "-form").removeClass('d-none');
})

$('.select-user-type').on('change',function(){
    let type = $(this).val();
    if (type === 'customer') {
        $('.seller_view').addClass('d-none');
        $('.delivery_man_view').addClass('d-none');
        $('.customer_view').removeClass('d-none');
    } else if (type === 'seller') {
        $('.seller_view').removeClass('d-none');
        $('.delivery_man_view').addClass('d-none');
        $('.customer_view').addClass('d-none');
    } else if (type === 'delivery_man') {
        $('.seller_view').addClass('d-none');
        $('.delivery_man_view').removeClass('d-none');
        $('.customer_view').addClass('d-none');
    }
})
