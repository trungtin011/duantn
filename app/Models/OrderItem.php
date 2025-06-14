<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'items_order';
    protected $primaryKey = 'id';
    protected $fillable = [
        'orderID',
        'shop_orderID',
        'productID',
        'variantID',
        'product_name',
        'brand',
        'category',
        'attribute_value',
        'attribute_name',
        'product_image',
        'quantity',
        'unit_price',
        'total_price',
        'discount_amount',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productID', 'id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variantID', 'id');
    }

    public function shopOrder(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class, 'shop_orderID', 'id');
    }

    // Methods
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function getDiscountAmountAttribute()
    {
        return $this->discount_amount ?? ($this->subtotal - $this->total_price);
    }
}