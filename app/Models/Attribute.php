<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $table = 'attributes';

    protected $fillable = ['name'];

    // Relationships
    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    // public function products(): HasMany
    // {
    //     return $this->hasManyThrough(
    //         Product::class,
    //         ProductVariant::class,
    //         'id',
    //         'id',
    //         'id',
    //         'productID'
    //     )->join('product_variant_attribute_values', 'product_variants.id', '=', 'product_variant_attribute_values.product_variant_id')
    //         ->join('attribute_values', 'product_variant_attribute_values.attribute_value_id', '=', 'attribute_values.id')
    //         ->where('attribute_values.attribute_id', $this->id);
    // }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute')
            ->withPivot('id');
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id', 'id');
    }
}
