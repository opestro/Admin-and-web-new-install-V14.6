<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/delivery-man/earning-statement-overview/*') ?'active':'' }}"><a href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $deliveryMan['id']]) }}">{{translate('overview')}}</a></li>
        <li class="{{ Request::is('admin/delivery-man/order-history-log*') ?'active':'' }}"><a href="{{ route('admin.delivery-man.order-history-log', ['id' => $deliveryMan['id']]) }}">{{translate('order_History_Log')}}</a></li>
        <li class="{{ Request::is('admin/delivery-man/order-wise-earning*') ?'active':'' }}"><a href="{{ route('admin.delivery-man.order-wise-earning', ['id' => $deliveryMan['id']]) }}">{{translate('earning')}}</a></li>
    </ul>
</div>
