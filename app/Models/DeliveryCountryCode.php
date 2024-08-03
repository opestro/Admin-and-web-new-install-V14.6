<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DeliveryCountryCode
 *
 * @package App\Models
 * @property int $id
 * @property string $country_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

class DeliveryCountryCode extends Model
{
    use HasFactory;

    protected $fillable = ['country_code'];

    protected $casts = [
        'id' => 'integer',
        'country_code' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
