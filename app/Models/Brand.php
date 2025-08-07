<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand'; // Đảm bảo khớp với tên bảng trong SQL
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function subBrands()
    {
        return $this->hasMany(Brand::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Brand::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Brand::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_brands', 'brand_id', 'product_id');
    }
}
