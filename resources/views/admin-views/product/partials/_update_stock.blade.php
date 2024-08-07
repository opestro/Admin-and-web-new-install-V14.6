<div class="card-header">
    <h4>{{ translate('product_price_&_stock') }}</h4>
    <input name="product_id" value="{{$product['id']}}" class="d-none">
</div>
<div class="card-body">
    <div class="form-group">
        <div class="row">
            <div class="col-12 pt-4 sku_combination" id="sku_combination">
                @include('admin-views.product.partials._edit_sku_combinations', ['combinations'=>json_decode($product['variation'],true)])
            </div>
            <div class="col-md-12" id="quantity">
                <label class="control-label">
                    {{ translate('total_Quantity') }}
                </label>
                <input type="number" min="0" value={{ $product->current_stock }} step="1"
                       placeholder="{{ translate('quantity') }}"
                       name="current_stock" class="form-control" required>
            </div>
        </div>
    </div>
    <br>
</div>
