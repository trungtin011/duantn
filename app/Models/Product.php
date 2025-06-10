<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
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
        'barcode',
        'quantity',
        'weight',
        'dimensions',
        'brand',
        'category',
        'sub_category',
        'status',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sold_quantity' => 'integer',
        'stock_total' => 'integer',
        'quantity' => 'integer',
        'weight' => 'decimal:2',
        'dimensions' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'productID');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'productID');
    }

    public function dimension(): HasOne
    {
        return $this->hasOne(ProductDimension::class, 'productID');
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
        if (!$this->hasDiscount()) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function isOutOfStock()
    {
        return $this->stock_total <= 0 || $this->status === 'out_of_stock';
    }

    public function getTotalStockAttribute()
    {
        return $this->stock_total + $this->variants->sum('stock');
    }
} 