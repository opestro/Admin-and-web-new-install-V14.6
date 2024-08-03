<?php

namespace App\Models;

use App\Models\AdminRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class Admin
 *
 * @property int $id Primary
 * @property string $name
 * @property string $phone
 * @property int $admin_role_id
 * @property string $image
 * @property string $identify_image
 * @property string $identify_type
 * @property int $identify_number
 * @property string $email Index
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $status
 *
 * @package App\Models
 */

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'admin_role_id',
        'image',
        'identify_image',
        'identify_type',
        'identify_number',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'phone' => 'string',
        'admin_role_id' => 'integer',
        'image' => 'string',
        'identify_image' => 'string',
        'identify_type' => 'string',
        'identify_number' => 'integer',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];


    public function role(): BelongsTo
    {
        return $this->belongsTo(AdminRole::class,'admin_role_id');
    }

}
