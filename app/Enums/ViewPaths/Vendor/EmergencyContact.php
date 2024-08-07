<?php

namespace App\Enums\ViewPaths\Vendor;

enum EmergencyContact
{
    const INDEX = [
        URI => 'index',
        VIEW => 'vendor-views.delivery-man.emergency-contact.index',
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'vendor-views.delivery-man.emergency-contact._update-emergency-contact'
    ];

}
