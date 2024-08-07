"use strict";
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

});

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


// Dognut Pie Chart
var options = {
    series: [
        $('#withdrawable_balance').data('text'),
        $('#pending_withdraw').data('text'),
        $('#already_withdrawn').data('text')
    ],

    chart: {
        width: 320,
        type: 'donut',
    },
    labels: [
        $('#withdrawable_balance_text').data('text') + " " + $('#currency_symbol').data('text') + " " + $('#withdrawable_balance_format').data('text') + " ",
        $('#pending_withdraw_text').data('text') + " " + $('#currency_symbol').data('text') + " " + $('#pending_withdraw_format').data('text') + " ",
        $('#already_withdrawn_text').data('text') + " " + $('#currency_symbol').data('text') + " " + $('#already_withdrawn_format').data('text') + " ",
    ],
    dataLabels: {
        enabled: false,
        style: {
            colors: ['#004188', '#004188', '#004188']
        }
    },
    responsive: [{
        breakpoint: 1650,
        options: {
            chart: {
                width: 260
            },
        }
    }],
    colors: ['#004188', '#0177CD', '#0177CD'],
    fill: {
        colors: ['#004188', '#A2CEEE', '#0177CD']
    },
    legend: {
        show: false
    },
};

var chart = new ApexCharts(document.querySelector("#dognut-pie"), options);
chart.render();

