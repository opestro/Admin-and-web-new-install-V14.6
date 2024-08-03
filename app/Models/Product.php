<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute as AttributeCast;

//use Rennokki\QueryCache\Traits\QueryCacheable;


/**
 * @property int $user_id
 * @property string $added_by
 * @property string $name
 * @property string $code
 * @property string $slug
 * @property int $category_id
 * @property int $sub_category_id
 * @property int $sub_sub_category_id
 * @property int $brand_id
 * @property string $unit
 * @property string $digital_product_type
 * @property string $product_type
 * @property string $details
 * @property int $min_qty
 * @property int $published
 * @property float $tax
 * @property string $tax_type
 * @property string $tax_model
 * @property float $unit_price
 * @property int $status
 * @property float $discount
 * @property int $current_stock
 * @property int $minimum_order_qty
 * @property int $free_shipping
 * @property int $request_status
 * @property int $featured_status
 * @property int $refundable
 * @property int $featured
 * @property int $flash_deal
 * @property int $seller_id
 * @property float $purchase_price
 * @property float $shipping_cost
 * @property int $multiply_qty
 * @property float $temp_shipping_cost
 * @property string $thumbnail
 * @property string $digital_file_ready
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_image
 * @property int $is_shipping_cost_updated
 */
class Product extends Model
{

    protected $fillable = [
        'user_id',
        'added_by',
        'name',
        'code',
        'slug',
        'category_ids',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'brand_id',
        'unit',
        'digital_product_type',
        'product_type',
        'details',
        'colors',
        'choice_options',
        'variation',
        'unit_price',
        'purchase_price',
        'tax',
        'tax_type',
        'tax_model',
        'discount',
        'discount_type',
        'attributes',
        'current_stock',
        'minimum_order_qty',
        'video_provider',
        'video_url',
        'status',
        'featured_status',
        'request_status',
        'shipping_cost',
        'multiply_qty',
        'color_image',
        'images',
        'thumbnail',
        'digital_file_ready',
        'meta_title',
        'meta_description',
        'meta_image'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'added_by' => 'string',
        'name' => 'string',
        'code' => 'string',
        'slug' => 'string',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'sub_sub_category_id' => 'integer',
        'brand_id' => 'integer',
        'unit' => 'string',
        'digital_product_type' => 'string',
        'product_type' => 'string',
        'details' => 'string',
        'min_qty' => 'integer',
        'published' => 'integer',
        'tax' => 'float',
        'tax_type' => 'string',
        'tax_model' => 'string',
        'unit_price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'current_stock' => 'integer',
        'minimum_order_qty' => 'integer',
        'free_shipping' => 'integer',
        'request_status' => 'integer',
        'featured_status' => 'integer',
        'refundable' => 'integer',
        'featured' => 'integer',
        'flash_deal' => 'integer',
        'seller_id' => 'integer',
        'purchase_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shipping_cost' => 'float',
        'multiply_qty' => 'integer',
        'temp_shipping_cost' => 'float',
        'thumbnail' => 'string',
        'digital_file_ready' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_image' => 'string',
        'is_shipping_cost_updated' => 'integer'
    ];

    protected $appends = ['is_shop_temporary_close'];

    public function translations(): MorphMany
    {
        return $this->morphMany('App\Models\Translation', 'translationable');
    }

    public function scopeActive($query)
    {
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $businessMode = getWebConfig(name: 'business_mode');

        if (!$digitalProductSetting) {
            $productType = ['physical'];
        } else {
            $productType = ['digital', 'physical'];
        }

        return $query->when($businessMode=='single', function ($query) {
            $query->where(['added_by'=>'admin']);
        })->when($brandSetting, function ($query) {
            $query->whereHas('brand', function ($query) {
                $query->where(['status' => 1]);
            });
        })->when(!$brandSetting, function ($query) {
            $query->whereNull('brand_id')->where('status', 1);
        })
        ->where(['status' => 1])
        ->where(['request_status' => 1])
        ->SellerApproved()
        ->whereIn('product_type', $productType);
    }

    public function scopeSellerApproved($query): void
    {
        $query->whereHas('seller', function ($query) {
            $query->where(['status' => 'approved']);
        })->orWhere(function ($query) {
            $query->where(['added_by' => 'admin', 'status' => 1]);
        });

    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    //old relation: reviews_by_customer
    public function reviewsByCustomer(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id')->where('customer_id', auth('customer')->id())->whereNotNull('product_id')->whereNull('delivery_man_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeStatus($query): Builder
    {
        return $query->where('featured_status', 1);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'seller_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'user_id');
    }

    public function getIsShopTemporaryCloseAttribute($value): int
    {
        $inhouseTemporaryClose = Cache::get('inhouseShopInTemporaryClose') ?? 0;
        if ($this->added_by == 'admin') {
            return $inhouseTemporaryClose ?? 0;
        } elseif ($this->added_by == 'seller') {
            return Cache::remember('product-shop-close-'.$this->id, 3600, function () {
                return $this?->seller?->shop?->temporary_close ?? 0;
            });
        }
        return 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    //old relation: sub_category
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    //old relation: sub_sub_category
    public function subSubCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_sub_category_id');
    }

    public function rating(): HasMany
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, product_id'))
            ->whereNull('delivery_man_id')
            ->groupBy('product_id');
    }

    //old relation: order_details
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    //old relation: order_delivered
    public function orderDelivered(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id')
            ->where('delivery_status', 'delivered');

    }

    //old relation: wish_list
    public function wishList(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    //old relation: flash_deal_product
    public function flashDealProducts(): HasMany
    {
        return $this->hasMany(FlashDealProduct::class);
    }

    public function scopeFlashDeal($query, $flashDealID)
    {
        return $query->whereHas('flashDealProducts.flashDeal', function ($query) use ($flashDealID) {
            return $query->where('id', $flashDealID);
        });
    }

    //old relation: compare_list
    public function compareList(): HasMany
    {
        return $this->hasMany(ProductCompare::class);
    }

    public function getNameAttribute($name): string|null
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/vendor') || strpos(url()->current(), '/seller')) {
            return $name;
        }
        return $this->translations[0]->value ?? $name;
    }

    public function getDetailsAttribute($detail): string|null
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/vendor') || strpos(url()->current(), '/seller')) {
            return $detail;
        }
        return $this->translations[1]->value ?? $detail;
    }

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')) {
                    return $query->where('locale', App::getLocale());
                } else {
                    return $query->where('locale', getDefaultLanguage());
                }
            }, 'reviews' => function ($query) {
                $query->whereNull('delivery_man_id');
            }]);
        });
    }
}
