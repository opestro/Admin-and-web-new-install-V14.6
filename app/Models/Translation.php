<?php

namespace App\Models;

use App\Models\Scopes\RememberScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Watson\Rememberable\Rememberable;

/**
 * @property int $translationable_id
 * @property string $translationable_type
 * @property string $locale
 * @property string $key
 * @property string $value
 */

class Translation extends Model
{
//    use Rememberable;

    public $timestamps = false;
    protected $table = 'translations';

    protected $fillable = [
        'translationable_type',
        'translationable_id',
        'locale',
        'key',
        'value',
    ];

    protected $casts = [
        'translationable_id' => 'integer',
        'translationable_type' => 'string',
        'locale' => 'string',
        'key' => 'string',
        'value' => 'string',
        'id' => 'integer',
    ];

    public function translationable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function boot(): void
    {
        parent::boot();
//        static::addGlobalScope(new RememberScope);
    }
}
