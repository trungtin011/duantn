<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpCategory extends Model
{
   protected $fillable = ['title', 'slug', 'parent_id', 'sort_order', 'status', 'icon'];

    public function children()
    {
        return $this->hasMany(HelpCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(HelpCategory::class, 'parent_id');
    }

    public function articles()
    {
        return $this->hasMany(HelpArticle::class, 'category_id');
    }
}
