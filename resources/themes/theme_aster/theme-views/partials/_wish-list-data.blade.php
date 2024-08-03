@php use App\Utils\Helpers;use App\Utils\ProductManager; @endphp
<div class="table-responsive d-none d-md-block">
    <table class="table align-middle table-striped">
        <tbody>
        @if($wishlists->count()>0)
            @foreach($wishlists as $key=>$wishlist)
                @php($product = $wishlist->productFullInfo)
                @if( $wishlist->productFullInfo)
                    <td>
                        <div class="media gap-3 align-items-center mn-w200">
                            <div class="avatar border rounded size-3-437rem">
                                <img class="img-fit dark-support rounded aspect-1" alt=""
                                    src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                            </div>
                            <div class="media-body">
                                <a href="{{route('product',$product['slug'])}}">
                                    <h6 class="text-truncate text-capitalize width--20ch">{{$product['name']}}</h6>
                                </a>
                            </div>
                            @if($brand_setting)
                                <div class="media-body">
                                    <h6 class="text-truncate width--10">{{$product->brand?$product->brand['name']:''}} </h6>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4 ">
                            <div class="text-primary d-flex gap-2 align-items-center">
                                {!! getPriceRangeWithDiscount(product: $product) !!}
                            </div>
                        </div>
                    </td>
                    <td>
                        @php($compare_list = count($product->compareList)>0 ? 1 : 0)
                        <div class="d-flex justify-content-center gap-2 align-items-center">
                            <a href="javascript:"
                               class="btn btn-outline-success rounded-circle btn-action add-to-compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
                               data-product-id ="{{$product['id']}}" data-action="{{route('product-compare.index')}}"
                               id="compare_list-{{$product['id']}}">
                                <i class="bi bi-repeat"></i>
                            </a>
                            <button type="button" data-confirm-text="{{ translate('ok') }}"
                                    data-wishlist="{{ translate('wishlist') }}"
                                    data-product-id = "{{$product['id']}}"
                                    data-action="{{ route('delete-wishlist') }}"
                                    class="btn btn-outline-danger rounded-circle btn-action remove-wishlist">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>

@if($wishlists->count()==0)
    <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-3 w-100">
        <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-wishlist.svg') }}" alt="">
        <h5 class="text-center text-muted">
            {{ translate('You_have_not_added_product_to_wishlist_yet') }}!
        </h5>
    </div>
@endif

<div class="d-flex flex-column gap-2 d-md-none">
    @if($wishlists->count()>0)
        @foreach($wishlists as $key=>$wishlist)
            @php($product = $wishlist->productFullInfo)
            @if( $wishlist->productFullInfo)
                <div class="media gap-3 bg-light p-3 rounded">
                    <div class="avatar border rounded size-3-437rem">
                        <img
                            src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                            class="img-fit dark-support rounded" alt="">
                    </div>
                    <div class="media-body d-flex flex-column gap-1">
                        <a href="{{route('product',$product['slug'])}}">
                            <h6 class="text-truncate text-capitalize width--20ch">{{$product['name']}}</h6>
                        </a>
                        <div>
                            {{ translate('price') }} :
                            <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4 ">
                                <div class="text-primary d-flex gap-2 align-items-center">
                                    {!! getPriceRangeWithDiscount(product: $product) !!}
                                </div>
                            </div>
                        </div>

                        @php($compare_list = count($product->compareList)>0 ? 1 : 0)
                        <div class="d-flex gap-2 align-items-center mt-1">
                            <a href="javascript:"
                               class="btn btn-outline-success rounded-circle btn-action add-to-compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
                               data-product-id ="{{$product['id']}}" data-action="{{route('product-compare.index')}}">
                                <i class="bi bi-repeat"></i>
                            </a>
                            <button type="button"
                                    data-confirm-text="{{ translate('ok') }}"
                                    data-wishlist="{{ translate('wishlist') }}"
                                    data-product-id = "{{$product['id']}}"
                                    data-action="{{ route('delete-wishlist') }}"
                                    class="btn btn-outline-danger rounded-circle btn-action remove-wishlist">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>

<div class="border-0">
    {{ $wishlists->links() }}
</div>
