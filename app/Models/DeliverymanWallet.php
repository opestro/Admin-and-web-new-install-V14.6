<?php

namespace App\Models;

use App\Models\DeliveryMan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DeliveryManBalance
 *
 * @property int $id Primary
 * @property int $delivery_man_id
 * @property float $current_balance
 * @property float $cash_in_hand
 * @property float $pending_withdraw
 * @property float $total_withdraw
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class DeliverymanWallet extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'delivery_man_id' => 'integer',
        'current_balance' => 'float',
        'cash_in_hand' => 'float',
        'pending_withdraw' => 'float',
        'total_withdraw' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'delivery_man_id',
        'current_balance',
        'cash_in_hand',
        'pending_withdraw',
        'total_withdraw',
    ];

    protected $guarded = [];
    /*delivery_man ->deliveryMan*/
    public function deliveryMan():BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }
}
