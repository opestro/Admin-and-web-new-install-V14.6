<?php

namespace App\Enums\ViewPaths\Admin;

enum Notification
{
    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.notification.index',
        ROUTE => 'admin.notification.index',
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.notification.update-view'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => ''
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
    const RESEND_NOTIFICATION = [
        URI => 'resend-notification',
        VIEW => ''
    ];

}
