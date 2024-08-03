<div class="card-header gap-10">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/shop-info.png')}}" alt="">
        {{translate('top_selling_store')}}
    </h4>
</div>

<div class="card-body">
    <div class="grid-item-wrap">
        @if($topVendorByEarning)
            @foreach($topVendorByEarning as $key=> $vendor)
                @if(isset($vendor->seller->shop))
                    <div class="cursor-pointer get-view-by-onclick"
                         data-link="{{ route('admin.vendors.view', $vendor['seller_id'])}}">
                        <div class="grid-item basic-box-shadow">
                            <div class="d-flex align-items-center gap-10">
                                <img class="avatar rounded-circle avatar-sm" alt=""
                                     src="{{getValidImage(path: 'storage/app/public/shop/'.$vendor->seller->shop['image'] ?? '',type:'backend-basic')}}">

                                <h5 class="shop-name">{{ $vendor->seller->shop['name'] ?? 'Not exist' }}</h5>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="shop-sell">
                                    {{ setCurrencySymbol(amount: currencyConverter(amount: $vendor['total_earning'])) }}
                                </h5>
                                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/cart2.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="text-center">
                <p class="text-muted">{{translate('no_Top_Selling_Products')}}</p>
                <img class="w-75" src="{{dynamicAsset(path: 'public/assets/back-end/img/no-data.png')}}" alt="">
            </div>
        @endif
    </div>
</div>
