<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $fillable = [
        'categoryID',
        'name',
        'slug',
        'description',
        'image_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryID');
    }
}
