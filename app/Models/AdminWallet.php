<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Earnings
 *
 * @property int $id Primary
 * @property int $admin_id
 * @property float $inhouse_earning
 * @property float $withdrawn
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property float $commission_earned
 * @property float $delivery_charge_earned
 * @property float $pending_amount
 * @property float $total_tax_collected
 *
 * @package App\Models
 */
class AdminWallet extends Model
{
    protected $casts = [
        'id' => 'integer',
        'admin_id' => 'integer',
        'inhouse_earning' => 'float',
        'withdrawn' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'commission_earned' => 'float',
        'delivery_charge_earned' => 'float',
        'pending_amount' => 'float',
        'total_tax_collected' => 'float',
    ];

    protected $fillable = [
        'admin_id',
        'inhouse_earning',
        'withdrawn',
        'commission_earned',
        'delivery_charge_earned',
        'pending_amount',
        'total_tax_collected',
    ];
}
