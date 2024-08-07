<html>
<table>
    <thead>
    <tr>
        <th>{{translate('vendor_Earning_Report')}}</th>
    </tr>
    <tr>

        <th>{{ translate('filter_Criteria') .' '.'-'}}</th>
        <th></th>
        <th>
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
        <td> {{translate('vendor_Info')}}    </td>
        <td> {{translate('earn_From_Order')}}    </td>
        <td> {{translate('earn_From_Shipping')}}</td>
        <td> {{translate('deliveryman_Incentive')}}</td>
        <td> {{translate('commission_Given')}}</td>
        <td> {{translate('discount_Given')}}</td>
        <td> {{translate('tax_Collected')}}</td>
        <td> {{translate('refund_Given')}}</td>
        <td> {{translate('total_Earning')}}</td>
    </tr>
    @foreach ($data['vendorEarnTable'] as $key=>$item)
        @php($shippingEarnTable = $data['shipping_earn_table'][$key]['amount'] ?? 0)
        @php($deliverymanIncentiveTable =$data['deliverymanIncentiveTable'][$key]['amount'] ?? 0)
        @php($commissionGivenTable = $data['commissionGivenTable'][$key]['amount'] ?? 0)
        @php($discountGivenTable = $data['discountGivenTable'][$key]['amount'] ?? 0)
        @php($discountGivenBearerAdminTable = $data['discountGivenBearerAdminTable'][$key]['amount'] ?? 0)
        @php($totalTaxTable = $data['totalTaxTable'][$key]['amount'] ?? 0)
        @php($totalRefundTable = $data['totalRefundTable'][$key]['amount'] ?? 0)
        @php($totalEarnFromOrder=$item['amount']+$discountGivenTable-$totalTaxTable)
        <tr>
            <td>{{$loop->iteration++}}</td>
            <td>{{ $item['name'] }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $totalEarnFromOrder), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $shippingEarnTable), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $deliverymanIncentiveTable), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $commissionGivenTable), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discountGivenTable), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $totalTaxTable), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $totalRefundTable), currencyCode: getCurrencyCode()) }}</td>
            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $totalEarnFromOrder+$shippingEarnTable+$totalTaxTable-$discountGivenTable-$totalRefundTable-$commissionGivenTable-$deliverymanIncentiveTable), currencyCode: getCurrencyCode()) }}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
