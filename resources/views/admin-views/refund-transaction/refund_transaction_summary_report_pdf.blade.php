<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('refund_Transaction_Summary_Report') }}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <style media="all">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            color: #111118;
        }

        body {
            font-size: .75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 25px 15px;
            text-align: left;
            padding: 0 10px;
            margin: 0;
            font-weight: 500;
            line-height: 133.9%;
            font-family: 'Fira Mono', monospace;
        }

        img {
            max-width: 100%;
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


        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .content-position {
            max-width: 595px;
            padding: 25px 40px 0;
            margin: 0 auto;
            background: #fff;
            /* box-shadow: 0 0 15px #11111110; */
            /* border-radius: 10px 10px 0 0; */
        }

        .content-footer:first-child,
        .content-position:first-child {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .content-footer:last-child,
        .content-position:last-child {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .text-white {
            color: white !important;
        }

        .bs-0 {
            border-spacing: 0;
        }

        .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h3 {
            font-weight: 700;
            font-size: 20px;
            line-height: 24px;
            font-family: 'Inter', sans-serif;
        }

        .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
            font-family: 'Inter', sans-serif;
        }

        .inter {
            font-family: 'Inter', sans-serif;
        }

        .bg-light {
            background-color: #F7F7F7;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 27%;
        }

        .fira {
            font-family: 'Fira Mono', monospace;
        }

        .logo {
            max-width: 180px
        }

        .p-0 {
            padding: 0;
        }

        .bold {
            font-weight: 700;
        }

        .mb-10 {
            margin-bottom: 15px;
        }

        .block {
            display: block;
        }

        .h-5 {
            height: 5px;
        }

        .black {
            color: #000000
        }

        .pt-0 {
            padding-top: 0;
        }

        .w-75px {
            width: 75px;
        }

        table {
            text-align: left;
        }

        .__product-table {
            font-weight: 400;
            font-size: 11px;
            line-height: 13px;
            color: #111118;
            border-collapse: separate;
            border-spacing: 1px;
        }

        .__product-table td {
            background: #FAFAFA;
        }

        .__product-table thead th {
            background: #0177CD;
            color: #fff;
            font-weight: 500;
            font-size: 11px;
            line-height: 13px;
            padding-top: 7px;
            padding-bottom: 7px;
        }

        .text-center {
            text-align: center;
        }

        .pl-0 {
            padding-left: 0 !important;
        }

        .pr-0 {
            padding-right: 0 !important;
        }

        @media (max-width: 460px) {
            .content-position {
                padding: 20px 0 0 !important
            }
        }

        @media (max-width: 400px) {
            .h3 {
                font-size: 14px;
            }

            .logo {
                width: 100px;
            }

            th {
                vertical-align: top;
            }
        }

        .bg-section {
            background: #FAFAFA;
        }

        .add-info-border-top-bottom tr:first-child td {
            border-top: 1px solid #A3B9D2 !important;
        }

        .add-info-border-top-bottom tr:last-child td {
            border-bottom: 1px solid #A3B9D2 !important;
        }

        .text-base {
            color: #0177CD
        }

        .content-footer {
            max-width: 595px;
            margin: 0 auto;
            /* border-radius: 0 0 10px 10px; */
            /* box-shadow: 0 0 15px #11111110; */
        }

        .content-footer tr td {
            background: #ECF0F2;
            border-radius: 0 0 10px 10px;
        }

        a {
            display: inline-block;
            text-decoration: none;
        }
    </style>


</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Mono:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<body>
<table class="content-position">
    <tr>
        <td>
            <table class="bs-0">
                <tr>
                    <th class="h3 p-0 text-left">
                        {{translate('order_Transaction_Statement')}}
                    </th>
                    <th class="p-0 text-right">
                        <img class="logo" src="{{dynamicStorage(path: "storage/app/public/company/".$data['company_web_logo'])}}"
                             alt="">
                    </th>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="pt-0">
            <table class="bs-0">
                <tr>
                    <td class="p-0 text-left">
                        <b class="bold black">{{translate('date')}}</b> : {{ date('F d, Y') }} <span
                                class="block h-5"></span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="content-position">
    <tr>
        <td class="pt-0">
            <table class="bs-0">
                <tr>
                    <td class="p-0 text-left">
                        <table>
                            <tr>
                                <th class="bold black p-0 text-left"
                                    style="padding: 3px 0">{{translate('duration')}}</th>
                                <td class="p-0" style="padding: 3px 0">: {{ $data['duration'] }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="p-0 text-left">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td class="pt-0">
            <table class="bs-0 __product-table inter">
                <thead>
                <tr>
                    <th class="pl-0 pr-0 text-center">{{translate('SL')}}</th>
                    <th>{{translate('details')}}</th>
                    <th class="text-right">{{translate('amount')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{translate('total_expense_amount')}}</td>
                    <td class="text-right">
                        {{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($data['total_expense'])) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>{{translate('free_delivery_amount')}}</td>
                    <td class="text-right" style="padding: 3px 0;">
                        {{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($data['free_delivery'])) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>{{translate('coupon_discount_amount')}}</td>
                    <td class="text-right" style="padding: 3px 0;">
                        {{ \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($data['coupon_discount'])) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<table class="">
    <tr>
        <th class="content-position-y bg-light py-4 footer">
            <div class="d-flex justify-content-center gap-2">
                <div class="mb-2">
                    <i class="fa fa-phone"></i>
                    {{translate('phone')}}
                    : {{ $data['company_phone'] }}
                </div>
                <div class="mb-2">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    {{translate('email')}}
                    : {{ $data['company_email'] }}
                </div>
            </div>
            <div class="mb-2">
                {{url('/')}}
            </div>
            <div>
                {{translate('all_copy_right_reserved_©_'.date('Y').'_').$data['company_name']}}
            </div>
        </th>
    </tr>
</table>
</body>
</html>
