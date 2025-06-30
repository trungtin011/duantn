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

    // Relationships
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'orderID');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'shop_orderID');
    }
}
