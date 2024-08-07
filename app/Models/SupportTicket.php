<?php

namespace App\Models;

use App\Traits\StorageTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class SupportTicket
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $subject
 * @property string|null $type
 * @property string $priority
 * @property string|null $description
 * @property array|null $attachment
 * @property string|null $reply
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */

class SupportTicket extends Model
{
    use StorageTrait;
    protected $fillable = [
        'customer_id',
        'subject',
        'type',
        'priority',
        'description',
        'reply',
        'status',
        'created_at',
        'updated_at',
        'attachment'
    ];

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'priority' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'attachment' => 'array'
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(SupportTicketConv::class);
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
