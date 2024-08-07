<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bonus
 *
 * @property int $id Primary
 * @property string $title
 * @property string $description
 * @property string $bonus_type
 * @property float $bonus_amount
 * @property float $min_add_money_amount
 * @property float $max_bonus_amount
 * @property Carbon $start_date_time
 * @property Carbon $end_date_time
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class AddFundBonusCategories extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'bonus_type' => 'string',
        'bonus_amount' => 'float',
        'min_add_money_amount' => 'float',
        'max_bonus_amount' => 'float',
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'description',
        'bonus_type',
        'bonus_amount',
        'min_add_money_amount',
        'max_bonus_amount',
        'start_date_time',
        'end_date_time',
        'is_active',
    ];

    public function scopeActive($query)
    {

        return $query->where(['is_active' => 1]);
    }
}
