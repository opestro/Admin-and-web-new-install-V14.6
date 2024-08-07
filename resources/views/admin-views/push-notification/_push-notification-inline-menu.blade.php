<div class="inline-page-menu">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/push-notification/index') ?'active':'' }}">
            <a href="{{route('admin.push-notification.index')}}" class="text-capitalize">
                <i class="tio-notifications-on-outlined"></i>
                {{translate('push_notification')}}
            </a>
        </li>
        <li class="{{ Request::is('admin/push-notification/firebase-configuration') ?'active':'' }}">
            <a href="{{route('admin.push-notification.firebase-configuration')}}" class="text-capitalize">
                <i class="tio-cloud-outlined"></i>
                {{translate('firebase_configuration')}}
            </a>
        </li>
    </ul>
</div>
