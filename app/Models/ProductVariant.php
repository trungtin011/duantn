<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    protected $fillable = [
        'productID',
        'variant_name',
        'price',
        'purchase_price',
        'sale_price',
        'stock',
        'sku',
        'status',
        'vat_amount',
        'discount_type',
        'size',
        'color',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer'
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productID');
    }

    public function dimension(): HasOne
    {
        return $this->hasOne(ProductDimension::class, 'variantID');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variantID');
    }

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0)
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
        return $this->stock <= 0 || $this->status === 'out_of_stock';
    }
}
