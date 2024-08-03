<html>
    <table>
        <thead>
            <tr>
                <th style="font-size:18px">{{translate('customer_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('customer_Analytics').' '.'-' }}</th>
                <th></th>
                <th>
                        {{translate('total_Customer').' '.'-'.' '.count($data['customers'])}}
                    <br>
                        {{translate('active_Customer').' '.'-'.' '.$data['active']}}
                    <br>
                        {{translate('inactive_Customer').' '.'-'.' '.$data['inactive']}}
                </th>
            </tr>
            <tr>
                <th>{{translate('search_Criteria')}}-</th>
                <th></th>
                <th>  {{translate('search_Bar_Content').' '.'-'.' '.!empty($data['searchValue']) ? $data['searchValue'] : 'N/A'}}</th>
            </tr>
            <tr>
                <td> {{translate('SL')}}	</td>
                <td> {{translate('customer_Image')}}</td>
                <td> {{translate('Name')}}</td>
                <td> {{translate('phone')}}	</td>
                <td> {{translate('email')}}	</td>
                <td> {{translate('date_of_Joining')}} </td>
                <td> {{translate('total_Order')}}	</td>
                <td> {{translate('status')}}</td>
            </tr>
            @foreach ($data['customers'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height:80px"></td>
                    <td> {{ucwords(($item->f_name?? translate('not_found')).' '.$item->l_name)}}</td>
                    <td> {{$item?->phone ?? translate('not_found')}}</td>
                    <td> {{ucwords($item->email)}}</td>
                    <td> {{date('d M, Y ',strtotime($item->created_at))}}</td>
                    <td> {{$item->orders_count}}</td>
                    <td> {{translate($item->is_active == 1 ? 'active' : 'inactive')}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
