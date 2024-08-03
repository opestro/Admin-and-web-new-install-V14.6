<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
/**
 * Class Notification
 *
 * @property string $sent_by
 * @property string $sent_to
 * @property string $description
 * @property int $notification_count
 * @property string $image
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Notification extends Model
{
    protected $fillable = [
        'sent_by',
        'sent_to',
        'title',
        'description',
        'notification_count',
        'image',
        'status',
    ];
    protected $casts = [
        'sent_by' => 'string',
        'sent_to' => 'string',
        'title' => 'string',
        'description' => 'string',
        'notification_count' => 'integer',
        'image' => 'string',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeActive($query):mixed
    {
        return $query->where('status', 1);
    }

    /* notification_seen_by -> notificationSeenBy */
    public function notificationSeenBy():HasOne
    {
        return $this->hasOne(NotificationSeen::class, 'notification_id');
    }
}
