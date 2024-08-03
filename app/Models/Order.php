<?php

namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $customer_id
 * @property bool $is_guest
 * @property string $customer_type
 * @property string $payment_status
 * @property string $order_status
 * @property string $payment_method
 * @property string $transaction_ref
 * @property string $payment_by
 * @property string $payment_note
 * @property float $order_amount
 * @property float $admin_commission
 * @property bool $is_pause
 * @property string $cause
 * @property string $shipping_address
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property float $discount_amount
 * @property string $discount_type
 * @property string $coupon_code
 * @property string $coupon_discount_bearer
 * @property string $shipping_responsibility
 * @property int $shipping_method_id
 * @property float $shipping_cost
 * @property bool $is_shipping_free
 * @property string $order_group_id
 * @property string $verification_code
 * @property bool $verification_status
 * @property int $seller_id
 * @property string $seller_is
 * @property object $shipping_address_data
 * @property int $delivery_man_id
 * @property float $deliveryman_charge
 * @property \DateTime $expected_delivery_date
 * @property string $order_note
 * @property int $billing_address
 * @property object $billing_address_data
 * @property string $order_type
 * @property float $extra_discount
 * @property string $extra_discount_type
 * @property string $free_delivery_bearer
 * @property bool $checked
 * @property string $shipping_type
 * @property string $delivery_type
 * @property string $delivery_service_name
 * @property string $third_party_delivery_tracking_id
 */

class Order extends Model
{

    protected $fillable = [
        'id',
        'customer_id',
        'is_guest',
        'customer_type',
        'payment_status',
        'order_status',
        'payment_method',
        'transaction_ref',
        'payment_by',
        'payment_note',
        'order_amount',
        'admin_commission',
        'is_pause',
        'cause',
        'shipping_address',
        'discount_type',
        'discount_amount',
        'coupon_code',
        'coupon_discount_bearer',
        'shipping_responsibility',
        'shipping_method_id',
        'shipping_cost',
        'is_shipping_free',
        'order_group_id',
        'verification_code',
        'verification_status',
        'seller_id',
        'seller_is',
        'shipping_address_data',
        'delivery_man_id',
        'deliveryman_charge',
        'expected_delivery_date',
        'order_note',
        'billing_address',
        'billing_address_data',
        'order_type',
        'extra_discount',
        'extra_discount_type',
        'free_delivery_bearer',
        'checked',
        'shipping_type',
        'delivery_type',
        'delivery_service_name',
        'third_party_delivery_tracking_id',
    ];
    protected $casts = [
        'customer_id' => 'integer',
        'is_guest' => 'boolean',
        'customer_type' => 'string',
        'payment_status' => 'string',
        'order_status' => 'string',
        'payment_method' => 'string',
        'transaction_ref' => 'string',
        'payment_by' => 'string',
        'payment_note' => 'string',
        'order_amount' => 'double',
        'admin_commission' => 'decimal:2',
        'is_pause' => 'boolean',
        'cause' => 'string',
        'shipping_address' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'discount_amount' => 'double',
        'discount_type' => 'string',
        'coupon_code' => 'string',
        'coupon_discount_bearer' => 'string',
        'shipping_responsibility' => 'string',
        'shipping_method_id' => 'integer',
        'shipping_cost' => 'double',
        'is_shipping_free' => 'boolean',
        'order_group_id' => 'string',
        'verification_code' => 'string',
        'verification_status' => 'boolean',
        'seller_id' => 'integer',
        'seller_is' => 'string',
        'shipping_address_data' => 'object',
        'delivery_man_id' => 'integer',
        'deliveryman_charge' => 'double',
        'order_note' => 'string',
        'billing_address' => 'integer',
        'billing_address_data' => 'object',
        'order_type' => 'string',
        'extra_discount' => 'double',
        'extra_discount_type' => 'string',
        'free_delivery_bearer' => 'string',
        'checked' => 'boolean',
        'shipping_type' => 'string',
        'delivery_type' => 'string',
        'delivery_service_name' => 'string',
        'third_party_delivery_tracking_id' => 'string',
    ];


    public function details() : HasMany
    {
        return $this->hasMany(OrderDetail::class)->orderBy('seller_id', 'ASC');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function sellerName() : HasOne
    {
        return $this->hasOne(OrderDetail::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address');
    }
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'billing_address');
    }

    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class,'delivery_man_id');
    }
    /* delivery_man_review -> deliveryManReview */
    public function deliveryManReview():HasOne
    {
        return $this->hasOne(Review::class,'order_id')->whereNotNull('delivery_man_id');
    }
    /* order_transaction -> orderTransaction */
    public function orderTransaction() : HasOne
    {
        return $this->hasOne(OrderTransaction::class, 'order_id');
    }

    public function coupon():BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    /* order_status_history -> orderStatusHistory */
    public function orderStatusHistory():HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /* order_details -> orderDetails */
    public function orderDetails() : HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /* offline_payments -> offlinePayments */
    public function offlinePayments() : BelongsTo
    {
        return $this->belongsTo(OfflinePayments::class, 'id', 'order_id');
    }

    /* verification_images -> verificationImages */
    public function verificationImages(): HasMany
    {
        return $this->hasMany(OrderDeliveryVerification::class,'order_id');
    }


    protected static function boot(): void
    {
        parent::boot();
        //static::addGlobalScope(new RememberScope);
    }
}
