<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class YourModel
 *
 * @property int $id Primary
 * @property string $name
 * @property string $module_access
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */

class AdminRole extends Model
{
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'module_access' => 'string',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'module_access',
        'status',
    ];

}
