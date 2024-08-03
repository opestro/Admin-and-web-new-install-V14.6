<?php

namespace App\Enums\ViewPaths\Admin;

enum PushNotification
{
    const INDEX = [
        URI => 'index',
        VIEW => 'admin-views.push-notification.index'
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => ''
    ];
    const FIREBASE_CONFIGURATION = [
        URI => 'firebase-configuration',
        VIEW => 'admin-views.push-notification.firebase-configuration-view'
    ];
    const UPDATE_MESSAGES = [
        URI => 'update-fcm-messages',
        VIEW => ''
    ];

}
