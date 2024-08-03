<?php

namespace App\Models;

use App\Models\Scopes\RememberScope;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class BusinessSetting extends Model
{
//    use Rememberable;

    protected $fillable = ['type', 'value', 'created_at', 'updated_at'];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
//        static::addGlobalScope(new RememberScope);
    }
}
