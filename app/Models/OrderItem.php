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
        'attribute_value', // Thay color và size
        'attribute_name', // Thay color và size
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
        return $this->belongsTo(Order::class, 'orderID'); // Chỉ định khóa ngoại là orderID
    }

    public function shopOrder(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class, 'shop_orderID'); // Quan hệ với bảng shop_order
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productID'); // Đúng với productID
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variantID'); // Đúng với variantID
    }

    // Methods
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function getDiscountAmountAttribute()
    {
        // Trả về trực tiếp giá trị từ cột discount_amount
        return $this->discount_amount;
    }
}
