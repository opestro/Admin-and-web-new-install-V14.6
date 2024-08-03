<?php

namespace App\Models;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SellerProduct
 *
 * @property int $id Primary
 * @property int $seller_id
 * @property int $category_id
 * @property float $cost
 * @property bool $multiply_qty
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CategoryShippingCost extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'seller_id' => 'integer',
        'category_id' => 'integer',
        'cost' => 'float',
        'multiply_qty' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'seller_id',
        'category_id',
        'cost',
        'multiply_qty',
    ];

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
