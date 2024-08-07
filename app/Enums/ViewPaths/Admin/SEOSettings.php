<?php

namespace App\Enums\ViewPaths\Admin;

enum SEOSettings
{
    const WEB_MASTER_TOOL = [
        URI => 'web-master-tool',
        VIEW => 'admin-views.business-settings.seo-settings.web-master-tool',
    ];

    const ROBOT_TXT = [
        URI => 'robot-txt',
        VIEW => 'admin-views.business-settings.seo-settings.robot-txt'
    ];

}
