'use strict';
$('.status-filter').on('change',function (){
    let repliedFilter = $('.status-filter[value="replied"]').prop('checked');
    let notRepliedFilter = $('.status-filter[value="not_replied"]').prop('checked');
    let status = $(this).val();
    if ((repliedFilter && notRepliedFilter) || (!repliedFilter && !notRepliedFilter)) {
        status = 'all';
    }else if(repliedFilter === false){
        status = $('.status-filter[value="not_replied"]').val();
    }else if(notRepliedFilter === false){
        status = $('.status-filter[value="replied"]').val();
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post({
        url: $('#get-filter-route').data('action'),
        data: {
            status: status
        },
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function (data) {
            $('#status-wise-view').html(data.view)
            $('#row-count').empty().html(data.count)
        },
        complete: function () {
            $('#loading').fadeOut();
        }
    });
})
