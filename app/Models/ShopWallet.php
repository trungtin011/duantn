<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopWallet extends Model
{
    protected $fillable = ['shop_id', 'balance'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}

