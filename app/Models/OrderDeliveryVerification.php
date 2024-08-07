<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Image
 *
 * @property int $id Primary
 * @property int $order_id
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class OrderDeliveryVerification extends Model
{
    use HasFactory,StorageTrait;

    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'image' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'order_id',
        'image',
    ];
    public function getImageFullUrlAttribute():array|null
    {
        $value = $this->image;
        if (count($this->storage) > 0 ) {
            $storage = $this->storage->where('key','image')->first();
        }
        return $this->storageLink('delivery-man/verification-image',$value,$storage['value'] ?? 'public');
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
