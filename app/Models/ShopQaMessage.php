<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopQaMessage extends Model
{
    protected $table = 'shop_qa_messages';
    public $timestamps = false;

    protected $fillable = [
        'shop_id', 'user_id', 'sender_type', 'message', 'image_url', 'product_id', 'created_at'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
