<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class CreateOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $notification;
    public $shop_id;

    public function __construct( $shop_id, Order $order)
    {
        $this->order = $order;
        $this->shop_id = $shop_id;
        $this->notification = $this->storeNotification($order);
    }

    public function broadcastAs()
    {
        return 'create-order.event';
    }

    public function broadcastWith()
    {
        Log::info(' /////////////// Broadcast With ///////////////');
        return [
            'title' => 'Bạn có 1 đơn hàng mới ' . $this->order->order_code,
            'content' => 'Đơn hàng ' . $this->order->order_code . ' đã được đặt',
            'receiver_type' => 'shop',
            'type' => 'order',
            'priority' => 'high',
            'expired_at' => null,
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function broadcastOn(): array
    {
        Log::info(' /////////////// Broadcast On /////////////// ', [
            'shop_id' => $this->shop_id
        ]);
        return [
            new PrivateChannel('order.created.' . $this->shop_id)        
        ];
    }

    private function storeNotification($order)
    {
        $data = $this->broadcastWith();

        Log::info(' /////////////// Store Notification /////////////// ', [
            'data' => $data
        ]);
        $notification = new Notification();
        $notification->title = $data['title'];
        $notification->content = $data['content'];
        $notification->receiver_type = $data['receiver_type'];
        $notification->receiver_shop_id = $this->shop_id;
        $notification->type = $data['type'];
        $notification->priority = $data['priority'];
        $notification->expired_at = $data['expired_at'];
        $notification->created_at = $data['created_at'];
        $notification->save();
    }
}
