<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
        'bottom_banner',
        'offer_banner',
        'vacation_start_date',
        'vacation_end_date',
        'vacation_note',
        'vacation_status',
        'temporary_close',
        'banner',
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
}
