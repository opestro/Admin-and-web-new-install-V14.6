<html>
<table>
    <thead>
    <tr>
        <th>{{translate('product_Report_List')}}</th>
    </tr>
    <tr>

        <th>{{ translate('filter_Criteria') .' '.'-'}}</th>
        <th></th>
        <th>

            {{translate('search_Bar_Content').' '.'-'.' '. $data['search'] ?? 'N/A'}}
            <br>
            {{translate('store').' '.'-'.' '.ucwords($data['seller'] != 'all' && $data['seller'] !='inhouse' ? $data['seller']?->shop->name : translate($data['seller'] ?? 'all' ))}}
            <br>
            {{translate('date_type').' '.'-'.' '.translate($data['date_type'])}}
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
        <td> {{translate('product_Name')}}    </td>
        <td> {{translate('product_Unit_Price')}}    </td>
        <td> {{translate('total_Amount Sold')}}</td>
        <td> {{translate('total_Quantity_Sold')}}</td>
        <td> {{translate('average_Product_Value')}}</td>
        <td> {{translate('current_Stock_Amount')}}</td>
        <td> {{translate('average_Ratings')}}</td>
    </tr>
    @foreach ($data['products'] as $key=>$item)
        <tr>
            <td> {{++$key}}    </td>
            <td> {{$item['name']}}    </td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item->unit_price ?? 0 )) }}</td>
            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $item->orderDetails[0]->total_sold_amount ?? 0)) }}</td>
            <td>{{ $item->orderDetails[0]->product_quantity ?? 0 }}</td>
            <td>
                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:
                    ( $item->orderDetails[0]->total_sold_amount ?? 0) /
                    ( $item->orderDetails[0]->product_quantity ?? 1)))
                }}
            </td>
            <td>
                {{ $item->product_type == 'digital' ? ($item->status==1 ? translate('available') : translate('not_available')) : $item->current_stock }}
            </td>
            <td>{{$item?->rating && count($item->rating) > 0 ?  number_format($item->rating[0]->average,2) : 0}}
                ( {{$item->reviews->count()}} )
            </td>
        </tr>
        @endforeach
    </thead>
</table>
</html>
