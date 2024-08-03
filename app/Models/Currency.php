<?php

namespace App\Models;

use App\Models\Scopes\RememberScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

/**
 * Class Currency
 *
 * @property int $id Primary
 * @property string $name
 * @property string $symbol
 * @property string $code
 * @property string $exchange_rate
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Currency extends Model
{
//    use Rememberable;

    protected $casts = [
        'id' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'symbol',
        'code',
        'exchange_rate',
        'status',
    ];

    protected $table = 'currencies';

    protected static function boot(): void
    {
        parent::boot();
//        static::addGlobalScope(new RememberScope);
    }
}
