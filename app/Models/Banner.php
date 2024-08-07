<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class YourModel
 *
 * @property int $id Primary
 * @property string $photo
 * @property string $banner_type
 * @property string $theme
 * @property int $published
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $url
 * @property string $resource_type
 * @property int $resource_id
 * @property string $title
 * @property string $sub_title
 * @property string $button_text
 * @property string $background_color
 *
 * @package App\Models
 */
class Banner extends Model
{
    use StorageTrait;
    protected $casts = [
        'id' => 'integer',
        'published' => 'integer',
        'resource_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'photo',
        'banner_type',
        'theme',
        'published',
        'url',
        'resource_type',
        'resource_id',
        'title',
        'sub_title',
        'button_text',
        'background_color',
    ];

    public function product(){
        return $this->belongsTo(Product::class,'resource_id');
    }
    public function getPhotoFullUrlAttribute():string|null|array
    {
        $value = $this->photo;
        if (count($this->storage) > 0) {
            $storage = $this->storage->where('key', 'photo')->first();
        }
        return $this->storageLink('banner',$value,$storage['value'] ?? 'public');
    }
    protected $appends = ['photo_full_url'];

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            $file ='photo';
            $storage = config('filesystems.disks.default') ?? 'public';
            if($model->isDirty($file)){
                $value = $storage;
                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => $file,
                ], [
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
