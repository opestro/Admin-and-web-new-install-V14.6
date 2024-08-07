
<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/notification-setup/index/customer') ?'active':'' }}">
            <a class="text-capitalize"
                href="{{route('admin.notification-setup.index',['type'=>'customer'])}}">{{translate('customer')}}</a>
        </li>
        <li class="{{ Request::is('admin/notification-setup/index/vendor') ?'active':'' }}"><a
                class="text-capitalize"
                href="{{route('admin.notification-setup.index',['type'=>'vendor'])}}">{{translate('vendor')}}</a>
        </li>
        <li class="{{ Request::is('admin/notification-setup/index/deliveryMan') ?'active':'' }}"><a class="text-capitalize"
                href="{{route('admin.notification-setup.index',['type'=>'deliveryMan'])}}">{{translate('deliveryMan')}}</a>
        </li>
    </ul>
</div>
