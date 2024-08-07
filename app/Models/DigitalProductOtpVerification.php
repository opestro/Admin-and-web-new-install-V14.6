<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderVerification
 *
 * @property int $id Primary
 * @property string $order_details_id
 * @property string $identity
 * @property string $token
 * @property int $otp_hit_count
 * @property bool $is_temp_blocked
 * @property Carbon $temp_block_time
 * @property Carbon $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class DigitalProductOtpVerification extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'otp_hit_count' => 'integer',
        'is_temp_blocked' => 'boolean',
        'temp_block_time' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'order_details_id',
        'identity',
        'token',
        'otp_hit_count',
        'is_temp_blocked',
        'temp_block_time',
        'expires_at',
    ];

}
