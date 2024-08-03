<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between flex-grow-1 d-lg-block">
        <h5 class="">{{translate('order').'#'}}{{$order['id']}} </h5>
        <p class="fs-12">{{date('d M, Y h:i A',strtotime($order->created_at))}}</p>
    </div>
    <div class="">
        <div class="d-none d-lg-flex gap-3 justify-content-end">
            @if($order->order_status=='delivered' &&  $order->order_type == 'default_type')
                <a href="javascript:"
                   class="btn btn-primary rounded-pill order-again"
                   data-action="{{route('cart.order-again')}}"
                   data-order-id = "{{$order['id']}}">{{ translate('reorder') }}</a>
            @endif
        </div>

        <div class="d-none d-lg-flex gap-3 align-items-center mt-2">
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
        <div class="d-none d-lg-flex gap-3 align-items-center mt-2">
            <h6 class="text-capitalize">{{translate('payment_status')}}</h6>
            <div
                class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}"> {{ translate($order['payment_status']) }}</div>
        </div>
        @if($order->order_type == 'default_type' && getWebConfig(name: 'order_verification'))
            <div class="d-none d-lg-flex gap-3 align-items-center mt-2">
                <h6 class="text-capitalize">{{translate('verification_code')}}</h6>
                <div class="badge bg-primary rounded-pill"> {{ $order['verification_code'] }}</div>
            </div>
        @endif
        @if($order->payment_method == 'offline_payment' && isset($order->offlinePayments))
            @foreach ($order->offlinePayments->payment_info as $key=>$item)
                @if ($key != 'method_id' && $key != 'method_name')
                    <div class="d-none d-lg-flex gap-2 align-items-center mt-2">
                        <h6 class="text-nowrap">{{translate($key).':'}}</h6>
                        <div class="text-dark">{{ $item }}</div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

<div class="mt-4">
    <nav>
        <div class="nav nav-nowrap gap-3 gap-xl-4 nav--tabs hide-scrollbar">
            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
               class="{{Request::is('account-order-details')  ? 'active' :''}} text-capitalize">{{translate('order_summary')}}</a>
            <a href="{{ route('account-order-details-vendor-info', ['id'=>$order->id]) }}"
               class="{{Request::is('account-order-details-vendor-info')  ? 'active' :''}} text-capitalize">{{translate('vendor_info')}}</a>
            <a href="{{ route('account-order-details-delivery-man-info', ['id'=>$order->id]) }}"
               class="{{Request::is('account-order-details-delivery-man-info')  ? 'active' :''}} text-capitalize">{{translate('delivery_man_info')}}</a>
            <a href="{{route('track-order.order-wise-result-view',['order_id'=>$order['id']])}}"
               class="{{Request::is('track-order/order-wise-result-view*')  ? 'active' :''}} text-capitalize">{{translate('track_order')}}</a>
        </div>
    </nav>
</div>
