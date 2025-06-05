<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $table = 'attribute_values';
    protected $fillable = ['attribute_id', 'value', 'product_variant_id'];

    public function attribute()
    {
        return $this->belongsTo(\App\Models\Attribute::class, 'attribute_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(\App\Models\ProductVariant::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // Nếu có cột product_id trong bảng attribute_values.
    }
}
