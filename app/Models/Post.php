<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public const REACTION_TYPES = ['smile','love','grin','wow','joy','angry'];
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'quote',
        'photo',
        'tags',
        'post_cat_id',
        'post_tag_id',
        'added_by',
        'status'
    ];

    public function cat_info()
    {
        return $this->hasOne('App\Models\PostCategory', 'id', 'post_cat_id');
    }
    public function tag_info()
    {
        return $this->hasOne('App\Models\PostTag', 'id', 'post_tag_id');
    }

    public function author_info()
    {
        return $this->hasOne('App\Models\User', 'id', 'added_by');
    }
    public static function getAllPost()
    {
        return Post::with(['cat_info', 'author_info'])->orderBy('id', 'DESC')->paginate(10);
    }
    // public function get_comments(){
    //     return $this->hasMany('App\Models\PostComment','post_id','id');
    // }
    public static function getPostBySlug($slug)
    {
        return Post::with(['tag_info', 'author_info'])->where('slug', $slug)->where('status', 'active')->first();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id')
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->with(['user', 'children.user'])
            ->orderBy('id', 'DESC');
    }
    public function allComments()
    {
        return $this->hasMany(Comment::class, 'post_id')
            ->where('status', 'approved');
    }


    // public static function getProductByCat($slug){
    //     // dd($slug);
    //     return Category::with('products')->where('slug',$slug)->first();
    //     // return Product::where('cat_id',$id)->where('child_cat_id',null)->paginate(10);
    // }

    // public static function getBlogByCategory($id){
    //     return Post::where('post_cat_id',$id)->paginate(8);
    // }
    public static function getBlogByTag($slug)
    {
        // dd($slug);
        return Post::where('tags', $slug)->paginate(8);
    }

    // Thả cảm xúc (likes) cho bài viết
    public function likes()
    {
        return $this->hasMany(PostReaction::class, 'post_id');
    }

    public function reactionCountByType(string $type): int
    {
        return $this->likes()->where('type', $type)->count();
    }

    public static function countActivePost()
    {
        $data = Post::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }
}

