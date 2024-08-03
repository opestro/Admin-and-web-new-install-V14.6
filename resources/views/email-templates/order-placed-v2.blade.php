<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('Order Placed') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body { background-color: #ececec; font-family: 'Roboto', sans-serif; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 0; margin: 0; box-sizing: border-box; text-decoration: none; }
        .d-flex { display: flex; }
        .align-items-center { align-items: center; }
        .m-auto { margin: auto; }
        .text-center { text-align: center; }
        .text-start { text-align: left; }
        .text-end { text-align: right; }
        .credit-section { padding: 5px; width: 650px; margin: 5px auto 50px; }
        .order-action-btn { background-color: rgb(255, 255, 255); width: 90%; margin: 30px auto auto; }
        .order-main-table { width: 650px; background-color: white; margin: 100px auto auto; padding-top: 40px; padding-bottom: 40px; border-radius: 3px; }
        .order-main-sub-table { background-color: rgb(255, 255, 255); width: 90%; margin: auto; height: 72px; border-bottom: 1px ridge; }
        .color-green { color: green; }
        .table-header-items { background-color: #cacaca; padding: 5px; }
        .table-header-items th { padding: 10px 5px; }
        .calculation-section { width: 46%; margin-left: 41%; display: inline; }
        .m-10px { margin: 10px; }
        .width-100 { width: 100%; }
        .width-50 { width: 50%; }
        .width-50px { width: 50px; }
        .h-50px { height: 50px; }
        .pt-20px { padding-top: 20px; }
    </style>
</head>
<body>
<?php

use App\Models\Order;
use App\Models\Seller;
use App\Models\Shop;
use App\User;

$companyPhone = getWebConfig(name: 'company_phone');
$companyEmail = getWebConfig(name: 'company_email');
$companyName = getWebConfig(name: 'company_name');
$companyLogo = getWebConfig(name: 'company_web_logo');
$order = Order::find($id);

if ($order->seller_is == 'seller') {
    $seller = Seller::find($order->seller_id);
    $shop = Shop::find($seller->id);
}

if ($order->is_guest) {
    $userPhone = $order['shipping_address_data'] ? $order['shipping_address_data']->phone : $order['billing_address_data']->phone;
} else {
    $userPhone = User::find($order->customer_id)->phone;
}
?>

<div class="order-main-table">
    <table class="order-main-sub-table">
        <tbody>
        <tr>
            <td>
                <h2>{{ translate('thanks_for_the_order') }}</h2>
                <h3 class="color-green">{{ translate('Your_order_ID') }} : {{$id}}</h3>
            </td>
            <td>
                <div class="text-end me-1">
                    <img src="{{ dynamicStorage(path: 'storage/app/public/company/'.$companyLogo) }}" width="30%" alt=""/>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <table class="order-action-btn pb-2">
        <tbody>
        <tr class="width-100">
            <td class="width-50 mt-1">

                <div class="text-start mt-1">
                    <strong class="text-capitalize">{{ translate('vendor_details') }}  </strong>
                    <br>
                    @if ($order->seller_is == 'seller')

                        <div class="d-flex align-items-center mt-1">

                            <img src="{{dynamicStorage(path: 'storage/app/public/shop/'.$shop->image) }}" title=""
                                 class="" width="20%" alt=""/>

                            <span class="ps-1">{{$shop->name}}</span>
                        </div>

                    @else
                        <div class="d-flex align-items-center mt-1">
                        <span>
                            {{ translate('inhouse_products') }}
                        </span>
                        </div>
                    @endif
                </div>

            </td>
            <td class="width-50">
                <div class="text-end mt-1">
                    <strong>{{ translate('payment_details') }}  </strong>
                    <br>
                    <div class="mt-1">
                        <span>{{ str_replace('_',' ',$order->payment_method) }}</span><br>
                        <span style="color: {{$order->payment_status=='paid'?'green':'red'}};">
                          {{$order->payment_status}}
                        </span><br>
                        <span>
                          {{ date('d-m-y H:i:s',strtotime($order['created_at'])) }}
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>

    </table>


    <?php
    $subtotal = 0;
    $total = 0;
    $subTotal = 0;
    $totalTax = 0;
    $totalShippingCost = 0;
    $totalDiscountOnProduct = 0;
    $extraDiscount = 0;
    ?>
    <div class="order-action-btn">
        <div class="p-2">
            <table class="width-100">
                <tbody>
                <tr class="table-header-items">
                    <th>{{ translate('SL') }}</th>
                    <th>{{ translate('Ordered_Items') }}</th>
                    <th>{{ translate('Unit_price') }}</th>
                    <th>{{ translate('QTY') }}</th>
                    <th>{{ translate('Total') }}</th>
                </tr>
                @foreach ($order->details as $key=>$details)
                        <?php $subtotal = ($details['price']) * $details->qty; ?>
                    <tr class="text-center">

                        <td class="p-1">{{$key+1}}</td>
                        <td class="p-1">
                                  <span>
                                    {{$details['product']?Str::limit($details['product']->name,55):''}}
                                  </span>

                            <br>
                            @if ($details['variant']!=null)
                                <span>
                                    {{ translate('variation') }} : {{$details['variant']}}
                                  </span>
                            @endif

                        </td>
                        <td class="p-1">{{ webCurrencyConverter(amount: $details['price']) }}</td>
                        <td class="p-1">{{ $details->qty }}</td>
                        <td class="p-1">{{ webCurrencyConverter(amount: $subtotal) }}</td>
                    </tr>
                        <?php
                        $subTotal += $details['price'] * $details['qty'];
                        $totalTax += $details['tax'];
                        $totalShippingCost += $details->shipping ? $details->shipping->cost : 0;
                        $totalDiscountOnProduct += $details['discount'];
                        $total += $subtotal;
                        ?>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <?php
    if ($order['extra_discount_type'] == 'percent') {
        $extraDiscount = ($subTotal / 100) * $order['extra_discount'];
    } else {
        $extraDiscount = $order['extra_discount'];
    }
    $shipping = $order['shipping_cost'];
    ?>

    <table class="order-action-btn">
        <tr>
            <th></th>
            <td class="text-end">
                <table class="text-capitalize calculation-section">
                    <tbody>
                    <tr>
                        <th class="pb-2">{{ translate('sub_total') }} :</th>
                        <td class="pb-2">{{ webCurrencyConverter(amount: $subTotal) }}</td>
                    </tr>
                    <tr>
                        <td class="pb-2">{{ translate('tax') }} :</td>
                        <td class="pb-2">{{ webCurrencyConverter(amount: $totalTax) }}</td>
                    </tr>
                    @if($order->order_type == 'default_type')
                        <tr>
                            <td class="pb-2">{{ translate('shipping') }} :</td>
                            <td class="pb-2">{{ webCurrencyConverter(amount: $shipping - ($order->is_shipping_free ? $order->extra_discount : 0)) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="pb-2">{{ translate('coupon_discount') }} :</td>
                        <td class="pb-2">
                            - {{ webCurrencyConverter(amount: $order->discount_amount) }}
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="pb-2">{{ translate('discount_on_product') }} :</td>
                        <td class="pb-2">
                            - {{ webCurrencyConverter(amount: $totalDiscountOnProduct) }}
                        </td>
                    </tr>
                    @if ($order->order_type != 'default_type')
                        <tr class="border-bottom pb-2">
                            <th class="pb-2">{{ translate('extra_discount') }} :</th>
                            <td class="pb-2">
                                - {{ webCurrencyConverter(amount: $extraDiscount) }}
                            </td>
                        </tr>
                    @endif
                    <tr class="bg-primary">
                        <th class="pb-2">{{ translate('total') }} :</th>
                        <td class="pb-2 ps-3">
                            {{ webCurrencyConverter(amount: $order->order_amount) }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table class="order-action-btn">
        <tbody>
            <tr>
                <td>{{ translate('You_can_track_your_order_by_clicking_the_below_button') }}</td>
            </tr>
            <tr>
                <td>
                    <div class="my-4">
                        <a href="{{ route('track-order.result', ['order_id'=>$order->id, 'phone_number'=>$userPhone]) }}"
                           class="p-3 radius-5 text-capitalize border-0 btn btn-primary">
                            {{ translate('track_your_order') }}
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</div>

<div class="credit-section">

    <table class="m-auto width-100">
        <tbody>
        <tr>
            <th class="text-start">
                <h1>
                    {{ $companyName }}
                </h1>
            </th>
        </tr>
        <tr>
            <th class="text-start">
                <div> {{ translate('phone') }} : {{ $companyPhone }}</div>
                <div> {{ translate('website') }} : {{ url('/') }}</div>
                <div> {{ translate('email') }} : {{ $companyEmail }}</div>
            </th>
        </tr>
        <tr>
            @php($socialMedia = \App\Models\SocialMedia::where('active_status', 1)->get())
            @if(isset($socialMedia))
                <th class="text-start pt-20px">
                    <div class="width-100 d-flex">
                        @foreach ($socialMedia as $item)
                            <div>
                                <a href="{{$item->link}}" target=”_blank”>
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/'.$item->name.'.png') }}" alt=""
                                         class="h-50px width-50px m-10px">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </th>
            @endif
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>
