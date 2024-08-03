<html>
    <table>
        <thead>
            <tr>
                <th>{{translate('product_Wishlisted_Report_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('filter_Criteria').' '.'-' }}</th>
                <th></th>
                <th>

                    {{translate('search_Bar_Content')}} - {{ $data['search'] ?? 'N/A'}}
                    <br>
                    {{translate('store')}} - {{ucwords($data['seller'] != 'all' && $data['seller'] !='inhouse' ? $data['seller']?->shop->name : translate($data['seller'] ?? 'all' ))}}
                    <br>
                    {{translate('wishlist_Sort_By').' '.'-'.' '.translate($data['sort'] == 'ASC' ? 'low_to_high' : 'high_to_low')}}
                    <br>
                </th>
            </tr>
            <tr>
                <td> {{translate('SL')}}</td>
                <td> {{translate('product_Name')}}</td>
                <td> {{translate('Date')}}	</td>
                <td> {{translate('total_In_Wishlist	')}}</td>
            </tr>
            @foreach ($data['products'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td> {{$item['name']}}	</td>
                    <td> {{ date('d M, Y', $item['created_at']  ? strtotime($item['created_at']) : null) }}	</td>
                    <td> {{ $item->wish_list_count }}</td>
            @endforeach
        </thead>
    </table>
</html>
