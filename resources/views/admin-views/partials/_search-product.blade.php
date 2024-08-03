@if (count($products) > 0)
    @foreach ($products as $key => $product)
        <div class="select-product-item media gap-3 border-bottom py-2 cursor-pointer action-select-product"
             data-id="{{ $product['id'] }}">
            <img class="avatar avatar-xl border" width="75"
                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'] , type: 'backend-basic') }}" alt="">
            <div class="media-body d-flex flex-column gap-1">
                <h6 class="product-id" hidden>{{$product['id']}}</h6>
                <h6 class="fz-13 mb-1 text-truncate custom-width product-name">{{$product['name']}}</h6>
                <div
                    class="fz-10">{{translate('category').' '.':'.' '}}{{isset($product->category) ? $product->category->name : translate('category_not_found') }}</div>
                <div
                    class="fz-10">{{translate('brand').' '.':'.' '}}{{isset($product->brand) ? $product->brand->name : translate('brands_not_found') }}</div>
                @if ($product->added_by == "seller")
                    <div
                        class="fz-10">{{translate('shop').' '.':'.' '}}{{isset($product->seller) ? $product->seller->shop->name : translate('shop_not_found') }}</div>
                @else
                    <div class="fz-10">{{translate('shop').' '.':'.' '}}{{$web_config['name']->value}}</div>
                @endif
            </div>
        </div>
    @endforeach
@else
    <div>
        <h5 class="m-0 text-muted">{{ translate('No_Product_Found') }}</h5>
    </div>
@endif
