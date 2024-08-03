<html>
<table>
    <thead>
    <tr>
        <th style="font-size: 18px">{{translate('vendor_Order_List')}}</th>
    </tr>
    <tr>

        <th>{{ translate('vendor_Info').' '.'-' }}</th>
        <th></th>
        <th>
            {{translate('Shop_Name').' '.'-'.' '.ucwords($data['shop']->name)}}
            <br>
            {{translate('shop_Phone').' '.'-'.' '.$data['shop']->contact ?? translate('data_not_found')}}
            <br>
            {{translate('shop_Address').' '.'-'.' '.$data['shop']->address ?? translate('data_not_found')}}
            <br>
            {{translate('total_Order').' '.'-'.' '.count($data['orders']) }}
        </th>
    </tr>
    <tr>
        <th>{{translate('order_Status')}}</th>
        <th></th>
        <th>
            @foreach ($data['statusArray'] as $key=>$value)
                {{translate($key != 'failed' ? $key : 'failed_to_deliver').' '.'-'.' '.$value}}
            @endforeach
        </th>
    </tr>
    <tr>
        <th> {{translate('SL')}}    </th>
        <th> {{translate('Order_ID')}}    </th>
        <th> {{translate('Order_Date')}}    </th>
        <th> {{translate('customer_Name')}}    </th>
        <th> {{translate('Total_Amount')}}    </th>
        <th> {{translate('Payment_Status')}}</th>
        <th> {{translate('Order_Status')}}</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($data['orders'] as $key=>$order)
        <tr>
            <td> {{++$key}}	</td>
            <td> {{$order->id}}	</td>
            <td> {{date('d M, Y h:i A',strtotime($order->created_at))}}</td>
            <td> {{ucwords($order->is_guest == 0 ? (($order?->customer?->f_name ?? translate('not_found')) .' '. $order?->customer?->l_name) : translate('guest_customer'))}}	</td>
            <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order['order_amount'] ?? 0), currencyCode: getCurrencyCode())}}</td>
            <td> {{translate($order->payment_status)}}</td>
            <td> {{translate($order->order_status)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</html>
