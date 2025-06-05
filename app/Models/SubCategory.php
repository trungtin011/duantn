<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories'; // Đảm bảo khớp với tên bảng trong SQL
    protected $fillable = [
        'categoryID',
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

    // Mối quan hệ với Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryID');
    }
}
