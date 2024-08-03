@if ($totalHoldOrders > 0)
    <div class="table-responsive datatable-custom custom-scrollbar-pos min-h-300">
        <table class="table table-hover table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
            <thead class="thead-light thead-50 text-capitalize">
            <tr>
                <th>{{translate('SL')}}</th>
                <th>{{translate('date')}}</th>
                <th>{{translate('customer_info')}}</th>
                <th>{{translate('quantity')}}</th>
                <th>{{translate('total_amount')}}</th>
                <th class="text-center">{{translate('action')}}</th>
            </tr>
            </thead>

            <tbody>
            @if (session()->has('cart_name') && count(session()->get('cart_name')) > 0 )
                @php($totalHoldOrdersCount=1)
                @foreach ($cartItems as $key => $singleCart)
                    @if($singleCart['customerOnHold'])
                        <tr>
                            <td>{{ $totalHoldOrdersCount }}</td>
                                <?php $totalHoldOrdersCount++; ?>
                            <td>
                                @if (isset(session()->get($key)['add_to_cart_time']))
                                    <div>{{ session()->get($key)['add_to_cart_time']->format('d/m/Y') ?? 'N/a' }}</div>
                                    <div>{{ session()->get($key)['add_to_cart_time']->format('h:m A') ?? '' }}</div>
                                @else
                                    <div>{{ translate('now') }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $singleCart['customerName'] }}</div>
                                <a href="tel:{{ $singleCart['customerPhone'] ?? '' }}"
                                   class="text-dark">{{ $singleCart['customerPhone'] ?? '' }}</a>
                            </td>
                            <td>
                                <div class="table-items">
                                    <div class="cursor-pointer">
                                        {{ $singleCart['countItem'] }} {{ translate('items') }}
                                    </div>
                                    @if (session()->has($key) && count(session()->get($key)) > 0)
                                        <div class="bg-white p-0 box-shadow table-items-popup">
                                            @foreach($singleCart['cartItemValue'] as  $item)
                                                @if(is_array($item))
                                                    <div class="p-3 border-bottom rounded d-flex justify-content-between gap-2">
                                                        <div class="media gap-2">
                                                            <img width="40" alt=""
                                                                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$item['image'], type: 'backend-product') }}">
                                                            <div class="media-body">
                                                                <h6 class="text-truncate"> {{ Str::limit($item['name'], 12 )}}</h6>
                                                                @if($item['variant'])
                                                                    <div class="text-muted">{{ translate('variation') }}
                                                                        : {{ $item['variant'] }}</div>
                                                                @endif
                                                                <div class="text-muted">{{ translate('qty') }}
                                                                    : {{ $item['quantity'] }}</div>
                                                            </div>
                                                        </div>
                                                        <h5>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item['productSubtotal']), currencyCode: getCurrencyCode())}}</h5>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($singleCart['discountOnProduct']>0)
                                    <del>{{setCurrencySymbol(amount:usdToDefaultCurrency(amount: round($singleCart['subtotal']+$singleCart['discountOnProduct']+$singleCart['totalTax'], 2)), currencyCode: getCurrencyCode())}}</del>
                                @endif
                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: round($singleCart['total']+$singleCart['totalTax'], 2)), currencyCode: getCurrencyCode())}}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-soft-warning action-cart-change" data-cart="{{ $key }}">
                                        {{ translate('resume') }}
                                    </button>
                                    <button type="button" class="btn btn-soft-danger action-cancel-customer-order"
                                            data-cart-id="{{ $key }}">
                                        {{ translate('cancel_order') }}
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
@else
    <div class="d-flex align-items-center justify-content-center h-100">
        <div>
            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product.svg') }}" alt="">
            <h4 class="text-muted text-center mt-4">{{ translate('No_Order_Found') }}</h4>
        </div>
    </div>
@endif
