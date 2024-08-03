<html>
<table>
    <thead>
    <tr>
        <th>{{translate('order_Transaction_Report_List')}}</th>
    </tr>
    <tr>
        <th>{{ translate('filter_Criteria') .' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('search_Bar_Content').' '.'-'.' '. ($data['search'] ?? 'N/A')}}
            <br>
            {{translate('status').' '.'-'.' '. translate($data['status'] ?? translate('all'))}}
            <br>
            {{translate('store')}} - {{ucwords($data['vendor'] != 'all' && $data['vendor'] !='inhouse' ? $data['vendor']?->shop->name : $data['vendor'])}}
            <br>
            {{translate('customer')}} - {{ucwords($data['customer'] != 'all' ? ($data['customer']->f_name.' '.$data['customer']->l_name) : translate('all'))}}
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
        @if(isset($data['data-from']) && $data['data-from'] == 'admin')
        <td> {{translate('shop_Name')}}    </td>
        @endif
        <td> {{translate('customer_Name')}}</td>
        <td> {{translate('total_Product_Amount')}}</td>
        <td> {{translate('product_Discount')}}</td>
        <td> {{translate('coupon_Discount')}}</td>
        <td> {{translate('discounted_Amount')}}</td>
        <td> {{translate('VAT/TAX')}}</td>
        <td> {{translate('shipping_Charge')}}</td>
        <td> {{translate('order_Amount')}}</td>
        <td> {{translate('delivered_By')}}</td>
        <td> {{translate('deliveryman_Incentive')}}</td>
        <td> {{translate('admin_Discount')}}</td>
        <td> {{translate('vendor_Discount')}}</td>
        <td> {{translate('admin_Commission')}}</td>
        @if(isset($data['data-from']) && $data['data-from'] == 'admin')
        <td> {{translate('admin_Net_Income')}}</td>
        @endif
        <td> {{translate('vendor_Net_income')}}</td>
        <td> {{translate('payment_Method')}}</td>
        <td> {{translate('payment_Status')}}</td>
    </tr>
    @foreach ($data['transactions'] as $key=>$transaction)
        <tr>
            <td> {{++$key}} </td>
            <td>
                {{$transaction['order_id']}}
            </td>
            @if(isset($data['data-from']) && $data['data-from'] == 'admin')
            <td>
                @if($transaction['seller_is'] == 'admin')
                    {{ getWebConfig('company_name') }}
                @else
                    @if (isset($transaction->seller->shop))
                        {{ $transaction->seller->shop->name }}
                    @else
                        {{translate('not_found')}}
                    @endif
                @endif
            </td>
            @endif
            <td>
                @if (!$transaction->order->is_guest && isset($transaction->customer))
                    {{ $transaction->customer->f_name}} {{ $transaction->customer->l_name }}
                @elseif($transaction->order->is_guest)
                    {{translate('guest_customer')}}
                @else
                    {{translate('not_found')}}
                @endif
            </td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->orderDetails[0]?->order_details_sum_price??0), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->orderDetails[0]?->order_details_sum_discount??0), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order->discount_amount), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($transaction->orderDetails[0]?->order_details_sum_price??0) - ($transaction->orderDetails[0]?->order_details_sum_discount??0) - (isset($transaction->order->coupon) && $transaction->order->coupon->coupon_type != 'free_delivery'?$transaction->order->discount_amount:0)), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->tax ), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order->shipping_cost), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction->order->order_amount), currencyCode: getCurrencyCode()) }}</td>
            <td>{{$transaction['delivered_by']}}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($transaction->order->delivery_type == 'self_delivery' && $transaction->order->delivery_man_id) ? $transaction->order->deliveryman_charge : 0), currencyCode: getCurrencyCode()) }}</td>
            <td>
                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['adminCouponDiscount']+$transaction['adminShippingDiscount']), currencyCode: getCurrencyCode()) }}
            </td>
            <td>
                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['vendorCouponDiscount'] + $transaction['vendorShippingDiscount']), currencyCode: getCurrencyCode()) }}
            </td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['admin_commission']), currencyCode: getCurrencyCode()) }}</td>
            @if(isset($data['data-from']) && $data['data-from'] == 'admin')
            <td>
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['adminNetIncome']), currencyCode: getCurrencyCode()) }}
            </td>
            @endif
            <td>
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['vendorNetIncome']-$transaction['vendorShippingDiscount']), currencyCode: getCurrencyCode()) }}
            </td>
            <td>{{str_replace('_',' ',$transaction['payment_method'])}}</td>
            <td>{{translate(str_replace('_',' ',$transaction['status']))}}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
