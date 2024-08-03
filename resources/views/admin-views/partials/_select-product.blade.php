@if(isset($selectedProducts))
    @foreach($selectedProducts as $key=>$product)
    <div class="select-product-item media gap-3 p-2 align-items-center border rounded position-relative">
        <input type="text" class="product_id" name="product_id[]" value="{{$product['id']}}" required hidden>
        <img class="avatar avatar-xl border" width="75" src="{{ getStorageImages(path:$product->thumbnail_full_url , type: 'backend-basic') }}" alt="">
        <div class="media-body d-flex flex-column gap-1">
            <div>
                <h6 class="fz-13 mb-1 product-name">{{$product['name']}}</h6>
            </div>
            <div class="fz-10">{{translate('price').' '.':'.' '}} <span>{{setCurrencySymbol(usdToDefaultCurrency(amount: $product['unit_price']))}}</span></div>
            @if ($product->added_by == "seller")
                <div class="fz-10">{{translate('shop').' '.':'.' '}} <span class="text-primary">{{isset($product->seller) ? $product->seller->shop->name : translate('shop_not_found') }}</span></div>
            @else
                <div class="fz-10">{{translate('shop').' '.':'.' '}} <span class="text-primary">{{$web_config['name']->value}}</span></div>
            @endif
        </div>
        <button class="remove-selected-product" type="button" data-product-id="{{$product['id']}}">
            <i class="tio-clear-circle"></i>
        </button>
    </div>
    @endforeach
@endif
