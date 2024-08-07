<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class SupportTicketConv
 *
 * @property int $id
 * @property int|null $support_ticket_id
 * @property int|null $admin_id
 * @property string|null $customer_message
 * @property string|null $admin_message
 * @property int $position
 * @property array|null $attachment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class SupportTicketConv extends Model
{
    Use StorageTrait;
    protected $fillable = [
        'support_ticket_id',
        'admin_id',
        'customer_message',
        'admin_message',
        'position',
        'attachment',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'support_ticket_id' => 'integer',
        'attachment' => 'array',
        'admin_id' => 'integer',
        'position' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function adminInfo(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id','id');
    }
    public function getAttachmentFullUrlAttribute():array|null
    {
        $images = [];
        $value = $this->attachment;
        if ($value){
            foreach ($value as $item){
                $item = isset($item['file_name']) ? (array)$item : ['file_name' => $item, 'storage' => 'public'];
                $images[] =  $this->storageLink('support-ticket',$item['file_name'],$item['storage'] ?? 'public');
            }
        }
        return $images;
    }
    protected $appends = ['attachment_full_url'];

}
