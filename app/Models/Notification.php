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
        'receiver_user_id',
        'receiver_shop_id',
        'title',
        'content',
        'type',
        'reference_id',
        'reference_type',
        'priority',
        'status',
        'receiver_type',
        'read_at',
        'expired_at',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'priority' => 'integer',
        'expired_at' => 'datetime'
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