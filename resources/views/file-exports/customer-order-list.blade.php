<html>
<table>
    <thead>
        <tr>
            <th style="font-size: 18px">{{translate('customer_Order_List')}}</th>
        </tr>
        <tr>

            <th>{{ translate('Customer_Info').' '.'-' }}</th>
            <th></th>
            <th>
                {{translate('customer_Name').' '.'-'.' '.ucwords($data['customer']->f_name.' '.$data['customer']->l_name)}}
                <br>
                {{translate('customer_Email').' '.'-'.' '.$data['customer']->email ?? translate('data_not_found')}}
                <br>
                {{translate('customer_Phone').' '.'-'.' '.$data['customer']->phone ?? translate('data_not_found')}}
                <br>
                {{translate('total_Order').' '.'-'.' '.count($data['orders']) }}
            </th>
        </tr>
        <tr>
            <th>{{translate('search_Bar_Content'.' - ')}}</th>
            <th></th>
            <th>
                {{$data['searchValue'] ?? 'N/A'}}
            </th>
        </tr>
        <tr>
            <th> {{translate('SL')}}    </th>
            <th> {{translate('Order_ID')}}    </th>
            <th> {{translate('Order_Date')}}    </th>
            <th> {{translate('Store_Name')}}    </th>
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
                <td> {{ucwords($order?->seller_is == 'seller' ? ($order?->seller?->shop->name ?? translate('not_found')) : translate('inhouse'))}}	</td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order['order_amount'] ?? 0), currencyCode: getCurrencyCode())}}</td>
                <td> {{translate($order->payment_status)}}</td>
                <td> {{translate($order->order_status)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</html>
