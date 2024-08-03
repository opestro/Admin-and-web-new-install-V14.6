<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate('employee_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('employee_Analytics') .' '.'-'}}</th>
                <th></th>
                <th>
                        {{translate('filter_By').' '.'-'.' '.ucwords($data['filter'])}}
                    <br>
                        {{translate('total_Employee').' '.'-'.' '.count($data['employees'])}}
                    <br>
                        {{translate('active_Employee').' '.'-'.' '.$data['active']}}
                    <br>
                        {{translate('inactive_Employee').' '.'-'.' '.$data['inactive']}}
                </th>
            </tr>
            <tr>
                <th>{{translate('search_Criteria')}}-</th>
                <th></th>
                <th>  {{translate('search_Bar_Content').' '.'-'.' '.$data['search'] ?? 'N/A'}}</th>
            </tr>
            <tr>
                <td> {{translate('SL')}}	</td>
                <td> {{translate('employee_Image')}}</td>
                <td> {{translate('Name')}}</td>
                <td> {{translate('phone')}}	</td>
                <td> {{translate('email')}}	</td>
                <td> {{translate('role')}}	</td>
                <td> {{translate('accesses')}} </td>
                <td> {{translate('date_of_Joining')}} </td>
                <td> {{translate('status')}}</td>
            </tr>
            @foreach ($data['employees'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height: 70px"></td>
                    <td> {{ucwords($item->name)}}</td>
                    <td> {{$item->phone}}</td>
                    <td> {{ucwords($item->email)}}</td>
                    <td> {{ucwords($item?->role?->name)}}</td>
                    <td>
                        @if(!empty($item->role->module_access))
                            @foreach ( json_decode($item?->role->module_access) as $value)
                                @isset($value)
                                    {{ucwords(str_replace('_',' ',$value))}}
                                    <br>
                                @endisset
                            @endforeach
                        @endif
                    </td>
                    <td> {{date('d M, Y h:i A',strtotime($item->created_at))}}</td>
                    <td> {{translate($item->status == 1 ? 'active' : 'inactive')}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
