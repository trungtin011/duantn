<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $table = 'shop_order';
    protected $fillable = ['shopID', 'orderID', 'shipping_provider', 'shipping_fee', 'tracking_code', 'expected_delivery_date', 'actual_delivery_date', 'status', 'note'];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }
}
