<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class RobotsMetaContent
 *
 * @package App\Models
 * @property int $id
 * @property string|null $page_title
 * @property string|null $page_name
 * @property string|null $page_url
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_image
 * @property string|null $canonicals_url
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class RobotsMetaContent extends Model
{
    use HasFactory, StorageTrait;

    protected $fillable = [
        'page_title',
        'page_name',
        'page_url',
        'meta_title',
        'meta_description',
        'meta_image',
        'canonicals_url',
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
    ];

    protected $casts = [
        'id' => 'int',
        'page_title' => 'string',
        'page_name' => 'string',
        'page_url' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_image' => 'string',
        'canonicals_url' => 'string',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getMetaImageFullUrlAttribute(): array
    {
        $value = $this->meta_image;
        if (count($this->storage) > 0 && $this->storageConnectionCheck() == 's3') {
            $storage = $this->storage->where('key','meta_image')->first();
        }
        return $this->storageLink('robots-meta-content', $value, $storage['value'] ?? 'public');
    }

    protected $appends = ['meta_image_full_url'];

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            if ($model->isDirty('meta_image')) {
                $storage = config('filesystems.disks.default') ?? 'public';
                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => 'meta_image',
                ], [
                    'value' => $storage,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
