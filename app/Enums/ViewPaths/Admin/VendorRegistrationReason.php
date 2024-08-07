<?php

namespace App\Enums\ViewPaths\Admin;

enum VendorRegistrationReason
{
    const ADD = [
        URI => 'add',
        VIEW => ''
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.business-settings.vendor-registration-setting.partial.update-reason-modal'
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => 'admin-views.business-settings.vendor-registration-setting.partial.update-reason-modal'
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
}
