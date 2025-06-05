<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBrand extends Model
{
    protected $table = 'sub_brand'; // Đảm bảo khớp với tên bảng trong SQL
    protected $fillable = [
        'brandID',
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

    // Mối quan hệ với Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brandID');
    }
}
