<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate('vendor_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('vendor_Analytics') }} -</th>
                <th></th>
                <th>
                        {{translate('total_Vendor')}} - {{count($data['vendors'])}}
                    <br>
                        {{translate('active_Vendors ')}} - {{$data['active']}}
                    <br>
                        {{translate('inactive_Vendors ')}} - {{$data['inactive']}}
                </th>
            </tr>
            <tr>
                <th>{{translate('search_Criteria')}}-</th>
                <th></th>
                <th>  {{translate('search_Bar_Content')}} - {{!empty($data['search']) ? $data['search'] : 'N/A'}}</th>
            </tr>
            <tr>
                <td> {{translate('SL')}}</td>
                <td> {{translate('store_Logo')}}</td>
                <td> {{translate('store_Name')}}</td>
                <td> {{translate('vendor_Name')}}</td>
                <td> {{translate('phone')}}	</td>
                <td> {{translate('email')}}	</td>
                <td> {{translate('joined_At')}}	</td>
                <td> {{translate('total_Products')}}	</td>
                <td> {{translate('total_Order')}} </td>
                <td> {{translate('status')}}</td>
            </tr>
            @foreach ($data['vendors'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height: 70px"></td>
                    <td> {{ucwords($item?->shop->name)}}</td>
                    <td> {{ucwords($item->f_name.' '.$item->l_name)}}</td>
                    <td> {{$item?->phone ?? translate('not_found')}}</td>
                    <td> {{ucwords($item->email)}}</td>
                    <td> {{date('d M, Y h:i A',strtotime($item->created_at))}}</td>
                    <td> {{$item->product_count}}</td>
                    <td> {{$item->orders_count}}</td>
                    <td> {{translate($item->status == 'approved' ? 'active' : 'inactive')}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
