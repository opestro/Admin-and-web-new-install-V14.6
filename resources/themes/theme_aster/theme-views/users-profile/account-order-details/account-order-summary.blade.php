@php
    use App\Utils\Helpers;
    use App\Utils\OrderManager;
    use App\Utils\ProductManager;
@endphp
@extends('theme-views.layouts.app')
@section('title', translate('order_Details').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="d-lg-none row align-items-center mt-2 mb-3">
                        <div class="col-9">
                            <div class="d-flex gap-3 justify-content-start mt-2">
                                <h6 class="text-capitalize">{{translate('order_status')}}</h6>

                                @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                    <span class="badge bg-danger rounded-pill">
                                {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                            </span>
                                @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                    <span class="badge bg-success rounded-pill">
                                {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                            </span>
                                @else
                                    <span class="badge bg-info rounded-pill">
                                {{translate($order['order_status'])}}
                            </span>
                                @endif
                            </div>
                            <div class="d-flex gap-3 justify-content-start mt-2">
                                <h6 class="text-capitalize">{{translate('payment_status')}}</h6>
                                <div
                                    class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}"> {{ translate($order['payment_status']) }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex gap-3 justify-content-end mt-2">
                                @if($order->order_status=='delivered' && $order->order_type == 'default_type')
                                    <a href="javascript:"
                                       class="btn btn-primary rounded-pill order-again"
                                       data-action="{{route('cart.order-again')}}"
                                       data-order-id="{{$order['id']}}">{{ translate('reorder') }}</a>
                                @endif
                            </div>
                        </div>
                        @if($order->order_type == 'default_type' && getWebConfig(name: 'order_verification'))
                            <div class="d-flex gap-3 justify-content-start mt-2">
                                <h6>{{translate('Verification_Code')}}</h6>
                                <div class="badge bg-primary rounded-pill"> {{ $order['verification_code'] }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            @include('theme-views.users-profile.account-order-details._order-details-head',['order'=>$order])
                            <div class="mt-4 card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @php($digitalProduct = false)
                                        @foreach ($order->details as $key=>$detail)
                                            @if(isset($detail->product->digital_product_type))
                                                @php($digitalProduct = $detail->product->product_type === 'digital' ? true : false)
                                                @if($digitalProduct === true)
                                                    @break
                                                @else
                                                    @continue
                                                @endif
                                            @endif
                                        @endforeach
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                            <tr>
                                                <th class="border-0 text-capitalize">{{translate('product_details')}}</th>
                                                <th class="border-0 text-center">{{translate('qty')}}</th>
                                                <th class="border-0 text-end text-capitalize">{{translate('unit_price')}}</th>
                                                <th class="border-0 text-end text-capitalize">{{translate('discount')}}</th>
                                                <th class="border-0 text-end" {{ ($order->order_type == 'default_type' && $order->order_status=='delivered') ? 'colspan="2"':'' }}>{{translate('Total')}}</th>
                                                @if($order->order_type == 'default_type' && ($order->order_status=='delivered' || ($order->payment_status == 'paid' && $digitalProduct)))
                                                    <th class="border-0 text-center text-capitalize">{{translate('action')}}</th>
                                                @elseif($order->order_type != 'default_type' && $order->order_status=='delivered')
                                                    <th class="border-0 text-center"></th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($order->details as $key=>$detail)
                                                @php($product=json_decode($detail->product_details,true))
                                                @if($product)
                                                    <tr>
                                                        <td>
                                                            <div class="media gap-3">
                                                                <div
                                                                    class="avatar avatar-xxl rounded border overflow-hidden">

                                                                    @if($detail->product_all_status)
                                                                        <img class="d-block img-fit" alt="" width="60"
                                                                             src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$detail->product_all_status['thumbnail'], type: 'product') }}">
                                                                    @else
                                                                        <img class="d-block img-fit"
                                                                             src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                                                                             alt="" width="60">
                                                                    @endif

                                                                </div>
                                                                <div class="media-body d-flex gap-1 flex-column">
                                                                    <h6>
                                                                        <a href="{{route('product',[$product['slug']])}}">
                                                                            {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                                                        </a>
                                                                        @if($detail->refund_request == 1)
                                                                            <small> ({{translate('refund_pending')}}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 2)
                                                                            <small> ({{translate('refund_approved')}}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 3)
                                                                            <small> ({{translate('refund_rejected')}}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 4)
                                                                            <small> ({{translate('refund_refunded')}}
                                                                                ) </small> <br>
                                                                        @endif<br>
                                                                    </h6>
                                                                    @if($detail->variant)
                                                                        <small>{{translate('variant')}}
                                                                            :{{$detail->variant}} </small>

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{$detail->qty}}</td>
                                                        <td class="text-end">{{Helpers::currency_converter($detail->price)}} </td>
                                                        <td class="text-end">{{Helpers::currency_converter($detail->discount)}}</td>
                                                        <td class="text-end">{{Helpers::currency_converter(($detail->qty*$detail->price)-$detail->discount)}}</td>
                                                        @php($length = $detail->created_at->diffInDays($current_date))
                                                        <td>
                                                            <div class="d-flex justify-content-center gap-2">
                                                                @if($detail->product && $order->payment_status == 'paid' && $detail->product->digital_product_type == 'ready_product')
                                                                    <a href="javascript:"
                                                                       data-action="{{ route('digital-product-download', $detail->id) }}"
                                                                       class="btn btn-primary rounded-pill mb-1 digital-product-download"
                                                                       data-bs-toggle="tooltip"
                                                                       data-bs-placement="bottom"
                                                                       data-bs-title="{{translate('download')}}">
                                                                        <i class="bi bi-download"></i>
                                                                    </a>
                                                                @elseif($detail->product && $order->payment_status == 'paid' && $detail->product->digital_product_type == 'ready_after_sell')
                                                                    @if($detail->digital_file_after_sell)
                                                                        <a href="javascript:"
                                                                           data-action="{{ route('digital-product-download', $detail->id) }}"
                                                                           class="btn btn-primary rounded-pill mb-1 digital-product-download"
                                                                           data-bs-toggle="tooltip"
                                                                           data-bs-placement="bottom"
                                                                           data-bs-title="{{translate('download')}}">
                                                                            <i class="bi bi-download"></i>
                                                                        </a>
                                                                    @else
                                                                        <span
                                                                            class="btn btn-success mb-1 opacity-half cursor-auto"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="bottom"
                                                                            data-bs-title="{{translate('product_not_uploaded_yet')}}">
                                                                            <i class="bi bi-download"></i>
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="d-flex justify-content-center gap-2">
                                                                @if($order->order_type == 'default_type')
                                                                    @if($order->order_status=='delivered')
                                                                        <button class="btn btn-primary rounded-pill text-nowrap"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#reviewModal{{$detail->id}}">
                                                                            @if (isset($detail->reviewData))
                                                                                {{ translate('Update_Review') }}
                                                                            @else
                                                                                {{ translate('review') }}
                                                                            @endif
                                                                        </button>
                                                                        @include('theme-views.layouts.partials.modal._review',['id'=>$detail->id,'order_details'=>$detail,])
                                                                        @if($detail->refund_request !=0)
                                                                            <a class="btn btn-outline-primary rounded-pill text-nowrap text-capitalize"
                                                                               href="{{route('refund-details',[$detail->id])}}">{{translate('refund_details')}}</a>
                                                                        @endif
                                                                        @if( $length <= $refund_day_limit && $detail->refund_request == 0)
                                                                            <button
                                                                                class="btn btn-outline-primary rounded-pill"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#refundModal{{$detail->id}}">{{translate('refund')}}</button>
                                                                            @include('theme-views.layouts.partials.modal._refund',['id'=>$detail->id,'order_details'=>$detail,'order'=>$order,'product'=>$product])
                                                                        @endif

                                                                    @endif
                                                                @else
                                                                    <label
                                                                        class="badge bg-info rounded-pill text-capitalize">{{translate('POS_order')}}</label>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @php($summary=OrderManager::order_summary($order))
                                            <?php
                                            if ($order['extra_discount_type'] == 'percent') {
                                                $extra_discount = ($summary['subtotal'] / 100) * $order['extra_discount'];
                                            } else {
                                                $extra_discount = $order['extra_discount'];
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row justify-content-end mt-2">
                                        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-10">
                                            <div class="d-flex flex-column gap-3 text-dark">
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('item')}}</div>
                                                    <div>{{$order->details->count()}}</div>
                                                </div>
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('subtotal')}}</div>
                                                    <div>{{Helpers::currency_converter($summary['subtotal'])}}</div>
                                                </div>
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div>{{translate('tax_fee')}}</div>
                                                    <div>{{Helpers::currency_converter($summary['total_tax'])}}</div>
                                                </div>
                                                @if($order->order_type == 'default_type')
                                                    <div
                                                        class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                        <div class="text-capitalize">{{translate('shipping_fee')}}</div>
                                                        <div>{{Helpers::currency_converter($summary['total_shipping_cost'] - ($order['is_shipping_free'] ? $order['extra_discount'] : 0))}}</div>
                                                    </div>
                                                @endif
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div class="text-capitalize">{{translate('discount_on_product')}}</div>
                                                    <div> {{Helpers::currency_converter($summary['total_discount_on_product'])}}</div>
                                                </div>
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <div class="text-capitalize">{{translate('coupon_discount')}}</div>
                                                    <div>
                                                        -{{Helpers::currency_converter($order->discount_amount)}}</div>
                                                </div>
                                                @if($order->order_type != 'default_type')
                                                    <div
                                                        class="d-flex flex-wrap justify-content-between align-`item`s-center gap-2">
                                                        <div class="text-capitalize">{{translate('extra_discount')}}</div>
                                                        <div>
                                                            -{{Helpers::currency_converter($extra_discount)}}</div>
                                                    </div>
                                                @endif
                                                <div
                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                    <h4 class="text-capitalize">{{translate('total')}}</h4>
                                                    <h2 class="text-primary">{{Helpers::currency_converter($order->order_amount)}}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/spartan-multi-image-picker.js') }}"></script>
    <script src="{{ theme_asset('assets/js/order-summary.js') }}"></script>
@endpush
