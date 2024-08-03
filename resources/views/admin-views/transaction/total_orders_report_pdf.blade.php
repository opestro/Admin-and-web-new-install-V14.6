<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ 'Order Report Statement - '.$data['date_type'] }}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{dynamicAsset(path: 'public/assets/back-end/css/google-fonts.css')}}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/css/admin/order-transaction.css') }}">
</head>
<body>
<table class="content-position">
    <tr>
        <td>
            <table class="bs-0">
                <tr>
                    <th class="h3 p-0 text-left">
                        {{translate('order_Report_Statement')}}
                    </th>
                    <th class="p-0 text-right">
                        <img class="logo" src="{{getValidImage(path:'storage/app/public/company/'.($data['company_web_logo']),type:'backend-logo')}}"  alt="">
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
                                <th class="bold black p-0 text-left p-3">{{translate('duration')}}</th>
                                <td class="p-0 p-3 text-capitalize">
                                    : {{ str_replace('_',' ', $data['date_type']) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left p-3">{{translate('vendor_Info')}}</th>
                                <td class="p-0 p-3">:
                                    {{ ucfirst($data['seller']) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="p-0 text-left">
                        <table>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('total_Order')}} </th>
                                <td class="p-0p-3">:
                                    {{ $data['total_orders'] }}
                                </td>
                            </tr>

                            <tr>
                                <th class="bold black p-0 text-left">{{ translate('type') }}</th>
                                <td class="p-0p-3">:
                                    {{ ucfirst($data['type']) }}
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
                    <td class="pl-0 pr-0" style="background: #0177CD !important;color: white;font-weight: bold;">{{translate('SL')}}</td>
                    <td style="background: #0177CD !important;color: white;font-weight: bold;">{{translate('details')}}</td>
                    <td class="text-right" style="background: #0177CD !important;color: white;font-weight: bold;">{{translate('amount')}}</td>
                </tr>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{translate('total_Ordered_Amount')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_order_amount']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>{{translate('total_Product_Discount')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_product_discount']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>{{translate('total_Coupon_Discount')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_coupon_discount']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>{{translate('total_Shipping_Charge')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_delivery_charge']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td>{{translate('total')}} {{translate('VAT/TAX')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_tax']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">6</td>
                    <td>{{translate('total_Order_Commission')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_order_commission']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">7</td>
                    <td>{{translate('total_Deliveryman_Incentive')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_deliveryman_incentive']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

{{--<table class="content-position">--}}
{{--    <tr>--}}
{{--        <th class="text-left black bold"><b>{{translate('additional_information')}}</b></th>--}}
{{--        <th class="text-right black bold"><b>{{translate('totals')}}</b></th>--}}
{{--    </tr>--}}
{{--    <tbody class="bs-0 __product-table inter add-info-border-top-bottom">--}}
{{--    <tr>--}}
{{--        <td>--}}
{{--            {{translate('admin_Discount')}}--}}
{{--        </td>--}}
{{--        <td class="text-right">--}}
{{--            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_admin_discount']), currencyCode: getCurrencyCode()) }}--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>--}}
{{--            {{ translate('vendor_Discount') }}--}}
{{--        </td>--}}
{{--        <td class="text-right">--}}
{{--            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_seller_discount']), currencyCode: getCurrencyCode()) }}--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>--}}
{{--            {{ translate('admin_Commission') }}--}}
{{--        </td>--}}
{{--        <td class="text-right">--}}
{{--            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_admin_commission']), currencyCode: getCurrencyCode()) }}--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>--}}
{{--            {{translate('admin_Net_Income')}}--}}
{{--        </td>--}}
{{--        <td class="text-right">--}}
{{--            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_admin_net_income']), currencyCode: getCurrencyCode()) }}--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>--}}
{{--            {{translate('vendor_Net_Income')}}--}}
{{--        </td>--}}
{{--        <td class="text-right">--}}
{{--            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_seller_net_income']), currencyCode: getCurrencyCode()) }}--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    </tbody>--}}
{{--</table>--}}
<br>
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
                {{translate('all_copy_right_reserved_©_'.date('Y').'_'). $data['company_name'] }}
            </div>
        </th>
    </tr>
</table>
</body>
</html>
