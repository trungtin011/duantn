<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'productID',
        'image_path',
        'variantID',
        'is_default',
        'display_order',
        'alt_text'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'display_order' => 'integer'
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productID');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variantID');
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByDisplayOrder($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
} 