<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopOrder extends Model
{
    protected $table = 'shop_order';

    protected $fillable = [
        'shopID',
        'orderID',
        'code',
        'shipping_shop_fee',
        'discount_shop_amount',
        'shipping_provider',
        'shipping_fee',
        'tracking_code',
        'expected_delivery_date',
        'actual_delivery_date',
        'status',
        'note',
    ];

    protected $casts = [
        'expected_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'status' => 'string',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }

    public function items()
    {
        return $this->hasMany(ItemsOrder::class, 'shop_orderID', 'id');
    }

    public function history(){
        return $this->hasMany(ShopOrderHistory::class, 'shop_order_id', 'id');
    }
}
