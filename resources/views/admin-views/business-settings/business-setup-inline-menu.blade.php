@php
    use App\Enums\ViewPaths\Admin\BusinessSettings;
    use App\Enums\ViewPaths\Admin\PaymentMethod;
@endphp
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/web-config') ?'active':'' }}"><a
                href="{{route('admin.business-settings.web-config.index')}}">{{translate('general')}}</a></li>
        <li class="text-capitalize {{ Request::is('admin/business-settings/payment-method/'.PaymentMethod::PAYMENT_OPTION[URI]) ?'active':'' }}">
            <a
                href="{{route('admin.business-settings.payment-method.payment-option')}}">{{translate('payment_options')}}</a>
        </li>
        <li class="{{ Request::is('admin/product-settings') ?'active':'' }}"><a
                href="{{ route('admin.product-settings.index') }}">{{translate('products')}}</a>
        </li>
        <li class="text-capitalize {{ Request::is('admin/business-settings/priority-setup') ?'active':'' }}">
            <a href="{{route('admin.business-settings.priority-setup.index')}}">{{translate('priority_setup')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/order-settings/'.BusinessSettings::ORDER_VIEW[URI]) ?'active':'' }}">
            <a href="{{route('admin.business-settings.order-settings.index')}}">{{translate('orders')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/vendor-settings') ?'active':'' }}"><a
                href="{{route('admin.business-settings.vendor-settings.index')}}">{{translate('vendors')}}</a></li>
        <li class="{{ Request::is('admin/customer/customer-settings') ?'active':'' }}"><a
                href="{{route('admin.customer.customer-settings')}}">{{translate('customers')}}</a></li>
        <li class="text-capitalize {{ Request::is('admin/business-settings/delivery-man-settings') ?'active':'' }}"><a
                href="{{route('admin.business-settings.delivery-man-settings.index')}}">{{translate('delivery_men')}}</a>
        </li>
        <li class="text-capitalize {{ Request::is('admin/business-settings/shipping-method/index') ?'active':'' }}"><a
                href="{{route('admin.business-settings.shipping-method.index')}}">{{translate('shipping_Method')}}</a>
        </li>
        <li class="text-capitalize {{ Request::is('admin/business-settings/delivery-restriction') ? 'active':'' }}"><a
                href="{{ route('admin.business-settings.delivery-restriction.index') }}">{{translate('delivery_restriction')}}</a>
        </li>
        <li class="text-capitalize {{ Request::is('admin/business-settings/invoice-settings') ? 'active':'' }}"><a
                href="{{ route('admin.business-settings.invoice-settings.index') }}">{{translate('invoice')}}</a>
        </li>
    </ul>
</div>
