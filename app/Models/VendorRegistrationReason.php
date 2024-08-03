<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class VendorRegistrationReason
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $priority
 * @property int $status
 * @package App\Models
 */
class VendorRegistrationReason extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'description',
        'priority',
        'status',
        'created_at',
        'updated_at'
        ];
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'priority' => 'integer',
        'status' => 'integer',
        ];

}
