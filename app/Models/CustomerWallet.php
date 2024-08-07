<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerWallet
 *
 * @property int $id Primary
 * @property int $customer_id
 * @property float $balance
 * @property float $royalty_points
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CustomerWallet extends Model
{
    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'balance' => 'float',
        'royalty_points' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'customer_id',
        'balance',
        'royalty_points',
    ];

}
