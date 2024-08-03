<div class="card-header">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/top-selling-product-icon.png')}}" alt="">
        {{translate('top_selling_products')}}
    </h4>
</div>

<div class="card-body">
    @if($topSell)
        <div class="grid-item-wrap">
            @foreach($topSell as $key=>$product)
                    <div class="cursor-pointer"  onclick="location.href='{{route('vendor.products.view',[$product['id']])}}'">
                        <div class="grid-item bg-transparent basic-box-shadow">
                            <div class="d-flex align-items-center gap-10">
                                <img class="avatar avatar-lg rounded avatar-bordered"
                                     src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'. $product['thumbnail'], type: 'backend-product') }}"
                                     alt="{{$product->name}} image">
                                <span class="title-color line--limit-2">{{substr($product['name'],0,40)}} {{strlen($product['name'])>20?'...':''}}</span>
                            </div>
                            <div class="orders-count py-2 px-3 d-flex gap-1">
                                <div>{{translate('sold')}} :</div>
                                <div class="sold-count">{{$product['order_details_count']}}</div>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">{{translate('no_Top_Selling_Products')}}</p>
            <img class="w-75" src="{{dynamicAsset(path: 'public/assets/back-end/img/no-data.png')}}" alt="">
        </div>
    @endif
</div>
