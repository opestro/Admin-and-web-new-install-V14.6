<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate('subscriber_List')}}</th>
            </tr>
            <tr>
                <th>{{translate('search_Criteria')}}-</th>
                <th>  {{translate('search_Bar_Content')}} - {{!empty($data['search']) ? $data['search'] : 'N/A'}}</th>
                <th></th>
            </tr>
            <tr>
                <td> {{translate('SL')}}	</td>
                <td> {{translate('Email_ID')}}</td>
                <td> {{translate('subscription_Date')}}</td>
            </tr>
            @foreach ($data['subscription'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td>{{$item->email}}</td>
                    <td> {{date('d M, Y',strtotime($item->created_at))}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
