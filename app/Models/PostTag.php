<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostTag extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'status'];

     public function post()
    {
        return $this->hasMany('App\Models\Post', 'post_tag_id', 'id')->where('status', 'active');
    }

    public static function getBlogByTag($slug)
    {
        return PostCategory::with('post')->where('slug', $slug)->first();
    }
}
