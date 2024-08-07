<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Wishlist
 *
 * @property int $id
 * @property int $customer_id
 * @property int $product_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Wishlist extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'product_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'customer_id' => 'integer',
        'product_id' => 'integer',
    ];

    public function wishlistProduct()
    {
        return $this->belongsTo(Product::class, 'product_id')->active();
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->select(['id','slug']);
    }

    public function productFullInfo(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
