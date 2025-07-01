<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'items_order';

    protected $fillable = [
        'orderID', // Đổi từ order_id thành orderID để khớp với bảng
        'shop_orderID',
        'productID', // Đổi từ product_id thành productID
        'variantID', // Đổi từ variant_id thành variantID
        'product_name',
        'brand',
        'category',
        'variant_name',
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

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_orderID', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productID'); // Đúng với productID
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
