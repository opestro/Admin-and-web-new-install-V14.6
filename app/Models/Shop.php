<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class YourModel
 *
 * @property int $id
 * @property int $seller_id
 * @property string $name
 * @property string $address
 * @property string $contact
 * @property string $image
 * @property string|null $bottom_banner
 * @property string|null $offer_banner
 * @property string|null $vacation_start_date
 * @property string|null $vacation_end_date
 * @property string|null $vacation_note
 * @property bool $vacation_status
 * @property bool $temporary_close
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $banner
 *
 * @package App\Models
 */
class Shop extends Model
{
    use StorageTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'address',
        'contact',
        'image',
        'image_storage_type',
        'bottom_banner',
        'bottom_banner_storage_type',
        'offer_banner',
        'offer_banner_storage_type',
        'vacation_start_date',
        'vacation_end_date',
        'vacation_note',
        'vacation_status',
        'temporary_close',
        'banner',
        'banner_storage_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'seller_id' => 'integer',
        'vacation_status' => 'boolean',
        'temporary_close' => 'boolean',
    ];

    public function seller():BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    // old relation: product
    public function products():HasMany
    {
        return $this->hasMany(Product::class, 'user_id', 'seller_id')->where(['added_by'=>'seller', 'status'=>1, 'request_status'=>1]);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('seller', function ($query) {
            $query->where(['status' => 'approved']);
        });
    }

    public function getImageFullUrlAttribute():string|null|array
    {
        if($this->id == 0){
            return getWebConfig(name: 'company_fav_icon');
        }
        $value = $this->image;
        return $this->storageLink('shop', $value, $this->image_storage_type ?? 'public');
    }
    public function getBannerFullUrlAttribute():string|null|array
    {
        if($this->id == 0){
            return getWebConfig(name: 'shop_banner');
        }
        $value = $this->banner;
        return $this->storageLink('shop/banner', $value, $this->banner_storage_type ?? 'public');
    }
   public function getBottomBannerFullUrlAttribute():string|null|array
    {
        if($this->id == 0){
            return getWebConfig(name: 'bottom_banner');
        }
        $value = $this->bottom_banner;
        return $this->storageLink('shop/banner', $value, $this->bottom_banner_storage_type ?? 'public');
    }
   public function getOfferBannerFullUrlAttribute():string|null|array
    {
        if($this->id == 0){
            return getWebConfig(name: 'offer_banner');
        }
        $value = $this->offer_banner;
        return $this->storageLink('shop/banner', $value, $this->offer_banner_storage_type ?? 'public');
    }
    protected $appends = ['image_full_url','bottom_banner_full_url','offer_banner_full_url','banner_full_url'];
}
