<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ 'Expense Transaction Statement '.$transaction->order_id }}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css')}}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/vendor/order-transaction.css') }}">
</head>

<body>
<table class="content-position">
    <tr>
        <td>
            <table class="bs-0">
                <tr>
                    <th class="h3 p-0 text-left">
                        {{translate('expense_Transaction_Statement')}}
                    </th>
                    <th class="p-0 text-right">
                        <img class="logo" src="{{getValidImage(path: 'storage/app/public/company/'.$company_web_logo,type: 'backend-logo')}}" alt="">
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
                                <th class="bold black p-0 text-left p-3">{{translate('transaction_date')}}</th>
                                <td class="p-0 p-3">
                                    : {{ date_format($transaction->orderTransaction->updated_at, 'd F Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left p-3">{{translate('order_id')}}</th>
                                <td class="p-0 p-3">:
                                    {{ $transaction->id }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="p-0 text-left">
                        <table>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('XID')}}</th>
                                <td class="p-0 p-3">
                                    : {{ ucfirst($transaction->orderTransaction->transaction_id) }}</td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('expense_type')}} </th>
                                <td class="p-0 p-3">:
                                    {{ $transaction->coupon_discount_bearer == 'seller'?(isset($transaction->coupon->coupon_type) ? ($transaction->coupon->coupon_type == 'free_delivery' ? 'Free Delivery Promotion':ucwords(str_replace('_', ' ', $transaction->coupon->coupon_type))) : ''):'' }}
                                    {{ $transaction->free_delivery_bearer == 'seller'?ucwords(str_replace('_', ' ', $transaction->extra_discount_type)):'' }}
                                </td>
                            </tr>
                        </table>
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
                <tbody>
                <tr>
                    <td class="text-center"
                        style="background-color: #0177CD !important; color: white; font-weight: bold">{{translate('SL')}}</td>
                    <td style="background-color: #0177CD !important; color: white; font-weight: bold">{{translate('details')}}</td>
                    <td class="text-right"
                        style="background-color: #0177CD !important; color: white; font-weight: bold">{{translate('amount')}}</td>
                </tr>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{translate('expense_Amount')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($transaction->coupon_discount_bearer == 'seller'?$transaction->discount_amount:0) + ($transaction->free_delivery_bearer=='seller'?$transaction->extra_discount:0)), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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
</body>
</html>
