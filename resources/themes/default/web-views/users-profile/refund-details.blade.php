<div class="border rounded bg-white">
    <div class="p-3">
        <div class="media gap-3">

            @if (isset($refund->product))
                @php($product = $refund->product)
                <div class="position-relative">
                    <img class="d-block get-view-by-onclick" data-link="{{route('product',$product['slug'])}}"
                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}"
                         alt="{{ translate('product') }}" width="100">

                    @if($product->discount > 0)
                        <span class="price-discount badge badge-primary position-absolute top-1 left-1">
                                @if ($product->discount_type == 'percent')
                                {{round($product->discount)}}%
                            @elseif($product->discount_type =='flat')
                                {{ webCurrencyConverter(amount: $product->discount) }}
                            @endif
                        </span>
                    @endif
                </div>

                <div class="media-body">
                    <a href="{{route('product',[$product['slug']])}}">
                        <h6 class="mb-1">
                            {{Str::limit($product['name'],40)}}
                        </h6>
                    </a>
                    @if($order_details->variant)
                        <div><small class="text-muted">{{translate('variant')}} : {{$order_details->variant}}</small>
                        </div>
                    @endif

                    <div><small class="text-muted">{{translate('qty')}} : {{$order_details->qty}}</small></div>
                    <div><small class="text-muted">{{translate('price')}} : <span class="text-primary">
                        {{ webCurrencyConverter(amount: $order_details->price) }}
                        </span></small>
                    </div>
                    <div><small class="text-muted">{{ $order_details->created_at->format('d M Y, h:i a') }}</small>
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
            <div class="text-muted">{{translate('vat')}}/{{translate('tax')}}</div>
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
        <div class="text-muted font-weight-bold text-capitalize">{{translate($refund->status == 'refunded' ? 'total_refunded_amount' : 'total_refundable_amount')}}</div>
        <div class="font-weight-bold">{{ webCurrencyConverter(amount: $refund_amount) }}</div>
    </div>

</div>
<div class="border rounded bg-white">
    <div class="d-flex gap-2 border-top py-2 px-3 fs-12">
        <div class="text-muted font-weight-bold text-capitalize">{{translate('refund_status')}} :</div>
        <p class="font-weight-bold">
            @if ($refund->status == 'pending')
                <span class="text-capitalize __color-coral"> {{translate($refund->status)}}</span>
            @elseif($refund->status == 'approved')
                <span class="text-capitalize __color-1573ff"> {{translate($refund->status)}}</span>
            @elseif($refund->status == 'refunded')
                <span class="text-capitalize __color-01ff2cfa"> {{translate($refund->status)}}</span>
            @elseif($refund->status == 'rejected')
                <span class="text-capitalize __color-ff2a05fa"> {{translate($refund->status)}}</span>
            @endif
        </p>

    </div>
    <div class="d-flex gap-2 px-3 fs-12">
        <div class="text-muted font-weight-bold text-capitalize text-nowrap">{{translate('refund_reason')}} :</div>
        <p class="font-weight-bold text-justify">{{$refund->refund_reason}}</p>
    </div>

</div>
@if ($refund->images !=null)
    <div class="mt-3">
        <h6>{{translate('upload_images')}}</h6>

        <div class="mt-2">
            <div class="d-flex gap-2 flex-wrap">
                @foreach (json_decode($refund->images) as $key => $photo)
                    <a data-lightbox="mygallery" href="{{dynamicStorage(path: 'storage/app/public/refund')}}/{{$photo}}">
                        <img class="border rounded border-primary-light"
                             src="{{ getValidImage(path: 'storage/app/public/refund/'.$photo, type: 'product') }}"
                             alt="{{ translate('product') }}" width="60">
                    </a>
                @endforeach
            </div>
            <p class="text-muted fs-12 mt-2">{{count(json_decode($refund->images))}} {{translate('files_Uploaded')}}</p>
        </div>
    </div>
@endif
