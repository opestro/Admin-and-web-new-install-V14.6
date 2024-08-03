<?php

namespace App\Enums\ViewPaths\Admin;

enum DatabaseSetting
{

    const VIEW = [
        URI => 'db-index',
        VIEW => 'admin-views.business-settings.db-index'
    ];

    const DELETE = [
        URI => 'db-clean',
        VIEW => ''
    ];

}
