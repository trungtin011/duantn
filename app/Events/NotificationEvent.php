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
        return new Channel('notifications.all');
    }

    public function broadcastAs()
    {
        return 'new-notification.event';
    }
    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'title' => $this->notification->title,
            'content' => $this->notification->content,
            'receiver_type' => $this->notification->receiver_type,
            'type' => $this->notification->type,
            'priority' => $this->notification->priority,
            'expired_at' => $this->notification->expired_at ? $this->notification->expired_at->toDateTimeString() : null,
            'created_at' => $this->notification->created_at->toDateTimeString(),
        ];
    }
}