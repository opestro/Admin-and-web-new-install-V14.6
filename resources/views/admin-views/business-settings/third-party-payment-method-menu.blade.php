<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/payment-method') ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.payment-method.index')}}">{{translate('digital_payment_methods')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/offline-payment-method/*') ?'active':'' }}"><a class="text-capitalize" href="{{route('admin.business-settings.offline-payment-method.index')}}">{{translate('offline_payment_methods')}}</a></li>
    </ul>
</div>
