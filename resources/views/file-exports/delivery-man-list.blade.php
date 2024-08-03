<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate('delivery_Man_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('delivery_Man_Analytics') .' '.'-'}}</th>
                <th></th>
                <th>
                    @if(isset($data['vendor']))
                        {{translate('store_Name')}} - {{$data['vendor']?->shop?->name}}
                        <br>
                    @endif
                        {{translate('total_Delivery_Man').' '.'-'.' '.count($data['delivery_men'])}}
                    <br>
                        {{translate('active_Delivery_Man').' '.'-'.' '.$data['active']}}
                    <br>
                        {{translate('inactive_Delivery_man').' '.'-'.' '.$data['inactive']}}
                </th>
            </tr>
            <tr>
                <th>{{translate('search_Criteria')}}-</th>
                <th></th>
                <th>  {{translate('search_Bar_Content').' '.'-'.' '.!empty($data['search']) ? $data['search'] : 'N/A'}}</th>
            </tr>
            <tr>
                <td> {{translate('SL')}}	</td>
                <td> {{translate('delivery_Man_Image')}}</td>
                <td> {{translate('first_Name')}}</td>
                <td> {{translate('last_Name')}}</td>
                <td> {{translate('phone')}}	</td>
                <td> {{translate('email')}}	</td>
                <td> {{translate('identity_Number')}}	</td>
                <td> {{translate('total_Order')}} </td>
                <td> {{translate('rating')}} </td>
                <td> {{translate('status')}}</td>
            </tr>
            @foreach ($data['delivery_men'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height: 70px"></td>
                    <td> {{ucwords($item->f_name)}}</td>
                    <td> {{ucwords($item->l_name)}}</td>
                    <td> {{$item->phone}}</td>
                    <td> {{ucwords($item->email)}}</td>
                    <td> {{ucwords($item?->identity_number)}}</td>
                    <td> {{$item->orders_count}}</td>
                    <td> {{isset($item->rating[0]->average) ? number_format($item->rating[0]->average, 1, '.', ' ') : 0 }}</td>
                    <td> {{translate($item->is_active == 1 ? 'active' : 'inactive')}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
