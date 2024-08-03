<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

/**
 * Class YourModel
 *
 * @property int $id
 * @property string $title
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool $status
 * @property bool $featured
 * @property string $background_color
 * @property string $text_color
 * @property string $banner
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $product_id
 * @property string $deal_type
 *
 * @package App\Models
 */
class FlashDeal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'status',
        'featured',
        'background_color',
        'text_color',
        'banner',
        'slug',
        'product_id',
        'deal_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(FlashDealProduct::class, 'flash_deal_id');
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function getTitleAttribute($title): string|null
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/vendor') || strpos(url()->current(), '/seller')) {
            return $title;
        }

        return $this->translations[0]->value??$title;
    }

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
