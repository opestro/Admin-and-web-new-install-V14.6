<?php

namespace App\Enums\ViewPaths\Admin;

enum OfflinePaymentMethod
{
    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.business-settings.offline-payment-method.index',
        ROUTE => 'admin.business-settings.offline-payment-method.index'
    ];
    const ADD= [
        URI => 'add',
        VIEW => 'admin-views.business-settings.offline-payment-method.add-view',
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.business-settings.offline-payment-method.update-view',
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => '',
    ];
    const DELETE = [
        URI => 'delete',
    ];
}
