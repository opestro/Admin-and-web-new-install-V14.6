<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Seller;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Message
 *
 * @property int $id Primary
 * @property int $user_id
 * @property int $seller_id
 * @property int $admin_id
 * @property int $delivery_man_id
 * @property string $message
 * @property string attachment
 * @property bool $sent_by_customer
 * @property bool $sent_by_seller
 * @property bool $sent_by_admin
 * @property bool $sent_by_delivery_man
 * @property bool $seen_by_customer
 * @property bool $seen_by_seller
 * @property bool $seen_by_admin
 * @property bool $seen_by_delivery_man
 * @property bool $status
 * @property string $notification_receiver
 * @property bool $seen_notification
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $shop_id
 *
 * @package App\Models
 */
class Chatting extends Model
{
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'seller_id' => 'integer',
        'admin_id' => 'integer',
        'message' => 'string',
        'delivery_man_id' => 'integer',
        'sent_by_customer' => 'boolean',
        'sent_by_seller' => 'boolean',
        'sent_by_admin' => 'boolean',
        'sent_by_delivery_man' => 'boolean',
        'seen_by_customer' => 'boolean',
        'seen_by_seller' => 'boolean',
        'seen_by_admin' => 'boolean',
        'seen_by_delivery_man' => 'boolean',
        'status' => 'boolean',
        'notification_receiver' => 'string',
        'seen_notification' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shop_id' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'seller_id',
        'admin_id',
        'delivery_man_id',
        'message',
        'attachment',
        'sent_by_customer',
        'sent_by_seller',
        'sent_by_admin',
        'sent_by_delivery_man',
        'seen_by_customer',
        'seen_by_seller',
        'seen_by_admin',
        'seen_by_delivery_man',
        'status',
        'notification_receiver',
        'seen_notification',
        'shop_id',
    ];

    protected $guarded=[];

    /* seller_info -> sellerInfo*/
    public function sellerInfo():BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function customer():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function shop():BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    /* delivery_man -> deliveryMan*/
    public function deliveryMan():BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }
    public function admin():BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
