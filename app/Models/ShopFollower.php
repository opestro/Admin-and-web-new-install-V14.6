<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class ShopFollower
 *
 * @property int $id
 * @property int $shop_id
 * @property string $user_id
 * @package App\Models
 */
class ShopFollower extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id'
    ];
    protected $casts = [
        'shop_id' => 'integer',
        'user_id' => 'integer',
    ];
}
