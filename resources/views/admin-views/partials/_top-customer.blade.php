<!-- Header -->
<div class="card-header">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/top-customers.png')}}" alt="">
        {{translate('top_customer')}}
    </h4>
</div>
<div class="card-body">
    @if($top_customer)
        <div class="grid-card-wrap">
            @foreach($top_customer as $key=>$item)
                @if(isset($item->customer))
                    <div class="cursor-pointer"
                         onclick="location.href='{{route('admin.customer.view',[$item['customer_id']])}}'">
                        <div class="grid-card basic-box-shadow">
                            <div class="text-center">
                                <img class="avatar rounded-circle avatar-lg"
                                     src="{{getValidImage(path: 'storage/app/public/profile/'.$item->customer->image,type:'backend-profile')}}"
                                     alt="">
                            </div>

                            <h5 class="mb-0">{{$item->customer['f_name']??translate('not_exist')}}</h5>

                            <div class="orders-count d-flex gap-1">
                                <div>{{translate('orders')}} : </div>
                                <div>{{$item['count']}}</div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">{{translate('no_Top_Selling_Products')}}</p>
            <img class="w-75" src="{{dynamicAsset(path: 'public/assets/back-end/img/no-data.png')}}" alt="">
        </div>
    @endif
</div>
