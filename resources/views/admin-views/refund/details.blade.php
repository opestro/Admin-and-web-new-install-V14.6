@extends('layouts.back-end.app')

@section('title', translate('refund_details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/refund_transaction.png')}}" alt="">
                {{translate('refund_details')}}
            </h2>
        </div>
        <div class="refund-details-card--2 p-4">
            <div class="row gy-2">
            <div class="col-lg-4">
                <div class="card h-100 refund-details-card">
                    <div class="card-body">
                        <h4 class="mb-3">{{translate('refund_summary')}}</h4>
                        <ul class="dm-info p-0 m-0">
                            <li class="align-items-center">
                                <span class="left">{{translate('refund_id')}} </span> <span>:</span> <span class="right">{{$refund->id}}</span>
                            </li>
                            <li class="align-items-center">
                                <span class="left text-capitalize">{{translate('refund_requested_date')}}</span>
                                <span>:</span>
                                <span class="right">{{date('d M Y, h:s:A',strtotime($refund['created_at']))}}</span>
                            </li>
                            <li class="align-items-center">
                                <span class="left">{{translate('refund_status')}}</span> <span>:</span> <span class="right">
                                    @if ($refund['status'] == 'pending')
                                        <span class="badge badge-secondary-2"> {{translate($refund['status'])}}</span>
                                    @elseif($refund['status'] == 'approved')
                                        <span class="badge badge--primary-2"> {{translate($refund['status'])}}</span>
                                    @elseif($refund['status'] == 'refunded')
                                        <span class="badge badge-success-2"> {{translate($refund['status'])}}</span>
                                    @elseif($refund['status'] == 'rejected')
                                        <span class="badge badge--danger-2"> {{translate($refund['status'])}}</span>
                                    @endif
                                </span>
                            </li>
                            <li class="align-items-center">
                                <span class="left">{{translate('payment_method')}} </span> <span>:</span> <span class="right">{{str_replace('_',' ',$order->payment_method)}}</span>
                            </li>
                            <li class="align-items-center">
                                <span class="left">{{translate('order_details')}} </span> <span>:</span> <span class="right"><a class="badge py-2 badge-soft-primary border border-primary px-2" href="{{route('admin.orders.details',['id'=>$order->id])}}">{{translate('view_details')}}</a></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card h-100 refund-details-card">
                    <div class="card-body">
                        <div class="gap-3 mb-4 d-flex justify-content-between flex-wrap align-items-center">
                            <h4 class="">{{translate('product_details')}}</h4>
                            <div class="d-flex flex-wrap gap-3">
                                @if($refund['status'] != 'refunded')
                                    @if($refund['status'] != 'rejected')
                                        <button class="btn btn-soft-danger p-2 px-3" data-toggle="modal" data-target="#rejectModal">
                                            {{ translate('reject') }}
                                        </button>
                                    @endif
                                    @if($refund['status'] != 'approved')
                                        <button class="btn btn-soft-primary p-2 px-3" data-toggle="modal" data-target="#approveModal">
                                            {{ translate('approve') }}
                                        </button>
                                    @endif
                                    <button class="btn btn-soft-success p-2 px-3" data-toggle="modal" data-target="#refundModal">
                                        {{ translate('refund') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="refund-details">
                            <div class="img">
                                <div class="onerror-image border rounded">
                                    <img src="{{getValidImage(path:  'storage/app/public/product/thumbnail/'.($refund->product ? $refund->product->thumbnail:''),type: 'backend-product')}}" alt="">
                                </div>
                            </div>
                            <div class="--content flex-grow-1">
                                <h4>
                                    @if ($refund->product!=null)
                                        <a href="{{route('admin.products.view',['addedBy'=>($refund->product->added_by =='seller'?'vendor' : 'in-house'),'id'=>$refund->product->id])}}">
                                            {{$refund->product->name}}
                                        </a>
                                    @else
                                        {{translate('product_name_not_found')}}
                                    @endif
                                </h4>
                                @if ($refund->orderDetails->variant)
                                    <div class="font-size-sm text-body">
                                        <strong><u>{{translate('variation')}}</u></strong>
                                        <span>:</span>
                                        <span class="font-weight-bold">{{$refund->orderDetails->variant}}</span>
                                    </div>
                                @endif
                                @if($refund->orderDetails->digital_file_after_sell)
                                    @php($downloadPath =dynamicStorage(path: 'storage/app/public/product/digital-product/'.$refund->orderDetails->digital_file_after_sell))
                                    <a href="{{file_exists( $downloadPath) ?  $downloadPath : 'javascript:' }}" class="btn btn-outline--primary btn-sm mt-3 {{file_exists( $downloadPath) ?  $downloadPath : 'download-path-not-found'}}" title="{{translate('download')}}">
                                        {{translate('download')}} <i class="tio-download"></i>
                                    </a>
                                @endif
                            </div>
                            <ul class="dm-info p-0 m-0 w-l-115">
                                <li>
                                    <span class="left">{{translate('QTY')}}</span>
                                    <span>:</span>
                                    <span class="right">
                                        <strong>
                                            {{$refund->orderDetails->qty}}
                                        </strong>
                                    </span>
                                </li>
                                <li>
                                    <span class="left">{{translate('total_price')}} </span>
                                    <span>:</span>
                                    <span class="right">
                                        <strong>
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $refund->orderDetails->price*$refund->orderDetails->qty), currencyCode: getCurrencyCode())}}
                                        </strong>
                                    </span>
                                </li>
                                <li>
                                    <span class="left">{{translate('total_discount')}} </span>
                                    <span>:</span>
                                    <span class="right">
                                        <strong>
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $refund->orderDetails->discount), currencyCode: getCurrencyCode())}}
                                        </strong>
                                    </span>
                                </li>
                                <li>
                                    <span class="left">{{translate('coupon_discount')}} </span>
                                    <span>:</span>
                                    <span class="right">
                                        <strong>
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $couponDiscount), currencyCode: getCurrencyCode())}}
                                        </strong>
                                    </span>
                                </li>

                                <li>
                                    <span class="left">{{translate('total_tax')}} </span>
                                    <span>:</span>
                                    <span class="right">
                                        <strong>
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $refund->orderDetails->tax), currencyCode: getCurrencyCode())}}
                                        </strong>
                                    </span>
                                </li>

                                <li>
                                    <span class="left">{{translate('subtotal')}} </span>
                                    <span>:</span>
                                    <span class="right">
                                        <strong>
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $subtotal), currencyCode: getCurrencyCode())}}
                                        </strong>
                                    </span>
                                </li>

                                <li>
                                    <span class="left">{{translate('refundable_amount')}} </span>
                                    <span>:</span>
                                    <span class="right">
                                        <strong>
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $refundAmount), currencyCode: getCurrencyCode())}}
                                        </strong>
                                    </span>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="{{$order?->seller ? 'col-sm-4' : 'col-sm-6'}}">
                <div class="card h-100 refund-details-card--2">
                    <div class="card-body">
                        <h4 class="mb-3 text-capitalize">{{translate('refund_reason_by_customer')}}</h4>
                        <p>
                            {{$refund->refund_reason}}
                        </p>
                        @if ($refund->images)
                            <div class="gallery grid-gallery">
                                @foreach (json_decode($refund->images) as $key => $photo)
                                    <a href="{{getValidImage(path: 'storage/app/public/refund/'.$photo,type:'backend-basic')}}"
                                       data-lightbox="mygallery" class="d-flex">
                                        <img src="{{getValidImage(path: 'storage/app/public/refund/'.$photo,type:'backend-basic')}}" width="65" alt="">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if($order?->seller)
                <div class="col-sm-4">
                    <div class="card h-100 refund-details-card--2">
                        <div class="card-body">
                            <h4 class="mb-3 text-capitalize">{{translate('vendor_info')}}</h4>
                            <div class="key-val-list d-flex flex-column gap-2 min-width--60px">
                                <div class="key-val-list-item d-flex gap-3">
                                    <span class="text-capitalize">{{translate('shop_name')}}</span>:
                                    <span>{{$order->seller?->shop->name ?? translate('no_data_found')}}</span>
                                </div>
                                <div class="key-val-list-item d-flex gap-3">
                                    <span class="text-capitalize">{{translate('email_address')}}</span>:
                                    <span>
                                        <a class="text-dark"
                                              href="mailto:{{ $order->seller->email }}">{{$order->seller?->email ?? translate('no_data_found') }}
                                        </a>
                                    </span>
                                </div>
                                <div class="key-val-list-item d-flex gap-3">
                                    <span class="text-capitalize">{{translate('phone_number')}} </span>:
                                    <span>
                                        <a class="text-dark"
                                           href="tel:{{ $order->seller->phone }}">{{$order->seller?->phone ?? translate('no_data_found') }}
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="{{$order?->seller ? 'col-sm-4' : 'col-sm-6'}}">
                <div class="card h-100 refund-details-card--2">
                    <div class="card-body">
                        <h4 class="mb-3 text-capitalize">{{translate('deliveryman_info')}}</h4>
                        <div class="key-val-list d-flex flex-column gap-2 min-width--60px">
                            @if($order->deliveryMan)
                                <div class="key-val-list-item d-flex gap-3">
                                    <span class="text-capitalize">{{translate('name')}}</span>:
                                    <span>{{$order->deliveryMan->f_name . ' ' .$order->deliveryMan->l_name}}</span>
                                </div>
                                <div class="key-val-list-item d-flex gap-3">
                                    <span class="text-capitalize">{{translate('email_address')}}</span>:
                                    <span>
                                        <a class="text-dark"
                                           href="mailto:{{ $order->deliveryMan->email }}">{{$order->deliveryMan?->email }}
                                        </a>
                                    </span>
                                </div>
                                <div class="key-val-list-item d-flex gap-3">
                                    <span class="text-capitalize">{{translate('phone_number')}} </span>:
                                    <span>
                                        <a class="text-dark"
                                           href="tel:{{ $order->deliveryMan->phone }}">{{$order->deliveryMan?->phone }}
                                        </a>
                                    </span>
                                </div>
                            @else
                                <div class="p-2 bg-light rounded">
                                    <div class="media m-1 gap-3">
                                        <img class="avatar rounded-circle" src="{{dynamicAsset(path: 'public/assets/back-end/img/delivery-man.png')}}" alt="{{translate('image')}}">
                                        <div class="media-body">
                                            <h5 class="mt-3">{{translate('no_delivery_man_assigned')}}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card refund-details-card--2">
                    <div class="card-body ">
                        <h4 class="mb-3">{{translate('refund_status_changed_log')}}</h4>
                        <div class="table-responsive datatable-custom">
                            <table
                                class="table table-hover text-center table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('changed_by')}}</th>
                                    <th>{{translate('Date')}}</th>
                                    <th>{{translate('status')}}</th>
                                    <th>{{translate('approved_/_rejected_note')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($refund->refundStatus as $key=>$status)
                                    <tr>
                                        <td>
                                            {{$key+1}}
                                        </td>
                                        <td class="text-capitalize">
                                            {{$status->change_by == 'seller' ? 'vendor' : $status->change_by}}
                                        </td>
                                        <td>{{date('d M Y, h:s:A',strtotime($refund['created_at']))}}</td>
                                        <td class="text-capitalize">
                                            {{translate($status->status)}}
                                        </td>
                                        <td class="text-break">
                                            <div class="word-break max-w-360px mx-auto">
                                                {{$status->message}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if(count($refund->refundStatus)==0)
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160" src="{{dynamicAsset(path: 'public/assets/back-end/svg/illustrations/sorry.svg')}}"
                                         alt="{{translate('image_description')}}">
                                    <p class="mb-0">{{ translate('no_data_to_show')}}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="rejectModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('admin.refund-section.refund.refund-status-update')}}" method="post" id="submit-rejected-form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$refund->id}}">
                        <input type="hidden" name="refund_status" value="rejected">
                        <div class="text-center">
                            <img class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/refund-reject.png')}}" alt="{{translate('refund_reject')}}">
                            <h4 class="mb-4 mx-auto max-w-283 text-capitalize">
                                {{translate('rejection_note')}}
                            </h4>
                        </div>
                        <textarea class="form-control text-area-max-min" placeholder="{{translate('please_write_the_reject_reason').'...'}}" name="rejected_note" rows="3"></textarea>
                        <div class="d-flex flex-wrap justify-content-end gap-3 mt-3">
                            <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">{{ translate('close') }}</button>
                            <button type="button" class="btn btn--primary form-submit" data-form-id="submit-rejected-form" data-message="{{translate('want_to_reject_this_refund_request').'?'}}"  data-redirect-route="{{route('admin.refund-section.refund.list',['status'=>$refund['status']])}}">{{ translate('submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approveModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('admin.refund-section.refund.refund-status-update')}}" method="post" id="submit-approve-form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$refund->id}}">
                        <input type="hidden" name="refund_status" value="approved">
                        <div class="text-center ">
                            <img class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/refund-approve.png')}}" alt="{{translate('refund_approve')}}">
                            <h4 class="mb-4 mx-auto max-w-283 text-capitalize">
                                {{translate('approval_note')}}
                            </h4>
                        </div>
                        <textarea class="form-control text-area-max-min" placeholder="{{translate('please_write_the_approve_reason').'...'}}" name="approved_note" rows="3"></textarea>
                        <div class="d-flex flex-wrap justify-content-end gap-3 mt-3">
                            <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">{{ translate('close') }}</button>
                            <button type="button" class="btn btn--primary form-submit" data-form-id="submit-approve-form" data-message="{{translate('want_to_approve_this_refund_request').'?'}}" data-redirect-route="{{route('admin.refund-section.refund.list',['status'=>$refund['status']])}}">{{ translate('submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="refundModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('admin.refund-section.refund.refund-status-update')}}" method="post" id="submit-refund-form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$refund->id}}">
                        <input type="hidden" name="refund_status" value="refunded">
                        <div class="text-center">
                            <img class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/refund-approve.png')}}" alt="{{translate('refund_approve')}}">
                            <h4 class="mb-4 mx-auto max-w-283">
                                {{translate('once_you_refund_that_refund_request').','.translate('then_you_won’t_able_change_any_status')}}
                            </h4>
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="">{{translate('payment_method')}}</label>
                            <select class="form-control" name="payment_method">
                                <option value="cash">{{translate('cash')}}</option>
                                <option value="digitally_paid">{{translate('digitally_paid')}}</option>
                                @if ($walletStatus == 1 && $walletAddRefund == 1)
                                    <option value="customer_wallet">{{translate('customer_wallet')}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="input-label d-flex" for="">{{translate('payment_info')}}
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                      data-placement="right"
                                      title="{{translate('please_enter_the_payment_information_according_to_your_chosen_payment_method').'.'.translate('without_a_proper_payment_info,you_cannot_change_the_Refund_Status').'.'}}">
                                    <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                         alt="">
                                </span>
                            </label>
                            <input type="text" class="form-control" name="payment_info" placeholder="{{translate('ex').' : '.'Paypal'}}">
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-3 mt-3">
                            <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">{{ translate('close') }}</button>
                            <button type="button" class="btn btn--primary form-submit" data-form-id="submit-refund-form" data-message="{{translate('want_to_refund_this_refund_request').'?'}}" data-redirect-route="{{route('admin.refund-section.refund.list',['status'=>$refund['status']])}}">{{ translate('submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/refund.js')}}"></script>
@endpush
