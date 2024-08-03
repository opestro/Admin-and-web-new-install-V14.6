<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class YourModel
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $identity
 * @property string $token
 * @property int $otp_hit_count
 * @property bool $is_temp_blocked
 * @property Carbon $temp_block_time
 * @property Carbon $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $user_type
 * @property mixed $doc
 */
class PasswordReset extends Model
{
    use HasFactory;
    protected $fillable = [
        'identity',
        'token',
        'otp_hit_count',
        'is_temp_blocked',
        'temp_block_time',
        'expires_at',
        'created_at',
        'updated_at',
        'user_type',
    ];

    protected $casts = [
        'identity' => 'string',
        'token' => 'string',
        'otp_hit_count' => 'integer',
        'is_temp_blocked' => 'boolean',
        'temp_block_time' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
