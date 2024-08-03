<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class WithdrawalMethod
 *
 * @property int $id
 * @property string $method_name
 * @property array $method_fields
 * @property bool $is_default
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class WithdrawalMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'method_name',
        'method_fields',
        'is_default',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'method_fields' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected function scopeOfStatus($query, $status)
    {
        $query->where('is_active', $status);
    }
}
