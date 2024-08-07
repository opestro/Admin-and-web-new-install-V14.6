<?php

namespace App\Enums\ViewPaths\Admin;

enum DeliveryManCash
{
    const LIST = [
        URI => 'collect-cash',
        VIEW => 'admin-views.delivery-man.earning-statement.collect-cash'
    ];

    const ADD = [
        URI => 'cash-receive',
        VIEW => ''
    ];

}
