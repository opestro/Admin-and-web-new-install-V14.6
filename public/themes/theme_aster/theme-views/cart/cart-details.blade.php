@php
    use App\Models\Cart;
    use App\Models\CartShipping;
    use App\Models\ShippingType;
    use App\Utils\Helpers;
    use App\Utils\OrderManager;
    use App\Utils\ProductManager;
    use function App\Utils\get_shop_name;
    $shippingMethod = getWebConfig(name: 'shipping_method');
    $cart = Cart::where(['customer_id' => (auth('customer')->check() ? auth('customer')->id() : session('guest_id'))])->with(['seller','allProducts.category'])->get()->groupBy('cart_group_id');
@endphp
<div class="container">
    <h4 class="text-center mb-3 text-capitalize">{{ translate('cart_list') }}</h4>
    <form action="javascript:">
        <div class="row gy-3">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-30">
                            <ul class="cart-step-list">
                                <li class="current cursor-pointer get-view-by-onclick"
                                    data-link="{{route('shop-cart')}}">
                                    <span><i class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                <li class="cursor-pointer text-capitalize" data-link="{{ route('checkout-details') }}">
                                    <span><i class="bi bi-check2"></i></span> {{ translate('shopping_details') }}</li>
                                <li><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                            </ul>
                        </div>
                        @if(count($cart)==0)
                            @php $physical_product = false; @endphp
                        @endif


                        @foreach($cart as $group_key=>$group)
                            @php
                                $physical_product = false;
                                foreach ($group as $row) {
                                    if ($row->product_type == 'physical' && $row->is_checked) {
                                        $physical_product = true;
                                    }
                                }
                            @endphp
                            <div class="cart_information">
                                @foreach($group as $cart_key=>$cartItem)
                                    @if ($shippingMethod=='inhouse_shipping')
                                            <?php
                                                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                                $shipping_type = isset($admin_shipping) === true ? $admin_shipping->shipping_type : 'order_wise';
                                            ?>
                                    @else
                                            <?php
                                            if ($cartItem->seller_is == 'admin') {
                                                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                                $shipping_type = isset($admin_shipping) === true ? $admin_shipping->shipping_type : 'order_wise';
                                            } else {
                                                $seller_shipping = ShippingType::where('seller_id', $cartItem->seller_id)->first();
                                                $shipping_type = isset($seller_shipping) === true ? $seller_shipping->shipping_type : 'order_wise';
                                            }
                                            ?>
                                    @endif
                                    @if($cart_key==0)
                                        @php
                                            $verify_status = OrderManager::minimum_order_amount_verify($request, $group_key);
                                        @endphp
                                        <div class="bg-primary-light py-2 px-2 px-sm-3 mb-3 mb-sm-4">
                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                                <div class="d-flex align-items-center">
                                                    @if($cartItem->seller_is=='admin')
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <input type="checkbox" class="shop-head-check shop-head-check-desktop">
                                                            <a href="{{route('shopView',['id'=>0])}}">
                                                                <h5>
                                                                    {{getWebConfig(name: 'company_name')}}
                                                                </h5>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="d-flex gap-3 align-items-center">
                                                            <input type="checkbox" class="shop-head-check shop-head-check-desktop">
                                                            <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}">
                                                                @if(get_shop_name($cartItem['seller_id']))
                                                                    <h5>{{ get_shop_name($cartItem['seller_id']) }}</h5>
                                                                @else
                                                                    <h5 class="text-danger">{{ translate('vendor_not_available') }}</h5>
                                                                @endif
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                        <span
                                                            class="ps-2 text-danger pulse-button minimum-order-amount-message"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="right"
                                                            data-bs-custom-class="custom-tooltip"
                                                            data-bs-title="{{ translate('minimum_Order_Amount') }} {{ Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{getWebConfig(name: 'company_name')}} @else {{ get_shop_name($cartItem['seller_id']) }} @endif">
                                                        <i class="bi bi-info-circle"></i>
                                                    </span>
                                                    @endif
                                                </div>
                                                @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                    @php
                                                        $choosen_shipping=CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first()
                                                    @endphp

                                                    @if(isset($choosen_shipping)===false)
                                                        @php $choosen_shipping['shipping_method_id']=0 @endphp
                                                    @endif
                                                    @php
                                                        $shippings=Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is'])
                                                    @endphp
                                                    @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                        @if(count($shippings) > 0)
                                                            <div class="border bg-white rounded custom-ps-3">
                                                                <div class="shiiping-method-btn d-flex gap-2 p-2 flex-wrap">
                                                                    <div
                                                                        class="flex-middle flex-nowrap fw-semibold text-dark gap-2">
                                                                        <i class="bi bi-truck"></i>
                                                                        {{ translate('Shipping_Method') }}:
                                                                    </div>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <?php
                                                                                $shippings_title = translate('choose_shipping_method');
                                                                                foreach ($shippings as $shipping) {
                                                                                    if ($choosen_shipping['shipping_method_id'] == $shipping['id']) {
                                                                                        $shippings_title = ucfirst($shipping['title']) . ' ( ' . $shipping['duration'] . ' ) ' . Helpers::currency_converter($shipping['cost']);
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            {{ $shippings_title }}
                                                                        </button>
                                                                        <ul class="dropdown-menu dropdown-left-auto bs-dropdown-min-width--8rem">
                                                                            @foreach($shippings as $shipping)
                                                                                <li class="cursor-pointer set-shipping-id" data-id="{{$shipping['id']}}" data-cart-group="{{$cartItem['cart_group_id']}}">
                                                                                    {{$shipping['title'].' ( '.$shipping['duration'].' ) '.Helpers::currency_converter($shipping['cost'])}}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="badge badge-soft-danger cursor-pointer border-danger border fs-12" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="{{ translate('No_shipping_options_available_at_this_shop') }}, {{ translate('please_remove_all_items_from_this_shop') }}">
                                                                {{ translate('shipping_Not_Available') }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="table-responsive d-none d-sm-block">
                                    @php
                                        $physical_product = false;
                                        foreach ($group as $row) {
                                            if ($row->product_type == 'physical' && $row->is_checked) {
                                                $physical_product = true;
                                            }
                                        }
                                    @endphp
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th class="border-0">{{ translate('product_details') }}</th>
                                            <th class="border-0 text-center">{{ translate('qty') }}</th>
                                            <th class="border-0 text-end">{{ translate('unit_price') }}</th>
                                            <th class="border-0 text-end">{{ translate('discount') }}</th>
                                            <th class="border-0 text-end">{{ translate('total') }}</th>
                                            @if ( $shipping_type != 'order_wise')
                                                <th class="border-0 text-end">{{ translate('shipping_cost') }} </th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($group as $cart_key=>$cartItem)
                                            @if($cartItem->allProducts)
                                                @php($product = $cartItem->allProducts)
                                            @else
                                                @php($product = $cartItem)
                                            @endif

                                            <?php
                                                $getProductCurrentStock = $product->current_stock;
                                                if(!empty($product->variation)) {
                                                    foreach(json_decode($product->variation, true) as $productVariantSingle) {
                                                        if($productVariantSingle['type'] == $cartItem->variant) {
                                                            $getProductCurrentStock = $productVariantSingle['qty'];
                                                        }
                                                    }
                                                }
                                            ?>

                                            <?php
                                                $checkProductStatus = $cartItem->allProducts?->status ?? 0;
                                                if($cartItem->seller_is == 'admin') {
                                                    $inhouseTemporaryClose = getWebConfig(name: 'temporary_close') ? getWebConfig(name: 'temporary_close')['status'] : 0;
                                                    $inhouseVacation = getWebConfig(name: 'vacation_add');
                                                    $vacationStartDate = $inhouseVacation['vacation_start_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_start_date'])) : null;
                                                    $vacationEndDate = $inhouseVacation['vacation_end_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_end_date'])) : null;
                                                    $vacationStatus = $inhouseVacation['status'] ?? 0;
                                                    if ($inhouseTemporaryClose || ($vacationStatus && (date('Y-m-d') >= $vacationStartDate) && (date('Y-m-d') <= $vacationEndDate))) {
                                                        $checkProductStatus = 0;
                                                    }
                                                }else{
                                                    if (!isset($cartItem->allProducts->seller) || (isset($cartItem->allProducts->seller) && $cartItem->allProducts->seller->status != 'approved')) {
                                                        $checkProductStatus = 0;
                                                    }
                                                    if (!isset($cartItem->allProducts->seller->shop) || $cartItem->allProducts->seller->shop->temporary_close) {
                                                        $checkProductStatus = 0;
                                                    }
                                                    if(isset($cartItem->allProducts->seller->shop) && ($cartItem->allProducts->seller->shop->vacation_status && (date('Y-m-d') >= $cartItem->allProducts->seller->shop->vacation_start_date) && (date('Y-m-d') <= $cartItem->allProducts->seller->shop->vacation_end_date))) {
                                                        $checkProductStatus = 0;
                                                    }
                                                }
                                            ?>

                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <input type="checkbox" class="shop-item-check shop-item-check-desktop" value="{{ $cartItem['id'] }}" {{ $cartItem['is_checked'] ? 'checked' : '' }}>
                                                        <div class="media align-items-center gap-3">
                                                            <div
                                                                class="avatar avatar-xxl rounded border position-relative overflow-hidden">
                                                                <img alt="{{ translate('product') }}"
                                                                    src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$cartItem['thumbnail'], type: 'product') }}"
                                                                    class="dark-support img-fit rounded img-fluid overflow-hidden {{ $cartItem->allProducts ? ($product->status == 0 ?'custom-cart-opacity-50':'') : 'custom-cart-opacity-50' }}">

                                                                @if ($checkProductStatus == 0)
                                                                    <span class="temporary-closed position-absolute text-center p-2">
                                                                        <span class="text-capitalize">{{ translate('not_available') }}</span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="media-body d-flex gap-1 flex-column {{ $checkProductStatus == 0 ? 'custom-cart-opacity-50' : '' }}">
                                                                <h6 class="text-truncate text-capitalize width--20ch" >
                                                                    <a href="{{ $checkProductStatus ? route('product', $cartItem['slug']):'javascript:' }}">{{$cartItem['name']}}</a>
                                                                </h6>
                                                                @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                                                    <div class="fs-12">{{$key1}} : {{$variation}}</div>
                                                                @endforeach
                                                                <div class="fs-12 text-capitalize">{{ translate('unit_price') }}
                                                                    : {{ Helpers::currency_converter($cartItem['price']) }}</div>

                                                                @if($product->product_type == 'physical' && $getProductCurrentStock < $cartItem['quantity'])
                                                                    <div class="d-flex text-danger font-bold">
                                                                        <span>{{ translate('Out_Of_Stock') }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if ($checkProductStatus == 1)
                                                        <div class="quantity quantity--style-two d-inline-flex">
                                                            <span
                                                                class="quantity__minus cart-qty-btn update-cart-quantity-list-cart-data"
                                                                data-min-order="{{ $product->minimum_order_qty }}"
                                                                data-prevent=true
                                                                data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                                data-action="{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}">

                                                                @if($getProductCurrentStock < $cartItem['quantity'] || ($cartItem['quantity'] == ($cartItem?->product?->minimum_order_qty ?? 1)))
                                                                    <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                                @else
                                                                    <i class="bi bi-dash"></i>
                                                                @endif

                                                            </span>
                                                            <input type="text"
                                                                class="quantity__qty update-cart-quantity-list-cart-data-input"
                                                                value="{{$cartItem['quantity']}}" name="quantity"
                                                                id="cartQuantityWeb{{$cartItem['id']}}"
                                                                data-min-order="{{ $product->minimum_order_qty }}"
                                                                data-cart="{{ $cartItem['id'] }}" data-value="0"
                                                                data-action=""
                                                                data-current-stock="{{ $getProductCurrentStock }}"
                                                                data-min="{{ $cartItem?->product?->minimum_order_qty ?? 1 }}">
                                                            <span
                                                                class="quantity__plus cart-qty-btn update-cart-quantity-list-cart-data"
                                                                data-prevent=true
                                                                data-min-order="{{ $product->minimum_order_qty }}"
                                                                data-cart="{{ $cartItem['id'] }}" data-value="1"
                                                                data-action="">
                                                                <i class="bi bi-plus"></i>
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="quantity quantity--style-two d-inline-flex">
                                                            <span class="quantity__minus cartQuantity{{$cartItem['id']}} update-cart-quantity-list-cart-data"
                                                                data-min-order="{{ $product->minimum_order_qty }}"
                                                                data-prevent=true
                                                                data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                                data-action="delete"
                                                                data-min="{{$cartItem['quantity']}}">
                                                                <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                            </span>
                                                            <input type="hidden"
                                                                class="quantity__qty cartQuantity{{ $cartItem['id'] }}"
                                                                value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]"
                                                                id="cartQuantityWeb{{$cartItem['id']}}"
                                                                data-min="{{$cartItem['quantity']}}">
                                                        </div>
                                                    @endif

                                                </td>
                                                <td class="text-end">{{ Helpers::currency_converter($cartItem['price']*$cartItem['quantity']) }}</td>
                                                <td class="text-end">{{ Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</td>
                                                <td class="text-end">{{ Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</td>
                                                <td>
                                                    @if ( $shipping_type != 'order_wise')
                                                        {{ Helpers::currency_converter($cartItem['shipping_cost']) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                    @php($free_delivery_status = OrderManager::free_delivery_order_amount($group[0]->cart_group_id))

                                    @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                                        <div class="free-delivery-area px-3 mb-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <img
                                                    src="{{ dynamicAsset(path: 'public/assets/front-end/img/icons/free-shipping.png') }}"
                                                    alt="{{translate('image')}}" width="40">
                                                @if ($free_delivery_status['amount_need'] <= 0)
                                                    <span
                                                        class="text-muted fs-16 text-capitalize">{{ translate('you_get_free_delivery_bonus') }}</span>
                                                @else
                                                    <span
                                                        class="need-for-free-delivery font-bold">{{ Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                                                    <span
                                                        class="text-muted fs-16">{{ translate('add_more_for_free_delivery') }}</span>
                                                @endif
                                            </div>
                                            <div class="progress free-delivery-progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $free_delivery_status['percentage'] .'%'}}"
                                                    aria-valuenow="{{ $free_delivery_status['percentage'] }}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex flex-column d-sm-none">
                                    @foreach($group as $cart_key=>$cartItem)
                                        @if($cartItem->allProducts)
                                            @php($product = $cartItem->allProducts)
                                        @endif

                                        <?php
                                            $checkProductStatus = $cartItem->allProducts?->status ?? 0;
                                            if($cartItem->seller_is == 'admin') {
                                                $inhouseTemporaryClose = getWebConfig(name: 'temporary_close') ? getWebConfig(name: 'temporary_close')['status'] : 0;
                                                $inhouseVacation = getWebConfig(name: 'vacation_add');
                                                $vacationStartDate = $inhouseVacation['vacation_start_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_start_date'])) : null;
                                                $vacationEndDate = $inhouseVacation['vacation_end_date'] ? date('Y-m-d', strtotime($inhouseVacation['vacation_end_date'])) : null;
                                                $vacationStatus = $inhouseVacation['status'] ?? 0;
                                                if ($inhouseTemporaryClose || ($vacationStatus && (date('Y-m-d') >= $vacationStartDate) && (date('Y-m-d') <= $vacationEndDate))) {
                                                    $checkProductStatus = 0;
                                                }
                                            }else{
                                                if (!isset($cartItem->allProducts->seller) || (isset($cartItem->allProducts->seller) && $cartItem->allProducts->seller->status != 'approved')) {
                                                    $checkProductStatus = 0;
                                                }
                                                if (!isset($cartItem->allProducts->seller->shop) || $cartItem->allProducts->seller->shop->temporary_close) {
                                                    $checkProductStatus = 0;
                                                }
                                                if(isset($cartItem->allProducts->seller->shop) && ($cartItem->allProducts->seller->shop->vacation_status && (date('Y-m-d') >= $cartItem->allProducts->seller->shop->vacation_start_date) && (date('Y-m-d') <= $cartItem->allProducts->seller->shop->vacation_end_date))) {
                                                    $checkProductStatus = 0;
                                                }
                                            }
                                        ?>

                                        <div class="border-bottom d-flex align-items-start justify-content-between gap-2 py-2">
                                            <div class="d-flex gap-2 align-items-center">
                                                <input type="checkbox" class="shop-item-check shop-item-check-mobile" value="{{ $cartItem['id'] }}" {{ $cartItem['is_checked'] ? 'checked' : '' }}>
                                                <div class="media align-items-center gap-2">
                                                    <div
                                                        class="avatar avatar-lg rounded border position-relative overflow-hidden">
                                                        <img
                                                            src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$cartItem['thumbnail'], type: 'product') }}"
                                                            class="dark-support img-fit rounded img-fluid overflow-hidden {{ $checkProductStatus == 0 ? 'custom-cart-opacity-50' : '' }}"
                                                            alt="">
                                                        @if ($checkProductStatus == 0)
                                                            <span class="temporary-closed position-absolute text-center p-2">
                                                                <span>{{ translate('N/A') }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="media-body d-flex gap-1 flex-column {{ $checkProductStatus == 0 ? 'custom-cart-opacity-50' : '' }}">
                                                        <h6 class="text-truncate text-capitalize width--20ch">
                                                            <a href="{{ $checkProductStatus ? route('product', $cartItem['slug']) : 'javascript:' }}">
                                                                {{ $cartItem['name'] }}
                                                            </a>
                                                        </h6>
                                                        @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                                            <div class="fs-12">{{$key1}} : {{$variation}}</div>
                                                        @endforeach
                                                        <div class="fs-12 text-capitalize">{{ translate('unit_price') }}
                                                            : {{ Helpers::currency_converter($cartItem['price']*$cartItem['quantity']) }}</div>
                                                        <div class="fs-12">{{ translate('discount') }}
                                                            : {{ Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</div>
                                                        <div class="fs-12">{{ translate('total') }}
                                                            : {{ Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</div>
                                                        @if ( $shipping_type != 'order_wise')
                                                            <div class="fs-12">{{ translate('shipping_cost') }}
                                                                : {{ Helpers::currency_converter($cartItem['shipping_cost']) }}</div>
                                                        @endif

                                                        @if($product->product_type == 'physical' && $getProductCurrentStock < $cartItem['quantity'])
                                                            <div class="d-flex text-danger font-bold">
                                                                <span>{{ translate('Out_Of_Stock') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="quantity quantity--style-two flex-column d-inline-flex">
                                                @if ($checkProductStatus == 1)
                                                    <span class="quantity__minus update-cart-quantity-list-cart-data"
                                                        data-min-order="{{ $product->minimum_order_qty }}"
                                                        data-prevent=true
                                                        data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                        data-action="{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}">

                                                        @if($getProductCurrentStock < $cartItem['quantity'] || ($cartItem['quantity'] == ($cartItem?->product?->minimum_order_qty ?? 1)))
                                                            <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                        @else
                                                            <i class="bi bi-dash"></i>
                                                        @endif
                                                    </span>
                                                    <input type="text"
                                                        class="quantity__qty update-cart-quantity-list-mobile-cart-data-input"
                                                        value="{{$cartItem['quantity']}}" name="quantity"
                                                        id="cartQuantityMobile{{$cartItem['id']}}"
                                                        data-min-order="{{ $product->minimum_order_qty }}"
                                                        data-cart="{{ $cartItem['id'] }}" data-value="0"
                                                        data-current-stock="{{ $getProductCurrentStock }}"
                                                        data-action="">
                                                    <span class="quantity__plus update-cart-quantity-list-mobile-cart-data"
                                                        data-prevent=true
                                                        data-min-order="{{ $product->minimum_order_qty }}"
                                                        data-cart="{{ $cartItem['id'] }}" data-value="1"
                                                        data-action="">
                                                        <i class="bi bi-plus"></i>
                                                    </span>
                                                @else
                                                    <span class="quantity__minus update-cart-quantity-list-mobile-cart-data"
                                                        data-prevent=true
                                                        data-min-order="{{ $product->minimum_order_qty }}"
                                                        data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                        data-action="{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}">
                                                            <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                    </span>
                                                    <input type="hidden"
                                                        class="quantity__qty cartQuantity{{ $cartItem['id'] }}"
                                                        data-min-order="{{ $product->minimum_order_qty ?? 1 }}"
                                                        data-cart="{{ $cartItem['id'] }}" data-value="0" data-action=""
                                                        value="{{$cartItem['quantity']}}" name="quantity"
                                                        id="cartQuantityMobile{{$cartItem['id']}}"
                                                        data-min="{{$cartItem['quantity']}}">
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    @php($free_delivery_status = OrderManager::free_delivery_order_amount($group[0]->cart_group_id))

                                    @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                                        <div class="free-delivery-area px-3 mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <img
                                                    src="{{ dynamicAsset(path: 'public/assets/front-end/img/icons/free-shipping.png') }}"
                                                    alt="" width="40">
                                                @if ($free_delivery_status['amount_need'] <= 0)
                                                    <span
                                                        class="text-muted fs-16">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                                                @else
                                                    <span
                                                        class="need-for-free-delivery font-bold">{{ Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                                                    <span
                                                        class="text-muted fs-16">{{ translate('add_more_for_free_delivery') }}</span>
                                                @endif
                                            </div>
                                            <div class="progress free-delivery-progress">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $free_delivery_status['percentage'] .'%'}}"
                                                    aria-valuenow="{{ $free_delivery_status['percentage'] }}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($shippingMethod=='inhouse_shipping')
                                <?php
                                $physical_product = false;
                                foreach ($cart as $group_key => $group) {
                                    foreach ($group as $row) {
                                        if ($row->product_type == 'physical' && $row->is_checked) {
                                            $physical_product = true;
                                        }
                                    }
                                }
                                ?>

                                <?php
                                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                $shipping_type = isset($admin_shipping) === true ? $admin_shipping->shipping_type : 'order_wise';
                                ?>
                            @if ($shipping_type == 'order_wise' && $physical_product)
                                @php($shippings=Helpers::get_shipping_methods(1,'admin'))
                                @php($choosen_shipping=CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                                @if(isset($choosen_shipping)===false)
                                    @php($choosen_shipping['shipping_method_id']=0)
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-control text-dark set-shipping-onchange">
                                            <option>{{ translate('choose_shipping_method')}}</option>
                                            @foreach($shippings as $shipping)
                                                <option
                                                    value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                    {{$shipping['title'].' ( '.$shipping['duration'].' ) '.Helpers::currency_converter($shipping['cost'])}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if( $cart->count() == 0)
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
                                    <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-cart.svg') }}" alt="">
                                    <h5 class="text-center text-muted">
                                        {{ translate('You_have_not_added_anything_to_your_cart_yet') }}!
                                    </h5>
                                </div>
                            </div>
                        @endif

                        <form method="get">
                            <div class="form-group mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="order-note"
                                               class="form-label input-label">{{translate('order_note')}} <span
                                                class="input-label-secondary">({{translate('optional')}})</span></label>
                                        <textarea class="form-control w-100" rows="5" id="order-note"
                                                  name="order_note">{{ session('order_note')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('theme-views.partials._order-summery')
        </div>
    </form>
</div>
@push('script')
    <script src="{{ theme_asset('assets/js/cart.js') }}"></script>
@endpush
