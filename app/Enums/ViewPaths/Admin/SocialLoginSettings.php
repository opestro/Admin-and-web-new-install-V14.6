<?php

namespace App\Enums\ViewPaths\Admin;

enum SocialLoginSettings
{
    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.business-settings.social-login.view'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => ''
    ];

    const APPLE_UPDATE = [
        URI => 'update-apple',
        VIEW => ''
    ];

}
