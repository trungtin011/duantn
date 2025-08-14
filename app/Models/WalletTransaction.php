<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model {
    protected $table = 'wallet_transactions';

    protected $fillable = [
        'shop_wallet_id', 'order_id', 'amount', 'direction', 'type', 'description', 'status'
    ];

    public function wallet() {
        return $this->belongsTo(ShopWallet::class);
    }
}

