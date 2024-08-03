<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
    use StorageTrait;
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
    public function getImageFullUrlAttribute():array|null
    {
        $value = $this->image;
        if (count($this->storage) > 0 ) {
            $storage = $this->storage->where('key','image')->first();
        }
        return $this->storageLink('notification',$value,$storage['value'] ?? 'public');
    }

    protected $appends = ['image_full_url'];
    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            if($model->isDirty('image')){
                $storage = config('filesystems.disks.default') ?? 'public';
                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'image',
                ], [
                    'value' => $storage,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
