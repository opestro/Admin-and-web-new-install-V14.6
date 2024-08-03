@extends('layouts.back-end.app-seller')

@section('title', translate('order_Details'))

@section('content')
    <div class="content container-fluid">

        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/all-orders.png') }}" alt="">
                {{ translate('order_Details') }}
            </h2>
        </div>

        <div class="row gx-2 gy-3" id="printableArea">
            <div class="col-lg-8 col-xl-9">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-10 flex-md-nowrap justify-content-between mb-4">
                            <div class="d-flex flex-column gap-10">
                                <h4 class="text-capitalize">{{ translate('order_ID') }} #{{$order['id']}}</h4>
                                <div class="">
                                    <i class="tio-date-range"></i> {{date('d M Y H:i:s',strtotime($order['created_at'])) }}
                                </div>
                            </div>
                            <div class="text-sm-right flex-grow-1">
                                <div class="d-flex flex-wrap gap-10 justify-content-sm-end">
                                    <a class="btn btn--primary px-4" target="_blank"
                                       href="{{ route('vendor.orders.generate-invoice',[$order['id']]) }}">
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/uil_invoice.svg') }}"
                                             alt="" class="mr-1">
                                        {{ translate('print_Invoice') }}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{ translate('status') }}: </span>
                                        @if($order['order_status']=='pending')
                                            <span
                                                class="badge badge-soft-info font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                            </span>
                                        @elseif($order['order_status']=='failed')
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                            </span>
                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span
                                                class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                            </span>
                                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                                            <span
                                                class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{ translate('payment_Method') }} :</span>
                                        <strong>  {{ translate(str_replace('_',' ',$order['payment_method'])) }}</strong>
                                    </div>
                                    @if(isset($order['transaction_ref']) && $order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet' && !isset($order->offline_payments))
                                        <div
                                            class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                            <span class="title-color">{{ translate('reference_Code') }} :</span>
                                            <strong>{{ translate(str_replace('_',' ',$order['transaction_ref'])) }} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                        </div>
                                    @endif
                                    <div class="payment-status d-flex justify-content-sm-end gap-10">
                                        <span class="title-color">{{ translate('payment_Status') }}:</span>
                                        @if($order['payment_status']=='paid')
                                            <span class="text-success font-weight-bold">
                                                {{ translate('paid') }}
                                            </span>
                                        @else
                                            <span class="text-danger font-weight-bold">
                                                {{ translate('unpaid') }}
                                            </span>
                                        @endif
                                    </div>
                                    @if(getWebConfig('order_verification') && $order->order_type == "default_type")
                                        <span class="ml-2 ml-sm-3">
                                            <b>
                                                {{ translate('order_verification_code') }} : {{$order['verification_code']}}
                                            </b>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive datatable-custom">
                            <table
                                class="table fz-12 table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('item_details') }}</th>
                                    <th>{{ translate('item_price') }}</th>
                                    <th>{{ translate('tax') }}</th>
                                    <th>{{ translate('item_discount') }}</th>
                                    <th>{{ translate('total_price') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($itemPrice=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($extraDiscount=0)
                                @php($productPrice=0)
                                @php($totalProductPrice=0)
                                @php($couponDiscount=0)
                                @foreach($order->details as $key=>$detail)
                                    <?php
                                        if($detail->product) {
                                            $productDetails = $detail->product;
                                        }else {
                                            $productDetails = json_decode($detail->product_details, true);
                                        }
                                    ?>
                                    @if($productDetails)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>
                                                <div class="media align-items-center gap-10">
                                                    <img class="avatar avatar-60 rounded"
                                                         src="{{getValidImage(path:'storage/app/public/product/thumbnail/'.$productDetails['thumbnail'], type:'backend-product') }}"
                                                         alt="{{translate('image_description')}}">
                                                    <div>
                                                        <h6 class="title-color">{{substr($productDetails['name'], 0, 30) }}{{strlen($productDetails['name'])>10?'...':''}}</h6>
                                                        <div><strong>{{ translate('qty') }}
                                                                :</strong> {{$detail['qty']}}
                                                        </div>
                                                        <div>
                                                            <strong>{{ translate('unit_price') }} :</strong>
                                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $detail->price+( $detail->tax_model =='include' ? $detail->tax : 0)), currencyCode: getCurrencyCode())}}
                                                            @if ($detail->tax_model =='include')
                                                                ({{translate('tax_incl.')}})
                                                            @else
                                                                ({{translate('tax').":".($productDetails->tax)}}{{$productDetails->tax_type ==="percent" ? '%' :''}})
                                                            @endif
                                                        </div>
                                                        @if ($detail->variant)
                                                            <div><strong>{{ translate('variation') }}
                                                                    :</strong> {{$detail['variant']}}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(isset($productDetails['digital_product_type']) && $productDetails['digital_product_type'] == 'ready_after_sell')
                                                    <button type="button" class="btn btn-sm btn--primary mt-2"
                                                            title="File Upload" data-toggle="modal"
                                                            data-target="#fileUploadModal-{{ $detail->id }}">
                                                        <i class="tio-file-outlined"></i> {{ translate('file') }}
                                                    </button>
                                                @endif

                                            </td>
                                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $detail['price']*$detail['qty']), currencyCode: getCurrencyCode()) }}</td>
                                            <td>
                                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $detail['tax']), currencyCode: getCurrencyCode()) }}
                                            </td>
                                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $detail['discount']), currencyCode: getCurrencyCode()) }}</td>
                                            @php($itemPrice+=$detail['price']*$detail['qty'])
                                            @php($subtotal=$detail['price']*$detail['qty']+$detail['tax']-$detail['discount'])
                                            @php($productPrice = $detail['price']*$detail['qty'])
                                            @php($totalProductPrice+=$productPrice)
                                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $subtotal), currencyCode: getCurrencyCode()) }}</td>
                                            @if($productDetails['product_type'] == 'digital')
                                                <div class="modal fade" id="fileUploadModal-{{ $detail->id }}"
                                                     tabindex="-1" aria-labelledby="exampleModalLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ route('vendor.orders.digital-file-upload-after-sell') }}"
                                                                method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    @if($productDetails['digital_product_type'] == 'ready_after_sell' && $detail->digital_file_after_sell)
                                                                        <div class="mb-4">
                                                                            {{ translate('uploaded_file') }} :
                                                                            <a href="{{ dynamicStorage(path: 'storage/app/public/product/digital-product/'.$detail->digital_file_after_sell) }}"
                                                                               class="btn btn-success btn-sm"
                                                                               title="{{translate('download')}}"><i
                                                                                    class="tio-download"></i>
                                                                                {{translate('download')}}</a>
                                                                        </div>
                                                                    @elseif($productDetails['digital_product_type'] == 'ready_product' && $productDetails['digital_file_ready'])
                                                                        <div class="mb-4">
                                                                            {{ translate('uploaded_file') }} :
                                                                            <a href="{{ dynamicStorage(path: 'storage/app/public/product/digital-product/'.$productDetails['digital_file_ready']) }}"
                                                                               class="btn btn-success btn-sm"
                                                                               title="{{translate('download')}}"><i
                                                                                    class="tio-download"></i>
                                                                                {{translate('download')}}</a>
                                                                        </div>
                                                                    @endif

                                                                    @if($productDetails['digital_product_type'] == 'ready_after_sell')
                                                                        <input type="file"
                                                                               name="digital_file_after_sell"
                                                                               class="form-control">
                                                                        <div
                                                                            class="mt-1 text-info">{{ translate('file_type').' '.':'.' '.' jpg, jpeg, png, gif, zip, pdf' }}
                                                                        </div>
                                                                        <input type="hidden" value="{{ $detail->id }}"
                                                                               name="order_id">
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">{{ translate('close') }}</button>
                                                                    @if($productDetails['digital_product_type'] == 'ready_after_sell')
                                                                        <button type="submit"
                                                                                class="btn btn--primary">{{ translate('upload') }}</button>
                                                                    @endif
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </tr>
                                        @php($discount+=$detail['discount'])
                                        @php($tax+=$detail['tax'])
                                        @php($total+=$subtotal)
                                    @endif
                                    @php($sellerId=$detail->seller_id)
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php($shipping=$order['shipping_cost'])
                        <hr>
                        <?php
                        if ($order['extra_discount_type'] == 'percent') {
                            $extraDiscount = (($totalProductPrice) / 100) * $order['extra_discount'];
                        } else {
                            $extraDiscount = $order['extra_discount'];
                        }
                        if (isset($order['discount_amount'])) {
                            $couponDiscount = $order['discount_amount'];
                        }
                        ?>
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                    <dt class="col-5">{{ translate('item_price') }}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $itemPrice), currencyCode: getCurrencyCode()) }}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{ translate('item_discount') }}</dt>
                                    <dd class="col-6 title-color">
                                        -
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $discount), currencyCode: getCurrencyCode()) }}</strong>
                                    </dd>
                                    <dt class="col-sm-5">{{ translate('extra_discount') }}</dt>
                                    <dd class="col-sm-6 title-color">
                                        <strong>- {{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $extraDiscount), currencyCode: getCurrencyCode()) }}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{ translate('sub_total') }}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $itemPrice-$discount-$extraDiscount), currencyCode: getCurrencyCode()) }}</strong>
                                    </dd>
                                    <dt class="col-sm-5">{{ translate('coupon_discount') }}</dt>
                                    <dd class="col-sm-6 title-color">
                                        <strong>- {{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $couponDiscount), currencyCode: getCurrencyCode()) }}</strong>
                                    </dd>
                                    <dt class="col-5 text-uppercase">{{ translate('vat') }}/{{ translate('tax') }}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $tax), currencyCode: getCurrencyCode()) }}</strong>
                                    </dd>
                                    <dt class="col-sm-5">{{ translate('total') }}</dt>
                                    <dd class="col-sm-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $total+$shipping-$extraDiscount-$couponDiscount), currencyCode: getCurrencyCode()) }}</strong>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xl-3">
                <div class="card">

                    @if($order->customer)
                        <div class="card-body">
                            <h4 class="mb-4 d-flex align-items-center gap-2">
                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png') }}" alt="">
                                {{ translate('customer_information') }}
                            </h4>

                            <div class="media flex-wrap gap-3">
                                <div class="">
                                    <img class="avatar rounded-circle avatar-70"
                                         src="{{ getValidImage(path: 'storage/app/public/profile/'.$order->customer->image,type: 'backend-profile')}}"
                                         alt="{{translate('image')}}">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span
                                        class="title-color hover-c1"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong></span>
                                    <span
                                        class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                                    <span class="title-color break-all">{{$order->customer['email']}}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{ translate('no_customer_found') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

