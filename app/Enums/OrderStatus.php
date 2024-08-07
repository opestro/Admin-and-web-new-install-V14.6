<?php

namespace App\Enums;

enum OrderStatus
{
    const PENDING = 'pending';
    const CONFIRMED= 'confirmed';
    const PROCESSING= 'processing';
    const OUT_FOR_DELIVERY= 'out_for_delivery';
    const DELIVERED= 'delivered';
    const RETURNED= 'returned';
    const FAILED= 'failed';
    const CANCELED= 'canceled';

    const LIST =[
        OrderStatus::PENDING ,
        OrderStatus::CONFIRMED ,
        OrderStatus::PROCESSING ,
        OrderStatus::OUT_FOR_DELIVERY ,
        OrderStatus::DELIVERED ,
        OrderStatus::RETURNED ,
        OrderStatus::FAILED ,
        OrderStatus::CANCELED ,
    ];
}
