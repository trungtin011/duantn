<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review'; 

    protected $fillable = [
        'userID',
        'productID',
        'shopID',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'userID');
    }
    public function product()
    {
        return $this->belongsTo(Product::class , 'productID');
    }
}