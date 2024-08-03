@extends('layouts.back-end.app')
@section('title', translate('order_Details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
          href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/css/intlTelInput.css') }}">
@endpush

@section('content')
    @php($shippingAddress = $order['shipping_address_data'] ?? null)
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/all-orders.png')}}" alt="">
                {{translate('order_Details')}}
            </h2>
        </div>

        <div class="row gy-3" id="printableArea">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap flex-md-nowrap gap-10 justify-content-between mb-4">
                            <div class="d-flex flex-column gap-10">
                                <h4 class="text-capitalize">{{translate('Order_ID')}} #{{$order['id']}}</h4>
                                <div class="">
                                    {{date('d M, Y , h:i A',strtotime($order['created_at']))}}
                                </div>
                                @if ($linkedOrders->count() >0)
                                    <div class="d-flex flex-wrap gap-10">
                                        <div
                                            class="color-caribbean-green-soft font-weight-bold d-flex align-items-center rounded py-1 px-2"> {{translate('linked_orders')}}
                                            ({{$linkedOrders->count()}}) :
                                        </div>
                                        @foreach($linkedOrders as $linked)
                                            <a href="{{route('admin.orders.details',[$linked['id']])}}"
                                               class="btn color-caribbean-green text-white rounded py-1 px-2">{{$linked['id']}}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="text-sm-right flex-grow-1">
                                <div class="d-flex flex-wrap gap-10 justify-content-end">
                                    @if ($order->verification_images && count($order->verification_images)>0 && $order->verification_status ==1)
                                        <div>
                                            <button class="btn btn--primary px-4" data-toggle="modal"
                                                    data-target="#order_verification_modal"><i
                                                    class="tio-verified"></i> {{translate('order_verification')}}
                                            </button>
                                        </div>
                                    @endif

                                    @if (getWebConfig('map_api_status') == 1 && isset($shippingAddress->latitude) && isset($shippingAddress->longitude))
                                        <div class="">
                                            <button class="btn btn--primary px-4" data-toggle="modal"
                                                    data-target="#locationModal"><i
                                                    class="tio-map"></i> {{translate('show_locations_on_map')}}
                                            </button>
                                        </div>
                                    @endif

                                    <a class="btn btn--primary px-4" target="_blank"
                                       href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                                        <img
                                            src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/uil_invoice.svg') }}"
                                            alt="" class="mr-1">
                                        {{translate('print_Invoice')}}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('status')}}: </span>
                                        @if($order['order_status']=='pending')
                                            <span
                                                class="badge color-caribbean-green-soft font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status']))}}</span>
                                        @elseif($order['order_status']=='failed')
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status'] == 'failed' ? 'Failed to Deliver' : ''))}}
                                            </span>
                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span
                                                class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status'] == 'processing' ? 'Packaging' : $order['order_status']))}}
                                            </span>
                                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                                            <span
                                                class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('payment_Method')}} :</span>
                                        <strong>{{translate($order['payment_method'])}}</strong>
                                    </div>

                                    @if($order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet' && !isset($order->offlinePayments))
                                        <div
                                            class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                            <span class="title-color">{{translate('reference_Code')}} :</span>
                                            <strong>{{str_replace('_',' ',$order['transaction_ref'])}} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                        </div>
                                    @endif

                                    <div class="payment-status d-flex justify-content-sm-end gap-10">
                                        <span class="title-color">{{translate('payment_Status')}}:</span>
                                        @if($order['payment_status']=='paid')
                                            <span class="text-success payment-status-span font-weight-bold">
                                                {{translate('paid')}}
                                            </span>
                                        @else
                                            <span class="text-danger payment-status-span font-weight-bold">
                                                {{translate('unpaid')}}
                                            </span>
                                        @endif
                                    </div>

                                    @if(getWebConfig('order_verification'))
                                        <span class="">
                                            <b>
                                                {{translate('order_verification_code')}} : {{$order['verification_code']}}
                                            </b>
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @if ($order->order_note !=null)
                            <div class="mt-2 mb-5 w-100 d-block">
                                <div class="gap-10">
                                    <h4>{{translate('order_Note')}}:</h4>
                                    <div class="text-justify">{{$order->order_note}}</div>
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive datatable-custom">
                            <table
                                class="table fz-12 table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('item_details')}}</th>
                                    <th>{{translate('item_price')}}</th>
                                    <th>{{translate('tax')}}</th>
                                    <th>{{translate('item_discount')}}</th>
                                    <th>{{translate('total_price')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($item_price=0)
                                @php($total_price=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($row=0)
                                @foreach($order->details as $key=>$detail)
                                    @php($productDetails = $detail?->product ?? json_decode($detail->product_details) )
                                    @if($productDetails)
                                        <tr>
                                            <td>{{ ++$row }}</td>
                                            <td>
                                                <div class="media align-items-center gap-10">
                                                    <img class="avatar avatar-60 rounded"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$productDetails->thumbnail, type: 'backend-product') }}"
                                                         alt="{{translate('image_Description')}}">
                                                    <div>
                                                        <h6 class="title-color">{{substr($productDetails->name, 0, 30)}}{{strlen($productDetails->name)>10?'...':''}}</h6>
                                                        <div><strong>{{translate('qty')}} :</strong> {{$detail['qty']}}
                                                        </div>
                                                        <div>
                                                            <strong>{{translate('unit_price')}} :</strong>
                                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $detail->price+( $detail->tax_model =='include' ? $detail->tax : 0)), currencyCode: getCurrencyCode())}}
                                                            @if ($detail->tax_model =='include')
                                                                ({{translate('tax_incl.')}})
                                                            @else
                                                                ({{translate('tax').":".($productDetails->tax)}}{{$productDetails->tax_type ==="percent" ? '%' :''}})
                                                            @endif

                                                        </div>
                                                        @if ($detail->variant)
                                                            <div><strong>{{translate('variation')}}
                                                                    :</strong> {{$detail['variant']}}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(isset($productDetails->digital_product_type) && $productDetails->digital_product_type == 'ready_after_sell')
                                                    <button type="button" class="btn btn-sm btn--primary mt-2"
                                                            title="{{translate('file_upload')}}" data-toggle="modal"
                                                            data-target="#fileUploadModal-{{ $detail->id }}">
                                                        <i class="tio-file-outlined"></i> {{translate('file')}}
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $detail['price']*$detail['qty']), currencyCode: getCurrencyCode()) }}
                                            </td>
                                            <td>
                                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $detail['tax']), currencyCode: getCurrencyCode()) }}
                                            </td>
                                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $detail['discount']), currencyCode: getCurrencyCode())}}</td>
                                            @php($subtotal=$detail['price']*$detail['qty']+$detail['tax']-$detail['discount'])
                                            <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $subtotal), currencyCode: getCurrencyCode())}}</td>
                                        </tr>
                                        @php($item_price+=$detail['price']*$detail['qty'])
                                        @php($discount+=$detail['discount'])
                                        @php($tax+=$detail['tax'])
                                        @php($total+=$subtotal)
                                    @endif
                                    @php($sellerId=$detail->seller_id)
                                    @if(isset($productDetails->digital_product_type) && $productDetails->digital_product_type == 'ready_after_sell')
                                        @php($product_details = json_decode($detail->product_details))
                                        <div class="modal fade" id="fileUploadModal-{{ $detail->id }}" tabindex="-1"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('admin.orders.digital-file-upload-after-sell') }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if($detail->digital_file_after_sell)
                                                                <div class="mb-4">
                                                                    {{translate('uploaded_file').' : '}}
                                                                    @php($downloadPath =dynamicStorage(path: 'storage/app/public/product/digital-product/'.$detail->digital_file_after_sell))
                                                                    <a href="{{file_exists( $downloadPath) ?  $downloadPath : 'javascript:' }}" class="btn btn-success btn-sm {{file_exists( $downloadPath) ?  $downloadPath : 'download-path-not-found'}}" title="{{translate('download')}}">
                                                                        {{translate('download')}} <i class="tio-download"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <h4 class="text-center">{{translate('file_not_found').'!'}}</h4>
                                                            @endif
                                                            @if(($product_details->added_by == 'admin') && $detail->seller_id == 1)
                                                                <div class="inputDnD">
                                                                    <div
                                                                        class="form-group inputDnD input_image input_image_edit"
                                                                        data-title="{{translate('drag_&_drop_file_or_browse_file')}}">
                                                                        <input type="file"
                                                                               name="digital_file_after_sell"
                                                                               class="form-control-file text--primary font-weight-bold image-input"
                                                                               accept=".jpg, .jpeg, .png, .gif, .zip, .pdf">
                                                                    </div>
                                                                </div>
                                                                <div class="mt-1 text-info">
                                                                    {{translate('file_type').' '.':'.' '.'jpg, jpeg, png, gif, zip, pdf'}}
                                                                </div>
                                                                <input type="hidden" value="{{ $detail->id }}"
                                                                       name="order_id">
                                                            @else
                                                                <h4 class="mt-3 text-center">{{translate('admin_have_no_permission_for_vendors_digital_product_upload')}}</h4>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{translate('close')}}</button>
                                                            @if(($product_details->added_by == 'admin') && $detail->seller_id == 1)
                                                                <button type="submit"
                                                                        class="btn btn--primary">{{translate('upload')}}</button>
                                                            @endif
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php($shipping=$order['shipping_cost'])
                        @php($coupon_discount = $order['discount_amount'])
                        <hr/>
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row gy-1 text-sm-right">
                                    <dt class="col-5">{{translate('item_price')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item_price), currencyCode: getCurrencyCode())}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{translate('item_discount')}}</dt>
                                    <dd class="col-6 title-color">
                                        -
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $discount), currencyCode: getCurrencyCode())}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{translate('sub_total')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $item_price-$discount), currencyCode: getCurrencyCode())}}</strong>
                                    </dd>
                                    <dt class="col-5 text-nowrap">
                                        {{translate('coupon_discount')}}
                                        <br>
                                        {{(!in_array($order['coupon_code'], [0, NULL]) ? '('.translate('expense_bearer_').($order['coupon_discount_bearer']=='inhouse' ? 'admin' : ($order['coupon_discount_bearer'] =='seller'? 'vendor' : $order['coupon_discount_bearer'])).')': '' )}}
                                    </dt>
                                    <dd class="col-6 title-color">
                                        -<strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $coupon_discount), currencyCode: getCurrencyCode())}}</strong>
                                    </dd>
                                    <dt class="col-5 text-uppercase">{{translate('vat')}}/{{translate('tax')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $tax), currencyCode: getCurrencyCode())}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">
                                        {{translate('delivery_fee')}}
                                        <br>
                                        {{($order['is_shipping_free'] ? '('.translate('expense_bearer_').($order['free_delivery_bearer'] == 'seller' ? 'vendor' : $order['free_delivery_bearer']).')': '' )}}
                                    </dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $shipping), currencyCode: getCurrencyCode())}}</strong>
                                    </dd>

                                    @php($delivery_fee_discount = 0)
                                    @if ($order['is_shipping_free'])
                                        @php($delivery_fee_discount = $shipping)
                                    @endif
                                    <dt class="col-5"><strong>{{translate('total')}}</strong></dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total+$shipping-$coupon_discount -$delivery_fee_discount), currencyCode: getCurrencyCode())}}</strong>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-flex flex-column gap-3">
                @if($order->payment_method == 'offline_payment' && isset($order->offlinePayments))
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/product_setup.png')}}"
                                         alt=""
                                         width="20">
                                    {{translate('Payment_Information')}}
                                </h4>
                            </div>
                            <div>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>{{translate('payment_Method')}}</td>
                                        <td class="py-1 px-2">:</td>
                                        <td><strong>{{ translate($order['payment_method']) }}</strong></td>
                                    </tr>
                                    @foreach ($order->offlinePayments->payment_info as $key=>$item)
                                        @if (isset($item) && $key != 'method_id')
                                            <tr>
                                                <td>{{translate($key)}}</td>
                                                <td class="py-1 px-2">:</td>
                                                <td><strong>{{ $item }}</strong></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($order->payment_note) && $order->payment_method == 'offline_payment')
                                <div class="payment-status mt-3">
                                    <h4>{{translate('payment_Note')}}:</h4>
                                    <p class="text-justify">
                                        {{ $order->payment_note }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body text-capitalize d-flex flex-column gap-4">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <h4 class="mb-0 text-center">{{translate('order_&_Shipping_Info')}}</h4>
                        </div>
                        <div class="">
                            <label
                                class="font-weight-bold title-color fz-14">{{translate('change_order_status')}}</label>
                            <select name="order_status" id="order_status"
                                    class="status form-control" data-id="{{$order['id']}}">

                                <option
                                    value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{translate('pending')}}</option>
                                <option
                                    value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{translate('confirmed')}}</option>
                                <option
                                    value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{translate('packaging')}} </option>
                                <option class="text-capitalize"
                                        value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}} >{{translate('out_for_delivery')}} </option>
                                <option
                                    value="delivered" {{$order->order_status == 'delivered'?'selected':''}} >{{translate('delivered')}} </option>
                                <option
                                    value="returned" {{$order->order_status == 'returned'?'selected':''}} > {{translate('returned')}}</option>
                                <option
                                    value="failed" {{$order->order_status == 'failed'?'selected':''}} >{{translate('failed_to_Deliver')}} </option>
                                <option
                                    value="canceled" {{$order->order_status == 'canceled'?'selected':''}} >{{translate('canceled')}} </option>
                            </select>
                        </div>
                        <div
                            class="d-flex justify-content-between align-items-center gap-10 form-control h-auto flex-wrap">
                            <span class="title-color">
                                {{translate('payment_status')}}
                            </span>
                            <div class="d-flex justify-content-end min-w-100 align-items-center gap-2">
                                <span
                                    class="text--primary font-weight-bold">{{ $order->payment_status=='paid' ? translate('paid'):translate('unpaid')}}</span>
                                <label
                                    class="switcher payment-status-text {{$order['payment_status'] == 'paid' ? 'payment-status-alert' : ''}}">
                                    <input class="switcher_input payment-status" type="checkbox" name="status"
                                           data-id="{{$order->id}}"
                                           value="{{$order->payment_status}}"
                                        {{ $order->payment_status=='paid' ? 'disabled' : '' }}
                                        {{ $order->payment_status == 'paid' ? 'checked':''}} >
                                    <span class="switcher_control switcher_control_add
                                        {{ $order->payment_status=='paid' ? 'checked':'unchecked'}}"></span>
                                </label>
                            </div>
                        </div>
                        @if($physicalProduct)
                            <ul class="list-unstyled list-unstyled-py-4">
                                <li>
                                    @if ($order->shipping_type == 'order_wise')
                                        <label class="font-weight-bold title-color fz-14">
                                            {{translate('shipping_Method')}}
                                            ({{$order->shipping ? translate(str_replace('_',' ',$order->shipping->title)) :translate('no_shipping_method_selected')}}
                                            )
                                        </label>
                                    @endif
                                    <select class="form-control text-capitalize" name="delivery_type"
                                            id="choose_delivery_type">
                                        <option value="0">
                                            {{translate('choose_delivery_type')}}
                                        </option>
                                        <option
                                            value="self_delivery" {{$order->delivery_type=='self_delivery'?'selected':''}}>
                                            {{translate('by_self_delivery_man')}}
                                        </option>
                                        <option
                                            value="third_party_delivery" {{$order->delivery_type=='third_party_delivery'?'selected':''}} >
                                            {{translate('by_third_party_delivery_service')}}
                                        </option>
                                    </select>
                                </li>

                                <li class="choose_delivery_man">
                                    <label class="font-weight-bold title-color fz-14">
                                        {{translate('delivery_man')}}
                                    </label>
                                    <select class="form-control text-capitalize js-select2-custom"
                                            name="delivery_man_id" id="addDeliveryMan" data-order-id="{{$order['id']}}">
                                        <option
                                            value="0" {{isset($order->deliveryMan) ? 'disabled':''}}>{{translate('select')}}</option>
                                        @foreach($deliveryMen as $deliveryMan)
                                            <option
                                                value="{{$deliveryMan['id']}}" {{$order['delivery_man_id']==$deliveryMan['id']?'selected':''}}>
                                                {{$deliveryMan['f_name'].' '.$deliveryMan['l_name'].' ('.$deliveryMan['phone'].' )'}}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if (isset($order->deliveryMan))
                                        <div class="p-2 bg-light rounded mt-4">
                                            <div class="media m-1 gap-3">
                                                <img class="avatar rounded-circle"
                                                     src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.$order->deliveryMan?->image, type: 'backend-profile') }}"
                                                     alt="{{translate('Image')}}">
                                                <div class="media-body">
                                                    <h5 class="mb-1">{{ $order->deliveryMan?->f_name.' '.$order->deliveryMan?->l_name}}</h5>
                                                    <a href="tel:{{$order->deliveryMan?->phone}}"
                                                       class="fz-12 title-color">{{$order->deliveryMan?->phone}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-2 bg-light rounded mt-4">
                                            <div class="media m-1 gap-3">
                                                <img class="avatar rounded-circle"
                                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery-man.png')}}"
                                                     alt="{{translate('Image')}}">
                                                <div class="media-body">
                                                    <h5 class="mt-3">{{translate('no_delivery_man_assigned')}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                                @if (isset($order->deliveryMan))
                                    <li class="choose_delivery_man">
                                        <label class="font-weight-bold title-color d-flex fz-14">
                                            {{translate('delivery_man_incentive')}} ({{ session('currency_symbol') }})
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="{{translate('encourage_your_deliveryman_by_giving_him_incentive').' '.translate('this_amount_will_be_count_as_admin_expense').'.'}}">
                                                    <img width="16"
                                                         src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                                         alt="">
                                            </span>
                                        </label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="number"
                                                   value="{{ usdToDefaultCurrency(amount: $order->deliveryman_charge) }}"
                                                   name="deliveryman_charge" data-order-id="{{$order['id']}}"
                                                   class="form-control" placeholder="{{translate('ex').': 20'}}"
                                                   {{$order['order_status']=='delivered' ? 'readonly':''}} required>
                                            <button
                                                class="btn btn--primary {{$order['order_status']=='delivered' ? 'disabled deliveryman-charge-alert':'deliveryman-charge'}}">{{translate('update')}}</button>
                                        </div>
                                    </li>
                                    <li class="choose_delivery_man">
                                        <label
                                            class="font-weight-bold title-color fz-14">{{translate('expected_delivery_date')}}</label>
                                        <input type="date" data-order-id="{{$order['id']}}"
                                               value="{{ $order->expected_delivery_date }}"
                                               name="expected_delivery_date" id="expected_delivery_date"
                                               class="form-control" required>
                                    </li>
                                @endif

                                <li class="mt-1" id="by_third_party_delivery_service_info">
                                    <div class="p-2 bg-light rounded">
                                        <div class="media m-1 gap-3">
                                            <img class="avatar rounded-circle"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/third-party-delivery.png')}}"
                                                 alt="{{translate('image')}}">
                                            <div class="media-body">
                                                <h5 class="">{{$order->delivery_service_name ?? translate('not_assign_yet')}}</h5>
                                                <span
                                                    class="fz-12 title-color">{{translate('track_ID').' '.':'.' '.$order->third_party_delivery_tracking_id}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
                @if(!$order->is_guest && $order->customer)
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img
                                        src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}"
                                        alt="">
                                    {{translate('customer_information')}}
                                </h4>
                            </div>
                            <div class="media flex-wrap gap-3">
                                <div class="">
                                    <img class="avatar rounded-circle avatar-70"
                                         src="{{ getValidImage(path: 'storage/app/public/profile/'.$order->customer->image , type: 'backend-basic') }}"
                                         alt="{{translate('Image')}}">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span
                                        class="title-color"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}} </strong></span>
                                    <span class="title-color"> <strong>{{ $orderCount }}</strong> {{translate('orders')}}</span>
                                    <span
                                        class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                                    <span class="title-color break-all">{{$order->customer['email']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($physicalProduct)
                    <div class="card">
                        @if($shippingAddress)
                            <div class="card-body">
                                <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                    <h4 class="d-flex gap-2">
                                        <img
                                            src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}"
                                            alt="">
                                        {{translate('shipping_address')}}
                                    </h4>
                                    @if($order['order_status'] != 'delivered')
                                        <button class="btn btn-outline-primary btn-sm square-btn" title="Edit"
                                                data-toggle="modal" data-target="#shippingAddressUpdateModal">
                                            <i class="tio-edit"></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div>
                                        <span>{{translate('name')}} :</span>
                                        <strong>{{$shippingAddress->contact_person_name}}</strong> {{ $order->is_guest ? '('. translate('guest_customer') .')':''}}
                                    </div>
                                    <div>
                                        <span>{{translate('contact')}} :</span>
                                        <strong>{{$shippingAddress->phone}}</strong>
                                    </div>
                                    @if ($order->is_guest && $shippingAddress->email)
                                        <div>
                                            <span>{{translate('email')}} :</span>
                                            <strong>{{$shippingAddress->email}}</strong>
                                        </div>
                                    @endif
                                    <div>
                                        <span>{{translate('country')}} :</span>
                                        <strong>{{$shippingAddress->country}}</strong>
                                    </div>
                                    <div>
                                        <span>{{translate('city')}} :</span>
                                        <strong>{{$shippingAddress->city}}</strong>
                                    </div>
                                    <div>
                                        <span>{{translate('zip_code')}} :</span>
                                        <strong>{{$shippingAddress->zip}}</strong>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/location.png')}}"
                                             alt="">
                                        {{$shippingAddress->address  ?? translate('empty')}}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <span>{{translate('no_shipping_address_found')}}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="card">
                    @php($billing=$order['billing_address_data'])
                    @if($billing)
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img
                                        src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}"
                                        alt="">
                                    {{translate('billing_address')}}
                                </h4>
                                @if($order['order_status'] != 'delivered')
                                    <button
                                        class="btn btn-outline-primary btn-sm square-btn billing-address-update-modal"
                                        title="{{translate('edit')}}"
                                        data-toggle="modal" data-target="#billingAddressUpdateModal">
                                        <i class="tio-edit"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span>{{translate('name')}} :</span>
                                    <strong>{{$billing->contact_person_name}}</strong> {{ $order->is_guest ? '('. translate('guest_customer') .')':''}}
                                </div>
                                <div>
                                    <span>{{translate('contact')}} :</span>
                                    <strong>{{$billing->phone}}</strong>
                                </div>
                                @if ($order->is_guest && $billing->email)
                                    <div>
                                        <span>{{translate('email')}} :</span>
                                        <strong>{{$billing->email}}</strong>
                                    </div>
                                @endif
                                <div>
                                    <span>{{translate('country')}} :</span>
                                    <strong>{{$billing->country}}</strong>
                                </div>
                                <div>
                                    <span>{{translate('city')}} :</span>
                                    <strong>{{$billing->city}}</strong>
                                </div>
                                <div>
                                    <span>{{translate('zip_code')}} :</span>
                                    <strong>{{$billing->zip}}</strong>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/location.png')}}" alt="">
                                    {{$billing->address}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{translate('no_billing_address_found')}}</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="d-flex gap-2 mb-4">
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/shop-information.png')}}" alt="">
                            {{translate('shop_Information')}}
                        </h4>
                        <div class="media">
                            @if($order->seller_is == 'admin')
                                <div class="mr-3">
                                    <img class="avatar rounded avatar-70 img-fit-contain "
                                         src="{{ getValidImage(path: 'storage/app/public/company/'.$companyWebLogo , type: 'backend-basic') }}"
                                         alt="">
                                </div>
                                <div class="media-body d-flex flex-column gap-2">
                                    <h5>{{ $companyName }}</h5>
                                    <span class="title-color"><strong>{{ $totalDelivered }}</strong> {{translate('orders_Served')}}</span>
                                </div>
                            @else
                                @if(!empty($order->seller->shop))
                                    <div class="mr-3">
                                        <img class="avatar rounded avatar-70 img-fit"
                                             src="{{ getValidImage(path: 'storage/app/public/shop/'.$order->seller->shop->image , type: 'backend-basic') }}"
                                             alt="">
                                    </div>
                                    <div class="media-body d-flex flex-column gap-2">
                                        <h5>{{ $order->seller->shop->name }}</h5>
                                        <span class="title-color"><strong>{{ $totalDelivered }}</strong> {{translate('orders_Served')}}</span>
                                        <span class="title-color"> <strong>{{ $order->seller->shop->contact }}</strong></span>
                                        <div class="d-flex align-items-start gap-2">
                                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/location.png')}}"
                                                 class="mt-1" alt="">
                                            {{ $order->seller->shop->address }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center p-4">
                                        <img class="w-25" src="{{dynamicAsset(path: 'public/assets/back-end/img/empty-state-icon/shop-not-found.png')}}"
                                             alt="{{translate('image_description')}}">
                                        <p class="mb-0">{{ translate('no_shop_found').'!'}}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($order->verification_images && count($order->verification_images)>0)
        <div class="modal fade" id="order_verification_modal" tabindex="-1" aria-labelledby="order_verification_modal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header pb-4">
                        <h3 class="mb-0">{{translate('order_verification_images')}}</h3>
                        <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                                class="tio-clear"></i></button>
                    </div>
                    <div class="modal-body px-4 px-sm-5 pt-0">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <div class="row gx-2">
                                @foreach ($order->verification_images as $image)
                                    <div class="col-lg-4 col-sm-6 ">
                                        <div class="mb-2 mt-2 border-1">
                                            <img
                                                src="{{ getValidImage(path: 'storage/app/public/delivery-man/verification-image/'.$image->image , type: 'backend-basic') }}"
                                                class="w-100" alt="">
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary px-5"
                                                data-dismiss="modal">{{translate('close')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="shippingAddressUpdateModal" tabindex="-1" aria-labelledby="shippingAddressUpdateModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-4">
                    <h3 class="mb-0 text-center w-100">{{translate('shipping_address')}}</h3>
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                            class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <form action="{{route('admin.orders.address-update')}}" method="post">
                        @csrf
                        <div class="d-flex flex-column align-items-center gap-2">
                            <input name="address_type" value="shipping" hidden>
                            <input name="order_id" value="{{$order->id}}" hidden>
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name"
                                               class="title-color">{{translate('contact_person_name')}}</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                               value="{{$shippingAddress? $shippingAddress->contact_person_name : ''}}"
                                               placeholder="{{ translate('ex') }}: {{translate('john_doe')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number"
                                               class="title-color">{{translate('phone_number')}}</label>
                                        <input class="form-control form-control-user phone-input-with-country-picker"
                                               type="tel" value="{{$shippingAddress ? $shippingAddress->phone  : ''}}"
                                               placeholder="{{ translate('ex').': 017xxxxxxxx' }}" required>
                                        <div class="">
                                            <input type="text" class="country-picker-phone-number w-50"
                                                   value="{{$shippingAddress ? $shippingAddress->phone  : ''}}"
                                                   name="phone_number" hidden readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="title-color">{{translate('country')}}</label>
                                        <select name="country" id="country" class="form-control">
                                            @forelse($countries as $country)
                                                <option
                                                    value="{{ $country['name'] }}" {{ isset($shippingAddress) && $country['name'] == $shippingAddress->country ? 'selected'  : ''}}>{{ $country['name'] }}</option>
                                            @empty
                                                <option value="">{{ translate('No_country_to_deliver') }}</option>
                                            @endforelse
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city" class="title-color">{{translate('city')}}</label>
                                        <input type="text" name="city" id="city"
                                               value="{{$shippingAddress ? $shippingAddress->city : ''}}"
                                               class="form-control"
                                               placeholder="{{ translate('ex') }}:{{translate('dhaka')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                        @if($zipRestrictStatus == 1)
                                            <select name="zip" class="form-control" data-live-search="true" required>
                                                @forelse($zipCodes as $code)
                                                    <option
                                                        value="{{ $code->zipcode }}"{{isset($shippingAddress) && $code->zipcode == $shippingAddress->zip ? 'selected'  : ''}}>{{ $code->zipcode }}</option>
                                                @empty
                                                    <option value="">{{ translate('No_zip_to_deliver') }}</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <input type="text" class="form-control"
                                                   value="{{$shippingAddress ? $shippingAddress->zip  : ''}}" id="zip"
                                                   name="zip"
                                                   placeholder="{{ translate('ex') }}: 1216" {{$shippingAddress?'required':''}}>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address" class="title-color">{{translate('address')}}</label>
                                        <textarea name="address" id="address" name="address" rows="3"
                                                  class="form-control"
                                                  placeholder="{{ translate('ex') }} : {{translate('street_1,_street_2,_street_3,_street_4')}}">{{$shippingAddress ? $shippingAddress->address : ''}}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude"
                                       name="latitude" class="form-control d-inline"
                                       placeholder="{{ translate('Ex') }} : -94.22213"
                                       value="{{$shippingAddress->latitude ?? 0}}" required readonly>
                                <input type="hidden"
                                       name="longitude" class="form-control"
                                       placeholder="{{ translate('Ex') }} : 103.344322" id="longitude"
                                       value="{{$shippingAddress->longitude ??0}}" required readonly>
                                @if(getWebConfig('map_api_status') ==1 )
                                    <div class="col-12 ">
                                        <input id="pac-input" class="form-control rounded __map-input mt-1"
                                               title="{{translate('search_your_location_here')}}" type="text"
                                               placeholder="{{translate('search_here')}}"/>
                                        <div class="dark-support rounded w-100 __h-200px mb-5"
                                             id="location_map_canvas_shipping"></div>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary px-5"
                                                data-dismiss="modal">{{translate('cancel')}}</button>
                                        <button type="submit"
                                                class="btn btn--primary px-5">{{translate('update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($billing)
        <div class="modal fade" id="billingAddressUpdateModal" tabindex="-1" aria-labelledby="billingAddressUpdateModal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header pb-4">
                        <h3 class="mb-0 text-center w-100">{{translate('billing_address')}}</h3>
                        <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i
                                class="tio-clear"></i></button>
                    </div>
                    <div class="modal-body px-4 px-sm-5 pt-0">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <form action="{{route('admin.orders.address-update')}}" method="post">
                                @csrf
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <input name="address_type" value="billing" hidden>
                                    <input name="order_id" value="{{$order->id}}" hidden>
                                    <div class="row gx-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name"
                                                       class="title-color">{{translate('contact_person_name')}}</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                       value="{{$billing? $billing->contact_person_name : ''}}"
                                                       placeholder="{{ translate('ex') }}: {{translate('john_doe')}}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone_number"
                                                       class="title-color">{{translate('phone_number')}}</label>
                                                <input
                                                    class="form-control form-control-user phone-input-with-country-picker-2"
                                                    type="tel" value="{{$billing ? $billing->phone  : ''}}"
                                                    placeholder="{{ translate('ex').': 017xxxxxxxx' }}" required>
                                                <div class="">
                                                    <input type="text" class="country-picker-phone-number-2 w-50"
                                                           value="{{$billing ? $billing->phone  : ''}}"
                                                           name="phone_number" hidden readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country"
                                                       class="title-color">{{translate('country')}}</label>
                                                <select name="country" id="country" class="form-control">
                                                    @forelse($countries as $country)
                                                        <option
                                                            value="{{ $country['name'] }}" {{ isset($billing) && $country['name'] == $billing->country ? 'selected'  : ''}}>{{ $country['name'] }}</option>
                                                    @empty
                                                        <option
                                                            value="">{{ translate('No_country_to_deliver') }}</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="city" class="title-color">{{translate('city')}}</label>
                                                <input type="text" name="city" id="city"
                                                       value="{{$billing ? $billing->city : ''}}" class="form-control"
                                                       placeholder="{{ translate('ex') }}:{{translate('dhaka')}}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                                @if($zipRestrictStatus == 1)
                                                    <select name="zip" class="form-control" data-live-search="true"
                                                            required>
                                                        @forelse($zipCodes as $code)
                                                            <option
                                                                value="{{ $code->zipcode }}"{{isset($billing) && $code->zipcode == $billing->zip ? 'selected'  : ''}}>{{ $code->zipcode }}</option>
                                                        @empty
                                                            <option
                                                                value="">{{ translate('no_zip_to_deliver') }}</option>
                                                        @endforelse
                                                    </select>
                                                @else
                                                    <input type="text" class="form-control"
                                                           value="{{$billing ? $billing->zip  : ''}}" id="zip"
                                                           name="zip"
                                                           placeholder="{{ translate('ex').': 1216' }}" {{$billing?'required':''}}>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address"
                                                       class="title-color">{{translate('address')}}</label>
                                                <textarea name="address" id="billing_address" rows="3"
                                                          class="form-control"
                                                          placeholder="{{ translate('ex') }} : {{translate('street_1,_street_2,_street_3,_street_4')}}">{{$billing ? $billing->address : ''}}</textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" id="billing_latitude"
                                               name="latitude" class="form-control d-inline"
                                               placeholder="{{ translate('ex') }} : -94.22213"
                                               value="{{$billing->latitude ?? 0}}" required readonly>
                                        <input type="hidden"
                                               name="longitude" class="form-control"
                                               placeholder="{{ translate('ex') }} : 103.344322" id="billing_longitude"
                                               value="{{$billing->longitude ?? 0}}" required readonly>
                                        @if(getWebConfig('map_api_status') ==1 )
                                            <div class="col-12 ">
                                                <input id="billing-pac-input" class="form-control rounded __map-input mt-1"
                                                       title="{{translate('search_your_location_here')}}" type="text"
                                                       placeholder="{{translate('search_here')}}"/>
                                                <div class="rounded w-100 __h-200px mb-5"
                                                     id="location_map_canvas_billing"></div>
                                            </div>
                                        @endif
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-3">
                                                <button type="button" class="btn btn-secondary px-5"
                                                        data-dismiss="modal">{{translate('cancel')}}</button>
                                                <button type="submit"
                                                        class="btn btn--primary px-5">{{translate('update')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header pb-0 pt-4">
                    <button type="button" class="close position-absolute right-3 top-3" data-dismiss="modal" aria-label="Close"><spanaria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-header justify-content-center pt-0 pb-0">
                    <h3 class="modal-title" id="locationModalLabel">{{translate('location_on_Map')}}</h3>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="row">
                            <div class="col-md-12 rounded border p-3">
                                <div class="h3 text-cyan-blue text-center">{{ translate('order') }} #{{ $order->id }}</div>
                                <ul class="nav nav-tabs border-0 media-tabs nav-justified order-track-info">
                                    <li class="nav-item">
                                        <div class="nav-link active-status">
                                            <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                <div class="media-tab-media mx-sm-auto mb-3">
                                                    <img
                                                        src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/order-placed.png') }}"
                                                        alt="">
                                                </div>
                                                <div class="media-body">
                                                    <div class="text-sm-center text-start">
                                                        <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('order_placed') }}</h6>
                                                    </div>
                                                    <div
                                                        class="d-flex align-items-center justify-content-sm-center gap-1 mt-2">
                                                <span
                                                    class="text-muted fs-12">{{date('h:i A, d M Y',strtotime($order->created_at))}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </li>


                                    @if ($order['order_status']!='returned' && $order['order_status']!='failed' && $order['order_status']!='canceled')
                                        @if(!$isOrderOnlyDigital)
                                            <li class="nav-item ">
                                                <div
                                                    class="nav-link {{($order['order_status']=='confirmed') || ($order['order_status']=='processing') || ($order['order_status']=='processed') || ($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered')?'active-status' : ''}}">
                                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                                            <img
                                                                src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/order-confirmed.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="text-sm-center text-start">
                                                                <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('order_confirmed') }}</h6>
                                                            </div>
                                                            @if(($order['order_status']=='confirmed') || ($order['order_status']=='processing') || ($order['order_status']=='processed') || ($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered') && \App\Utils\order_status_history($order['id'],'confirmed'))
                                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-1">
                                                            <span class="text-muted fs-12">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($order['id'],'confirmed')))}}
                                                            </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <div
                                                    class="nav-link {{($order['order_status']=='processing') || ($order['order_status']=='processed') || ($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered')?'active-status' : ''}}">
                                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                                            <img alt=""
                                                                 src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/shipment.png') }}">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="text-sm-center text-start">
                                                                <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">
                                                                    {{ translate('preparing_shipment') }}
                                                                </h6>
                                                            </div>
                                                            @if( ($order['order_status']=='processing') || ($order['order_status']=='processed') || ($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered')  && \App\Utils\order_status_history($order['id'],'processing'))
                                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                            <span class="text-muted fs-12">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($order['id'],'processing')))}}
                                                            </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <div
                                                    class="nav-link {{($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered')?'active-status' : ''}}">
                                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                                            <img
                                                                src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/on-the-way.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="text-sm-center text-start">
                                                                <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('order_is_on_the_way') }}</h6>
                                                            </div>
                                                            @if( ($order['order_status']=='out_for_delivery') || ($order['order_status']=='delivered') && \App\Utils\order_status_history($order['id'],'out_for_delivery'))
                                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                            <span class="text-muted fs-12">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($order['id'],'out_for_delivery')))}}
                                                            </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <div
                                                    class="nav-link {{($order['order_status']=='delivered')?'active-status' : ''}}">
                                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                                            <img
                                                                src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/delivered.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="text-sm-center text-start">
                                                                <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('order_Shipped') }}</h6>
                                                            </div>
                                                            @if(($order['order_status']=='delivered') && \App\Utils\order_status_history($order['id'],'delivered'))
                                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                            <span class="text-muted fs-12">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($order['id'],'delivered')))}}
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
                                                foreach ($order->orderDetails as $detail) {
                                                    $productData = json_decode($detail->product_details, true);
                                                    if (isset($productData->digital_product_type) && $productData->digital_product_type == 'ready_after_sell' && $detail->digital_file_after_sell == null) {
                                                        $digitalProductProcessComplete = false;
                                                    }
                                                }
                                                ?>

                                            <li class="nav-item">
                                                <div
                                                    class="nav-link {{ ($order['order_status']=='confirmed') ? 'active-status' : ''}}">
                                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                                            <img alt=""
                                                                 src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/shipment.png') }}">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="text-sm-center text-start">
                                                                <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">
                                                                    {{ translate('processing') }}
                                                                </h6>
                                                            </div>
                                                            @if($order['order_status']=='confirmed' && \App\Utils\order_status_history($order['id'],'confirmed'))
                                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                            <span class="text-muted fs-12">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($order['id'],'confirmed')))}}
                                                            </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <div
                                                    class="nav-link {{($order['order_status']=='confirmed' && $digitalProductProcessComplete)?'active-status' : ''}}">
                                                    <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                        <div class="media-tab-media mb-3 mx-sm-auto">
                                                            <img
                                                                src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/delivered.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="text-sm-center text-start">
                                                                <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('delivery_complete') }}</h6>
                                                            </div>

                                                            @if(($order['order_status']=='confirmed') && $digitalProductProcessComplete && \App\Utils\order_status_history($order['id'],'confirmed'))
                                                                <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                            <span class="text-muted fs-12">
                                                                {{date('h:i A, d M Y',strtotime(\App\Utils\order_status_history($order['id'],'confirmed')))}}
                                                            </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @elseif(in_array($order['order_status'], ['returned', 'canceled']))
                                        <li class="nav-item">
                                            <div class="nav-link active-status">
                                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                                    <div class="media-tab-media mx-sm-auto mb-3">
                                                        <img
                                                            src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/'.$order['order_status'].'.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-sm-center text-start">
                                                            <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">
                                                                {{ translate('order') }} {{ translate($order['order_status']) }}
                                                            </h6>
                                                        </div>
                                                        @if(\App\Utils\order_status_history($order['id'], $order['order_status']))
                                                            <div class="d-flex align-items-center justify-content-sm-center gap-1 mt-2">
                                                        <span class="text-muted fs-12">
                                                            {{ date('h:i A, d M Y', strtotime(\App\Utils\order_status_history($order['id'], $order['order_status']))) }}
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
                                                            src="{{ dynamicAsset(path: 'public/assets/back-end/img/track-order/order-failed.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-sm-center text-start">
                                                            <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('Failed_to_Deliver') }}</h6>
                                                        </div>
                                                        <div
                                                            class="d-flex align-items-center justify-content-sm-center gap-1 mt-2">
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
                            </div>

                            <div class="col-md-12 modal_body_map mt-5 pl-0 pr-0">
                                <div class="mb-2">
                                    <img src="{{ dynamicAsset('assets/back-end/img/location-blue.png') }}" alt="">
                                    <span>{{ $shippingAddress ? $shippingAddress->address : ($billing ? $billing->address : '') }}</span>
                                </div>
                                @if(getWebConfig('map_api_status') ==1 )
                                    <div class="location-map" id="location-map">
                                        <div class="w-100 __h-200px" id="location_map_canvas"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="third_party_delivery_service_modal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('update_third_party_delivery_info')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{route('admin.orders.update-deliver-info')}}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order['id']}}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">{{translate('delivery_service_name')}}</label>
                                        <input class="form-control" type="text" name="delivery_service_name"
                                               value="{{$order['delivery_service_name']}}" id="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{translate('tracking_id')}} ({{translate('optional')}})</label>
                                        <input class="form-control" type="text" name="third_party_delivery_tracking_id"
                                               value="{{$order['third_party_delivery_tracking_id']}}" id="">
                                    </div>
                                    <button class="btn btn--primary" type="submit">{{translate('update')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="message-status-title-text"
          data-text="{{$order['payment_method'] != 'cash_on_delivery' && $order['order_status']=='delivered' ? translate("Order_is_already_delivered_and_transaction_amount_has_been_disbursed_changing_status_can_be_the_reason_of_miscalculation") : translate("are_you_sure_change_this") }}"></span>
    <span id="message-status-subtitle-text"
          data-text="{{ $order['payment_method'] != 'cash_on_delivery' && $order['order_status']=='delivered' ? translate('think_before_you_proceed') : translate("you_will_not_be_able_to_revert_this") }}!"></span>
    <span id="payment-status-message" data-title="{{translate('confirm_payments_before_change_the_status').'.'}}"
          data-message="{{translate('change_the_status_paid_only_when_you_received_the_payment_from_customer').translate('_once_you_change_the_status_to_paid').','.translate('_you_cannot_change_the_status_again').'!' }}"></span>
    <span id="message-status-confirm-text" data-text="{{ translate("yes_change_it") }}!"></span>
    <span id="message-status-cancel-text" data-text="{{ translate("cancel") }}"></span>
    <span id="message-status-success-text" data-text="{{ translate("status_change_successfully") }}"></span>
    <span id="message-status-warning-text"
          data-text="{{ translate("account_has_been_deleted_you_can_not_change_the_status") }}"></span>
    <span id="message-order-status-delivered-text"
          data-text="{{ translate("order_is_already_delivered_you_can_not_change_it") }}!"></span>
    <span id="message-order-status-paid-first-text"
          data-text="{{ translate("before_delivered_you_need_to_make_payment_status_paid") }}!"></span>
    <span id="order-status-url" data-url="{{route('admin.orders.status')}}"></span>
    <span id="payment-status-url" data-url="{{ route('admin.orders.payment-status') }}"></span>

    <span id="message-deliveryman-add-success-text"
          data-text="{{ translate("delivery_man_successfully_assigned/changed") }}"></span>
    <span id="message-deliveryman-add-error-text"
          data-text="{{ translate("deliveryman_man_can_not_assign_or_change_in_that_status") }}"></span>
    <span id="message-deliveryman-add-invalid-text"
          data-text="{{ translate("deliveryman_man_can_not_assign_or_change_in_that_status") }}"></span>
    <span id="delivery-type" data-type="{{ $order->delivery_type }}"></span>
    <span id="add-delivery-man-url" data-url="{{url('/admin/orders/add-delivery-man/'.$order['id'])}}/"></span>

    <span id="message-deliveryman-charge-success-text"
          data-text="{{ translate("deliveryman_charge_add_successfully") }}"></span>
    <span id="message-deliveryman-charge-error-text"
          data-text="{{ translate("failed_to_add_deliveryman_charge") }}"></span>
    <span id="message-deliveryman-charge-invalid-text" data-text="{{ translate("add_valid_data") }}"></span>
    <span id="add-date-update-url" data-url="{{route('admin.orders.amount-date-update')}}"></span>

    <span id="customer-name" data-text="{{$order->customer['f_name']??""}} {{$order->customer['l_name']??""}}}"></span>
    <span id="is-shipping-exist" data-status="{{$shippingAddress ? 'true':'false'}}"></span>
    <span id="shipping-address" data-text="{{$shippingAddress->address??''}}"></span>
    <span id="shipping-latitude" data-latitude="{{$shippingAddress->latitude??'-33.8688'}}"></span>
    <span id="shipping-longitude" data-longitude="{{$shippingAddress->longitude??'151.2195'}}"></span>
    <span id="billing-latitude" data-latitude="{{$billing->latitude??'-33.8688'}}"></span>
    <span id="billing-longitude" data-longitude="{{$billing->longitude??'151.2195'}}"></span>
    <span id="location-icon"
          data-path="{{dynamicAsset(path: 'public/assets/front-end/img/customer_location.png')}}"></span>
    <span id="customer-image"
          data-path="{{dynamicStorage(path: 'storage/app/public/profile/')}}{{$order->customer->image??""}}"></span>
    <span id="deliveryman-charge-alert-message"
          data-message="{{translate('when_order_status_delivered_you_can`t_update_the_delivery_man_incentive').'.'}}"></span>
    <span id="payment-status-alert-message"
          data-message="{{translate('when_payment_status_paid_then_you_can`t_change_payment_status_paid_to_unpaid').'.'}}"></span>

@endsection

@push('script_2')
    @if(getWebConfig('map_api_status') ==1 )
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{getWebConfig('map_api_key')}}&callback=mapCallBackFunction&loading=async&libraries=places&v=3.56"
            defer>
        </script>
    @endif
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/plugins/intl-tel-input/js/intlTelInput.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/country-picker-init.js') }}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/order.js')}}"></script>
@endpush
