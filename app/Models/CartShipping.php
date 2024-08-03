<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CartShipping
 *
 * @property int $id Primary
 * @property string $cart_group_id
 * @property int $shipping_method_id
 * @property float $shipping_cost
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CartShipping extends Model
{
    protected $casts = [
        'id' => 'integer',
        'shipping_method_id' => 'integer',
        'shipping_cost' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'cart_group_id',
        'shipping_method_id',
        'shipping_cost',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class,'cart_group_id','cart_group_id');
    }
}
