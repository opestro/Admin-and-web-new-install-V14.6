<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class YourModel
 *
 * @property int $id
 * @property int $flash_deal_id
 * @property int $product_id
 * @property float $discount
 * @property string $discount_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class FlashDealProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flash_deal_id',
        'product_id',
        'discount',
        'discount_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'discount' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function flashDeal(): BelongsTo
    {
        return $this->belongsTo(FlashDeal::class)->where(['deal_type'=>'flash_deal','status'=>1]);
    }

    public function featureDeal(): BelongsTo
    {
        return $this->belongsTo(FlashDeal::class, 'flash_deal_id', 'id')->where(['deal_type'=>'feature_deal','status'=>1]);
    }
}
