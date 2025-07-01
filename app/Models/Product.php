<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'shopID',
        'name',
        'slug',
        'description',
        'price',
        'purchase_price',
        'sale_price',
        'sold_quantity',
        'stock_total',
        'sku',
        'brand',
        'category',
        'sub_category',
        'sub_brand',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'is_variant',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sold_quantity' => 'integer',
        'stock_total' => 'integer',
        'is_featured' => 'boolean',
        'is_variant' => 'boolean',
    ];

    // Relationships
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function variantAttributeValues()
    {
        return $this->hasMany(ProductVariantAttributeValue::class, 'product_id');
    }

    // App/Models/Product.php
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute', 'product_id', 'attribute_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'productID');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'productID');
    }

    public function dimension()
    {
        return $this->hasMany(ProductDimension::class, 'productID');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'productID');
    }

    public function defaultImage(): HasOne
    {
        return $this->hasOne(ProductImage::class, 'productID')->where('is_default', true);
    }

    // Mối quan hệ với bảng orders thông qua bảng trung gian items_order
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'items_order', 'productID', 'orderID')
            ->withPivot('variantID', 'quantity', 'unit_price', 'total_price', 'discount_amount')
            ->withTimestamps();
        return $this->hasMany(ProductVariantAttributeValue::class, '', 'id');
    }

    public function attributeValues(): HasMany
    {
        return $this->hasManyThrough(
            AttributeValue::class,
            ProductVariant::class,
            'productID',
            'id',
            'id',
            'id'
        )->join('product_variant_attribute_values', 'attribute_values.id', '=', 'product_variant_attribute_values.attribute_value_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_total', '>', 0)
            ->where('status', '!=', 'out_of_stock');
    }

    public function dimensions()
    {
        return $this->hasMany(ProductDimension::class, 'productID');
    }

    // Nếu cần mối quan hệ với tất cả kích thước (bao gồm biến thể)
    public function allDimensions()
    {
        return $this->hasMany(ProductDimension::class, 'productID', 'id');
    }

    // Methods
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function hasDiscount()
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->hasDiscount()) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function isOutOfStock()
    {
        return $this->stock_total <= 0 || $this->status === 'out_of_stock';
    }

    public function getTotalStockAttribute()
    {
        return $this->stock_total + $this->variants->sum('stock');
    }

    public function getImageUrlAttribute()
    {
        $mainImage = $this->images()->where('is_default', 1)->first();
        if ($mainImage) {
            return Storage::url($mainImage->image_path);
        }
        return Storage::url('product_images/default.png');
    }

    protected $dates = ['flash_sale_end_at'];

    public function isFlashSaleActive()
    {
        return $this->flash_sale_price && now()->lt($this->flash_sale_end_at);
    }
    public function orderReviews()
{
    return $this->hasMany(OrderReview::class, 'product_id');
}

}
