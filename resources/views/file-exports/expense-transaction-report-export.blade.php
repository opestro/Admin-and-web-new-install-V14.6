<html>
<table>
    <thead>
    <tr>
        <th>{{translate('expanse_Transaction_Report_List')}}</th>
    </tr>
    <tr>
        <th>{{ translate('filter_Criteria') .' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('search_Bar_Content').' '.'-'.' '. ($data['search'] ?? 'N/A')}}
            @if(isset($data['vendor']))
            <br>
                {{translate('store_Name')}} - {{$data['vendor']?->shop?->name}}
            @endif
            <br>
            {{translate('date_type').' '.'-'.' '.translate($data['dateType'])}}
            <br>
            @if($data['from'] && $data['to'])
                {{translate('from').' '.'-'.' '.date('d M, Y',strtotime($data['from']))}}
                <br>
                {{translate('to').' '.'-'.' '.date('d M, Y',strtotime($data['to']))}}
                <br>
            @endif
        </th>
    </tr>
    <tr>
        <td> {{translate('SL')}}</td>
        <th>{{translate('XID')}}</th>
        <th>{{translate('transaction_Date')}}</th>
        <th>{{translate('order_ID')}}</th>
        <th>{{translate('expense_Amount')}}</th>
        <th>{{translate('expense_Type')}}</th>
    </tr>
    @foreach ($data['transactions'] as $key=>$transaction)
        <tr>
            <td> {{++$key}} </td>
            <td>{{ $transaction->orderTransaction->transaction_id }}</td>
            <td>{{ date_format($transaction?->orderTransaction->updated_at, 'd F Y h:i:s a') }}</td>
            <td>{{$transaction->id}}</td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($transaction->coupon_discount_bearer == 'inhouse'? $transaction->discount_amount:0) + ($transaction->free_delivery_bearer=='admin'?$transaction->extra_discount:(($transaction->coupon_discount_bearer == 'seller'?$transaction->discount_amount:0) + ($transaction->free_delivery_bearer=='seller'?$transaction->extra_discount:0)))), currencyCode: getCurrencyCode()) }}</td>
            <td>
                @php($transactionCouponType = ($transaction->coupon_discount_bearer == 'inhouse'?(isset($transaction->coupon->coupon_type) ? ($transaction->coupon->coupon_type == 'free_delivery' ? translate('free_Delivery_Promotion'): (ucwords(str_replace('_', ' ', $transaction->coupon->coupon_type))) ): ''):($transaction->coupon_discount_bearer == 'seller'?(isset($transaction->coupon->coupon_type) ? ($transaction->coupon->coupon_type == 'free_delivery' ? 'Free Delivery Promotion':ucwords(str_replace('_', ' ', $transaction->coupon->coupon_type))) : ''):'')) )
                @php($extraDiscountType = ($transaction->free_delivery_bearer == 'admin' ? ucwords(str_replace('_', ' ', $transaction->extra_discount_type)):($transaction->free_delivery_bearer == 'seller' ? ucwords(str_replace('_', ' ', $transaction->extra_discount_type)):'' ) ))
                @if(!empty($transactionCouponType))
                    {{$transactionCouponType}}
                @endif
                @if(!empty($extraDiscountType))
                    <br>
                    {{$extraDiscountType}}
                @endif
            </td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
