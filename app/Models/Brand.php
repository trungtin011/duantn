<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';

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

    public function subBrands()
    {
        return $this->hasMany(SubBrand::class, 'brandID');
    }
}
