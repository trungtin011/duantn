<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';
    protected $fillable = [
        'user_id',
        'order_id',
        'provider',
        'method',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'raw_response',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
