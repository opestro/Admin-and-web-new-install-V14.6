@php
 use App\Models\OrderDetail;
 use App\Utils\Helpers;
 use App\Utils\ProductManager;
 use function App\Utils\order_status_history;
@endphp
@extends('theme-views.layouts.app')

@section('title', translate('Track_Order_Result ').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="card h-100">
                <div class="card-body py-4 px-sm-4">
                    <div class="mt-4">
                        <h4 class="text-center text-uppercase mb-5">{{ translate('your_order') }}
                            #{{ $orderDetails['id'] }} {{ translate('is') }}
                            @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                {{translate($orderDetails['order_status'] =='failed' ? 'Failed To Deliver' : $orderDetails['order_status'])}}
                            @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                            @else
                                {{translate($orderDetails['order_status'])}}
                            @endif
                        </h4>
                        <div class="row justify-content-center">
                            <div class="col-xl-10">
                                <div id="timeline">
                                    <div
                                        @if($orderDetails['order_status']=='processing')
                                            class="bar progress two"
                                        @elseif($orderDetails['order_status']=='out_for_delivery')
                                            class="bar progress three"
                                        @elseif($orderDetails['order_status']=='delivered')
                                            class="bar progress four"
                                        @elseif(in_array($orderDetails['order_status'], ['returned', 'canceled', 'failed']))
                                            class="bar progress four"
                                        @else
                                            class="bar progress one"
                                        @endif
                                    ></div>
                                    <div class="state" style="{{ in_array($orderDetails['order_status'], ['returned', 'canceled', 'failed']) ? '--items: 2;' : '' }}">
                                        <ul>
                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track1.png')}}"
                                                         class="dark-support" alt="">
                                                </div>
                                                <div class="badge active">
                                                    <span>{{translate('1')}}</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div>
                                                    <div class="state-text">{{translate('order_placed')}}</div>
                                                    <div
                                                        class="mt-2 fs-12">{{date('d M, Y h:i A',strtotime($orderDetails->created_at))}}</div>
                                                </div>
                                            </li>

                                            @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                                                <li>
                                                    <div class="state-img">
                                                        <img width="30" src="{{theme_asset('assets/img/icons/track2.png')}}"
                                                             class="dark-support" alt="">
                                                    </div>
                                                    <div
                                                        class="{{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                        <span>{{translate('2')}}</span>
                                                        <i class="bi bi-check"></i>
                                                    </div>
                                                    <div>
                                                        <div class="state-text">{{translate('packaging_order')}}</div>
                                                        @if(($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                            <div class="mt-2 fs-12">
                                                                @if(order_status_history($orderDetails['id'],'processing'))
                                                                    {{date('d M, Y h:i A',strtotime(order_status_history($orderDetails['id'],'processing')))}}
                                                                @endif
                                                            </div>
                                                        @endif

                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="state-img">
                                                        <img width="30" src="{{theme_asset('assets/img/icons/track4.png')}}"
                                                             class="dark-support" alt="">
                                                    </div>
                                                    <div
                                                        class="{{($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                        <span>{{translate('3')}}</span>
                                                        <i class="bi bi-check"></i>
                                                    </div>
                                                    <div class="state-text">{{translate('Order_is_on_the_way')}}</div>
                                                    @if(($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                        <div class="mt-2 fs-12">
                                                            @if(order_status_history($orderDetails['id'],'out_for_delivery'))
                                                                {{date('d M, Y h:i A',strtotime(order_status_history($orderDetails['id'],'out_for_delivery')))}}
                                                            @endif
                                                        </div>
                                                    @endif
                                                </li>
                                                <li>
                                                    <div class="state-img">
                                                        <img width="30" src="{{theme_asset('assets/img/icons/track5.png')}}"
                                                             class="dark-support" alt="">
                                                    </div>
                                                    <div
                                                        class="{{($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                        <span>{{translate('4')}}</span>
                                                        <i class="bi bi-check"></i>
                                                    </div>
                                                    <div class="state-text">{{translate('Order_Delivered')}}</div>
                                                    @if($orderDetails['order_status']=='delivered')
                                                        <div class="mt-2 fs-12">
                                                            @if(order_status_history($orderDetails['id'], 'delivered'))
                                                                {{date('d M, Y h:i A',strtotime(order_status_history($orderDetails['id'], 'delivered')))}}
                                                            @endif
                                                        </div>
                                                    @endif
                                                </li>
                                            @elseif(in_array($orderDetails['order_status'], ['returned', 'canceled']))
                                                <li>
                                                    <div class="state-img">
                                                        <img width="30"
                                                             src="{{theme_asset('assets/img/icons/'.$orderDetails['order_status'].'.png')}}"
                                                             class="dark-support" alt="">
                                                    </div>
                                                    <div class="badge active">
                                                        <span>{{translate('2')}}</span>
                                                        <i class="bi bi-check"></i>
                                                    </div>
                                                    <div class="state-text">
                                                        {{ translate('order') }} {{ translate($orderDetails['order_status']) }}
                                                    </div>

                                                    @if(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))
                                                        <div class="mt-2 fs-12">
                                                            {{ date('h:i A, d M Y', strtotime(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))) }}
                                                        </div>
                                                    @endif
                                                </li>
                                            @else
                                                <li>
                                                    <div class="state-img">
                                                        <img width="30"
                                                             src="{{theme_asset('assets/img/icons/'.$orderDetails['order_status'].'.png')}}"
                                                             class="dark-support" alt="">
                                                    </div>
                                                    <div class="badge active">
                                                        <span>{{translate('2')}}</span>
                                                        <i class="bi bi-check"></i>
                                                    </div>
                                                    <div class="state-text">
                                                        {{ translate('order') }} {{ translate($orderDetails['order_status']) }}
                                                    </div>

                                                    @if(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))
                                                        <div class="mt-2 fs-12">
                                                            {{ date('h:i A, d M Y', strtotime(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))) }}
                                                        </div>
                                                    @endif
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                        <div class="mt-5 bg-light p-3 p-sm-4">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-4">{{ translate('order_details') }}</h5>
                                <button class="btn btn-primary mb-4" data-bs-toggle="modal"
                                        data-bs-target="#order_details">
                                    <span
                                        class="media-body hover-primary text-nowrap">{{translate('view_order_details')}}</span>
                                </button>
                            </div>
                            <div class="row gy-3 text-dark track-order-details-info">
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="column-2">
                                            <div>{{ translate('order_ID') }}</div>
                                            @if(auth('customer')->check())
                                                <div class="fw-bold cursor-pointer get-view-by-onclick"
                                                     data-link="{{ route('account-order-details', ['id'=>$orderDetails->id]) }}">{{ $orderDetails['id'] }}</div>
                                            @else
                                                <div class="fw-bold cursor-pointer" data-bs-toggle="modal"
                                                     data-bs-target="#loginModal">{{ $orderDetails['id'] }}</div>
                                            @endif
                                        </div>
                                        @if ($order_verification_status && $orderDetails->order_type == "default_type")
                                            <div class="column-2">
                                                <div>{{translate('verification_code')}}</div>
                                                <div
                                                    class="fw-bold cursor-pointer">{{ $orderDetails['verification_code'] }}</div>
                                            </div>
                                        @endif
                                        <div class="column-2">
                                            <div>{{ translate('order_Created_At') }}</div>
                                            <div
                                                class="fw-bold">{{date('D, d M, Y ',strtotime($orderDetails['created_at']))}}</div>
                                        </div>
                                        @if($orderDetails->delivery_man_id && $orderDetails['order_status'] !="delivered" && $orderDetails['expected_delivery_date'] )
                                            <div class="column-2">
                                                <div class="text-capitalize">{{ translate('estimated_delivery_date') }}</div>
                                                <div class="fw-bold">
                                                    {{date('D, d M, Y ',strtotime($orderDetails['expected_delivery_date']))}}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="column-2">
                                            <div>{{ translate('Order_Status') }}</div>
                                            @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status'] =='failed' ? 'failed_to_deliver' : $orderDetails['order_status'])}}
                                                </div>
                                            @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                                                </div>
                                            @else
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status'])}}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="column-2">
                                            <div class="text-capitalize">{{ translate('payment_status') }}</div>
                                            @if($orderDetails['payment_status']=="paid")
                                                <div class="fw-bold">{{ translate('paid') }}</div>
                                            @else
                                                <div class="fw-bold">{{ translate('unpaid') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @php($order = OrderDetail::where('order_id', $orderDetails->id)->get())
        <div class="modal fade" id="order_details" tabindex="-1" aria-labelledby="order_details" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mx-3 border-0">
                        <div>
                            <h6 class="modal-title fs-5" id="reviewModalLabel">{{translate('order')}}
                                #{{ $orderDetails['id']  }}</h6>

                            @if ($order_verification_status && $orderDetails->order_type == "default_type")
                                <h5 class="small">{{translate('verification_code')}}
                                    : {{ $orderDetails['verification_code'] }}</h5>
                            @endif
                        </div>
                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0 px-sm-4">
                        <div class="product-table-wrap">
                            <div class="table-responsive">
                                <table class="table text-capitalize text-start align-middle">
                                    <thead class="mb-3">
                                    <tr>
                                        <th class="min-w-300 text-nowrap">{{translate('product_details')}}</th>
                                        <th>{{translate('QTY')}}</th>
                                        <th class="text-end text-nowrap">{{translate('sub_total')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php( $totalTax = 0)
                                    @php($sub_total=0)
                                    @php($total_tax=0)
                                    @php($total_shipping_cost=0)
                                    @php($total_discount_on_product=0)
                                    @php($extra_discount=0)
                                    @php($coupon_discount=0)
                                    @foreach($order as $key=>$orderDetail)
                                        @php($productDetails = $orderDetails?->product ?? json_decode($orderDetail->product_details) )
                                        <tr>
                                            <td>
                                                <div class="media align-items-center gap-3">
                                                    <img class="rounded border" alt="{{ translate('product') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$productDetails->thumbnail, type: 'product') }}"
                                                         width="100px">
                                                    <div class="get-view-by-onclick" data-link="{{route('product',$productDetails->slug)}}">
                                                        <a href="{{route('product',$productDetails->slug)}}">
                                                            <h6 class="title-color mb-2">{{Str::limit($productDetails->name,30)}}</h6>
                                                        </a>
                                                        <div class="d-flex flex-column">
                                                            <small>
                                                                <strong>{{translate('unit_price')}} :</strong>
                                                                {{Helpers::currency_converter($orderDetail['price'])}}
                                                                @if ($orderDetail->tax_model =='include')
                                                                    ({{translate('tax_incl.')}})
                                                                @else
                                                                    ({{translate('tax').":".($productDetails->tax)}}{{$productDetails->tax_type ==="percent" ? '%' :''}})
                                                                @endif
                                                            </small>
                                                            @if ($orderDetail->variant)
                                                                <small><strong>{{translate('variation')}}
                                                                        :</strong> {{$orderDetails['variant']}}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="d-none get-digital-product-download-url" data-action="{{ route('digital-product-download', $orderDetail->id) }}"></span>
                                                    @if($orderDetails->payment_status == 'paid' && $productDetails->digital_product_type == 'ready_product')
                                                        <a  href="javascript:"
                                                           class="btn btn-primary btn-sm rounded-pill mb-1 digital-product-download"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-placement="bottom"
                                                           data-bs-title="{{translate('download')}}">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    @elseif($orderDetails->payment_status == 'paid' && $productDetails->digital_product_type == 'ready_after_sell')
                                                        @if($orderDetail->digital_file_after_sell)
                                                            <a  href="javascript:"
                                                               class="btn btn-primary btn-sm rounded-pill mb-1 digital-product-download"
                                                               data-bs-toggle="tooltip"
                                                               data-bs-placement="bottom"
                                                               data-bs-title="{{translate('download')}}">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        @else
                                                            <span class="btn btn-success btn-sm mb-1 opacity-half cursor-auto" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                data-bs-title="{{translate('product_not_uploaded_yet')}}">
                                                                <i class="bi bi-download"></i>
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                {{$orderDetail->qty}}
                                            </td>
                                            <td class="text-end">
                                                {{Helpers::currency_converter($orderDetail['price']*$orderDetail['qty'])}}
                                            </td>
                                        </tr>
                                        @php($sub_total+=$orderDetail['price']*$orderDetail['qty'])
                                        @php($total_tax+=$orderDetail['tax'])
                                        @php($total_discount_on_product+=$orderDetail['discount'])
                                    @endforeach
                                    </tbody>

                                </table>

                            </div>
                        </div>
                        @php($total_shipping_cost=$orderDetails['shipping_cost'])
                        <?php
                        if ($orderDetails['extra_discount_type'] == 'percent') {
                            $extra_discount = ($sub_total / 100) * $orderDetails['extra_discount'];
                        } else {
                            $extra_discount = $orderDetails['extra_discount'];
                        }
                        if (isset($orderDetails['discount_amount'])) {
                            $coupon_discount = $orderDetails['discount_amount'];
                        }
                        ?>
                        <div class="bg-light rounded border p3">
                            <div class="table-responsive">
                                <table class="table __table text-end table-align-middle text-capitalize">
                                    <thead>
                                    <tr>
                                        <th class="text-muted text-nowrap">{{translate('sub_total')}}</th>
                                        @if ($orderDetails['order_type'] == 'default_type')
                                            <th class="text-muted">{{translate('shipping')}}</th>
                                        @endif
                                        <th class="text-muted">{{translate('tax')}}</th>
                                        <th class="text-muted">{{translate('discount')}}</th>
                                        <th class="text-muted text-nowrap">{{translate('coupon_discount')}}</th>
                                        @if ($orderDetails['order_type'] == 'POS')
                                            <th class="text-muted text-nowrap">{{translate('extra_discount')}}</th>
                                        @endif
                                        <th class="text-muted">{{translate('total')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-dark">
                                            {{Helpers::currency_converter($sub_total)}}
                                        </td>
                                        @if ($orderDetails['order_type'] == 'default_type')
                                            <td class="text-dark">
                                                {{Helpers::currency_converter($orderDetails['is_shipping_free'] ? $total_shipping_cost-$orderDetails['extra_discount']:$total_shipping_cost)}}
                                            </td>

                                        @endif

                                        <td class="text-dark">
                                            {{Helpers::currency_converter($total_tax)}}
                                        </td>
                                        <td class="text-dark">
                                            -{{Helpers::currency_converter($total_discount_on_product)}}
                                        </td>
                                        <td class="text-dark">
                                            - {{Helpers::currency_converter($coupon_discount)}}
                                        </td>
                                        @if ($orderDetails['order_type'] == 'POS')
                                            <td class="text-dark">
                                                - {{Helpers::currency_converter($extra_discount)}}
                                            </td>
                                        @endif
                                        <td class="text-dark">
                                            {{Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-($orderDetails->discount)-$total_discount_on_product - $coupon_discount - $extra_discount)}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade __sign-in-modal" id="digital-product-order-otp-verify-modal" tabindex="-1"
         aria-labelledby="digital_product_order_otp_verifyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/tracking-page.js') }}"></script>
@endpush
