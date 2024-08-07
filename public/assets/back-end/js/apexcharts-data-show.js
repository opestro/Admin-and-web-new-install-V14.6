let isMonthEarnChecked = false;
let count = 0;
let dateStart = 1;
let dateEnd = 31;
let currencySymbol =Boolean($('input[name=currency_symbol_show_status]').val()) === true ? $('#get-currency-symbol').data('currency-symbol'): '';
function setMonthResponsiveData() {
    let dateType =$('input[name=dateType]');
    if (dateType.val() === 'this_month' || (dateType.val() === 'custom_date' && (dateType.data('from') === dateType.data('to')))){
        isMonthEarnChecked = true;
        count = dateType.data('count');
        dateStart = dateType.data('start');
        dateEnd = dateType.data('end');
    }
}
setMonthResponsiveData();
let windowSize = getWindowSize();

function getApexChart(){
    let getData = $('#statistics-data');
    const statisticsValue = getData.data('statistics-value');
    let label = getData.data('label');
    if (windowSize.width < 767){
        label =  getLabelData(label,count,isMonthEarnChecked)
    }
    var options = {
        series: [
            {
                name: getData.data('statistics-title'),
                data: Object.values(statisticsValue)
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
        colors: ['#4FA7FF'],
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

getApexChart();

function getLabelData(label,count,status){
    let mod = (count % 5);
    if (status === true ) {
        label.forEach((val, index) => {
            if (val % 5 === 0 || val === count || val===dateStart || val === dateEnd) {
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
