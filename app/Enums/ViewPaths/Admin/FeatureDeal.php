<?php

namespace App\Enums\ViewPaths\Admin;

enum FeatureDeal
{

    const LIST = [
        URI => 'feature',
        VIEW => 'admin-views.deal.feature-index'
    ];

    const UPDATE = [
        URI => 'feature-update',
        VIEW => 'admin-views.deal.feature-update'
    ];

    const STATUS = [
        URI => 'feature-status',
        VIEW => ''
    ];
}
