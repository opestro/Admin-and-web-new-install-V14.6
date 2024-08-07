<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactMessage
 *
 * @property int $id Primary
 * @property string $name
 * @property string $email
 * @property string $mobile_number
 * @property string $subject
 * @property string $message
 * @property bool $seen
 * @property string $feedback
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $reply
 *
 * @package App\Models
 */
class Contact extends Model
{
    protected $casts = [
        'id' => 'integer',
        'seen' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'subject',
        'message',
        'seen',
        'feedback',
        'reply',
    ];

    protected $table = 'contacts';
}
