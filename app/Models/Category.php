<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; // Đảm bảo khớp với tên bảng trong SQL
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

    // Mối quan hệ với SubCategory
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'categoryID');
    }
}
