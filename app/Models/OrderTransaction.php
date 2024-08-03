<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class OrderTransaction
 *
 * @property int $seller_id
 * @property int $order_id
 * @property float $order_amount
 * @property float $seller_amount
 * @property float $admin_commission
 * @property string|null $received_by
 * @property string|null $status
 * @property float $delivery_charge
 * @property float $tax
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $customer_id
 * @property string|null $seller_is
 * @property string $delivered_by
 * @property string|null $payment_method
 * @property string|null $transaction_id
 *
 * @package App\Models
 */
class OrderTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'order_id',
        'order_amount',
        'seller_amount',
        'admin_commission',
        'received_by',
        'status',
        'delivery_charge',
        'tax',
        'customer_id',
        'seller_is',
        'delivered_by',
        'payment_method',
        'transaction_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order_amount' => 'float:2',
        'seller_amount' => 'float:2',
        'admin_commission' => 'float:2',
        'delivery_charge' => 'float:2',
        'tax' => 'float:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

}
