@if (count($products) > 0)
    @foreach ($products as $key => $product)
        <div class="select-product-item media gap-3 border-bottom py-3 cursor-pointer action-select-product align-items-center"
             data-id="{{ $product['id'] }}">
            <img class="avatar avatar-xl border" width="75"
                 src="{{ getStorageImages(path: $product->thumbnail_full_url , type: 'backend-basic') }}" alt="">
            <div class="media-body d-flex flex-column gap-1">
                <h6 class="product-id" hidden>{{$product['id']}}</h6>
                <h6 class="fz-13 mb-1 product-name">{{$product['name']}}</h6>
                <div  class="fz-10">
                    <span class="mr-2">{{translate('price').' '.':'.' '.setCurrencySymbol(usdToDefaultCurrency(amount: $product['unit_price']))}}</span>
                    <span class="mr-2">{{translate('category').' '.':'.' '}}{{isset($product->category) ? $product->category->name : translate('category_not_found') }}</span>
                    <span class="mr-2">{{translate('brand').' '.':'.' '}}{{isset($product->brand) ? $product->brand->name : translate('brands_not_found') }}</span>
                    @if ($product->added_by == "seller")
                        <span>{{translate('shop').' '.':'.' '}} <span class="text-primary">{{isset($product->seller) ? $product->seller->shop->name : translate('shop_not_found') }}</span></span>
                    @else
                        <span>{{translate('shop').' '.':'.' '}} <span class="text-primary">{{$web_config['name']->value}}</span></span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@else
    <div>
        <h5 class="m-0 text-muted">{{ translate('No_Product_Found') }}</h5>
    </div>
@endif
