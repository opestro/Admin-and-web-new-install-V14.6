<?php

namespace App\Models;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $added_by
 * @property string $coupon_type
 * @property string $coupon_bearer
 * @property integer $seller_id
 * @property integer $customer_id
 * @property string $title
 * @property string $code
 * @property datetime $start_date
 * @property datetime $expire_date
 * @property float $min_purchase
 * @property float $max_discount
 * @property float $discount
 * @property string $discount_type
 * @property integer $limit
 */
class Coupon extends Model
{
    protected $fillable = [
        'added_by',
        'coupon_type',
        'coupon_bearer',
        'customer_id',
        'seller_id',
        'title',
        'code',
        'start_date',
        'expire_date',
        'min_purchase',
        'max_discount',
        'discount',
        'discount_type',
        'limit',
    ];
    protected $casts = [
        'id' => 'integer',
        'added_by' => 'string',
        'coupon_type' => 'string',
        'coupon_bearer' => 'string',
        'seller_id' => 'integer',
        'customer_id' => 'integer',
        'title' => 'string',
        'code' => 'string',
        'start_date' => 'datetime',
        'expire_date' => 'datetime',
        'min_purchase' => 'float',
        'max_discount' => 'float',
        'discount' => 'float',
        'discount_type' => 'string',
        'limit' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order():HasMany
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }

    public function seller():BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
