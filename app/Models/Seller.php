<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $f_name
 * @property string $l_name
 * @property string $country_code
 * @property string $phone
 * @property string $image
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string $bank_name
 * @property string $branch
 * @property string $account_no
 * @property string $holder_name
 * @property string $auth_token
 * @property float $sales_commission_percentage
 * @property float $gst
 * @property string $cm_firebase_token
 * @property string $pos_status
 * @property float $minimum_order_amount
 * @property string $free_delivery_status
 * @property float $free_delivery_over_amount
 * @property string $app_language
 */
class Seller extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'f_name',
        'l_name',
        'country_code',
        'phone',
        'email',
        'free_delivery_over_amount',
        'image',
        'password',
        'status',
        'bank_name',
        'branch',
        'account_no',
        'holder_name',
        'auth_token',
        'sales_commission_percentage',
        'gst',
        'cm_firebase_token',
        'pos_status',
        'minimum_order_amount',
        'free_delivery_status',
        'app_language',
    ];

    protected $casts = [
        'id' => 'integer',
        'f_name' => 'string',
        'l_name' => 'string',
        'country_code' => 'string',
        'orders_count' => 'integer',
        'product_count' => 'integer',
        'pos_status' => 'integer'
    ];

    public function scopeApproved($query)
    {
        return $query->where(['status'=>'approved']);
    }

    public function shop():HasOne
    {
        return $this->hasOne(Shop::class, 'seller_id');
    }

    public function shops():HasMany
    {
        return $this->hasMany(Shop::class, 'seller_id');
    }

    public function orders():HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function product():HasMany
    {
        return $this->hasMany(Product::class, 'user_id')->where(['added_by'=>'seller']);
    }

    public function wallet():HasOne
    {
        return $this->hasOne(SellerWallet::class);
    }

    public function coupon():HasMany
    {
        return $this->hasMany(Coupon::class, 'seller_id')
            ->where(['coupon_bearer'=>'seller', 'status'=>1])
            ->whereDate('start_date','<=',date('Y-m-d'))
            ->whereDate('expire_date','>=',date('Y-m-d'));
    }

}
