<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Zipcode
 *
 * @property int $id Primary
 * @property string $zipcode
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class DeliveryZipCode extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'zipcode' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'zipcode',
    ];

}
