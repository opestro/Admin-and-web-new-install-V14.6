<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GuestUser
 *
 * @package App\Models
 *
 * @property int $id
 * @property string|null $ip_address
 * @property string|null $fcm_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class GuestUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip_address',
        'fcm_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ip_address' => 'string',
        'fcm_token' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
