<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ 'Order Transaction Statement - '.$duration }}</title>
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
                        {{translate('order_Transaction_Statement')}}
                    </th>
                    <th class="p-0 text-right">
                        <img class="logo" src="{{getValidImage(path: 'storage/app/public/company/'.$company_web_logo,type:'backend-logo')}}" alt="">
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
                                <td class="p-0 p-3">: {{ $duration }}</td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left  p-3">{{translate('vendor_Info')}}</th>
                                <td class="p-0 p-3">:
                                    {{ $seller_info }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left p-3">{{translate('customer_Info')}}</th>
                                <td class="p-0 p-3">:
                                    {{ $customer_info }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left p-3">{{translate('status')}}</th>
                                <td class="p-0 p-3">:
                                    {{ $status }}
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
                                <td class="p-0 p-3">:
                                    {{ $data['total_orders'] }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('completed_Orders')}}</th>
                                <td class="p-0 p-3">:
                                    {{ $data['completed_order'] }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('ongoing_Orders')}}</th>
                                <td class="p-0 p-3">:
                                    {{ $data['ongoing_order'] }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('canceled_Orders')}}</th>
                                <td class="p-0 p-3">:
                                    {{ $data['canceled_order'] }}
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
                    <td class="pl-0 pr-0 text-center"
                        style="background-color: #0177CD !important; color: white; font-weight: bold">{{translate('SL')}}</td>
                    <td style="background-color: #0177CD !important; color: white; font-weight: bold">{{translate('details')}}</td>
                    <td class="text-right"
                        style="background-color: #0177CD !important; color: white; font-weight: bold">{{translate('amount')}}</td>
                </tr>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{translate('total_Ordered_Product_Price')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_ordered_product_price']), currencyCode: getCurrencyCode()) }}
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
                    <td>{{translate('total_Discounted_Amount')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_discounted_amount']), currencyCode: getCurrencyCode()) }}
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
                    <td>{{translate('total_Delivery_Charge')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_delivery_charge']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">6</td>
                    <td>{{translate('total_Deliveryman_incentive')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_deliveryman_incentive']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">7</td>
                    <td>{{translate('total_Order_Amount')}}</td>
                    <td class="text-right">
                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_order_amount']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<table class="content-position">
    <tr>
        <th class="text-left black bold"><b>{{translate('additional')}} {{translate('information')}}</b></th>
        <th class="text-right black bold"><b>{{translate('totals')}}</b></th>
    </tr>
    <tbody class="bs-0 __product-table inter add-info-border-top-bottom">
    <tr>
        <td>
            {{translate('admin_Discount')}}
        </td>
        <td class="text-right">
            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_admin_discount']), currencyCode: getCurrencyCode()) }}
        </td>
    </tr>
    <tr>
        <td>
            {{ translate('vendor_Discount') }}
        </td>
        <td class="text-right">
            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_vendor_discount']), currencyCode: getCurrencyCode()) }}
        </td>
    </tr>
    <tr>
        <td>
            {{ translate('admin_Commission') }}
        </td>
        <td class="text-right">
            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_admin_commission']), currencyCode: getCurrencyCode()) }}
        </td>
    </tr>
    <tr>
        <td>
            {{translate('vendor_Net_Income')}}
        </td>
        <td class="text-right">
            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_vendor_net_income']), currencyCode: getCurrencyCode()) }}
        </td>
    </tr>
    </tbody>
</table>
<br><br><br><br><br><br><br><br>
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
