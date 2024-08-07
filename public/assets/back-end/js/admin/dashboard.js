"use strict";

let isMonthEarnCheckedForOrderStatistics = false;
let labelCountForOrderStatistics = 0;
let isMonthCheckedForEarningStatistic = false;
let labelCountForEarnStatistics = 0;
let currencySymbol = $('#get-currency-symbol').data('currency-symbol')

function orderStatistics(){
    $('.order-statistics').on('click', function () {
        let value = $(this).attr('data-date-type');
        let url = $('#order-statistics').data('action');
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                type: value
            },
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                $('#order-statistics-div').empty().html(data.view);
                setMonthEarnResponsiveDataForOrderStatistics();
                labelCountForOrderStatistics = parseInt($('input[name=order_statistics_label_count]').val());
                orderStatisticsApexChart();
                orderStatistics();
            },
            complete: function () {
                $('#loading').fadeOut();
            }
        });
    });
}
orderStatistics();
function setMonthEarnResponsiveDataForOrderStatistics() {
    $('.order-statistics-option input:radio[name="statistics4"]').change(function() {
        isMonthEarnCheckedForOrderStatistics = $('input:radio[name="statistics4"][value="MonthEarn"]').is(':checked');
    });
}
setMonthEarnResponsiveDataForOrderStatistics()

function setMonthResponsiveDataForEarningStatistic() {
    $('.earn-statistics-option input:radio[name="statistics"]').change(function() {
        isMonthCheckedForEarningStatistic = $('input:radio[name="statistics"][value="MonthEarn"]').is(':checked');
    });
}
setMonthResponsiveDataForEarningStatistic()

// Example usage
let windowSize = getWindowSize();
function orderStatisticsApexChart(){
    let orderStatisticsData = $('#order-statistics-data');
    const inHouseOrderEarn = orderStatisticsData.data('inhouse-order-earn');
    const vendorOrderEarn = orderStatisticsData.data('vendor-order-earn');
    let label = orderStatisticsData.data('label');
    if (windowSize.width < 767){
        label =  getLabelData(label,labelCountForOrderStatistics,isMonthEarnCheckedForOrderStatistics)
    }
    var options = {
        series: [
            {
                name: orderStatisticsData.data('inhouse-text'),
                data: Object.values(inHouseOrderEarn)
            },
            {
                name: orderStatisticsData.data('vendor-text'),
                data: Object.values(vendorOrderEarn)
            }
        ],
        chart: {
            height: 386,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        yaxis: {
            labels: {
                offsetX: 0,
                formatter: function(value) {
                    return  currencySymbol+value
                }
            },
        },
        colors: ['#4FA7FF', '#82C662'],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
        },
        grid: {
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            borderColor: '#CAD2FF',
            strokeDashArray: 5,
        },
        markers: {
            size: 1
        },
        theme: {
            mode: 'light',
        },
        xaxis: {
            categories: Object.values(label)
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            floating: false,
            offsetY: -10,
            offsetX: 0,
            itemMargin: {
                horizontal: 10,
                vertical: 10
            },
        },
        padding: {
            top: 0,
            right: 0,
            bottom: 200,
            left: 10
        },
    };
    var chart = new ApexCharts(document.getElementById("apex-line-chart"), options);
    chart.render();
}
orderStatisticsApexChart();
function UserOverViewChart(){
    const userOverViewData = $('#user-overview-data');
    var options = {
        series: [userOverViewData.data('customer'), userOverViewData.data('vendor'), userOverViewData.data('delivery-man')],
        labels: [userOverViewData.data('customer-title'), userOverViewData.data('vendor-title'), userOverViewData.data('delivery-man-title')],
        chart: {
            width: 320,
            type: 'donut',
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#017EFA', '#51CBFF',"#56E7E7"],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
            }
        }],
        legend: {
            show: false
        }
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
}
UserOverViewChart();
function earningStatistics(){
    $('.earn-statistics').on('click', function () {
        let value = $(this).attr('data-date-type');
        let url = $('#earn-statistics').data('action');
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                type: value
            },
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                $('#earn-statistics-div').empty().html(data.view);
                setMonthResponsiveDataForEarningStatistic();
                labelCountForEarnStatistics = parseInt($('input[name=earn_statistics_label_count]').val());
                earningStatisticsApexChart();
                earningStatistics();
            },
            complete: function () {
                $('#loading').fadeOut();
            }
        });
    });
}
earningStatistics();


$("#statistics_type").on("change", function () {
    let type = $(this).val();
    let url = $('#order-status-url').data('url');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post({
        url: url,
        data: {
            statistics_type: type
        },
        beforeSend: function () {
            $('#loading').fadeIn();
        },
        success: function (data) {
            $('#order_stats').html(data.view)
        },
        complete: function () {
            $('#loading').fadeOut();
        }
    });
});

$('#withdraw_method').on('change', function () {
    withdraw_method_field(this.value);
});

try{
    var ctx = document.getElementById('business-overview');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                '$("#customer-text").data("text") ',
                '$("#store-text").data("text") ',
                '$("#product-text").data("text") ',
                '$("#order-text").data("text") ',
                '$("#brand-text").data("text") ',
            ],
            datasets: [{
                label: '$("#business-text").data("text")',
                data: ['$("#customers-text").data("text")','$("#products-text").data("text")', '$("#orders-text").data("text")', '$("#brands-text").data("text")'],
                backgroundColor: [
                    '#041562',
                    '#DA1212',
                    '#EEEEEE',
                    '#11468F',
                    '#000000',
                ],
                hoverOffset: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}catch (e) {
}


function earningStatisticsApexChart(){

    let earnStatisticsData = $('#earn-statistics-data');
    const inHouseEarn = earnStatisticsData.data('inhouse-earn');
    const vendorEarn = earnStatisticsData.data('vendor-earn');
    const commissionEarn = earnStatisticsData.data('commission-earn');
    let label = earnStatisticsData.data('label');
    if (windowSize.width < 767){
        label = getLabelData(label,labelCountForEarnStatistics,isMonthCheckedForEarningStatistic)
    }
    var options = {
        series: [
            {
                name: earnStatisticsData.data('inhouse-text'),
                data: Object.values(inHouseEarn)
            },
            {
                name: earnStatisticsData.data('vendor-text'),
                data: Object.values(vendorEarn)
            },
            {
                name: earnStatisticsData.data('commission-text'),
                data: Object.values(commissionEarn)
            }
        ],
        chart: {
            height: 386,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        yaxis: {
            labels: {
                offsetX: 0,
                formatter: function(value) {
                    return  currencySymbol+value
                }
            },
        },
        colors: ['#4FA7FF', '#82C662','#c1c662'],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
        },
        grid: {
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            borderColor: '#CAD2FF',
            strokeDashArray: 5,
        },
        markers: {
            size: 1
        },
        theme: {
            mode: 'light',
        },
        xaxis: {
            categories: Object.values(label)
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            floating: false,
            offsetY: -10,
            offsetX: 0,
            itemMargin: {
                horizontal: 10,
                vertical: 10
            },
        },
        padding: {
            top: 0,
            right: 0,
            bottom: 200,
            left: 10
        },
    };
    var chart = new ApexCharts(document.getElementById("earning-apex-line-chart"), options);
    chart.render();
}
earningStatisticsApexChart();


function getLabelData(label,count,status){
    let mod = (count % 5);
    if (status === true ) {
        label.forEach((val, index) => {
            if (val % 5 === 0 || val === count) {
                label[index] = (val !== count && mod <= 1 && (count - mod === val) ? '' : val);
            }else{
                label[index]='';
            }
        });
    }else {
        label.forEach((val, index) => {
            label[index]=val.substring(0,3);
        });
    }
    return label;
}
