<?php

namespace App\Enums\ViewPaths\Vendor;

enum Auth
{
    const VENDOR_LOGIN = [
        URI => 'login',
        VIEW => 'vendor-views.auth.login',
    ];

    const VENDOR_LOGOUT = [
        URI => 'vendor.auth.login',
        VIEW => 'vendor-views.auth.login'
    ];
    const RECAPTURE = [
        URI => 'recaptcha',
    ];
    const VENDOR_REGISTRATION = [
        URI => 'index',
        VIEW => 'seller_registration'
    ];


}
