<?php

namespace App\Models;

use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SellerWallet
 *
 * @property int $id
 * @property int|null $seller_id
 * @property float $total_earning
 * @property float $withdrawn
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property float $commission_given
 * @property float $pending_withdraw
 * @property float $delivery_charge_earned
 * @property float $collected_cash
 * @property float $total_tax_collected
 */

class SellerWallet extends Model
{

    protected $fillable = [
        'seller_id',
        'total_earning',
        'withdrawn',
        'commission_given',
        'pending_withdraw',
        'delivery_charge_earned',
        'collected_cash',
        'total_tax_collected',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'total_earning' => 'float',
        'withdrawn' => 'float',
        'commission_given' => 'float',
        'pending_withdraw' => 'float',
        'delivery_charge_earned' => 'float',
        'collected_cash' => 'float',
        'total_tax_collected' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
