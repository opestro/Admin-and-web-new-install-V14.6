<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Earning Statement</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <style media="all">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: 'Inter', sans-serif;
            color: #333542;
        }


        /* IE 6 */
        * html .footer {
            position: absolute;
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
            font-family: 'Inter', sans-serif;
        }

        body {
            font-size: .75rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        img {
            max-width: 100%;
        }

        .customers {
            font-family: 'Inter', sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table {
            width: 100%;
        }

        table thead th {
            padding: 8px;
            font-size: 11px;
            text-align: left;
        }

        table tbody th,
        table tbody td {
            padding: 8px;
            font-size: 11px;
        }

        table.fz-12 thead th {
            font-size: 12px;
        }

        table.fz-12 tbody th,
        table.fz-12 tbody td {
            font-size: 12px;
        }

        table.customers thead th {
            background-color: #0177CD;
            color: #fff;
        }

        table.customers tbody th,
        table.customers tbody td {
            background-color: #FAFCFF;
        }

        table.calc-table th {
            text-align: left;
        }

        table.calc-table td {
            text-align: right;
        }

        table.calc-table td.text-left {
            text-align: left;
        }

        .table-total {
            font-family: "Inter", sans-serif;
        }


        .text-left {
            text-align: left !important;
        }

        .pb-2 {
            padding-bottom: 8px !important;
        }

        .pb-3 {
            padding-bottom: 16px !important;
        }

        .text-right {
            text-align: right;
        }

        .content-position {
            padding: 15px 40px;
        }

        .content-position-y {
            padding: 0px 40px;
        }

        .text-white {
            color: white !important;
        }

        .bs-0 {
            border-spacing: 0;
        }

        .text-center {
            text-align: center;
        }

        .mb-1 {
            margin-bottom: 4px !important;
        }

        .mb-2 {
            margin-bottom: 8px !important;
        }

        .mb-4 {
            margin-bottom: 24px !important;
        }

        .mb-30 {
            margin-bottom: 30px !important;
        }

        .px-10 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .fz-14 {
            font-size: 14px;
        }

        .fz-12 {
            font-size: 12px;
        }

        .fz-10 {
            font-size: 10px;
        }

        .font-normal {
            font-weight: 500;
        }

        .border-dashed-top {
            border-top: 1px dashed #ddd;
        }

        .font-weight-bold {
            font-weight: 700;
        }

        .bg-light {
            background-color: #F7F7F7;
        }

        .py-30 {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .py-4 {
            padding-top: 24px;
            padding-bottom: 24px;
        }

        .d-flex {
            display: flex;
        }

        .gap-2 {
            gap: 8px;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-center {
            justify-content: center;
        }

        a {
            color: rgba(0, 128, 245, 1);
        }

        .p-1 {
            padding: 4px !important;
        }

        .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 27%;
        }

        .max-w-595px {
            max-width: 595px;
            margin: 0 auto;
            background: #fff;
        }
    </style>
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>

<div class="max-w-595px" style="min-height: 100vh; display:flex;flex-direction: column;">
    <div class="first">
        <table class="bs-0 mb-30 px-10">
            <tr>
                <th class="content-position-y text-left">
                    <h2>{{translate('admin_Earning_Report')}}</h2>
                    <p class="fz-14">{{translate('date')}} : <span
                                style="font-weight: normal">{{ date('d/m/Y') }}</span></p>
                </th>
                <th class="content-position-y text-right">
                    <img height="50" src="{{dynamicStorage(path: "storage/app/public/company/$company_web_logo")}}" alt="">
                </th>
            </tr>
        </table>
    </div>
    <div class="">
        <section>
            <table class="content-position-y fz-12">
                <tr>
                    <td class="p-1">
                        <table>
                            <tr>
                                <td>
                                    <p class="fz-14"><b>{{translate('duration')}}</b> : {{ $earning_data['duration'] }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>


        </section>
    </div>

    <br>

    <div class="">
        <div class="content-position-y">
            <table class="customers bs-0">
                <tbody>
                <tr>
                    <td style="background-color: #0177CD important; color: white; font-weight: bold">{{translate('SL')}}</td>
                    <td style="background-color: #0177CD important; color: white; font-weight: bold">{{translate('details')}}</td>
                    <td class="text-right"
                        style="background-color: #0177CD important; color: white; font-weight: bold">{{translate('amount')}}</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>{{translate('in_House_earning')}}</td>
                    <td class="text-right">{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['inhouse_earning'])) }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>{{translate('admin_Commission')}}</td>
                    <td class="text-right">{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['admin_commission'])) }}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>{{translate('earning_From_Shipping')}}</td>
                    <td class="text-right">{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['shipping_earn'])) }}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>{{translate('deliveryman_incentive')}}</td>
                    <td class="text-right">{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['deliveryman_incentive'])) }}</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>{{translate('discount_Given')}}</td>
                    <td class="text-right">{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['discount_given'])) }}</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>{{translate('total_Tax')}}</td>
                    <td class="text-right">{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['total_tax'])) }}</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>{{translate('refund_Given')}}</td>
                    <td class="text-right">{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['refund_given'])) }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right">
                        <b>{{translate('total_Earning')}}</b>
                    </td>
                    <td class="text-right">
                        <b>{{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($earning_data['total_earning'])) }}</b>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <div class="row" style="margin-top: auto">
        <section>
            <table class="">
                <tr>
                    <th class="content-position-y bg-light py-4 footer">
                        <div class="d-flex justify-content-center gap-2">
                            <div class="mb-2">
                                <i class="fa fa-phone"></i>
                                {{translate('phone')}}
                                : {{ $company_phone }}
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                {{translate('email')}}
                                : {{ $company_email }}
                            </div>
                        </div>
                        <div class="mb-2">
                            {{url('/')}}
                        </div>
                        <div>
                            {{translate('all_copy_right_reserved_©_'.date('Y').'_').$company_name}}
                        </div>
                    </th>
                </tr>
            </table>
        </section>
    </div>
</div>
</body>
</html>
