<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $fillable = [
        'product_id',
        'brand_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
