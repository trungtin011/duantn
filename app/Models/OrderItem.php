<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'items_order'; // 👈 THÊM DÒNG NÀY

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'unit_price',
        'total_price',
        'discount_amount',
        'sku',
        'product_name',
        'brand',
        'category',
        'sub_category',
        'color',
        'size',
        'variant_name',
        'product_image',
        'note',
        'is_reviewed'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_reviewed' => 'boolean'
    ];

    protected $appends = ['subtotal']; // 👈 Để dùng accessor subtotal

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID');
    }


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id'); // 👈 Sửa từ productID
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Methods
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }

    public function getDiscountAmountAttribute(): float
    {
        return $this->subtotal - $this->total_price;
    }
}
