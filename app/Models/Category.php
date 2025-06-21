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
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'categoryID');
    }
}
