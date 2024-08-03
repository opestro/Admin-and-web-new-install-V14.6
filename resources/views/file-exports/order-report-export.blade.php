<html>
<table>
    <thead>
    <tr>
        <th>{{translate('order_Report_List')}}</th>
    </tr>
    <tr>
        <th>{{ translate('filter_Criteria') .' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('search_Bar_Content').' '.'-'.' '. ($data['search'] ?? 'N/A')}}
            <br>
            {{translate('store')}} - {{ucwords($data['vendor'] != 'all' && $data['vendor'] !='inhouse' ? $data['vendor']?->shop->name : ( $data['vendor'] ?? 'all' ))}}
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
        <td> {{translate('order_ID')}}    </td>
        <td> {{translate('total_Amount')}}    </td>
        <td> {{translate('product_Discount')}}</td>
        <td> {{translate('coupon_Discount')}}</td>
        <td> {{translate('shipping_Charge')}}</td>
        <td> {{translate('VAT/TAX')}}</td>
        <td> {{translate('commission')}}</td>
        <td> {{translate('deliveryman_incentive')}}</td>
        <td> {{translate('status')}}</td>
    </tr>
    @foreach ($data['orders'] as $key=>$item)
        <tr>
            <td> {{++$key}} </td>
            <td> {{$item['id']}} </td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item->order_amount ?? 0)) }}</td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:$item->details_sum_discount ?? 0)) }}</td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:$item->discount_amount ?? 0)) }}</td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:$item->shipping_cost - ($item->extra_discount_type == 'free_shipping_over_order_amount' ? $item->extra_discount : 0))) }}</td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:$item->details_sum_tax ?? 0)) }}</td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:$item->admin_commission ?? 0)) }}</td>
            <td>{{ ($item->delivery_type=='self_delivery' && $item->delivery_man_id) ? setCurrencySymbol(amount: usdToDefaultCurrency(amount:$item->deliveryman_charge ?? 0)) : setCurrencySymbol(amount: usdToDefaultCurrency(amount: 0)) }}</td>
            <td>{{translate($item['order_status'])}}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
