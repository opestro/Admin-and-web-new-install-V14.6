<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate('brand_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('brand_Analytics').' '.'-' }}</th>
                <th></th>
                <th>
                        {{translate('total_Brand').' '.'-'.' '.count($data['brands'])}}
                    <br>
                        {{translate('inactive_Brand').' '.'-'.' '.$data['active']}}
                    <br>
                        {{translate('active_Brand').' '.'-'.' '.$data['inactive']}}
                </th>
            </tr>
            <tr>
                <th>{{translate('search_Criteria')}}-</th>
                <th></th>
                <th>  {{translate('search_Bar_Content').' '.'-'.' '.$data['search'] ?? 'N/A'}}</th>
            </tr>
            <tr>
                <td> {{translate('SL')}}	</td>
                <td> {{translate('brand_Logo')}}</td>
                <td> {{translate('name')}}</td>
                <td> {{translate('total_Product')}}	</td>
                <td> {{translate('total_Order')}}	</td>
                <td> {{translate('status')}}	</td>
            </tr>
            @foreach ($data['brands'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height: 70px"></td>
                    <td> {{$item['defaultName']}}</td>
                    <td> {{$item->brand_all_products_count}}</td>
                    <td> {{$item['brandAllProducts']->sum('order_details_count')}}</td>
                    <td> {{translate($item->status == 1 ? 'active' : 'inactive')}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
