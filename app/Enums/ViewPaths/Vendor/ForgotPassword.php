<?php

namespace App\Enums\ViewPaths\Vendor;

enum ForgotPassword
{
    const INDEX = [
      URI => 'index',
      VIEW => 'vendor-views.auth.forgot-password.index'
    ];
    const OTP_VERIFICATION = [
      URI => 'otp-verification',
      VIEW => 'vendor-views.auth.forgot-password.verify-otp-view'
    ];
    const RESET_PASSWORD = [
        URI => 'reset-password',
        URL => 'vendor/auth/forgot-password/reset-password',
        ROUTE =>'vendor.auth.reset-password',
        VIEW => 'vendor-views.auth.forgot-password.reset-password-view'
    ];

}
