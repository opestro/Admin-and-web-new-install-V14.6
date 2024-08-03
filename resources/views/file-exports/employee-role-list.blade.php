<html>
<table>
    <thead>
    <tr>
        <th style="font-size: 18px">{{translate('employee_Role_List')}}</th>
    </tr>
    <tr>
        <th>{{ translate('employee_Role_Analytics') .' '.'-'}}</th>
        <th></th>
        <th>
            {{translate('search_Bar_Content')}} - {{!empty($data['searchValue']) ? $data['searchValue'] : 'N/A'}}
            <br>
            {{translate('active_Employee_Role').' '.'-'.' '.$data['active']}}
            <br>
            {{translate('inactive_Employee_Role').' '.'-'.' '.$data['inActive']}}
        </th>
    </tr>
    <tr>
        <td> {{translate('SL')}}	</td>
        <td> {{translate('role_Name')}}</td>
        <td> {{translate('Modules')}}</td>
        <td> {{translate('created_At')}}</td>
        <td> {{translate('status')}}</td>
    </tr>
    @foreach ($data['roles'] as $key=>$item)
        <tr>
            <td> {{++$key}}	</td>
            <td>{{ucwords($item['name'])}}</td>
            <td>
                @if($item['module_access'] != null)
                    @foreach((array)json_decode($item['module_access']) as $module)
                        @if($module == 'report')
                            {{translate('reports_and_analytics').(!$loop->last ? ',': '')}} <br>
                        @elseif($module == 'user_section')
                            {{translate('user_management').(!$loop->last ? ',': '')}} <br>
                        @elseif($module == 'support_section')
                            {{translate('Help_&_Support_Section').(!$loop->last ? ',': '')}} <br>
                        @else
                            {{translate(str_replace('_',' ', $module)).(!$loop->last ? ',': '')}} <br>
                        @endif
                    @endforeach
                @endif
            </td>
            <td> {{date('d M, Y h:i A',strtotime($item->created_at))}}</td>
            <td>{{translate($item['status'] ==1 ? 'active' : 'inactive')}}</td>
        </tr>
    @endforeach
    </thead>
</table>
</html>
