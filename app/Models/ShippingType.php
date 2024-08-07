<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class YourModel
 *
 * @property int $id
 * @property int|null $seller_id
 * @property string|null $shipping_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class ShippingType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'shipping_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'seller_id' => 'integer',
        'shipping_type' => 'string',
    ];
}
