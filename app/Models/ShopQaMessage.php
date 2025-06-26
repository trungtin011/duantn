<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopQaMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id', 'user_id', 'sender_type', 'message'
    ];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
