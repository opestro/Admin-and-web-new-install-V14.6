<html>
<table>
    <thead>
    <tr>
        <th style="font-size: 18px">{{translate($data['type'] == 'earn' ? 'delivery_Man_Earnings' : 'delivery_Man_Order_List')}}</th>
    </tr>
    <tr>

        <th>{{ translate('delivery_Man_Information').' '.'-' }}</th>
        <th></th>
        <th>
            {{translate('name').' '.'-'.' '.$data['delivery_man']['f_name'].' '.$data['delivery_man']['l_name']}}
            <br>
            {{translate('rating').' '.'-'.' '.(isset($data['delivery_man']?->rating[0]?->average) ? number_format($data['delivery_man']?->rating[0]?->average, 1) : 0) }}
            <br>
            {{translate('total_Order').' '.'-'.' '.count($data['orders'])}}
        </th>
    </tr>


    <tr>
        @if ($data['type'] == 'earn')
            <th>{{translate('earning_Analytics')}}-</th>
            <th></th>
            <th> {{translate('total_Earning').' '.'-'.' '.setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['total_earn'] ?? 0)) }} </th>
            <th></th>
            <th> {{translate('withdrawable_Balance').' '.'-'.' '. setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['withdrawable_balance'] ?? 0)) }}</th>
            <th></th>
            <th> {{translate('already_Withdrawn').' '.'-'.' '. setCurrencySymbol(amount: usdToDefaultCurrency(amount: $data['delivery_man']?->wallet?->total_withdraw ?? 0)) }}</th>
        @else
            <th>{{translate('search_Criteria').' '.'-'}}</th>
            <th></th>
            <th>  {{translate('search_Bar_Content').' '.'-'.' '.!empty($data['search']) ? $data['search'] : 'N/A'}}</th>
        @endif
    </tr>
    <tr>
        <td> {{translate('SL')}}    </td>
        <td> {{translate('order_ID')}}</td>
        <td> {{translate('order_Date')}}</td>
        @if ($data['type'] != 'earn')
        <td> {{translate('total_Item')}}</td>
        @endif
        @if ($data['type'] == 'earn')
            <td> {{translate('earnings')}}</td>
        @endif
        <td> {{translate($data['type'] == 'earn' ? 'earning_status' : 'payment_status')}}</td>
        @if ($data['type'] == 'earn')
            <td> {{translate('payment_method')}}</td>
        @endif
        <td> {{translate('order_Status')}}</td>
    </tr>
    @foreach ($data['orders'] as $key=>$item)
        <tr>
            <td> {{++$key}}    </td>
            <td> {{$item->id}} </td>
            <td> {{ date_format( $item->created_at, 'd M ,Y, h:i:s A') }} </td>
            @if ($data['type'] != 'earn')
            <td> {{$item->total_qty}} </td>
            @endif
            @if ($data['type'] == 'earn')
                <td> {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item?->deliveryman_charge ?? 0)) }}</td>
            @endif
            @if ($data['type'] == 'earn')
                <td> {{translate($item->order_status == 'delivered' && $item->payment_status == 'paid' ? translate('received') : translate('not_received'))}} </td>
            @else
                <td> {{translate($item->payment_status)}} </td>
            @endif
            @if ($data['type'] == 'earn')
                <td>{{translate($item->payment_method)}}</td>
            @endif
            <td> {{translate($item->order_status)}}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
