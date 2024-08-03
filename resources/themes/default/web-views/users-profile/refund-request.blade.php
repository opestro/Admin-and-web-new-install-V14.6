@extends('layouts.front-end.app')

@section('title',translate('refund_request'))

@push('css_or_js')
    <link href="{{theme_asset(path: 'public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ theme_asset(path: 'public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="container rtl text-align-direction">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 float-start headerTitle">
                    {{translate('refund_request')}}
                </h1>
            </div>
        </div>
    </div>

    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl text-align-direction">
        <div class="row g-3">

            @include('web-views.partials._profile-aside')

            @php($product = App\Models\Product::find($order_details->product_id))
            @php($order = App\Models\Order::find($order_details->order_id))
            <section class="col-lg-9 col-md-8">
                <div class="card box-shadow-sm">
                    <div class="overflow-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3 col-sm-2">
                                        <img class="d-block"
                                             src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                                             alt="{{ translate('product') }}" width="60">
                                    </div>
                                    <div class="col-9 col-sm-7 text-left">
                                        <p>{{$product['name']}}</p>
                                        <span>{{translate('variant')}} : </span>
                                        {{$order_details->variant}}
                                    </div>
                                    <div class="col-4 col-sm-3 text-left d-flex flex-column pl-0 mt-2 mt-sm-0 pl-sm-5">
                                        <span>{{translate('QTY')}} : {{$order_details->qty}}</span>
                                        <span>{{translate('price')}} : {{ webCurrencyConverter(amount: $order_details->price) }}</span>
                                        <span>{{translate('discount')}} : {{ webCurrencyConverter(amount: $order_details->discount) }}</span>
                                        <span>{{translate('tax')}} : {{ webCurrencyConverter(amount: $order_details->tax) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            $total_product_price = 0;
                            foreach ($order->details as $key => $or_d) {
                                $total_product_price += ($or_d->qty * $or_d->price) + $or_d->tax - $or_d->discount;
                            }
                            $refund_amount = 0;
                            $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

                            $coupon_discount = ($order->discount_amount * $subtotal) / $total_product_price;

                            $refund_amount = $subtotal - $coupon_discount;
                        ?>

                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="row text-center">
                                    <span class="col-sm-2">{{translate('subtotal')}}: {{ webCurrencyConverter(amount: $subtotal) }}</span>
                                    <span class="col-sm-5">{{translate('coupon_discount')}}: {{ webCurrencyConverter(amount: $coupon_discount) }}</span>
                                    <span class="col-sm-5">{{translate('total_refundable_amount')}}:{{ webCurrencyConverter(amount: $refund_amount) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="row">
                                    <form action="{{route('refund-store')}}" method="post"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="order_details_id" value="{{$order_details->id}}">
                                        <input type="hidden" name="amount" value="{{$refund_amount}}">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="input-label"
                                                       for="name">{{translate('refund_reason')}}</label>
                                                <textarea class="form-control" name="refund_reason" cols="120"
                                                          required>{{old('details')}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">{{translate('attachment')}}</label>
                                                <div class="row coba"></div>
                                            </div>

                                        </div>
                                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{theme_asset(path: 'public/assets/front-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        "use strict";

        $(function () {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{theme_asset(path: 'public/assets/front-end/img/image-place-holder.png')}}',
                    width: '100%'
                },
                dropFileLabel: "{{translate('drop_here')}}",
                onAddRow: function (index, file) {
                },
                onRenderedPreview: function (index) {
                },
                onRemoveRow: function (index) {
                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate('input_png_or_jpg')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate('file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
