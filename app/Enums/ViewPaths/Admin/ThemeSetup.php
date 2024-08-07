<?php

namespace App\Enums\ViewPaths\Admin;

enum ThemeSetup
{
    const VIEW = [
        URI => 'setup',
        VIEW => 'admin-views.business-settings.theme-setup'
    ];

    const UPLOAD = [
        URI => 'install',
        VIEW => ''
    ];

    const ACTIVE = [
        URI => 'activation',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'publish',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const NOTIFY_VENDOR = [
        URI => 'notify-all-the-vendors',
        VIEW => ''
    ];

    const INFO_MODAL = [
        URI => '',
        VIEW => 'admin-views.business-settings.partials.theme-information-modal-data'
    ];

    const ACTIVE_MODAL = [
        URI => '',
        VIEW => 'admin-views.business-settings.partials.theme-activate-modal-data'
    ];

}
