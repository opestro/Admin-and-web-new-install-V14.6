<?php

namespace App\Models;

use App\Traits\StorageTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class RefundRequest extends Model
{
    use HasFactory,StorageTrait;

    protected $fillable = [
        'order_details_id',
        'customer_id',
        'status',
        'approved_count',
        'denied_count',
        'amount',
        'product_id',
        'order_id',
        'refund_reason',
        'images',
        'approved_note',
        'rejected_note',
        'payment_info',
        'change_by',
    ];
    protected $casts = [
        'order_details_id' => 'integer',
        'customer_id' => 'integer',
        'status'=>'string',
        'amount' => 'float',
        'product_id' => 'integer',
        'order_id' => 'integer',
        'refund_reason'=>'string',
        'approved_note'=>'string',
        'rejected_note'=>'string',
        'payment_info'=>'string',
        'change_by'=>'string',
        'images' => 'array'
    ];

    public function customer():BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    /* order_details->orderDetails */
    public function orderDetails():BelongsTo
    {
        return $this->belongsTo(OrderDetail::class,'order_details_id');
    }
    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    /* refund_status->refundStatus */
    public function refundStatus():HasMany
    {
        return $this->hasMany(RefundStatus::class,'refund_request_id');
    }
    public function getImagesFullUrlAttribute():array|null
    {
        $images = [];
        $value = $this->images;
        if ($value){
            foreach ($value as $item){
                $item = isset($item['image_name']) ? (array)$item : ['image_name' => $item, 'storage' => 'public'];
                $images[] =  $this->storageLink('refund',$item['image_name'],$item['storage'] ?? 'public');
            }
        }
        return $images;
    }
    protected $appends = ['images_full_url'];
}
