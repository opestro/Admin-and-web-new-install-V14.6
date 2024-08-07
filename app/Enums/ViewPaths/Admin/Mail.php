<?php

namespace App\Enums\ViewPaths\Admin;

enum Mail
{
    const VIEW = [
        URI => '',
        VIEW => 'admin-views.business-settings.mail.index',
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => '',
    ];

    const SEND = [
        URI => 'send',
        VIEW => '',
    ];

    const UPDATE_SENDGRID = [
        URI => 'update-sendgrid',
        VIEW => '',
    ];

}
