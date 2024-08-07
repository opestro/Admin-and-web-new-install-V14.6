<?php

namespace App\Enums\ViewPaths\Admin;

enum Profile
{
    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.profile.index'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.profile.update-view'
    ];
}
