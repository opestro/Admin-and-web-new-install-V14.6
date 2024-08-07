<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class MostDemanded
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $banner
 * @property int $product_id
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 */
class MostDemanded extends Model
{
    use HasFactory,StorageTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['banner', 'product_id', 'status'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the related product for the most demanded item.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function getBannerFullUrlAttribute():array|null
    {
        $value = $this->banner;
        if (count($this->storage) > 0) {
            $storage = $this->storage->where('key', 'banner')->first();
        }
        return $this->storageLink('most-demanded',$value,$storage['value'] ?? 'public');
    }
    protected $appends = ['banner_full_url'];
    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            if($model->isDirty('banner')){
                $storage = config('filesystems.disks.default') ?? 'public';
                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'banner',
                ], [
                    'value' => $storage,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
