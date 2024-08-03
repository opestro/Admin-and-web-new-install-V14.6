<?php

namespace App\Enums\ViewPaths\Web;

enum UserLoyalty
{
    const LOYALTY = [
        URI => 'loyalty',
        VIEW => ''
    ];

    const EXCHANGE_CURRENCY = [
        URI => 'loyalty-exchange-currency',
        VIEW => ''
    ];

    const GET_CURRENCY_AMOUNT = [
        URI => 'ajax-loyalty-currency-amount',
        VIEW => ''
    ];

}
