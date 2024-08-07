<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class OrderStatusHistory
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $user_type
 * @property string $status
 * @property string|null $cause
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class OrderStatusHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'user_type',
        'status',
        'cause',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
    ];
}
