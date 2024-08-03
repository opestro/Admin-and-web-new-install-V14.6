"use strict";


$(document).ready(function () {
    $('.js-select2-custom').select2();
});

$('#from_date,#to_date').change(function () {
    let from_date = $('#from_date').val();
    let to_date = $('#to_date').val();
    if (from_date != '') {
        $('#to_date').attr('required', 'required');
    }
    if (to_date != '') {
        $('#from_date').attr('required', 'required');
    }
    if (from_date != '' && to_date != '') {
        if (from_date > to_date) {
            $('#from_date').val('');
            $('#to_date').val('');
            toastr.error('Invalid date range!', Error, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    }

})

$("#date_type").change(function () {
    let val = $(this).val();
    $('#from_div').toggle(val === 'custom_date');
    $('#to_div').toggle(val === 'custom_date');

    if (val === 'custom_date') {
        $('#from_date').attr('required', 'required');
        $('#to_date').attr('required', 'required');
    } else {
        $('#from_date').val(null).removeAttr('required')
        $('#to_date').val(null).removeAttr('required')
    }
}).change();

// Bar Charts
Chart.plugins.unregister(ChartDataLabels);

$('.js-chart').each(function () {
    $.HSCore.components.HSChartJS.init($(this));
});

var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

