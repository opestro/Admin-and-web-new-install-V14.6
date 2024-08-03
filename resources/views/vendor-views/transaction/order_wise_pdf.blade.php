<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ 'Order Transaction Statement - '.$transaction->order_id }}</title>
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
                                <th class="bold black p-0 text-left p-3">{{translate('order_ID')}}</th>
                                <td class="p-0 p-3">: {{ $transaction['order_id'] }}</td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left p-3">{{translate('date')}}</th>
                                <td class="p-0 p-3">
                                    : {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left p-3">{{translate('vendor_Info')}}</th>
                                <td class="p-0 p-3">:
                                    @if($transaction['seller_is'] == 'admin')
                                        {{ getWebConfig('company_name') }}
                                    @else
                                        @if (isset($transaction->seller))
                                            {{ $transaction->seller->shop->name }}
                                        @else
                                            {{translate('not_found')}}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left p-3">{{translate('customer_Info')}}</th>
                                <td class="p-0 p-3">:
                                    @if (isset($transaction->customer))
                                        {{ $transaction->customer->f_name}} {{ $transaction->customer->l_name }}
                                    @elseif($transaction->order->is_guest)
                                        {{translate('guest_customer')}}
                                    @else
                                        {{translate('not_found')}}
                                    @endif
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
                                <th class="bold black p-0 text-left">{{translate('delivered_By')}} </th>
                                <td class="p-0 p-3">:
                                    @if($transaction->order->delivery_type =='self_delivery' && !empty($transaction->order->delivery_man_id))
                                        {{translate('delivery_man')}} {{ $transaction->order->deliveryMan->seller_id == 0 ? 'admin':'seller' }}
                                    @else
                                        {{ $transaction->delivery_type }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('payment_Method')}}</th>
                                <td class="p-0 p-3">:
                                    @if(in_array($transaction->order->payment_method, ['cash', 'cash_on_delivery', 'pay_by_wallet', 'offline_payment']))
                                        {{ ucfirst(str_replace('_', ' ', $transaction->order->payment_method)) }}
                                    @else
                                        {{translate('digital_payment')}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bold black p-0 text-left">{{translate('payment_Status')}}</th>
                                <td class="p-0 p-3">
                                    : {{ ucfirst($transaction->order->payment_status) }}</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
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
                    <td>{{translate('total_Product_Amount')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order_details_sum_price * $transaction->order_details_sum_qty), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>{{translate('product_Discount')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order_details_sum_discount), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>{{translate('coupon_Discount')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order->discount_amount), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>{{translate('discounted_Amount')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($transaction->order_details_sum_price * $transaction->order_details_sum_qty) - $transaction->order_details_sum_discount - (isset($transaction->order->coupon) && $transaction->order->coupon->coupon_type != 'free_delivery'?$transaction->order->discount_amount:0)), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td>{{translate('VAT/TAX')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['tax']), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">6</td>
                    <td>{{translate('shipping_Charge')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order->shipping_cost), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">7</td>
                    <td>{{translate('deliveryman_incentive')}}</td>
                    <td class="text-right">
                        {{ ($transaction->order->delivery_type=='self_delivery' && $transaction->order->shipping_responsibility=='sellerwise_shipping' && $transaction->order->delivery_man_id) ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order->deliveryman_charge), currencyCode: getCurrencyCode()) : setCurrencySymbol(amount: usdToDefaultCurrency(amount: 0), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">8</td>
                    <td>{{translate('order_Amount')}}</td>
                    <td class="text-right">
                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order->order_amount), currencyCode: getCurrencyCode()) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<table class="content-position">
    <tr>
        <th class="text-left black bold"><b>{{translate('additional_Information')}}</b></th>
        <th class="text-right black bold"><b>{{translate('totals')}}</b></th>
    </tr>
    <tbody class="bs-0 __product-table inter add-info-border-top-bottom">
    <tr>
        <td>
            {{translate('admin_Discount')}}
        </td>
        <td class="text-right">
            @php($admin_coupon_discount = ($transaction->order->coupon_discount_bearer == 'inhouse' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0)
            @php($admin_shipping_discount = ($transaction->order->free_delivery_bearer=='admin' && $transaction->order->is_shipping_free) ? $transaction->order->extra_discount : 0)
            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $admin_coupon_discount+$admin_shipping_discount), currencyCode: getCurrencyCode()) }}
        </td>
    </tr>
    <tr>
        <td>
            {{ translate('vendor_Discount') }}
        </td>
        <td class="text-right">
            @php($seller_coupon_discount = ($transaction->order->coupon_discount_bearer == 'seller' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0)
            @php($seller_shipping_discount = ($transaction->order->free_delivery_bearer=='seller' && $transaction->order->is_shipping_free) ? $transaction->order->extra_discount : 0)
            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller_coupon_discount+$seller_shipping_discount), currencyCode: getCurrencyCode()) }}
        </td>
    </tr>
    <tr>
        <td>
            {{ translate('admin_Commission') }}
        </td>
        <td class="text-right">
            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['admin_commission']), currencyCode: getCurrencyCode()) }}
        </td>
    </tr>
    <tr>
        <td>
            {{translate('vendor_Net_Income')}}
        </td>
        <td class="text-right">
            <?php
            $seller_net_income = 0;
            if (isset($transaction->order->deliveryMan) && $transaction->order->deliveryMan->seller_id != '0') {
                $seller_net_income += $transaction['delivery_charge'];
            }

            if ($transaction['seller_is'] == 'seller') {
                $seller_net_income += $transaction['order_amount'] + $transaction['tax'] - $transaction['admin_commission'];
            }

            if($transaction->order->delivery_type == 'self_delivery' && $transaction->order->shipping_responsibility == 'sellerwise_shipping' && $transaction->order->delivery_man_id && $transaction->order->seller_is == 'seller'){
                $seller_net_income -= $transaction->order->deliveryman_charge;
            }

            if ($transaction['seller_is'] == 'seller') {
                if ($transaction->order->shipping_responsibility == 'inhouse_shipping') {
                    $seller_net_income += $transaction->order->coupon_discount_bearer == 'inhouse' ? $admin_coupon_discount : 0;
                    $seller_net_income -= ($transaction->order->coupon_discount_bearer == 'seller' && $transaction->order->coupon->coupon_type == 'free_delivery') ? $admin_coupon_discount : 0;
                    $seller_net_income -= ($transaction->order->free_delivery_bearer == 'seller') ? $admin_shipping_discount : 0;

                } elseif ($transaction->order->shipping_responsibility == 'sellerwise_shipping') {
                    $seller_net_income += $transaction->order->coupon_discount_bearer == 'inhouse' ? $admin_coupon_discount : 0;
                    $seller_net_income += $transaction->order->free_delivery_bearer == 'admin' ? $admin_shipping_discount : 0;
                    $seller_shipping_discount = 0;
                }
            }
            ?>

            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $seller_net_income-$seller_shipping_discount), currencyCode: getCurrencyCode()) }}
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
