"use strict";

$(".js-example-responsive").select2({
    width: 'resolve'
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#viewer').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#customFileEg1").change(function () {
    readURL(this);
});

$('.contact_status_form').on('submit', function (event){
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url:$(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function (data) {
            if(data.fail === 1 )
            {
                toastr.info(data.message);
            }else{
                toastr.success(data.message);
            }

        }
    });
});
$('.emergency-contact-update-view').on('click',function (){
    $.ajax({
        url: $(this).data('action'),
        type: 'GET',
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function(response) {
            $('.emergency-contact-update-modal').html(response.view);
            $('.js-select2-custom').select2();
            $('.emergency-contact-update-modal').modal('show');
        },
        complete: function () {
            $('#loading').fadeOut();
        }
    })
})
