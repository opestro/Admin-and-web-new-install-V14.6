<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * Class ProductCompare
 *
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 *
 * @package App\Models
 */
class ProductCompare extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
    ];
    protected $casts = [
        'product_id'  => 'integer',
        'user_id'     => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->active();
    }
}
