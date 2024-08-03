<?php

namespace App\Enums\ViewPaths\Admin;

enum FeaturesSection
{

    const VIEW = [
        URI => 'features-section',
        VIEW => 'admin-views.business-settings.features-section.view'
    ];

    const UPDATE = [
        URI => 'features-section/submit',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'features-section/icon-remove',
        VIEW => ''
    ];

    const COMPANY_RELIABILITY = [
        URI => 'company-reliability',
        VIEW => 'admin-views.business-settings.company-reliability.index'
    ];

}
