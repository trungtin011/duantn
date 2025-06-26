<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'shop_id',
        'sender_id',
        'title',
        'content',
        'type',
        'reference_id',
        'receiver_type',
        'priority',
        'status',
    ];

    // Relationships
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function sender(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
   
} 