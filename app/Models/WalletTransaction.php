<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'integer',
        'credit' => 'float',
        'debit' => 'float',
        'admin_bonus'=>'float',
        'balance'=>'float',
        'reference'=>'string',
        'created_at'=>'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
