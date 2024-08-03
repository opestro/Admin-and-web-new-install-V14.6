'use strict';
$('.zip-code').on('click', function(){
    if ($.trim($("input[name='zipcode']").val()) === '') {
        toastr.error($('#get-zip-code-text').data('error'));
    }
})
$(".js-example-responsive").select2({
    theme: "classic",
    placeholder: $('#get-select-country-text').data('text'),
    allowClear: true,

});
$('#country-area-form').on('submit', function(e){
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        success: function (data) {
            if (data.status === true) {
                toastr.success(data.message);
                location.reload();
            }
        }
    });
})
$('#zip-area-form').on('submit', function(e){
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        success: function (data) {
            if (data.status === true) {
                toastr.success(data.message);
                location.reload();
            }
        }
    });
})

if ($('#get-country-status').data('value') === 0) {
    $(".country-disable").hide();
}
if($('#get-zip-status').data('value') === 0) {
    $(".zip-disable").hide();
}
