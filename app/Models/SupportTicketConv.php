<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class SupportTicketConv
 *
 * @property int $id
 * @property int|null $support_ticket_id
 * @property int|null $admin_id
 * @property string|null $customer_message
 * @property string|null $admin_message
 * @property int $position
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class SupportTicketConv extends Model
{

    protected $fillable = [
        'support_ticket_id',
        'admin_id',
        'customer_message',
        'admin_message',
        'position',
        'attachment',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'support_ticket_id' => 'integer',
        'admin_id' => 'integer',
        'position' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function adminInfo(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id','id');
    }
}
