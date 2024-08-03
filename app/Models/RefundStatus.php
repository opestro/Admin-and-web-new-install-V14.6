<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_request_id',
        'change_by',
        'change_by_id',
        'status',
        'message'
    ];
    protected $casts = [
        'refund_request_id' => 'integer',
        'change_by' => 'string',
        'change_by_id' => 'string',
        'status' => 'string',
        'message' => 'string',
    ];
}
