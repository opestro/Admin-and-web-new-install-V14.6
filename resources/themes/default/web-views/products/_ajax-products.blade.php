@if(count($products) > 0)

    @php($decimal_point_settings = getWebConfig(name: 'decimal_point_settings'))
    @foreach($products as $product)
        @if(!empty($product['product_id']))
            @php($product=$product->product)
        @endif
        <div class=" {{Request::is('products*')?'col-lg-3 col-md-4 col-sm-4 col-6':'col-lg-2 col-md-3 col-sm-4 col-6'}} {{Request::is('shopView*')?'col-lg-3 col-md-4 col-sm-4 col-6':''}} p-2">
            @if(!empty($product))
                @include('web-views.partials._filter-single-product',['product'=>$product, 'decimal_point_settings'=>$decimal_point_settings])
            @endif
        </div>
    @endforeach

    <div class="col-12">
        <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation"
             id="paginator-ajax">
            {!! $products->links() !!}
        </nav>
    </div>
@else
    <div class="d-flex justify-content-center align-items-center w-100 py-5">
        <div>
            <img src="{{ theme_asset(path: 'public/assets/front-end/img/media/product.svg') }}" class="img-fluid" alt="">
            <h6 class="text-muted">{{ translate('no_product_found') }}</h6>
        </div>
    </div>
@endif
