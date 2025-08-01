<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;  
use Illuminate\Support\Facades\Log;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {   
        if ($this->notification->receiver_type === 'shop') {
            $shop_noti = 'seller';
            return new Channel('shop.' . $shop_noti);
        } else if ($this->notification->receiver_type === 'user') {
            $customer_noti = 'customer';
            return new Channel('user.' . $customer_noti);
        } else {
            return new Channel('notifications.all');
        }
    }

    public function broadcastAs()
    {
        if ($this->notification->receiver_type === 'shop') {
            return 'seller-notification.event';
        }
    
        if ($this->notification->receiver_type === 'user') {
            return 'customer-notification.event';
        }

        if ($this->notification->receiver_type === 'admin') {
            return 'admin-notification.event';
        }

        return 'new-notification.event';
    }
    
    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'title' => $this->notification->title,
            'content' => $this->notification->content,
            'image_path' => $this->notification->image_path,
            'receiver_type' => $this->notification->receiver_type,
            'type' => $this->notification->type,
            'priority' => $this->notification->priority,
            'expired_at' => $this->notification->expired_at ? $this->notification->expired_at->toDateTimeString() : null,
            'created_at' => $this->notification->created_at->toDateTimeString(),
        ];
    }
}