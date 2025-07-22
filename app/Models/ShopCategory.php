<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $fillable = ['shop_id', 'name'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
