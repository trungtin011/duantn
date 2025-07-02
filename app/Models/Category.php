<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'parent_id'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Mối quan hệ với SubCategory
    // public function subCategories()
    // {
    //     return $this->hasMany(SubCategory::class, 'categoryID', 'id');
    // }



    // public function parentCategory()
    // {
    //     return $this->belongsTo(Category::class, 'parent_id');
    // }
    
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'id');
    }
}
