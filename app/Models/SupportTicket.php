<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class SupportTicket
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $subject
 * @property string|null $type
 * @property string $priority
 * @property string|null $description
 * @property string|null $reply
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */

class SupportTicket extends Model
{
    protected $fillable = [
        'customer_id',
        'subject',
        'type',
        'priority',
        'description',
        'reply',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'priority' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(SupportTicketConv::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
