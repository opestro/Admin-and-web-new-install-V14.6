<?php

namespace App\Models;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class WithdrawRequest
 *
 * @property int $id
 * @property int|null $seller_id
 * @property int|null $delivery_man_id
 * @property int|null $admin_id
 * @property string $amount
 * @property int|null $withdrawal_method_id
 * @property array $withdrawal_method_fields
 * @property string|null $transaction_note
 * @property int $approved
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class WithdrawRequest extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'delivery_man_id',
        'admin_id',
        'amount',
        'withdrawal_method_id',
        'withdrawal_method_fields',
        'transaction_note',
        'approved',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'withdrawal_method_fields' => 'array',
        'approved' => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class,'seller_id');
    }

    /*  delivery_men->deliveryMan*/
    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class,'delivery_man_id');
    }

    /*  withdraw_method->deliveryMan*/
    public function withdrawMethod(): BelongsTo
    {
        return $this->belongsTo(WithdrawalMethod::class,'withdrawal_method_id');
    }
}
