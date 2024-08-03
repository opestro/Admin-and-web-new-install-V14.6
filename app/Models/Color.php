<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class YourClass
 *
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @package App\Models
 */
class Color extends Model
{
    protected $table = 'colors';

    protected $fillable = [
        'id',
        'name',
        'code',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
    ];
}
