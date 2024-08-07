<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 *
 * @property int $id Primary
 * @property int $customer_id
 * @property string $contact_person_name
 * @property string $address_type
 * @property string $address
 * @property string $city
 * @property string $zip
 * @property string $phone
 * @property string $state
 * @property string $country
 * @property string $latitude
 * @property string $longitude
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class BillingAddress extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'customer_id',
        'contact_person_name',
        'address_type',
        'address',
        'city',
        'zip',
        'phone',
        'state',
        'country',
        'latitude',
        'longitude',
    ];

}
