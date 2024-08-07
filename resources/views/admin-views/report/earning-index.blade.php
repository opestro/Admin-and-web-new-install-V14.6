@extends('layouts.back-end.app')

@section('title', translate('Earning_Report'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/earning_report.png')}}" alt="">
                {{translate('Earning_Report')}}
            </h2>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="media align-items-center">
                    <!-- Avatar -->
                    <div class="avatar avatar-xl avatar-4by3">
                        <img class="avatar-img" src="{{dynamicAsset(path: 'public/assets/back-end/svg/illustrations/earnings.png')}}"
                             alt="Image Description">
                    </div>

                    <div class="media-body">
                        <div class="row align-items-center">
                            <div class="d-block col-sm mb-1 mb-sm-0 text-start {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}">
                                <div>
                                    <h1 class="page-header-title">{{translate('Earning_Report')}}  {{translate('overview')}} </h1>
                                </div>

                                <div class="row align-items-center">
                                    <div class="flex-between col-auto">
                                        <h5 class="text-muted {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">{{translate('admin')}}
                                            : </h5>
                                        <h5 class="text-muted">{{auth('admin')->user()->name}}</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="row align-items-center g-0">
                                            <h5 class="text-muted col-auto {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}}">{{translate('date')}}</h5>

                                            <!-- Flatpickr -->
                                            <h5 class="text-muted">( {{session('from_date')}} - {{session('to_date')}}
                                                )</h5>
                                            <!-- End Flatpickr -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-auto">
                                <div class="d-flex">
                                    <a class="btn btn-icon btn--primary rounded-circle"
                                       href="{{route('admin.dashboard.index')}}">
                                        <i class="tio-home-outlined"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.report.set-date')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="title-color d-flex">{{translate('show_data_by_date_range')}}</label>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="mb-3">
                                        <input type="date" name="from" value="{{date('Y-m-d',strtotime($from))}}"
                                               id="start-date-time"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="mb-3">
                                        <input type="date" value="{{date('Y-m-d',strtotime($to))}}" name="to"
                                               id="end-date-time"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <button type="submit"
                                                class="btn btn--primary btn-block">{{translate('show')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @php
                $total_tax=\App\Models\OrderTransaction::where(['status'=>'disburse'])
                ->whereBetween('created_at', [$from, $to])
                ->sum('tax');
            @endphp
            @php
                $total_earning =\App\Models\OrderTransaction::where(['status'=>'disburse'])
               ->whereBetween('created_at', [$from, $to])
               ->sum('order_amount');
            @endphp
            @php
                $total_commission =\App\Models\OrderTransaction::where(['status'=>'disburse'])
               ->whereBetween('created_at', [$from, $to])
               ->sum('admin_commission');
            @endphp
            @php
                $total = $total_earning+$total_tax + $total_commission;
            @endphp

            <div class="col-sm-6 mb-3 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <!-- Media -->
                                <div class="media">
                                    <i class="tio-dollar-outlined nav-icon {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}"></i>

                                    <div class="media-body {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <h4 class="mb-1">{{translate('total_earning')}} </h4>
                                        <span class="font-size-sm text-success">
                                          <i class="tio-trending-up"></i> {{\App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($total_earning))}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto">
                                <div class="js-circle"
                                     data-hs-circles-options='{
                                       "value": {{$total_earning==0?0:round((($total_earning)/$total)*100)}},
                                       "maxValue": 100,
                                       "duration": 2000,
                                       "isViewportInit": true,
                                       "colors": ["#e7eaf3", "green"],
                                       "radius": 25,
                                       "width": 3,
                                       "fgStrokeLinecap": "round",
                                       "textFontSize": 14,
                                       "additionalText": "%",
                                       "textClass": "circle-custom-text",
                                       "textColor": "green"
                                     }'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 mb-3 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="media">
                                    <i class="tio-money nav-icon {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}"></i>

                                    <div class="media-body {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <h4 class="mb-1">{{translate('total_Tax')}} </h4>
                                        <span class="font-size-sm text-warning">
                                          <i class="tio-trending-up"></i>  {{\App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($total_tax))}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto">
                                <div class="js-circle"
                                     data-hs-circles-options='{
                           "value": {{$total_tax==0?0:round(((abs($total_tax))/$total)*100)}},
                           "maxValue": 100,
                           "duration": 2000,
                           "isViewportInit": true,
                           "colors": ["#e7eaf3", "#ec9a3c"],
                           "radius": 25,
                           "width": 3,
                           "fgStrokeLinecap": "round",
                           "textFontSize": 14,
                           "additionalText": "%",
                           "textClass": "circle-custom-text",
                           "textColor": "#ec9a3c"
                         }'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-3 col-lg-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="media">
                                    <i class="tio-money nav-icon {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}"></i>

                                    <div class="media-body {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <h4 class="mb-1">{{translate('total_commission')}} </h4>
                                        <span class="font-size-sm text-primary">
                                          <i class="tio-trending-up"></i>  {{\App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($total_commission))}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto">
                                <div class="js-circle"
                                     data-hs-circles-options='{
                           "value": {{$total_commission==0?0:round(((abs($total_commission))/$total)*100)}},
                           "maxValue": 100,
                           "duration": 2000,
                           "isViewportInit": true,
                           "colors": ["#e7eaf3", "#355db5"],
                           "radius": 25,
                           "width": 3,
                           "fgStrokeLinecap": "round",
                           "textFontSize": 14,
                           "additionalText": "%",
                           "textClass": "circle-custom-text",
                           "textColor": "#355db5"
                         }'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3 mb-lg-5 border-top border-left border-right border-bottom">
            <div class="card-header flex-wrap">
                @php
                    $total_sold=\App\Models\OrderTransaction::where(['status'=>'disburse'])->whereBetween('created_at', [date('y-01-01'), date('y-12-31')])->sum('order_amount');
                    $t=\App\Models\OrderTransaction::where(['status'=>'disburse'])->whereBetween('created_at', [date('y-01-01'), date('y-12-31')])->sum('tax');
                    $c=\App\Models\OrderTransaction::where(['status'=>'disburse'])->whereBetween('created_at', [date('y-01-01'), date('y-12-31')])->sum('admin_commission');
                    $t_c_t = $total_sold +$t +$c;
                @endphp
                <div class="flex-start">
                    <h6 class="card-subtitle mt-1">{{translate('total_sale_of')}} {{date('Y')}} :</h6>
                    <h6>
                        <span class="h3 {{Session::get('direction') === "rtl" ? 'mr-sm-2' : 'ml-sm-2'}}"> {{\App\Utils\BackEndHelper::usd_to_currency($total_sold)." "}}</span>
                    </h6>
                    <h6>
                        <span class="h3 {{Session::get('direction') === "rtl" ? 'mr-sm-2' : 'ml-sm-2'}}"> {{\App\Utils\BackEndHelper::currency_symbol()}}</span>
                    </h6>
                </div>
                <div class="hs-unfold">
                    <a class="js-hs-unfold-invoker btn btn-white"
                       href="{{route('admin.orders.list',['all'])}}">
                        <i class="tio-shopping-cart-outlined {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i> {{translate('orders')}}
                    </a>
                </div>
            </div>

            @php
                $sold=[];

                $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
                $to = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d');

                $data=\App\Models\OrderTransaction::where(['status'=>'disburse'])->select(
                \Illuminate\Support\Facades\DB::raw('SUM(order_amount) as sum'),
                \Illuminate\Support\Facades\DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

                for ($inc = 1; $inc <= 12; $inc++) {
                $sold[$inc] = 0;
                foreach ($data as $match) {
                    if ($match['month'] == $inc) {
                        $sold[$inc] = $match['sum'];
                    }
                }
            }
            @endphp

            @php
                $tax=[];

                $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
                $to = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d');

                $data=\App\Models\OrderTransaction::where(['status'=>'disburse'])->select(
                \Illuminate\Support\Facades\DB::raw('SUM(tax) as sum'),
                \Illuminate\Support\Facades\DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

                for ($inc = 1; $inc <= 12; $inc++) {
                $tax[$inc] = 0;
                foreach ($data as $match) {
                    if ($match['month'] == $inc) {
                        $tax[$inc] = $match['sum'];
                    }
                }
            }
            @endphp
            @php
                $commission=[];

                $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
                $to = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d');

                $data=\App\Models\OrderTransaction::where(['status'=>'disburse'])->select(
                \Illuminate\Support\Facades\DB::raw('SUM(admin_commission) as sum'),
                \Illuminate\Support\Facades\DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

                for ($inc = 1; $inc <= 12; $inc++) {
                $commission[$inc] = 0;
                foreach ($data as $match) {
                    if ($match['month'] == $inc) {
                        $commission[$inc] = $match['sum'];
                    }
                }
            }
            @endphp

            <div class="card-body">
                <div class="chartjs-custom __h-18rem">
                    <canvas class="js-chart"
                            data-hs-chartjs-options='{
                        "type": "line",
                        "data": {
                           "labels": ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                           "datasets": [{
                            "data": [{{$sold[1]}},{{$sold[2]}},{{$sold[3]}},{{$sold[4]}},{{$sold[5]}},{{$sold[6]}},{{$sold[7]}},{{$sold[8]}},{{$sold[9]}},{{$sold[10]}},{{$sold[11]}},{{$sold[12]}}],
                            "backgroundColor": ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "green",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "green",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#377dff"
                          },
                          {
                            "data": [{{$tax[1]}},{{$tax[2]}},{{$tax[3]}},{{$tax[4]}},{{$tax[5]}},{{$tax[6]}},{{$tax[7]}},{{$tax[8]}},{{$tax[9]}},{{$tax[10]}},{{$tax[11]}},{{$tax[12]}}],
                            "backgroundColor": ["rgba(0, 201, 219, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "#ec9a3c",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "#ec9a3c",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#00c9db"
                          },
                          {
                            "data": [{{$commission[1]}},{{$commission[2]}},{{$commission[3]}},{{$commission[4]}},{{$commission[5]}},{{$commission[6]}},{{$commission[7]}},{{$commission[8]}},{{$commission[9]}},{{$commission[10]}},{{$commission[11]}},{{$commission[12]}}],
                            "backgroundColor": ["rgba(0, 201, 219, 0)", "rgba(255, 255, 255, 0)"],
                            "borderColor": "#355db5",
                            "borderWidth": 2,
                            "pointRadius": 0,
                            "pointBorderColor": "#fff",
                            "pointBackgroundColor": "#355db5",
                            "pointHoverRadius": 0,
                            "hoverBorderColor": "#fff",
                            "hoverBackgroundColor": "#00c9db"
                          }]
                        },
                        "options": {
                          "gradientPosition": {"y1": 200},
                           "scales": {
                              "yAxes": [{
                                "gridLines": {
                                  "color": "#e7eaf3",
                                  "drawBorder": false,
                                  "zeroLineColor": "#e7eaf3"
                                },
                                "ticks": {
                                    "beginAtZero": true,
                                    "fontSize": 12,
                                    "fontColor": "#97a4af",
                                    "fontFamily": "Open Sans, sans-serif",
                                    "padding": 5,
                                    "postfix": " {{ \App\Utils\BackEndHelper::currency_symbol() }}"
                                }
                              }],
                              "xAxes": [{
                                "gridLines": {
                                  "display": false,
                                  "drawBorder": false
                                },
                                "ticks": {
                                  "fontSize": 12,
                                  "fontColor": "#97a4af",
                                  "fontFamily": "Open Sans, sans-serif",
                                  "padding": 5
                                }
                              }]
                          },
                          "tooltips": {
                            "prefix": "",
                            "postfix": "",
                            "hasIndicator": true,
                            "mode": "index",
                            "intersect": false,
                            "lineMode": true,
                            "lineWithLineColor": "rgba(19, 33, 68, 0.075)"
                          },
                          "hover": {
                            "mode": "nearest",
                            "intersect": true
                          }
                        }
                      }'>
                    </canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/chart.js/dist/Chart.min.js')}}"></script>
    <script
            src="{{dynamicAsset(path: 'public/assets/back-end/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/hs.chartjs-matrix.js')}}"></script>

    <script>
        'use strict';
        $(document).on('ready', function () {
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });
            $('.js-nav-scroller').each(function () {
                new HsNavScroller($(this)).init()
            });
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            $('.js-chart').each(function () {
                $.HSCore.components.HSChartJS.init($(this));
            });

            var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

            $('[data-toggle="chart"]').click(function (e) {
                let keyDataset = $(e.currentTarget).attr('data-datasets')

                // Update datasets for chart
                updatingChart.data.datasets.forEach(function (dataset, key) {
                    dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingChart.update();
            })
            function generateHoursData() {
                var data = [];
                var dt = moment().subtract(365, 'days').startOf('day');
                var end = moment().startOf('day');
                while (dt <= end) {
                    data.push({
                        x: dt.format('YYYY-MM-DD'),
                        y: dt.format('e'),
                        d: dt.format('YYYY-MM-DD'),
                        v: Math.random() * 24
                    });
                    dt = dt.add(1, 'day');
                }
                return data;
            }

            $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
                data: {
                    datasets: [{
                        label: 'Commits',
                        data: generateHoursData(),
                        width: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.right - a.left) / 70;
                        },
                        height: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.bottom - a.top) / 10;
                        }
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            title: function () {
                                return '';
                            },
                            label: function (item, data) {
                                var v = data.datasets[item.datasetIndex].data[item.index];

                                if (v.v.toFixed() > 0) {
                                    return '<span class="font-weight-bold">' + v.v.toFixed() + ' hours</span> on ' + v.d;
                                } else {
                                    return '<span class="font-weight-bold">No time</span> on ' + v.d;
                                }
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'week',
                                round: 'week',
                                displayFormats: {
                                    week: 'MMM'
                                }
                            },
                            ticks: {
                                "labelOffset": 20,
                                "maxRotation": 0,
                                "minRotation": 0,
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 12,
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'day',
                                parser: 'e',
                                displayFormats: {
                                    day: 'ddd'
                                }
                            },
                            ticks: {
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 2,
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });

            $('.js-clipboard').each(function () {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });

            $('.js-circle').each(function () {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });
        });
    </script>
@endpush

