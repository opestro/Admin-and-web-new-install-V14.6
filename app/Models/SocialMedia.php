<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialMedia
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property string|null $icon
 * @property int $active_status
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SocialMedia extends Model
{
    protected $table = 'social_medias';

    protected $fillable = [
        'name',
        'link',
        'icon',
        'active_status',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
        'active_status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
