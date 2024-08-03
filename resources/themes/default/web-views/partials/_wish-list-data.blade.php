@php($decimal_point_settings = getWebConfig(name: 'decimal_point_settings'))

@if($wishlists->count()>0)
    <div class="d-flex flex-column gap-10px">
        @foreach($wishlists as $key=>$wishlist)
            @php($product = $wishlist->productFullInfo)
            @if( $wishlist->productFullInfo)
                <div class="wishlist-item" id="row_id{{$product->id}}">
                    <div class="wishlist-img position-relative">
                        <a href="{{route('product',$product->slug)}}" class="d-block h-100">
                            <img class="__img-full"
                                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                                 alt="{{ translate('wishlist') }}">
                        </a>

                        @if($product->discount > 0)
                            <span class="for-discount-value px-1 font-bold fs-13 direction-ltr">
                                @if ($product->discount_type == 'percent')
                                    -{{round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                @elseif($product->discount_type =='flat')
                                    -{{ webCurrencyConverter(amount: $product->discount) }}
                                @endif
                            </span>
                        @endif

                    </div>
                    <div class="wishlist-cont align-items-end align-items-sm-center">
                        <div class="wishlist-text">
                            <div class="font-name">
                                <a class="fs-12 font-semibold line-height-16" href="{{route('product',$product['slug'])}}">{{$product['name']}}</a>
                            </div>
                            @if($brand_setting)
                                <span class="sellerName fs-12"> {{translate('brand')}} : <span
                                        class="text-base">{{$product->brand?$product->brand['name']:''}}</span> </span>
                            @endif

                            <div class=" mt-sm-1">
                                <span class="font-weight-bold amount text-dark price-range d-flex align-items-center gap-2">{!! getPriceRangeWithDiscount(product: $product) !!}</span>
                            </div>
                        </div>
                        <a href="javascript:" class="remove--icon function-remove-wishList" data-id="{{ $product['id'] }}"
                           data-modal="remove-wishlist-modal">
                            <i class="fa fa-heart web-text-primary"></i>
                        </a>

                    </div>
                </div>
            @else
                <span class="badge badge-danger">{{ translate('item_removed') }}</span>
            @endif
        @endforeach
    </div>
@else
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="login-card w-100 border-0 shadow-none">
            <div class="text-center py-3 text-capitalize">
                <img src="{{ theme_asset(path: 'public/assets/front-end/img/icons/wishlist.png') }}" alt="" class="mb-4" width="70">
                <h5 class="fs-14">{{ translate('no_product_found_in_wishlist') }}!</h5>
            </div>
        </div>
    </div>
@endif

<div class="card-footer border-0">{{ $wishlists->links() }}</div>
