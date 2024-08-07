<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserProfile
 *
 * @property int $id Primary
 * @property int $user_id
 * @property string $name
 * @property string $country_code
 * @property string $phone
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class EmergencyContact extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'status' => 'boolean',
        'country_code' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'country_code',
        'phone',
        'status',
        'created_at',
        'updated_at',
    ];

}
