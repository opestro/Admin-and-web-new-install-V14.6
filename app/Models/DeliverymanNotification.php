<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DeliveryHistory
 *
 * @property int $id Primary
 * @property int $delivery_man_id
 * @property int $order_id
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class DeliverymanNotification extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'delivery_man_id' => 'integer',
        'order_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'delivery_man_id',
        'order_id',
        'description',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
