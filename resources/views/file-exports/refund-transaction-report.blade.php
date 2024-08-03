<html>
<table>
    <thead>
    <tr>
        <th>{{translate('refund_Transaction_Report_List')}}</th>
    </tr>
    <tr>
        <th>{{ translate('filter_Criteria') .' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('search_Bar_Content').' '.'-'.' '. ($data['searchValue'] ?? 'N/A')}}
            <br>
            {{translate('payment_Method').' '.'-'.' '.translate($data['paymentMethod'] ?? 'all')}}
        </th>
    </tr>
    <tr>
        <td> {{translate('SL')}}</td>
        <th>{{translate('product_Image')}}</th>
        <th>{{translate('product_Name')}}</th>
        <th>{{translate('refund_ID')}}</th>
        <th>{{translate('order_ID')}}</th>
        <th>{{translate('shop_Name')}}</th>
        <th>{{translate('payment_Method')}}</th>
        <th>{{translate('payment_Status')}}</th>
        <th>{{translate('paid_By')}}</th>
        <th>{{translate('amount')}}</th>
        <th>{{translate('transaction_Type')}}</th>
    </tr>
    @foreach ($data['transactions'] as $key=>$transaction)
        <tr>
            <td> {{++$key}} </td>
            <td style="height: 100px"></td>
            <td>{{ isset($transaction->orderDetails->product->name) ? Str::limit($transaction->orderDetails->product->name, 40) : translate('data_not_found') }}
            </td>
            <td>{{$transaction->refund_id}}</td>
            <td>{{$transaction->order_id}}</td>
            <td>
                {{$transaction->order->seller_is == 'seller' && $transaction->order->seller ? $transaction->order->seller->shop->name : translate('inhouse')}}
            </td>
            <td>
                {{translate(str_replace('_',' ',$transaction->payment_method))}}
            </td>
            <td>
                {{translate(str_replace('_',' ',$transaction->payment_status))}}
            </td>
            <td>
                {{translate($transaction->paid_by)}}
            </td>
            <td>
                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->amount), currencyCode: getCurrencyCode())}}
            </td>
            <td>
                {{ $transaction->transaction_type == 'Refund' ? translate('refunded') : str_replace('_',' ',$transaction->transaction_type)}}
            </td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
