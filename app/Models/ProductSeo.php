<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductSeo
 *
 * @package App\Models
 * @property int $id
 * @property int $product_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $index
 * @property string|null $no_follow
 * @property string|null $no_image_index
 * @property string|null $no_archive
 * @property string|null $no_snippet
 * @property string|null $max_snippet
 * @property string|null $max_snippet_value
 * @property string|null $max_video_preview
 * @property string|null $max_video_preview_value
 * @property string|null $max_image_preview
 * @property string|null $max_image_preview_value
 * @property string|null $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductSeo extends Model
{
    use HasFactory , StorageTrait;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'index',
        'no_follow',
        'no_image_index',
        'no_archive',
        'no_snippet',
        'max_snippet',
        'max_snippet_value',
        'max_video_preview',
        'max_video_preview_value',
        'max_image_preview',
        'max_image_preview_value',
        'image',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'index' => 'string',
        'no_follow' => 'string',
        'no_image_index' => 'string',
        'no_archive' => 'string',
        'no_snippet' => 'string',
        'max_snippet' => 'string',
        'max_snippet_value' => 'string',
        'max_video_preview' => 'string',
        'max_video_preview_value' => 'string',
        'max_image_preview' => 'string',
        'max_image_preview_value' => 'string',
        'image' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getImageFullUrlAttribute(): array
    {
        $value = $this->image;
        if (count($this->storage) > 0 ) {
            $storage = $this->storage->where('key','image')->first();
        }
        return $this->storageLink('product/meta', $value, $storage['value'] ?? 'public');
    }

    protected $appends = ['image_full_url'];

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            if ($model->isDirty('image')) {
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
