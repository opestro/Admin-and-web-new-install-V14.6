<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class OrderDetail
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $product_id
 * @property int|null $seller_id
 * @property string|null $digital_file_after_sell
 * @property string|null $product_details
 * @property int $qty
 * @property float $price
 * @property float $tax
 * @property float $discount
 * @property string $tax_model
 * @property string $delivery_status
 * @property string $payment_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int|null $shipping_method_id
 * @property string|null $variant
 * @property string|null $variation
 * @property string|null $discount_type
 * @property bool $is_stock_decreased
 * @property int|null $refund_request
 *
 * @package App\Models
 */
class OrderDetail extends Model
{
    protected $fillable = [
        'product_id',
        'order_id',
        'product_details',
        'price',
        'discount',
        'qty',
        'tax',
        'tax_model',
        'digital_file_after_sell',
        'discount',
        'discount_type',
        'delivery_status',
        'payment_status',
        'shipping_method_id',
        'seller_id',
        'refund_request',
        'variant',
        'variation'
    ];
    protected $casts = [
        'product_id' => 'integer',
        'order_id' => 'integer',
        'price' => 'float',
        'discount' => 'float',
        'qty' => 'integer',
        'tax' => 'float',
        'shipping_method_id' => 'integer',
        'digital_file_after_sell' => 'string',
        'seller_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'refund_request'=>'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->where('status', 1);
    }

    //active_product
    public function activeProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class)->where('status', 1);
    }

    //product_all_status
    public function productAllStatus(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address');
    }

    //verification_images
    public function verificationImages(): HasMany
    {
        return $this->hasMany(OrderDeliveryVerification::class,'order_id','order_id');
    }

    public function orderStatusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class,'order_id','order_id');
    }
}
