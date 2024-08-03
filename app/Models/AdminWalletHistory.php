<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class YourModel
 *
 * @property int $id Primary
 * @property int $admin_id
 * @property float $amount
 * @property int $order_id
 * @property int $product_id
 * @property string $payment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class AdminWalletHistory extends Model
{
    protected $casts = [
        'id' => 'integer',
        'admin_id' => 'integer',
        'amount' => 'float',
        'order_id' => 'integer',
        'product_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'admin_id',
        'amount',
        'order_id',
        'product_id',
        'payment',
    ];

}
