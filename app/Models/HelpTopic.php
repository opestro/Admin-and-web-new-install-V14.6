<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    protected $table = 'help_topics';
    protected $casts = [
        'type' => 'string',
        'ranking'    => 'integer',
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
        'type',
        'question',
        'answer',
        'status',
        'ranking',
    ];

    public function scopeStatus($query)
    {
        return $query->where('status', 1);
    }
}
