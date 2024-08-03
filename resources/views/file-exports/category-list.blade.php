<html>
<table>
    <thead>
    <tr>
        <th style="font-size: 18px">{{translate($data['title'].'_'.'List')}}</th>
    </tr>
    <tr>

        <th>{{ translate($data['title'].'_'.'Analytics').' '.'-' }}</th>
        <th></th>
        <th>
            {{translate('total'.'_'.$data['title']).' '.'-'.' '.count($data['categories'])}}
            @if($data['title'] == 'category')
                <br>
                {{translate('inactive'.'_'.$data['title']).' '.'-'.' '.$data['active']}}
                <br>
                {{translate('active'.'_'.$data['title']).' '.'-'.' '.$data['inactive']}}
            @endif
        </th>
    </tr>
    <tr>
        <th>{{translate('search_Criteria')}}-</th>
        <th></th>
        <th>  {{translate('search_Bar_Content').' '.'-'.' '.$data['search'] ?? 'N/A'}}</th>
    </tr>
    <tr>
        <td> {{translate('ID')}}</td>
        @if($data['title'] == 'category')
            <td> {{translate('category_Image')}}</td>
        @endif
        <td> {{translate($data['title'].'_'.'Name')}}</td>
        @if($data['title'] == 'sub_sub_category')
            <td> {{translate('sub_Category_Name')}}</td>
        @endif
        @if($data['title'] == 'sub_category' || $data['title'] == 'sub_sub_category')
            <td> {{translate('category_Name')}}</td>
        @endif
        <td> {{translate('priority')}}	</td>
        @if($data['title'] == 'category')
            <td> {{translate('home_category_status')}}</td>
        @endif
    </tr>
    @foreach ($data['categories'] as $key=>$item)
        <tr>
            <td> {{$item['id']}}	</td>
            @if($data['title'] == 'category')
                <td style="height: 70px"></td>
            @endif
            <td> {{$item['defaultName']}}</td>
            @if($data['title'] == 'sub_sub_category')
                <td> {{$item?->parent?->parent->defaultName ??translate('sub_category_not_found') }}</td>
            @endif
            @if($data['title'] == 'sub_category' || $data['title'] == 'sub_sub_category')
                <td> {{$item?->parent?->defaultName ?? translate('category_not_found')}}</td>
            @endif
            <td> {{$item->priority}}</td>
            @if($data['title'] == 'category')
                <td> {{translate($item->home_status == 1 ? 'active' : 'inactive')}}</td>
            @endif
        </tr>
    @endforeach
    </thead>
</table>
</html>
