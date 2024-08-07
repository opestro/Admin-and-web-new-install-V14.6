<html>
<table>
    <thead>
    <tr>
        <th style="font-size: 18px">{{translate('coupon_List')}}</th>
    </tr>
    <tr>
        <th>{{translate('search_Criteria').' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('search_Bar_Content').' '.'-'.' '.($data['search'] ?? 'N/A')}}
            @if(isset($data['vendor']))
                <br>
                {{translate('store_Name')}} - {{$data['vendor']?->shop?->name}}
            @endif
        </th>
    </tr>
    <tr>
        <td> {{translate('SL')}}    </td>
        <td> {{translate('coupon_Title')}}</td>
        <td> {{translate('coupon_Code')}}</td>
        <td> {{translate('coupon_Type')}}</td>
        <td> {{translate('number_of_Uses')}}</td>
        <td> {{translate('limit_for_Same_User')}}</td>
        <td> {{translate('min_Purchase_Amount')}}</td>
        <td> {{translate('max_discount_Amount')}}</td>
        <td> {{translate('discount_Type')}}</td>
        <td> {{translate('discount_Amount')}}</td>
        <td> {{translate('coupon_Bearer')}}</td>
        <td> {{translate('start_Date')}}</td>
        <td> {{translate('end_Date')}}</td>
    </tr>
    @foreach ($data['coupon'] as $key=>$item)
        <tr>
            <td> {{++$key}}    </td>
            <td> {{ucwords($item['title'])}}    </td>
            <td> {{$item['code']}}    </td>
            <td> {{translate($item['coupon_type'])}}</td>
            <td> {{ $item['order_count'] }}    </td>
            <td> {{ $item['limit']==0 ? 'N/A' : $item['limit'] }}    </td>
            <td> {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item['min_purchase'] ?? 0)) }}    </td>
            <td> {{ $item['discount_type'] == 'percentage' ? setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item['max_discount'] ?? 0)) : 'N/A'   }}    </td>
            <td> {{ translate($item['discount_type']) }} </td>
            <td> {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item['discount'] ?? 0))  }}    </td>
            <td> {{translate($item['coupon_bearer'] == 'inhouse' ? 'admin' : ($item['coupon_bearer'] == 'seller' ? 'vendor' : $item['coupon_bearer']))}}    </td>
            <td> {{date('d M, y',strtotime($item['start_date']))}}    </td>
            <td> {{date('d M, y',strtotime($item['expire_date']))}}    </td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
