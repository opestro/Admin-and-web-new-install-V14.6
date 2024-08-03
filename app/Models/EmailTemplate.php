<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property string $template_name
 * @property string $user_type
 * @property string $template_design_name
 * @property string $title
 * @property string $body
 * @property string $banner_image
 * @property string $image
 * @property string $logo
 * @property string $button_name
 * @property string $button_url
 * @property string $footer_text
 * @property string $copyright_text
 * @property array $pages
 * @property array $social_media
 * @property array $hide_field
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class EmailTemplate extends Model
{
    use HasFactory,StorageTrait;
    protected $fillable = [
        'template_name',
        'user_type',
        'template_design_name',
        'title',
        'body',
        'banner_image',
        'image',
        'logo',
        'button_name',
        'button_url',
        'footer_text',
        'copyright_text',
        'pages',
        'social_media',
        'hide_field',
        'button_content_status',
        'product_information_status',
        'order_information_status',
        'status',
    ];
    protected $casts = [
        'template_name' => 'string',
        'user_type' => 'string',
        'template_design_name' => 'string',
        'title' => 'string',
        'body' => 'string',
        'banner_image' => 'string',
        'image' => 'string',
        'logo' => 'string',
        'button_name' => 'string',
        'button_url' => 'string',
        'footer_text' => 'string',
        'copyright_text' => 'string',
        'pages' => 'array',
        'social_media' => 'array',
        'hide_field' => 'array',
        'status' => 'integer',
        'button_content_status' => 'integer',
        'product_information_status' => 'integer',
        'order_information_status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function translations(): MorphMany
    {
        return $this->morphMany('App\Models\Translation', 'translationable');
    }
    public function translationCurrentLanguage(): MorphMany
    {
        return $this->morphMany('App\Models\Translation', 'translationable')->where('locale', getDefaultLanguage());
    }
    public function getImageFullUrlAttribute():string|null|array
    {
        $value = $this->image;
        if (count($this->storage) > 0 && $this->storageConnectionCheck() == 's3') {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'image') {
                    return $this->storageLink('email-template',$value,$storage['value']);
                }
            }
        }
        return $this->storageLink('email-template',$value,'public');
    }
    public function getLogoFullUrlAttribute():string|null|array
    {
        $value = $this->logo;
        if (count($this->storage) > 0 && $this->storageConnectionCheck() == 's3') {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'logo') {
                    return $this->storageLink('email-template',$value,$storage['value']);
                }
            }
        }
        return $this->storageLink('email-template',$value,'public');
    }
    public function getBannerImageFullUrlAttribute():string|null|array
    {
        $value = $this->banner_image;
        if (count($this->storage) > 0 && $this->storageConnectionCheck() == 's3') {
            foreach ($this->storage as $storage) {
                if ($storage['key'] == 'banner_image') {
                    return $this->storageLink('email-template',$value,$storage['value']);
                }
            }
        }
        return $this->storageLink('email-template',$value,'public');
    }
    protected $with = ['translations','translationCurrentLanguage','storage'];
    protected $appends = ['logo_full_url','image_full_url','banner_image_full_url'];
    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            $fileArray =['logo','image','banner_image'];
            $storage = config('filesystems.disks.default') ?? 'public';
            foreach ($fileArray as $file) {
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
            }
        });
    }
}
