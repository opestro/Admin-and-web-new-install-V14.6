<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{

    protected $fillable = [
        'order_id',
        'payment_for',
        'payer_id',
        'payment_receiver_id',
        'paid_by',
        'paid_to',
        'payment_method',
        'payment_status',
        'created_at',
        'updated_at',
        'amount',
        'transaction_type',
        'order_details_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'payer_id' => 'integer',
        'payment_receiver_id' => 'integer',
        'amount' => 'float',
        'order_details_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
