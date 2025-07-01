<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    protected $fillable = ['title', 'slug', 'category_id', 'content', 'status'];

    public function category()
    {
        return $this->belongsTo(HelpCategory::class, 'category_id');
    }
}
