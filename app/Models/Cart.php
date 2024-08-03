<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class CartItem
 *
 * @property int $id Primary
 * @property int $customer_id
 * @property string $cart_group_id
 * @property int $product_id
 * @property string $product_type
 * @property string $digital_product_type
 * @property string $color
 * @property array $choices
 * @property array $variations
 * @property array $variant
 * @property int $quantity
 * @property float $price
 * @property float $tax
 * @property float $discount
 * @property string $tax_model
 * @property string $slug
 * @property string $name
 * @property string $thumbnail
 * @property int $seller_id
 * @property string $seller_is
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $shop_info
 * @property float $shipping_cost
 * @property string $shipping_type
 * @property int $is_guest
 *
 * @package App\Models
 */
class Cart extends Model
{

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'product_id' => 'integer',
//        'choices' => 'array',
//        'variations' => 'array',
//        'variant' => 'array',
        'quantity' => 'integer',
        'price' => 'float',
        'tax' => 'float',
        'discount' => 'float',
        'seller_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shipping_cost' => 'float',
        'is_guest' => 'integer',
    ];

    protected $fillable = [
        'customer_id',
        'cart_group_id',
        'product_id',
        'product_type',
        'digital_product_type',
        'color',
        'choices',
        'variations',
        'variant',
        'quantity',
        'price',
        'tax',
        'discount',
        'tax_model',
        'is_checked',
        'slug',
        'name',
        'thumbnail',
        'seller_id',
        'seller_is',
        'shop_info',
        'shipping_cost',
        'shipping_type',
        'is_guest',
    ];

    public function cartShipping(): HasOne
    {
        return $this->hasOne(CartShipping::class,'cart_group_id','cart_group_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->where('status', 1);
    }
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'seller_id', 'seller_id');
    }

    public function allProducts(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
