<html>
<table>
    <thead>
        <tr>
            <th style="font-size: 18px">{{translate('order_List')}}</th>
        </tr>
        <tr>

            <th>{{ translate('filter_criteria').' '.'-' }}</th>
            <th></th>
            <th>
                @if($data['order_status'] != 'all')
                    {{translate('order_Status').' '.'-'.' '.translate($data['order_status'] != 'failed' ? $data['order_status'] : 'failed_to_deliver')}}
                    <br>
                @endif
                {{translate('search_Bar_Content').' '.'-'.' '.$data['searchValue'] ?? 'N/A'}}
                <br>
                {{translate('order_Type').' '.'-'.' '.translate($data['order_type']== 'admin' ? 'inhouse' : ($data['order_type'] == 'default_type' ? 'website_order' : $data['order_type']) )}}
                <br>
                {{translate('store').' '.'-'.' '.ucwords($data['seller']?->shop?->name ?? translate('all'))}}
                <br>
                {{translate('customer_Name').' '.'-'.' '.ucwords(isset($data['customer']->f_name) ? $data['customer']->f_name.' '.$data['customer']->l_name : translate('all_customers') )}}
                <br>
                {{translate('date_type').' '.'-'.' '.translate(!empty($data['date_type']) ? $data['date_type'] : 'all')}}
                <br>
                @if ($data['date_type'] == 'custom_date')
                    {{translate('from').' '.'-'.' '.$data['from'] ?? date('d M, Y',strtotime($data['from']))}}
                    <br>
                    {{translate('to').' '.'-'.' '.$data['to'] ?? date('d M, Y',strtotime($data['to']))}}
                    <br>
                @endif
            </th>
        </tr>
        <tr>
            @if($data['order_status'] == 'all')
                <th>{{translate('order_Status')}}</th>
                <th></th>
                <th>
                    @foreach ($data['status_array'] as $key=>$value)
                        {{translate($key != 'failed' ? $key : 'failed_to_deliver').' '.'-'.' '.$value}}
                    @endforeach
                </th>
            @endif
        </tr>
        <tr>
            <th> {{translate('SL')}}    </th>
            <th> {{translate('Order_ID')}}    </th>
            <th> {{translate('Order_Date')}}    </th>
            <th> {{translate('Customer_Name')}}    </th>
            @if(isset($data['data-from']) && $data['data-from'] == 'admin')
            <th> {{translate('Store_Name')}}    </th>
            @endif
            <th> {{translate('Total_Items')}}    </th>
            <th> {{translate('Item_Price')}}    </th>
            <th> {{translate('Item_Discount')}}    </th>
            <th> {{translate('Coupon_Discount')}}    </th>
            <th> {{translate('extra_Discount')}}    </th>
            <th> {{translate('Discounted_Amount')}}    </th>
            <th> {{translate('Vat/Tax')}}    </th>
            <th> {{translate('shipping')}}    </th>
            <th> {{translate('Total_Amount')}}    </th>
            <th> {{translate('Payment_Status')}}</th>
            @if($data['order_status'] == 'all')
                <th> {{translate('Order_Status')}}</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @foreach ($data['orders'] as $key=>$order)
            @php
                if ($order->extra_discount_type == 'percent') {
                    $extra_discount = $order->total_price*$order->extra_discount /100;
                }else {
                    $extra_discount = $order->extra_discount;
                }
                $extraDiscountFinal = $order->is_shipping_free == 1 ? $extra_discount : 0;
                $totalAmount = ($order?->order_amount ?? 0) + $extraDiscountFinal;
                $defaultCurrencyCode = getCurrencyCode();
            @endphp

            <tr>
                <td> {{++$key}}	</td>
                <td> {{$order->id}}	</td>
                <td> {{date('d M, Y h:i A',strtotime($order->created_at))}}</td>
                <td> {{ucwords($order->is_guest == 0 ? (($order?->customer?->f_name ?? translate('not_found')) .' '. $order?->customer?->l_name) : translate('guest_customer'))}}	</td>
                @if(isset($data['data-from']) && $data['data-from'] == 'admin')
                <td> {{ucwords($order?->seller_is == 'seller' ? ($order?->seller?->shop->name ?? translate('not_found')) : translate('inhouse'))}}	</td>
                @endif
                <td> {{$order->total_qty}} </td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order?->total_price ?? 0), currencyCode: getCurrencyCode())}} </td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order?->total_discount ?? 0), currencyCode: getCurrencyCode())}} </td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order?->discount_amount ?? 0), currencyCode: getCurrencyCode())}}</td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $extra_discount), currencyCode: getCurrencyCode())}}</td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($order?->total_price ?? 0) - ($order?->total_discount ?? 0)- ($order?->discount_amount ?? 0) - ($order->is_shipping_free == 0 ? $extra_discount : 0)), currencyCode: getCurrencyCode())}}  </td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order?->total_tax ?? 0), currencyCode: getCurrencyCode())}}	</td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->is_shipping_free == 0 ? ($order?->shipping_cost ?? 0) : 0), currencyCode: getCurrencyCode())}}	</td>
                <td> {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $totalAmount ?? 0), currencyCode: getCurrencyCode())}}</td>
                <td> {{translate($order->payment_status)}}</td>
                @if($data['order_status'] == 'all')
                    <td> {{translate($order->order_status)}}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
</html>
