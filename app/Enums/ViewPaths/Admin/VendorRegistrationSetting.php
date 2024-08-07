<?php

namespace App\Enums\ViewPaths\Admin;

enum VendorRegistrationSetting
{
    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.business-settings.vendor-registration-setting.header'
    ];
    const WITH_US = [
        URI => 'with-us',
        VIEW => 'admin-views.business-settings.vendor-registration-setting.with-us'
    ];
    const BUSINESS_PROCESS = [
        URI => 'business-process',
        VIEW => 'admin-views.business-settings.vendor-registration-setting.business-process'
    ];
    const DOWNLOAD_APP = [
        URI => 'download-app',
        VIEW => 'admin-views.business-settings.vendor-registration-setting.download-app'
    ];
    const FAQ = [
        URI => 'faq',
        VIEW => 'admin-views.business-settings.vendor-registration-setting.faq'
    ];

}
