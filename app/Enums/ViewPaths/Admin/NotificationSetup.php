<?php

namespace App\Enums\ViewPaths\Admin;

enum NotificationSetup
{
    const INDEX = [
        URI => 'index',
    ];
    const CUSTOMER = [
        VIEW => 'admin-views.notification-setup.customer',
    ];
    const VENDOR = [
        VIEW => 'admin-views.notification-setup.vendor',
    ];
    const DELIVERYMAN = [
        VIEW => 'admin-views.notification-setup.delivery-man',
    ];
}
