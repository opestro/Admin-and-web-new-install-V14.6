<html>
<table>
    <thead>
    <tr>
        <th style="font-size: 18px">{{translate('delivery_Man_Withdraw_Request_List')}}</th>
    </tr>
    <tr>

        <th>{{ translate('withdraw_Request_Analytics') .' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('search_Bar_Content').' '.'-'.' '. ($data['searchValue'] ?? 'N/A')}}
            <br>
            {{translate('total__Request').' '.'-'.' '.count($data['withdraw_request'])}}
            <br>
            {{translate('pending_Request').' '.'-'.' '.$data['pending_request']}}
            <br>
            {{translate('approved_Request').' '.'-'.' '.$data['approved_request']}}
            <br>
            {{translate('denied_Request').' '.'-'.' '.$data['denied_request']}}
        </th>
    </tr>
    <tr>
        <th>{{translate('filter_Criteria')}}-</th>
        <th></th>
        <th> {{ucwords($data['filter'] ?? 'all')}}</th>
    </tr>
    <tr>
        <td> {{translate('SL')}}    </td>
        <td> {{translate('first_Name')}}</td>
        <td> {{translate('last_Name')}}</td>
        <td> {{translate('request_Time')}}</td>
        <td> {{translate('amount')}}</td>
        <td> {{translate('status')}}</td>
    </tr>
    @foreach ($data['withdraw_request'] as $key=>$item)
        <tr>
            <td> {{++$key}}  </td>
            <td> {{ ucwords($item?->deliveryMan?->f_name ?? translate('not_found')) }}</td>
            <td> {{ ucwords($item?->deliveryMan?->l_name ?? translate('not_found')) }}</td>
            <td> {{ date_format( $item->created_at, 'd M ,Y, h:i:s A') }} </td>
            <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item['amount'] ?? 0))}} </td>
            <td> {{ucwords($item->approved==0 ? 'pending' : ($item->approved==1 ? 'approved' : 'denied'))}}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
