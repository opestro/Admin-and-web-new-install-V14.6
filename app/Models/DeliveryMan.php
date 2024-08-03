<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $seller_id
 * @property string $f_name
 * @property string $l_name
 * @property string $address
 * @property string $email
 * @property string $country_code
 * @property string $phone
 * @property string $identity_type
 * @property string $identity_number
 * @property string $identity_image
 * @property int $is_active
 * @property int $is_online
 * @property string $password
 * @property string $auth_token
 * @property string $fcm_token
 * @property string $app_language
 */
class DeliveryMan extends Model
{
    protected $hidden = ['password','auth_token'];

    protected $fillable = [
        'seller_id',
        'f_name',
        'l_name',
        'address',
        'email',
        'country_code',
        'phone',
        'image',
        'identity_number',
        'identity_type',
        'identity_image',
        'password',
        'is_active',
        'is_online',
        'auth_token',
        'fcm_token',
        'app_language',
    ];
    protected $casts = [
        'id' => 'integer',
        'seller_id'=>'integer',
        'f_name'=>'string',
        'l_name'=>'string',
        'address'=>'string',
        'email'=>'string',
        'country_code'=>'string',
        'phone'=>'string',
        'image'=>'string',
        'identity_number'=>'string',
        'identity_type'=>'string',
        'identity_image'=>'string',
        'is_active'=>'integer',
        'is_online'=>'integer',
        'password'=>'string',
        'auth_token'=>'string',
        'fcm_token'=>'string',
        'app_language'=>'string',
    ];

    public function orders():HasMany
    {
        return $this->hasMany(Order::class,'delivery_man_id');
    }
    public function deliveredOrders():HasMany
    {
        return $this->hasMany(Order::class,'delivery_man_id')->where('order_status','delivered');
    }

    public function wallet():HasOne
    {
        return $this->hasOne(DeliverymanWallet::class);
    }

    public function transactions():HasMany
    {
        return $this->hasMany(DeliveryManTransaction::class);
    }
    public function chats():HasMany
    {
        return $this->hasMany(Chatting::class);
    }
    public function review():HasMany
    {
        return $this->hasMany(Review::class, 'delivery_man_id');
    }

    public function rating():HasMany
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, delivery_man_id'))
            ->groupBy('delivery_man_id');
    }
}
