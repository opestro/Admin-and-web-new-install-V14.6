@extends('layouts.front-end.app')

@section('title', translate('track_Order'))

@push('css_or_js')
    <meta property="og:image" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{dynamicStorage(path: 'storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description"
          content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <link rel="stylesheet" media="screen" href="{{theme_asset(path: 'public/assets/front-end/vendor/nouislider/distribute/nouislider.min.css')}}"/>
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')

    <?php
    $order = \App\Models\OrderDetail::where('order_id', $orderDetails->id)->get();
    ?>
    <div class="modal fade rtl" id="order-details">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 mx-2">
                    <div>
                        <h5 class="modal-title fs-18 font-bold">{{ translate('order')}} # {{$orderDetails['id']}}</h5>
                        @if ($order_verification_status && $orderDetails->order_type == "default_type")
                            <h5 class="small">{{translate('verification_code')}}
                                : {{ $orderDetails['verification_code'] }}</h5>
                        @endif
                    </div>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body pt-0 ">
                    <div class="product-table-wrap">
                        <div class="table-responsive">
                            <table class="table __table text-capitalize text-start table-align-middle min-w400">
                                <thead class="mb-3">
                                <tr>
                                    <th class="min-w-300">
                                        <span class="fs-12 font-semi-bold text-black">{{ translate('product_details') }}</span>
                                    </th>
                                    <th>
                                        <span class="fs-12 font-semi-bold text-black">{{ translate('QTY') }}</span>
                                    </th>
                                    <th class="text-end">
                                        <span class="fs-12 font-semi-bold text-black">{{ translate('sub_total') }}</span>
                                    </th>
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
                                @foreach($order as $key=>$order_details)
                                    @php($productDetails = $orderDetails?->product ?? json_decode($order_details->product_details) )
                                    <tr>
                                        <td class="pt-1 px-0">
                                            <div class="media align-items-center gap-3 p-0">
                                                <img class="rounded border"
                                                     src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$productDetails->thumbnail, type: 'product') }}"
                                                     width="100px" alt="{{ translate('product') }}">
                                                <div>
                                                    <h6 class="title-color mb-2 fs-14 font-semi-bold">{{Str::limit($productDetails->name, 50)}}</h6>
                                                    <div class="d-flex flex-column mb-1">
                                                        <small class="fs-12">
                                                            <strong>{{translate('unit_price')}} :</strong>
                                                            {{ webCurrencyConverter(amount: $order_details['price']) }}
                                                            @if ($order_details->tax_model =='include')
                                                                ({{translate('tax_incl.')}})
                                                            @else
                                                                ({{ translate('tax').":".($productDetails->tax) }} {{ ($productDetails->tax_type ==="percent" ? '%' :'') }})
                                                            @endif
                                                        </small>
                                                        @if ($order_details->variant)
                                                            <small class="fs-12">
                                                                <strong>{{translate('variation')}} :</strong>
                                                                {{$order_details['variant']}}
                                                            </small>
                                                        @endif
                                                    </div>
                                                    @if($orderDetails->payment_status == 'paid' && $productDetails->digital_product_type == 'ready_product')
                                                        <a data-link="{{ route('digital-product-download', $order_details->id) }}"
                                                           href="javascript:" class="btn btn-success btn-sm action-digital-product-download-track-order px-4"
                                                           data-toggle="tooltip" data-placement="bottom"
                                                           title="{{translate('download')}}">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    @elseif($orderDetails->payment_status == 'paid' && $productDetails->digital_product_type == 'ready_after_sell')
                                                        @if($order_details->digital_file_after_sell)
                                                            <a data-link="{{ route('digital-product-download', $order_details->id) }}"
                                                               href="javascript:" class="btn btn-success btn-sm action-digital-product-download-track-order px-4"
                                                               data-toggle="tooltip" data-placement="bottom"
                                                               title="{{ translate('download') }}">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        @else
                                                            <span class="btn btn-success disabled px-4" data-toggle="tooltip"
                                                                  data-placement="top"
                                                                  title="{{ translate('product_not_uploaded_yet') }}">
                                                                <i class="fa fa-download"></i>
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fs-12 font-semi-bold">
                                            {{$order_details->qty}}
                                        </td>
                                        <td class="text-end fs-12 font-semi-bold">
                                            {{ webCurrencyConverter(amount: $order_details['price']*$order_details['qty']) }}
                                        </td>
                                    </tr>
                                    @php($sub_total+=$order_details['price']*$order_details['qty'])
                                    @php($total_tax+=$order_details['tax'])
                                    @php($total_discount_on_product+=$order_details['discount'])
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
                            <table class="table border-0 text-end table-align-middle text-capitalize">
                                <thead>
                                <tr class="fs-14 font-semibold">
                                    <th class="text-muted font-semibold">{{translate('sub_total')}}</th>
                                    @if ($orderDetails['order_type'] == 'default_type')
                                        <th class="text-muted font-semibold">{{translate('shipping')}}</th>
                                    @endif
                                    <th class="text-muted font-semibold">{{translate('tax')}}</th>
                                    <th class="text-muted font-semibold">{{translate('discount')}}</th>
                                    <th class="text-muted font-semibold">{{translate('coupon_discount')}}</th>
                                    @if ($orderDetails['order_type'] == 'POS')
                                        <th class="text-muted font-semibold">{{translate('extra_discount')}}</th>
                                    @endif
                                    <th class="text-muted font-semibold">{{translate('total')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="fs-14 font-semibold">
                                    <td class="text-dark">
                                        {{ webCurrencyConverter(amount: $sub_total) }}
                                    </td>
                                    @if ($orderDetails['order_type'] == 'default_type')
                                        <td class="text-dark">
                                            {{ webCurrencyConverter(amount: $orderDetails['is_shipping_free'] ? $total_shipping_cost-$orderDetails['extra_discount']:$total_shipping_cost) }}
                                        </td>

                                    @endif

                                    <td class="text-dark">
                                        {{ webCurrencyConverter(amount: $total_tax) }}
                                    </td>
                                    <td class="text-dark">
                                        -{{ webCurrencyConverter(amount: $total_discount_on_product) }}
                                    </td>
                                    <td class="text-dark">
                                        - {{ webCurrencyConverter(amount: $coupon_discount) }}
                                    </td>
                                    @if ($orderDetails['order_type'] == 'POS')
                                        <td class="text-dark">
                                            - {{ webCurrencyConverter(amount: $extra_discount) }}
                                        </td>
                                    @endif
                                    <td class="text-dark">
                                        {{ webCurrencyConverter(amount: $sub_total+$total_tax+$total_shipping_cost-($orderDetails->discount)-$total_discount_on_product - $coupon_discount - $extra_discount) }}
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

    <div class="container pt-4 pb-5 rtl">

        <div class="card border-0 box-shadow-lg">
            <div class="card-body py-5">
                <h6 class="text-end small font-bold fs-14">
                    <a href="{{ route('track-order.index') }}">
                        <span class="text-primary"><i class="tio-refresh"></i></span>
                        {{ translate('clear') }}
                    </a>
                </h6>

                <div class="mx-auto mw-1000">
                    <h2 class="text-center text-capitalize font-bold fs-25">{{ translate('track_order')}}</h2>

                    <form action="{{route('track-order.result')}}" type="submit" method="post" class="p-3">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <input class="form-control form-control-sm prepended-form-control" type="text" name="order_id"
                                       placeholder="{{translate('order_id')}}" value="{{$orderDetails->id}}" required>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <input class="form-control form-control-sm prepended-form-control" type="tel"
                                       placeholder="{{translate('your_phone_number')}}" value="{{ $user_phone }}" name="phone_number"
                                       required>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn--primary btn-sm w-100 font-bold" type="submit"
                                        name="trackOrder">{{translate('track_order')}}</button>
                            </div>
                        </div>
                    </form>

                </div>
                <h6 class="font-weight-bold text-center m-0 pt-5 pb-4">
                    <span class="text-capitalize">{{ translate('your_order')}}</span> <span>:</span> <span
                            class="text-base">{{$orderDetails['id']}}</span>
                </h6>
                <ul class="nav nav-tabs media-tabs nav-justified order-track-info">
                    <li class="nav-item">
                        <div class="nav-link active-status">
                            <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                <div class="media-tab-media mx-sm-auto mb-3">
                                    <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/order-placed.png')}}"
                                         alt="">
                                </div>
                                <div class="media-body">
                                    <div class="text-sm-center">
                                        <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('order_placed')}}</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-sm-center gap-1 mt-2">
                                        <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png')}}"
                                             width="14" alt="">
                                        <span class="text-muted fs-12">{{date('h:i A, d M Y',strtotime($orderDetails->created_at))}}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </li>

                    @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                        @if(!$isOrderOnlyDigital)
                            <li class="nav-item ">
                                <div class="nav-link {{($orderDetails['order_status']=='confirmed') || ($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                            <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/order-confirmed.png')}}"
                                                 alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="text-sm-center">
                                                <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('order_confirmed')}}</h6>
                                            </div>
                                            @if(($orderDetails['order_status']=='confirmed') || ($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered') && \App\Utils\order_status_history($orderDetails['id'],'confirmed'))
                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-1">
                                                    <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png')}}"
                                                         width="14" alt="">
                                                    <span class="text-muted fs-12">
                                                        {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($orderDetails['id'],'confirmed')))}}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item">
                                <div class="nav-link {{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                            <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/shipment.png')}}"
                                                 alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="text-sm-center">
                                                <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('preparing_shipment')}}</h6>
                                            </div>
                                            @if( ($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')  && \App\Utils\order_status_history($orderDetails['id'],'processing'))
                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                    <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png')}}"
                                                         width="14" alt="">
                                                    <span class="text-muted fs-12">
                                                        {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($orderDetails['id'],'processing')))}}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <div class="nav-link {{($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                            <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/on-the-way.png')}}"
                                                 alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="text-sm-center">
                                                <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('order_is_on_the_way')}}</h6>
                                            </div>

                                            @if( ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                <div class="d-flex align-items-center justify-content-sm-center mt-1">
                                                    @if(\App\Utils\order_status_history($orderDetails['id'],'out_for_delivery'))
                                                        <img class="mx-sm-1"
                                                             src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png')}}"
                                                             width="20" alt="">
                                                        <span class="text-muted fs-14">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($orderDetails['id'],'out_for_delivery')))}}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                            @if ($orderDetails->delivery_type == 'third_party_delivery')
                                                <div class="mt-1">
                                                    <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                        <span class="text-muted fs-14 text-capitalize">{{translate('delivery_service_name')}} : </span> <span
                                                                class="fs-14 fw-semibold text-dark">{{$orderDetails->delivery_service_name}}</span>
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                        <span class="text-muted fs-14 text-capitalize"> {{translate('tracking_ID')}} : </span><span
                                                                class="fs-14 fw-semibold text-dark">{{$orderDetails->third_party_delivery_tracking_id}}</span>
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($orderDetails->delivery_type == 'self_delivery' && isset($orderDetails->delivery_man))
                                                <div class="mt-1">
                                                    <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                        <span class="text-muted fs-14 text-capitalize">{{translate('delivery_man_name')}} : </span> <span
                                                                class="fs-14 fw-semibold text-dark">{{$orderDetails->delivery_man->f_name.' '.$orderDetails->delivery_man->l_name}}</span>
                                                    </span>
                                                    <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                        <span class="text-muted fs-14 text-capitalize"> {{translate('contact_number')}} : </span><span
                                                                class="fs-14 fw-semibold text-dark">{{$orderDetails->delivery_man->phone}}</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item">
                                <div class="nav-link {{($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                            <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/delivered.png')}}"
                                                 alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="text-sm-center">
                                                <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('order_Shipped')}}</h6>
                                            </div>
                                            @if(($orderDetails['order_status']=='delivered') && \App\Utils\order_status_history($orderDetails['id'],'delivered'))
                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                    <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png')}}"
                                                         width="14" alt="">
                                                    <span class="text-muted fs-12">
                                                        {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($orderDetails['id'],'delivered')))}}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                        @else

                            <?php
                                $digitalProductProcessComplete = true;
                                foreach ($orderDetails->orderDetails as $detail) {
                                    $productData = json_decode($detail->product_details);
                                    if ($productData->product_type == 'digital' && $productData->digital_product_type == 'ready_after_sell' && $detail->digital_file_after_sell == null) {
                                        $digitalProductProcessComplete = false;
                                    }
                                }
                            ?>

                            <li class="nav-item">
                                <div class="nav-link {{ ($orderDetails['order_status'] == 'processing' || $orderDetails['order_status'] == 'processed' || $orderDetails['order_status'] == 'out_for_delivery' || $orderDetails['order_status'] == 'delivered') ? 'active-status' : ''}}">
                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                            <img alt=""
                                                 src="{{theme_asset(path: 'public/assets/front-end/img/track-order/shipment.png') }}">
                                        </div>
                                        <div class="media-body">
                                            <div class="text-sm-center">
                                                <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">
                                                    {{ translate('Processing') }}
                                                </h6>
                                            </div>
                                            @if(($orderDetails['order_status'] == 'processing' || $orderDetails['order_status'] == 'processed' || $orderDetails['order_status'] == 'out_for_delivery' || $orderDetails['order_status'] == 'delivered') && \App\Utils\order_status_history($orderDetails['id'], 'processing'))
                                                <div
                                                    class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                    <img width="14" alt=""
                                                         src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png') }}">
                                                    <span class="text-muted fs-12">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($orderDetails['id'], 'processing')))}}
                                                            </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item">
                                <div
                                    class="nav-link {{($orderDetails['order_status']=='delivered' && $digitalProductProcessComplete)?'active-status' : ''}}">
                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                            <img
                                                src="{{theme_asset(path: 'public/assets/front-end/img/track-order/delivered.png') }}"
                                                alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="text-sm-center">
                                                <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('delivery_complete') }}</h6>
                                            </div>

                                            @if(($orderDetails['order_status']=='delivered') && $digitalProductProcessComplete && \App\Utils\order_status_history($orderDetails['id'],'delivered'))
                                                <div
                                                    class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                    <img
                                                        src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png') }}"
                                                        width="14" alt="">
                                                    <span class="text-muted fs-12">
                                                            {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($orderDetails['id'],'delivered')))}}
                                                        </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif
                    @elseif(in_array($orderDetails['order_status'], ['returned', 'canceled']))
                        <li class="nav-item">
                            <div class="nav-link active-status">
                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                    <div class="media-tab-media mx-sm-auto mb-3">
                                        <img src="{{ theme_asset(path: 'public/assets/front-end/img/track-order/'.$orderDetails['order_status'].'.png') }}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-sm-center">
                                            <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">
                                                {{ translate('order') }} {{ translate($orderDetails['order_status']) }}
                                            </h6>
                                        </div>
                                        @if(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))
                                            <div class="d-flex align-items-center justify-content-sm-center gap-1 mt-2">
                                                <img src="{{theme_asset(path: 'public/assets/front-end/img/track-order/clock.png') }}"
                                                     width="14" alt="">
                                                <span class="text-muted fs-12">
                                                {{ date('h:i A, d M Y', strtotime(\App\Utils\order_status_history($orderDetails['id'], $orderDetails['order_status']))) }}
                                            </span>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <div class="nav-link active-status">
                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                    <div class="media-tab-media mx-sm-auto mb-3">
                                        <img
                                            src="{{theme_asset(path: 'public/assets/front-end/img/track-order/order-failed.png') }}"
                                            alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-sm-center">
                                            <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('Failed_to_Deliver') }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-sm-center gap-1 mt-2">
                                                    <span class="text-muted fs-12">
                                                        {{ translate('sorry_we_can_not_complete_your_order') }}
                                                    </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </li>
                    @endif

                </ul>
                <div class="text-center pt-4">
                    <a class="btn btn--primary btn-sm text-capitalize" href="#order-details"
                       data-toggle="modal">{{ translate('view_order_details')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="digital_product_order_otp_verify" tabindex="-1"
         aria-labelledby="digital_product_order_otp_verifyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <span id="route-digital-product-download-otp-reset" data-url="{{ route('digital-product-download-otp-reset') }}"></span>

@endsection

@push('script')
    <script src="{{theme_asset(path: 'public/assets/front-end/vendor/nouislider/distribute/nouislider.min.js')}}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/tracking.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/plugin/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ theme_asset(path: 'public/assets/front-end/js/country-picker-init.js') }}"></script>
@endpush
