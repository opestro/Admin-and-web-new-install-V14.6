<?php

namespace App\Enums\ViewPaths\Admin;

enum EmailTemplate
{
    const VIEW = [
        URI => '/',
        VIEW => 'admin-views.business-settings.email-template.index'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => ''
    ];
    const UPDATE_STATUS = [
        URI => 'update-status',
        VIEW => ''
    ];

}
