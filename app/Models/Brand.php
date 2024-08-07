<?php

namespace App\Models;

use App\Traits\SettingsTrait;
use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $status
 */
class Brand extends Model
{
    use StorageTrait;
    protected $fillable = [
        'name',
        'image',
        'image_storage_type',
        'image_alt_text',
        'status'
    ];

    protected $casts = [
        'name' => 'string',
        'image' => 'string',
        'image_storage_type' => 'string',
        'image_alt_text' => 'string',
        'status' => 'integer',
        'brand_products_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeActive(): mixed
    {
        return $this->where('status',1);
    }

    public function brandProducts(): HasMany
    {
        return $this->hasMany(Product::class)->active();
    }

    public function brandAllProducts(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function translations(): MorphMany
    {
        return $this->morphMany('App\Models\Translation', 'translationable');
    }

    public function getNameAttribute($name): string|null
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/vendor') || strpos(url()->current(), '/seller')) {
            return $name;
        }

        return $this->translations[0]->value??$name;
    }

    public function getDefaultNameAttribute(): string|null
    {
        return $this->translations[0]->value ?? $this->name;
    }
    public function storage():MorphMany
    {
        return $this->morphMany(Storage::class, 'data');
    }
    public function getImageFullUrlAttribute():array
    {
        $value = $this->image;
        return $this->storageLink('brand',$value,$this->image_storage_type ??'public');
    }
    protected $appends = ['image_full_url'];
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')){
                    return $query->where('locale', App::getLocale());
                }else{
                    return $query->where('locale', getDefaultLanguage());
                }
            }]);
        });
    }
}
