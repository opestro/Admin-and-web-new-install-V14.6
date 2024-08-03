<div class="card-header">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/top-customers.png')}}" alt="">
        {{translate('top_Delivery_Man')}}
    </h4>
</div>

<div class="card-body">
    @if($topRatedDeliveryMan)
        <div class="grid-card-wrap">
            @foreach($topRatedDeliveryMan as $key=> $deliveryMan)
                @if(isset($deliveryMan['id']))
                    <div class="cursor-pointer get-view-by-onclick" data-link="{{ route('admin.delivery-man.earning-statement-overview',[$deliveryMan['id']]) }}">
                        <div class="grid-card basic-box-shadow">
                            <div class="text-center">
                                <img class="avatar rounded-circle avatar-lg get-view-by-onclick" alt=""
                                     src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.$deliveryMan['image']??'',type:'backend-profile') }}"
                                     data-link="{{ route('admin.delivery-man.earning-statement-overview',[$deliveryMan['id']]) }}">
                            </div>
                            <h5 class="mb-0 get-view-by-onclick line--limit-1 text-center" data-link="{{ route('admin.delivery-man.earning-statement-overview',[$deliveryMan['id']]) }}">
                                {{Str::limit($deliveryMan['f_name'].' '.$deliveryMan['l_name'], 25)}}
                            </h5>
                            <div class="orders-count d-flex gap-1">
                                <div>{{translate('order_delivered')}} :</div>
                                <div>{{$deliveryMan['delivered_orders_count']}}</div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">{{translate('no_data_found').'!'}}</p>
            <img class="w-75" src="{{dynamicAsset(path: 'public/assets/back-end/img/no-data.png')}}" alt="">
        </div>
    @endif
</div>
