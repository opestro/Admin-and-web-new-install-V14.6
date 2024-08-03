<div class="modal fade" id="refundModal{{$id}}" tabindex="-1" aria-labelledby="refundRequestModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="text-center text-capitalize flex-grow-1 m-0">{{translate('refund_request')}}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body d-flex flex-column gap-3">
                <div class="border rounded bg-white">
                    <div class="p-3">
                        <div class="media gap-3">
                            @if (isset($order_details->product))
                                <div class="position-relative">
                                    <img class="d-block get-view-by-onclick"
                                         data-link="{{ route('product',$order_details->product['slug'])}}"
                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$order_details->product['thumbnail'], type: 'product') }}"
                                         alt="{{ translate('product') }}" width="100">

                                    @if($order_details->product->discount > 0)
                                        <span class="price-discount badge badge-primary position-absolute top-1 left-1">
                                            @if ($order_details->product->discount_type == 'percent')
                                                -{{round($order_details->product->discount)}}%
                                            @elseif($order_details->product->discount_type =='flat')
                                                -{{ webCurrencyConverter(amount: $order_details->product->discount) }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                <div class="media-body">

                                    <a href="{{route('product',[$order_details->product['slug']])}}">
                                        <h6 class="mb-1">
                                            {{Str::limit($product['name'],40)}}
                                        </h6>
                                    </a>
                                    @if($order_details->variant)
                                        <div>
                                            <small class="text-muted">
                                                {{translate('variant')}} : {{$detail->variant}}
                                            </small>
                                        </div>
                                    @endif

                                    <div>
                                        <small class="text-muted">{{translate('qty')}} : {{$detail->qty}}</small>
                                    </div>
                                    <div>
                                        <small class="text-muted">{{translate('price')}} :
                                            <span
                                                class="text-primary">{{ webCurrencyConverter(amount: $order_details->price)}}</span>
                                        </small>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            {{ $order_details->created_at->format('d M Y, h:i a') }}
                                        </small>
                                    </div>
                                </div>
                            @else
                                <div class="media-body">
                                    <h6 class="mb-1">{{translate('product_not_found')}}</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="border rounded bg-white">
                    <div class="p-3 fs-12 d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between gap-2">
                            <div class="text-muted text-capitalize">{{translate('total_price')}}</div>
                            <div>{{ webCurrencyConverter(amount: $order_details->price) }}</div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="text-muted text-capitalize">{{translate('product_discount')}}</div>
                            <div>-{{ webCurrencyConverter(amount: $order_details->discount) }}</div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="text-muted">vat/tax</div>
                            <div>{{ webCurrencyConverter(amount: $order_details->tax) }}</div>
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
                        <div class="d-flex justify-content-between gap-2">
                            <div class="text-muted text-capitalize">{{translate('sub_total')}}</div>
                            <div>{{ webCurrencyConverter(amount: $subtotal) }}</div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="text-muted text-capitalize">{{translate('coupon_discount')}}</div>
                            <div> -{{ webCurrencyConverter(amount: $coupon_discount) }}</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between gap-2 border-top py-2 px-3 fs-12">
                        <div
                            class="text-muted font-weight-bold text-capitalize">{{translate('total_refundable_amount')}}</div>
                        <div class="font-weight-bold">{{ webCurrencyConverter(amount: $refund_amount) }}</div>
                    </div>
                </div>

                <form action="{{route('refund-store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <h6 class="d-flex gap-2 align-items-center cursor-pointer" data-toggle="collapse"
                        data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        {{translate('give_a_refund_reason')}} <i class="tio-chevron-down"></i>
                    </h6>
                    <div class="collapse" id="collapseExample">
                        <input type="hidden" name="order_details_id" value="{{$order_details->id}}">
                        <input type="hidden" name="amount" value="{{$refund_amount}}">
                        <textarea rows="4" class="form-control" name="refund_reason"
                                  placeholder="{{translate('write_here')}}..."></textarea>
                    </div>

                    <div class="mt-3">
                        <h6>{{translate('upload_images')}}</h6>

                        <div class="mt-2">
                            <div class="mt-2">
                                <div class="d-flex flex-wrap upload_images_area">

                                    <div class="d-flex flex-wrap filearray"></div>
                                    <div class="selected-files-container"></div>

                                    <label class="py-0 d-flex align-items-center m-0 cursor-pointer">
                                        <span class="position-relative">
                                            <img class="border rounded border-primary-light h-70px"
                                                 src="{{ getValidImage(path: 'public/assets/front-end/img/image-place-holder.png', type: 'logo') }}"
                                                 alt="">
                                        </span>
                                        <input type="file" class="msgfilesValue h-100 position-absolute w-100 " hidden
                                               multiple accept=".jpg, .png, .jpeg, .gif, .bmp, .webp |image/*">
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn--primary text-capitalize">
                            {{translate('send_request')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
