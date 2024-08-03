<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_type',
        'data_id',
        'value'
    ];

    public function data():MorphTo
    {
        return $this->morphTo();
    }
}
