<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'product_id',
        'category_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
