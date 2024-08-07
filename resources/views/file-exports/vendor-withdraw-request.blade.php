<html>
<table>
    <thead>
    <tr>
        <th>{{translate('vendor_Withdraw_Request')}}</th>
    </tr>
    <tr>

        <th>{{ translate('vendor_Withdraw_Analytics') .' '.'-'}}</th>
        <th></th>
        <th>
            @if(isset($data['vendor']))
                {{translate('store_Name')}} - {{$data['vendor']?->shop?->name}}
                <br>
            @endif
            {{translate('total_Withdraw_Request').' '.'-'.' '.count($data['withdraw_request'])}}
            <br>
            @if($data['filter']=='all')
                {{translate('total_Pending').' '.'-'.' '.$data['pending']}}
                <br>
                {{translate('total_Approved').' '.'-'.' '.$data['approved']}}
                <br>
                {{translate('total_Denied').' '.'-'.' '.$data['denied']}}
            @endif
        </th>
    </tr>
    <tr>
        <th>{{translate('filter_Criteria')}}-</th>
        <th></th>
        <th> {{translate($data['filter'])}}</th>
    </tr>
    <tr>
        <td> {{translate('SL')}}    </td>
        <td> {{translate('amount')}}</td>
        @if(isset($data['data-from']) && $data['data-from'] == 'admin')
        <td> {{translate('name')}}</td>
        @endif
        <td> {{translate('request_Time')}}</td>
        <td> {{translate('status')}}</td>
    </tr>
    @foreach ($data['withdraw_request'] as $key=>$item)
        <tr>
            <td> {{++$key}}    </td>
            <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item['amount'] ?? 0))}} </td>
            @if(isset($data['data-from']) && $data['data-from'] == 'admin')
            <td>{{ ucwords(($item?->seller?->f_name ?? translate('not_found')) . ' ' . $item?->seller?->l_name) }}</td>
            @endif
            <td> {{ date_format( $item->created_at, 'd M ,Y, h:i:s A') }} </td>
            <td> {{ucwords($item->approved==0 ? 'pending' : ($item->approved==1 ? 'approved' : 'denied'))}}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
