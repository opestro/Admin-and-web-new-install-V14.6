@if (count($products) > 0)
    <div class="auto-col gap-3 mobile_two_items minWidth-12rem {{(session()->get('product_view_style') == 'list-view'?'product-list-view':'')}}" id="filtered-products" style="{{(count($products) > 4?'--maxWidth:1fr':'--maxWidth:14rem')}}">
        @foreach($products as $product)
            @include('theme-views.partials._product-small-card', ['product'=>$product])
        @endforeach
    </div>
@else
    <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 my-5 w-100">
        <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-product.svg') }}" alt="">
        <h5 class="text-center text-muted">
            {{ translate('There_is_no_product') }}!
        </h5>
    </div>
@endif
@if (count($products) > 0)
<div class="my-4" id="paginator-ajax">
    {!! $products->links() !!}
</div>
@endif
