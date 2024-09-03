<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'email',
        'phone',
        'amount',
        // 'status',
        'payment_response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
