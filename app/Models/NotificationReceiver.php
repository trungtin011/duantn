<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationReceiver extends Model
{   
    public $timestamps = false;
    protected $table = 'notification_receiver';
    protected $fillable = ['notification_id', 'receiver_id', 'receiver_type', 'is_read', 'read_at'];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }

    public function receiver_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function receiver_shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'receiver_id', 'id');
    }
}
