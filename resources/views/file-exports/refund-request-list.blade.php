<html>
<table>
    <thead>
    <tr>
        <th style="font-size: 18px">{{translate('refund_Order_List')}}</th>
    </tr>
    <tr>
        <th>{{ translate('filter_Criteria') .' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('refund_Status').' '.'-'.' '.translate($data['status'])}}
            <br>
            {{translate('search_Bar_Content')}} - {{!empty($data['search']) ?  ucwords($data['search']) : 'N/A'}}
            @if(isset($data['vendor']))
            <br>
            {{translate('store_Name')}} - {{$data['vendor']?->shop?->name}}
            @endif
            <br>
            {{ucwords(translate('total'.' '.$data['status'].' '.'refund_Requests'))}} - {{count($data['refundList'])}}
            @if(isset($data['data-from']) && $data['data-from'] == 'admin')
                <br>
                {{translate('filter_By').' '.'-'.' '.ucwords(translate($data['filter_By']))}}
            @endif
        </th>
    </tr>
    <tr>
        <td> {{translate('SL')}}    </td>
        <td> {{translate('order_ID')}}    </td>
        <td> {{translate('order_Date')}}</td>
        <td> {{translate('product_Information')}}    </td>
        <td> {{translate('product_Amount')}}</td>
        <td> {{translate('Refund_Amount')}}</td>
        <td> {{translate('customer_Name')}}    </td>
        @if(isset($data['data-from']) && $data['data-from'] == 'admin')
        <td> {{translate('store_Name')}}</td>
        @endif
        <td> {{translate('delivery_Name')}}</td>
        <td> {{translate('refund_Reason')}}</td>
    </tr>
    @foreach ($data['refundList'] as $key=>$item)
        @php($product =  json_decode($item?->orderDetails->product_details))
        <tr>
            <td> {{++$key}}    </td>
            <td> {{$item->order_id}}</td>
            <td> {{date('d M, Y h:i A',strtotime($item->order->created_at))}}</td>
            <td>
                {{$product->name ?? translate('product_not_found')}}
                <br>
                {{translate('qty') .'-'. $item?->orderDetails->qty}}
            </td>
            <td>
                {{setCurrencySymbol(amount:usdToDefaultCurrency(amount: $product->unit_price - getProductDiscount(product: (array)$product, price: $product->unit_price)))}}
            </td>
            <td> {{ setCurrencySymbol(amount:usdToDefaultCurrency(amount: $item->amount ?? 0))}}</td>
            <td> {{ucwords(($item?->customer?->f_name ?? translate('not_found')) .' '. $item?->customer?->l_name)}}    </td>
            @if(isset($data['data-from']) && $data['data-from'] == 'admin')
            <td> {{ucwords($item?->order?->seller_is == 'seller' ? ($item?->order?->seller?->shop?->name ?? translate('not_found')) : translate('inhouse'))}}</td>
            @endif
            <td> {{ucwords($item?->order?->delivery_type == 'self_delivery' ? (isset($item?->order?->deliveryMan) ? $item?->order?->deliveryMan?->f_name.' '.$item?->order?->deliveryMan?->l_name : translate('not_found')) : ($item?->order?->delivery_service_name ??translate('not_found')."\n".$item?->order?->third_party_delivery_tracking_id ?? translate('not_found')))}}    </td>
            <td> {{translate($item->refund_reason)}}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
