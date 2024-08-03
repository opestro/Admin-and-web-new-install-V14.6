<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerTransaction
 *
 * @property int $id Primary
 * @property int $customer_id
 * @property float $transaction_amount
 * @property string $transaction_type
 * @property string $transaction_method
 * @property string $transaction_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class CustomerWalletHistory extends Model
{
    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'transaction_amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'customer_id',
        'transaction_amount',
        'transaction_type',
        'transaction_method',
        'transaction_id',
    ];
}
