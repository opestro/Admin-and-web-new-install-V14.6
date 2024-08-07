<?php

namespace App\Enums\ViewPaths\Admin;

enum DeliveryRestriction
{
    const VIEW = [
        URI => '',
        VIEW => 'admin-views.business-settings.delivery-restriction'
    ];

    const ADD = [
        URI => 'add-delivery-country',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delivery-country-delete',
        VIEW => ''
    ];

    const ZIPCODE_ADD = [
        URI => 'add-zip-code',
        VIEW => ''
    ];

    const ZIPCODE_DELETE = [
        URI => 'zip-code-delete',
        VIEW => ''
    ];

    const COUNTRY_RESTRICTION = [
        URI => 'country-restriction-status-change',
        VIEW => ''
    ];

    const ZIPCODE_RESTRICTION = [
        URI => 'zipcode-restriction-status-change',
        VIEW => ''
    ];
}
