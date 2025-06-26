<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrderHistory extends Model
{
    protected $table = 'history_order_shop';
    protected $fillable = ['shop_order_id', 'status'];

    public function shopOrder()
    {
        return $this->belongsTo(ShopOrder::class);
    }
}
