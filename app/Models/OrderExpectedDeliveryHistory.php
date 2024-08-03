<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $user_type
 * @property string $expected_delivery_date
 * @property string|null $cause
 */
class OrderExpectedDeliveryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'user_type',
        'expected_delivery_date',
        'cause',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'user_id' => 'integer',
        'user_type' => 'string',
        'expected_delivery_date' => 'date',
        'cause' => 'string',
    ];
}
