<html>
    <table>
        <thead>
            <tr>
                <th>{{translate('product_Stock_Report_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('filter_Criteria') }} -</th>
                <th></th>
                <th>

                    {{translate('search_Bar_Content')}} - {{ $data['search'] ?? 'N/A'}}
                    <br>
                    {{translate('store')}} - {{ucwords($data['seller'] != 'all' && $data['seller'] !='inhouse' ? $data['seller']?->shop->name : translate($data['seller'] ?? 'all' ))}}
                    <br>
                    {{translate('category')}} - {{ucwords($data['category'] != 'all' ? $data['category']['defaultName'] : translate($data['category'] ?? 'all' ))}}
                    <br>
                    {{translate('stock_Sort_By')}} - {{translate($data['sort'] == 'ASC' ? 'low_to_high' : 'high_to_low')}}
                    <br>
                </th>
            </tr>
            <tr>
                <td> {{translate('SL')}}</td>
                <td> {{translate('product_Name')}}</td>
                <td> {{translate('last_Updated_Stock')}}	</td>
                <td> {{translate('current_Stock	')}}</td>
                <td> {{translate('status')}}</td>
            </tr>
            @foreach ($data['products'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td> {{$item['name']}}	</td>
                    <td> {{ date('d M Y, h:i:s a', $item['updated_at'] ? strtotime($item['updated_at']) : null) }}	</td>
                    <td> {{$item['current_stock']}}</td>
                    <td>
                        @if($item['current_stock'] >= $data['stock_limit'])
                             {{translate('in-Stock')}}
                        @elseif($item['current_stock']  <= 0)
                            {{translate('out_of_Stock')}}
                        @elseif($item['current_stock'] < $data['stock_limit'])
                            {{translate('soon_Stock_Out')}}
                        @endif
                    </td>
            @endforeach
        </thead>
    </table>
</html>
