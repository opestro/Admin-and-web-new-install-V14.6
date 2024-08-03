@php use App\Utils\Helpers; @endphp
@extends('layouts.back-end.app')

@section('title', translate('order_details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/all-orders.png')}}" alt="">
                {{translate('order_details')}}
            </h2>
        </div>
        <div class="row gy-3" id="printableArea">
            <div class="col-lg-8 col-xl-9">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-10 flex-md-nowrap justify-content-between mb-4">
                            <div class="d-flex flex-column gap-10">
                                <h4 class="text-capitalize">{{translate('order_details')}} #{{$order['id']}}</h4>
                                <div class="">
                                    {{date('d M, Y , h:i A',strtotime($order['created_at']))}}
                                </div>
                                @if ($linked_orders->count() >0)
                                    <div class="d-flex flex-wrap gap-10">
                                        <div
                                            class="badge-soft-info font-weight-bold d-flex align-items-center rounded py-1 px-2"> {{translate('linked_orders')}}
                                            ({{$linked_orders->count().':'}})
                                        </div>
                                        @foreach($linked_orders as $linked)
                                            <a href="{{route('admin.orders.details',[$linked['id']])}}"
                                               class="btn btn-info rounded py-1 px-2">{{$linked['id']}}</a>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-2 mb-5">
                                    @if ($order->order_note !=null)
                                        <div class="d-flex align-items-center gap-10">
                                            <strong class="c1 bg-soft--primary text-capitalize py-1 px-2">
                                                 {{'#'.translate('note').':'}}
                                            </strong>
                                            <div>{{$order->order_note}}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm-right flex-grow-1">
                                <div class="d-flex flex-wrap gap-10">
                                    @if (isset($order->verification_images) && count($order->verification_images)>0 && $order->verification_status ==1)
                                        <div>
                                            <button class="btn btn--primary px-4" data-toggle="modal"
                                                    data-target="#order_verification_modal"><i
                                                    class="tio-verified"></i> {{translate('order_verification')}}
                                            </button>
                                        </div>
                                    @endif
                                    <div class="">
                                        @if (isset($shipping_address['latitude']) && isset($shipping_address['longitude']))
                                            <button class="btn btn--primary px-4" data-toggle="modal"
                                                    data-target="#locationModal"><i
                                                    class="tio-map"></i> {{translate('show_locations_on_map')}}
                                            </button>
                                        @else
                                            <button class="btn btn-warning px-4">
                                                <i class="tio-map"></i> {{translate('shipping_address_has_been_given_below')}}
                                            </button>
                                        @endif
                                    </div>

                                    <a class="btn btn--primary px-4" target="_blank"
                                       href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/uil_invoice.svg') }}"
                                             alt="" class="mr-1">
                                        {{translate('print_Invoice')}}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('status')}}: </span>
                                        @if($order['order_status']=='pending')
                                            <span
                                                class="badge badge-soft-info font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status']))}}</span>
                                        @elseif($order['order_status']=='failed')
                                            <span
                                                class="badge badge-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{ $order['order_status'] === 'failed' ? translate('failed_to_Deliver') : ''}}
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
                                        <span class="title-color">{{translate('payment_Method').':'}}</span>
                                        <strong>{{translate($order['payment_method'])}}</strong>
                                    </div>
                                    @if($order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet' && !isset($order->offline_payments))
                                        <div
                                            class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                            <span class="title-color">{{translate('reference_Code')}} :</span>
                                            <strong>{{str_replace('_',' ',$order['transaction_ref'])}} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                        </div>
                                    @endif
                                    @if($order->payment_method == 'offline_payment' && isset($order->offline_payments))
                                        @foreach (json_decode($order->offline_payments->payment_info) as $key=>$item)
                                            @if (isset($item) && $key != 'method_id')
                                                <div class="d-flex justify-content-sm-end gap-10 text-capitalize">
                                                    <span class="title-color">{{translate($key)}} :</span>
                                                    <strong>{{ $item }}</strong>
                                                </div>
                                            @endif
                                        @endforeach
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

                                    @if(isset($order->payment_note) && $order->payment_method == 'offline_payment')
                                        <div class="col-md-12 payment-status d-flex justify-content-sm-end gap-10">
                                            <strong>{{translate('payment_Note')}}:</strong>
                                            <span>
                                                {{ $order->payment_note }}
                                            </span>
                                        </div>
                                    @endif
                                    @if(Helpers::get_business_settings('order_verification'))
                                        <span class="ml-2 ml-sm-3">
                                            <b>
                                                {{translate('order_verification_code').':'.$order['verification_code']}}
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
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('item Details')}}</th>
                                    <th>{{translate('variations')}}</th>
                                    <th>{{translate('tax')}}</th>
                                    <th>{{translate('discount')}}</th>
                                    <th>{{translate('price')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($item_price=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($row=0)

                                @foreach($order->details as $key=>$detail)
                                    @if($detail->productAllStatus)
                                        <tr>
                                            <td>{{ ++$row }}</td>
                                            <td>
                                                <div class="media align-items-center gap-10">
                                                    <img class="avatar avatar-60 rounded"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$detail->productAllStatus['thumbnail'], type: 'backend-product') }}"
                                                         alt="{{translate('image_description')}}">
                                                    <div>
                                                        <h6 class="title-color">{{substr($detail->productAllStatus['name'],0,30)}}{{strlen($detail->productAllStatus['name'])>10?'...':''}}</h6>
                                                        <div><strong>{{translate('qty')}} :</strong> {{$detail['qty']}}
                                                        </div>
                                                        <div>
                                                            <strong>{{translate('unit_price')}} :</strong>
                                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $detail['price']+($detail->tax_model =='include' ? $detail['tax']:0)), currencyCode: getCurrencyCode())}}
                                                            @if ($detail->tax_model =='include')
                                                                ({{translate('tax_incl.')}})
                                                            @else
                                                                ({{translate('tax').":".($detail->productAllStatus->tax)}}{{$detail->productAllStatus->tax_type ==="percent" ? '%' :''}})
                                                            @endif

                                                        </div>
                                                        @if ($detail->variant)
                                                            <div><strong>{{translate('variation')}}
                                                                    :</strong> {{$detail['variant']}}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($detail->productAllStatus->digital_product_type == 'ready_after_sell')
                                                    <button type="button" class="btn btn-sm btn--primary mt-2"
                                                            title="File Upload" data-toggle="modal"
                                                            data-target="#fileUploadModal-{{ $detail->id }}"
                                                    >
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
                                        <!-- End Media -->
                                    @endif
                                    @php($sellerId=$detail->seller_id)

                                    @if(isset($detail->productAllStatus->digital_product_type) && $detail->productAllStatus->digital_product_type == 'ready_after_sell')
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
                                                                    {{translate('uploaded_file')}} :
                                                                    <a href="{{ dynamicStorage(path: 'storage/app/public/product/digital-product/'.$detail->digital_file_after_sell) }}"
                                                                       class="btn btn-success btn-sm" title="Download"
                                                                       download><i
                                                                            class="tio-download"></i> {{translate('download')}}
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <h4 class="text-center">{{translate('file_not_found')}}
                                                                    !</h4>
                                                            @endif
                                                            @if(($product_details->added_by == 'admin') && $detail->seller_id == 1)
                                                                <div class="inputDnD">
                                                                    <div
                                                                        class="form-group inputDnD input_image input_image_edit"
                                                                        data-title="{{translate('drag_&_drop_file_or_browse_file')}}">
                                                                        <input type="file"
                                                                               name="digital_file_after_sell"
                                                                               class="form-control-file text--primary font-weight-bold readUrl"
                                                                               id="inputFile"
                                                                               accept=".jpg, .jpeg, .png, .gif, .zip, .pdf"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="mt-1 text-info">{{translate('file_type')}}:
                                                                    jpg, jpeg, png, gif, zip, pdf
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
                        @php($coupon_discount=$order['discount_amount'])
                        <hr/>
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row gy-1 text-sm-right">
                                    <dt class="col-5">{{translate('item_price')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$item_price))}}</strong>
                                    </dd>
                                    <dt class="col-5">{{translate('sub_total')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$total))}}</strong>
                                    </dd>
                                    <dt class="col-5">{{translate('coupon_discount')}}</dt>
                                    <dd class="col-6 title-color">
                                         {{'-'.setCurrencySymbol(amount: usdToDefaultCurrency(amount:$coupon_discount))}}
                                    </dd>
                                    <dt class="col-5 text-uppercase">{{translate('vat').'/'.translate('tax')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$tax))}}</strong>
                                    </dd>
                                    <dt class="col-5">{{translate('shipping')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$shipping))}}</strong>
                                    </dd>

                                    @php($delivery_fee_discount = 0)
                                    @if ($order['is_shipping_free'])
                                        <dt class="col-5">{{translate('delivery_fee_discount')}}
                                            ({{ translate($order['free_delivery_bearer']) }} {{translate('bearer')}})
                                        </dt>
                                        <dd class="col-6 title-color">
                                            {{'-'.' '.setCurrencySymbol(amount: usdToDefaultCurrency(amount:$shipping))}}
                                        </dd>
                                        @php($delivery_fee_discount = $shipping)
                                    @endif

                                    @if($order['coupon_discount_bearer'] == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                        <dt class="col-5">{{translate('coupon_discount').'('.translate('admin_bearer').')'}}
                                        </dt>
                                        <dd class="col-6 title-color">
                                            {{'+'.' '.setCurrencySymbol(amount: usdToDefaultCurrency(amount:$coupon_discount))}}
                                        </dd>
                                        @php($total += $coupon_discount)
                                    @endif

                                    <dt class="col-5"><strong>{{translate('total')}}</strong></dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount:$total+$shipping-$coupon_discount -$delivery_fee_discount))}}</strong>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xl-3 d-flex flex-column gap-3">
                <div class="card">
                    <div class="card-body text-capitalize d-flex flex-column gap-4">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <h4 class="mb-0 text-center text-capitalize">{{translate('order_&_shipping_info')}}</h4>
                        </div>
                        <div class="">
                            <label
                                class="font-weight-bold title-color fz-14">{{translate('change_order_status')}}</label>
                            <select name="order_status" id="order_status" class="status form-control" data-id="{{$order['id']}}">

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
                        <div class="d-flex justify-content-between align-items-center gap-10 form-control h-auto flex-wrap">
                            <span class="title-color">
                                {{translate('payment_status')}}
                            </span>
                            <div class="d-flex justify-content-end min-w-100 align-items-center gap-2">
                                <span
                                    class="text--primary font-weight-bold">{{ $order->payment_status=='paid' ? translate('paid'):translate('unpaid')}}</span>
                                <label class="switcher payment-status-text">
                                    <input class="switcher_input payment_status" type="checkbox" name="status"
                                           data-id="{{$order->id}}"
                                           value="{{$order->payment_status}}"
                                        {{ $order->payment_status=='paid' ? 'checked':''}} >
                                    <span class="switcher_control switcher_control_add"></span>
                                </label>
                            </div>
                        </div>
                        @if($physical_product)
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
                                        <option value="0"> {{translate('choose_delivery_type')}} </option>

                                        <option value="self_delivery" {{$order->delivery_type=='self_delivery'?'selected':''}}>
                                            {{translate('by_self_delivery_man')}}
                                        </option>
                                        <option value="third_party_delivery" {{$order->delivery_type=='third_party_delivery'?'selected':''}} >
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
                                            value="0">{{translate('select')}}</option>
                                        @foreach($delivery_men as $deliveryMan)
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
                                                     src="{{ getValidImage(path: "storage/app/public/profile/".isset($order->deliveryMan->image) ?? '', type: 'backend-basic') }}"
                                                     alt="{{translate('image')}}">
                                                <div class="media-body">
                                                    <h5 class="mb-1">{{ isset($order->delivery_man) ? $order->deliveryMan->f_name.' '.$order->delivery_man->l_name :''}}</h5>
                                                    <a href="tel:{{isset($order->deliveryMan) ? $order->deliveryMan->phone : ''}}"
                                                       class="fz-12 title-color">{{isset($order->deliveryMan) ? $order->deliveryMan->phone :''}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-2 bg-light rounded mt-4">
                                            <div class="media m-1 gap-3">
                                                <img class="avatar rounded-circle"
                                                     src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery-man.png')}}"
                                                     alt="{{translate('image')}}">
                                                <div class="media-body">
                                                    <h5 class="mt-3">{{translate('no_delivery_man_assigned')}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                                @if (isset($order->delivery_man))
                                    <li class="choose_delivery_man">
                                        <label class="font-weight-bold title-color fz-14">
                                            {{translate('deliveryman_will_get')}} ({{ session('currency_symbol') }})
                                        </label>
                                        <input type="number" id="deliveryman_charge"
                                               data-order-id="{{$order['id']}}"
                                               value="{{ $order->deliveryman_charge }}" name="deliveryman_charge"
                                               class="form-control amountDateUpdate" placeholder="Ex: 20" required>
                                    </li>
                                    <li class="choose_delivery_man">
                                        <label class="font-weight-bold title-color fz-14">
                                            {{translate('expected_delivery_date')}}
                                        </label>
                                        <input type="date"
                                               data-order-id="{{$order['id']}}"
                                               value="{{ $order->expected_delivery_date }}"
                                               name="expected_delivery_date amountDateUpdate" id="expected_delivery_date"
                                               class="form-control" required>
                                    </li>
                                @endif
                                <li class="mt-1" id="by_third_party_delivery_service_info">
                                    <div class="p-2 bg-light rounded">
                                        <div class="media m-1 gap-3">
                                            <img class="avatar rounded-circle"
                                                 src="{{dynamicAsset(path: 'public/assets/back-end/img/third-party-delivery.png')}}"
                                                 alt="Image">
                                            <div class="media-body">
                                                <h5 class="">{{ $order?->delivery_service_name ?? translate('not_assign_yet')}}</h5>
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
                <div class="card">
                    @if(!$order->is_guest && $order->customer)
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" alt="">
                                    {{translate('customer_information')}}
                                </h4>
                            </div>
                            <div class="media flex-wrap gap-3">
                                <div class="">
                                    <img class="avatar rounded-circle avatar-70"
                                         src="{{ getValidImage(path: 'storage/app/public/profile/'.$order->customer->image, type: 'backend-profile') }}"
                                         alt="{{translate('image')}}">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span
                                        class="title-color"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}} </strong></span>
                                    <span
                                        class="title-color"> <strong>{{$orderCount}}</strong> {{translate('orders')}}</span>
                                    <span
                                        class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                                    <span class="title-color break-all">{{$order->customer['email']}}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            @if($order->is_guest)
                                <div class="d-flex gap-2 align-items-center justify-content-between">
                                    <h4 class="d-flex gap-2">
                                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" alt="">
                                        {{translate('guest_customer')}}
                                    </h4>
                                </div>
                            @else
                                <div class="media">
                                    <span>{{ translate('no_customer_found') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                @if($physical_product)
                    <div class="card">
                        @php($shipping_address=$order['shipping_address_data'])
                        @if($shipping_address)
                            <div class="card-body">
                                <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                    <h4 class="d-flex gap-2">
                                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}"
                                             alt="">
                                        {{translate('shipping_address')}}
                                    </h4>
                                    <button class="btn btn-outline-primary btn-sm square-btn" title="Edit"
                                            data-toggle="modal" data-target="#shippingAddressUpdateModal">
                                        <i class="tio-edit"></i>
                                    </button>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div>
                                        <span>{{translate('name')}} :</span>
                                        <strong>{{$shipping_address->contact_person_name}}</strong>
                                    </div>
                                    <div>
                                        <span>{{translate('contact')}}:</span>
                                        <strong>{{$shipping_address->phone}}</strong>
                                    </div>
                                    <div>
                                        <span>{{translate('city')}}:</span>
                                        <strong>{{$shipping_address->city}}</strong>
                                    </div>
                                    <div>
                                        <span>{{translate('zip_code')}} :</span>
                                        <strong>{{$shipping_address->zip}}</strong>
                                    </div>
                                    <div class="d-flex align-items-start gap-2">
                                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/location.png')}}" alt="">
                                        {{$shipping_address->address  ?? translate('empty')}}
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
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/vendor-information.png')}}" alt="">
                                    {{translate('billing_address')}}
                                </h4>

                                <button class="btn btn-outline-primary btn-sm square-btn" title="Edit"
                                        data-toggle="modal" data-target="#billingAddressUpdateModal">
                                    <i class="tio-edit"></i>
                                </button>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span>{{translate('name')}} :</span>
                                    <strong>{{$billing->contact_person_name}}</strong>
                                </div>
                                <div>
                                    <span>{{translate('contact')}}:</span>
                                    <strong>{{$billing->phone}}</strong>
                                </div>
                                <div>
                                    <span>{{translate('city')}}:</span>
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
                        <h4 class="d-flex gap-2 mb-4 text-capitalize">
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/shop-information.png')}}" alt="">
                            {{translate('shop_information')}}
                        </h4>

                        <div class="media">
                            @if($order->seller_is == 'admin')
                                <div class="mr-3">
                                    <img class="avatar rounded avatar-70"
                                         src="{{getValidImage(path: 'storage/app/public/company/'.$company_web_logo,type: 'backend-basic')}}" alt="">
                                </div>

                                <div class="media-body d-flex flex-column gap-2">
                                    <h5>{{ $company_name }}</h5>
                                    <span class="title-color"><strong>{{ $total_delivered }}</strong> {{translate('orders_Served')}}</span>
                                </div>
                            @else
                                @if(!empty($order->seller->shop))
                                    <div class="mr-3">
                                        <img class="avatar rounded avatar-70"
                                             src="{{ getValidImage(path:'storage/app/public/shop/'.$order->seller->shop->image,type: 'backend-basic')}}"
                                             alt="">
                                    </div>
                                    <div class="media-body d-flex flex-column gap-2">
                                        <h5>{{ $order->seller->shop->name }}</h5>
                                        <span class="title-color"><strong>{{ $total_delivered }}</strong> {{translate('orders_Served')}}</span>
                                        <span class="title-color"> <strong>{{ $order->seller->shop->contact }}</strong></span>
                                        <div class="d-flex align-items-start gap-2">
                                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/location.png')}}"
                                                 class="mt-1" alt="">
                                            {{ $order->seller->shop->address }}
                                        </div>
                                    </div>
                                @else
                                    <div class="card-body">
                                        <div class="media align-items-center">
                                            <span>{{translate('no_data_found')}}</span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (isset($order->verification_images) && count($order->verification_images)>0)
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
                                            <img src="{{ getValidImage(path: "storage/app/public/delivery-man/verification-image/".$image->image, type: 'backend-basic') }}"
                                                class="w-100" alt=""
                                            >
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">{{translate('close')}}</button>
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
                    <h3 class="mb-0">{{translate('shipping_address')}}</h3>
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
                                               value="{{$shipping_address? $shipping_address->contact_person_name : ''}}"
                                               placeholder="{{ translate('ex') .':'.translate('john_doe')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number"
                                               class="title-color">{{translate('phone_number')}}</label>
                                        <input type="tel" name="phone_number" id="phone_number"
                                               value="{{$shipping_address ? $shipping_address->phone  : ''}}"
                                               class="form-control" placeholder="{{ translate('ex').':'.'32416436546' }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="title-color">{{translate('country')}}</label>
                                        <select name="country" id="country" class="form-control">
                                            @forelse($countries as $country)
                                                <option value="{{ $country['name'] }}" {{ isset($shipping_address) && $country['name'] == $shipping_address->country ? 'selected'  : ''}}>{{ $country['name'] }}</option>
                                            @empty
                                                <option value="">{{ translate('no_country_to_deliver') }}</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city" class="title-color">{{translate('city')}}</label>
                                        <input type="text" name="city" id="city"
                                               value="{{$shipping_address ? $shipping_address->city : ''}}"
                                               class="form-control"
                                               placeholder="{{ translate('ex') .':'.translate('dhaka')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                        @if($zip_restrict_status == 1)
                                            <select name="zip" class="form-control" data-live-search="true" required>
                                                @forelse($zip_codes as $code)
                                                    <option
                                                        value="{{ $code->zipcode }}"{{isset($shipping_address) && $code->zipcode == $shipping_address->zip ? 'selected'  : ''}}>{{ $code->zipcode }}</option>
                                                @empty
                                                    <option value="">{{ translate('No_zip_to_deliver') }}</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <input type="text" class="form-control"
                                                   value="{{$shipping_address ? $shipping_address->zip  : ''}}" id="zip" name="zip"
                                                   placeholder="{{ translate('ex').':'.'1216' }}" {{$shipping_address?'required':''}}>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address" class="title-color">{{translate('address')}}</label>
                                        <textarea name="address" id="address" name="address" rows="3"
                                                  class="form-control"
                                                  placeholder="{{ translate('ex') .':'.translate('street_1,_street_2,_street_3,_street_4')}}">{{$shipping_address ? $shipping_address->address : ''}}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude"
                                       name="latitude" class="form-control d-inline"
                                       placeholder="{{ translate('ex').':'.'-94.22213' }}"
                                       value="{{$shipping_address->latitude ?? 0}}" required readonly>
                                <input type="hidden"
                                       name="longitude" class="form-control"
                                       placeholder="{{ translate('ex').':'.'103.344322' }}" id="longitude"
                                       value="{{$shipping_address->longitude??0}}" required readonly>
                                <div class="col-12 ">
                                    <input id="pac-input" class="form-control rounded __map-input mt-1"
                                           title="{{translate('search_your_location_here')}}" type="text"
                                           placeholder="{{translate('search_here')}}"/>
                                    <div class="dark-support rounded w-100 __h-200px mb-5"
                                         id="location_map_canvas_shipping"></div>
                                </div>
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
                        <h3 class="mb-0">{{translate('billing_address')}}</h3>
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
                                                       placeholder="{{ translate('ex') .':'.translate('john_doe')}}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone_number"
                                                       class="title-color">{{translate('phone_number')}}</label>
                                                <input type="tel" name="phone_number" id="phone_number"
                                                       value="{{$billing ? $billing->phone  : ''}}" class="form-control"
                                                       placeholder="{{ translate('ex').':'.' '.'32416436546' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country"
                                                       class="title-color">{{translate('country')}}</label>
                                                <select name="country" id="country" class="form-control">
                                                    <option value="">{{ translate('No_country_to_deliver') }}</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="city" class="title-color">{{translate('city')}}</label>
                                                <input type="text" name="city" id="city"
                                                       value="{{$billing ? $billing->city : ''}}" class="form-control"
                                                       placeholder="{{ translate('ex').':'.translate('dhaka')}}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                                <input type="text" class="form-control"
                                                       value="{{$billing ? $billing->zip  : ''}}" id="zip" name="zip"
                                                       placeholder="{{ translate('ex').':'.' '.'1216'}}" {{$billing?'required':''}}>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address"
                                                       class="title-color">{{translate('address')}}</label>
                                                <textarea name="address" id="billing_address" rows="3"
                                                          class="form-control"
                                                          placeholder="{{ translate('ex') .':'.' '.translate('street_1,_street_2,_street_3,_street_4')}}">{{$billing ? $billing->address : ''}}</textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" id="billing_latitude"
                                               name="latitude" class="form-control d-inline"
                                               placeholder="{{ translate('ex') .':'.' '.'-94.22213'}}"
                                               value="{{$billing->latitude ?? 0}}" required readonly>
                                        <input type="hidden"
                                               name="longitude" class="form-control"
                                               placeholder="{{ translate('ex') .':'.' '.'103.344322'}}" id="billing_longitude"
                                               value="{{$billing->longitude ?? 0}}" required readonly>
                                        <div class="col-12 ">
                                            <input id="billing-pac-input" class="form-control rounded __map-input mt-1"
                                                   title="{{translate('search_your_location_here')}}" type="text"
                                                   placeholder="{{translate('search_here')}}"/>
                                            <div class="rounded w-100 __h-200px mb-5"
                                                 id="location_map_canvas_billing"></div>
                                        </div>
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
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="locationModalLabel">{{translate('location_Data')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div class="w-100 __h-400px" id="location_map_canvas"></div>
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
          data-text="{{ $order['order_status']=='delivered' ? translate("Order_is_already_delivered_and_transaction_amount_has_been_disbursed_changing_status_can_be_the_reason_of_miscalculation") : translate("are_you_sure_change_this") }}"></span>
    <span id="message-status-subtitle-text"
          data-text="{{ $order['order_status']=='delivered' ? translate('think_before_you_proceed') : translate("you_will_not_be_able_to_revert_this") }}!"></span>
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
          data-text="{{ translate("deliveryman_man_can_not_assign/change_in_that_status") }}"></span>
    <span id="message-deliveryman-add-invalid-text" data-text="{{ translate("add_valid_data") }}"></span>
    <span id="delivery-type" data-type="{{ $order->delivery_type }}"></span>
    <span id="add-delivery-man-url" data-url="{{url('/admin/orders/add-delivery-man/'.$order['id'])}}/"></span>

    <span id="message-deliveryman-charge-success-text"
          data-text="{{ translate("deliveryman_charge_add_successfully") }}"></span>
    <span id="message-deliveryman-charge-error-text"
          data-text="{{ translate("failed_to_add_deliveryman_charge") }}"></span>
    <span id="message-deliveryman-charge-invalid-text" data-text="{{ translate("add_valid_data") }}"></span>
    <span id="add-date-update-url" data-url="{{route('admin.orders.amount-date-update')}}"></span>

    <span id="customer-name" data-text="{{$order->customer['f_name']??""}} {{$order->customer['l_name']??""}}}"></span>
    <span id="is-shipping-exist" data-status="{{$shipping_address ? 'true':'false'}}"></span>
    <span id="shipping-address" data-text="{{$shipping_address->address??''}}"></span>
    <span id="shipping-latitude" data-latitude="{{$shipping_address->latitude??'-33.8688'}}"></span>
    <span id="shipping-longitude" data-longitude="{{$shipping_address->longitude??'151.2195'}}"></span>
    <span id="billing-latitude" data-latitude="{{$billing->latitude??'-33.8688'}}"></span>
    <span id="billing-longitude" data-longitude="{{$billing->longitude??'151.2195'}}"></span>
    <span id="location-icon" data-path="{{dynamicAsset(path: 'public/assets/front-end/img/customer_location.png')}}"></span>
    <span id="customer-image"
          data-path="{{dynamicStorage(path: 'storage/app/public/profile/')}}{{$order->customer->image??""}}"></span>
@endsection
@push('script_2')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{getWebConfig('map_api_key')}}&callback=map_callback_fucntion&libraries=places&v=3.49"
        defer></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/order.js')}}"></script>
@endpush
