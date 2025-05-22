<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDimension extends Model
{
    protected $fillable = [
        'product_id',
        'variant_id',
        'length',
        'width',
        'height',
        'weight'
    ];

    protected $casts = [
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'weight' => 'decimal:2'
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Methods
    public function getVolumeAttribute()
    {
        return $this->length * $this->width * $this->height;
    }

    public function getDimensionsStringAttribute()
    {
        return "{$this->length} x {$this->width} x {$this->height}";
    }
} 