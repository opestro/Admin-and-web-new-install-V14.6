<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReviewReply
 * @package App\Models
 *
 * @property int $id
 * @property int $review_id
 * @property int|null $added_by_id
 * @property string $added_by Possible values: customer, seller, admin, deliveryman
 * @property string|null $reply_text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ReviewReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'added_by_id',
        'added_by',
        'reply_text',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'review_id' => 'integer',
        'added_by_id' => 'integer',
        'added_by' => 'string',
        'reply_text' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
