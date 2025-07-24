<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->shop_id = $shop_id;
    }

    public function broadcastAs()
    {
        return 'update-order';
    }

    public function broadcastWith()
    {
        return [
            'shop_id' => $this->shop_id,
            'order' => $this->order
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order.updated.' . $this->shop_id)
        ];
    }
}
