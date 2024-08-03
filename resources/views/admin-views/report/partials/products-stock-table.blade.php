<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">
            {{translate('product_Name')}} <label class="badge badge-success ml-3 cursor-pointer">{{translate('Asc/Dsc')}}</label>
        </th>
        <th scope="col">
            {{translate('total_Stock')}} <label class="badge badge-success ml-3 cursor-pointer">{{translate('Asc/Dsc')}}</label>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $key=>$data)
        <tr>
            <th scope="row">{{$key+1}}</th>
            <td>{{$data['name']}}</td>
            <td>{{$data['current_stock']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script type="text/javascript">
    "use strict";
    $(document).ready(function () {
        $('input').addClass('form-control');
    });
</script>
