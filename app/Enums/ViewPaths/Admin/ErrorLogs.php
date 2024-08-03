<?php

namespace App\Enums\ViewPaths\Admin;

enum ErrorLogs
{
    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.business-settings.seo-settings.error-logs'
    ];
    const DELETE_SELECTED_ERROR_LOGS = [
        URI => 'delete-selected-error-logs',
        VIEW => ''
    ];
}
