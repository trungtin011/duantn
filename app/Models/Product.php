<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

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

    //linh
    public function defaultImage()
    {
        return $this->hasOne(ProductImage::class, 'productID')->where('is_default', true);
    }
    //linh

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shopID');
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

    public function attribute()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function dimensions()
    {
        return $this->hasOne(ProductDimension::class, 'productID');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class)->with('user');
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

    // public function attributes()
    // {
    //     return $this->hasMany(\App\Models\Attribute::class);
    // }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes');
    }

    public function attributeValues()
    {
        return $this->hasManyThrough(\App\Models\AttributeValue::class, \App\Models\Attribute::class, 'product_id', 'attribute_id');
    }

    // Phương thức để lấy đường dẫn ảnh chính
    public function getImageUrlAttribute()
    {
        $mainImage = $this->images()->where('is_default', 1)->first();
        if ($mainImage) {
            return Storage::url($mainImage->image_path); // Tạo URL từ đường dẫn lưu trữ
        }
        return Storage::url('product_images/default.png'); // Ảnh mặc định nếu không có
    }
// App\Models\Product.php
public function seller()
{
    return $this->belongsTo(Seller::class, 'shopID', 'id'); // nếu shopID là seller_id
}


}
