<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointTransaction extends Model
{
    use HasFactory;

    protected $table = 'point_transactions';


    protected $fillable = [
        'userID',
        'points',
        'type',
        'description',
        'orderID',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'points' => 'integer',
        'type' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'userID', 'id');
    }
}
