<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $fillable = ['shop_id', 'amount', 'status', 'bank_account'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
