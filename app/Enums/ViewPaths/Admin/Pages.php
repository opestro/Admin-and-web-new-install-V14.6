<?php

namespace App\Enums\ViewPaths\Admin;

enum Pages
{
    const VIEW = [
        URI => 'page',
        VIEW => 'admin-views.business-settings.page.page'
    ];

    const TERMS_CONDITION = [
        URI => 'terms-condition',
        VIEW => 'admin-views.business-settings.page.terms-condition'
    ];

    const PRIVACY_POLICY = [
        URI => 'privacy-policy',
        VIEW => 'admin-views.business-settings.page.privacy-policy'
    ];

    const ABOUT_US = [
        URI => 'about-us',
        VIEW => 'admin-views.business-settings.page.about-us'
    ];


}
