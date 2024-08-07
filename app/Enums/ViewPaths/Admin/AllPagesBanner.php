<?php

namespace App\Enums\ViewPaths\Admin;

enum AllPagesBanner
{
    const LIST = [
        URI => 'all-pages-banner',
        VIEW => 'admin-views.theme-features.all-pages-banner.view'
    ];

    const ADD = [
        URI => 'all-pages-banner-store',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'all-pages-banner-update',
        VIEW => 'admin-views.theme-features.all-pages-banner.edit'
    ];

    const DELETE = [
        URI => 'all-pages-banner-delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'all-pages-banner-status',
        VIEW => ''
    ];

}
