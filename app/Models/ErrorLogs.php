<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ErrorLogs
 * @property int $id Primary
 * @property int $status_code
 * @property int $hit_counts
 * @property string $url
 * @property string $redirect_url
 * @property string $redirect_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class ErrorLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_code',
        'url',
        'hit_counts',
        'redirect_url',
        'redirect_status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'url' => 'string',
        'hit_counts' => 'integer',
        'redirect_url' => 'string',
        'redirect_status' => 'string',
    ];
}
