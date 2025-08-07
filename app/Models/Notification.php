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
        'image_path',
        'type',
        'reference_id',
        'receiver_type',
        'priority',
        'status',
        'order_code',       
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->hasMany(NotificationReceiver::class, 'notification_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_code', 'order_code');
    }
}
