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
use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationReceiver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderStatusUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $user_id;
    public $status;

    public function __construct($order, $status)
    {
        $this->order = $order;
        $this->user_id = $order->order->userID;
        $this->status = $status;
        $this->storeNotification($this->user_id);
        $this->mailNotification($this->user_id);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order-status-update.' . $this->user_id),
        ];
    }

    public function broadcastAs()
    {
        return 'order-status-update.event';
    }

    public function broadcastWith()
    {
        return [
            'title' => 'Trạng thái đơn hàng ' . $this->order->code,
            'content' => 'Trạng thái đã được cập nhật thành ' . $this->status,
            'receiver_type' => 'user',
            'type' => 'order',
            'priority' => 'normal',
        ];
    }

    private function storeNotification($user_id)
    {
        Log::info(' /////////////// Store Notification /////////////// ', [
            'log store notification trong order status update'
        ]);

        $data = $this->broadcastWith();

        $notification = new Notification();
        $notification->title = $data['title'];
        $notification->content = $data['content'];
        $notification->receiver_type = $data['receiver_type'];
        $notification->type = $data['type'];
        $notification->priority = $data['priority'];
        $notification->save();

        $notificationReceiver = new NotificationReceiver();
        $notificationReceiver->notification_id = $notification->id;
        $notificationReceiver->receiver_id = $user_id;
        $notificationReceiver->receiver_type = 'user';
        $notificationReceiver->save();
        
        Log::info(' /////////////// Store Notification /////////////// ', [
            'notification' => $notification,
            'notificationReceiver' => $notificationReceiver
        ]);
    }

    private function mailNotification($user_id)
    {
        try {
            $user = User::find($user_id);
            
            if (!$user || !$user->email) {
                Log::warning('Không thể gửi email: User không tồn tại hoặc không có email', [
                    'user_id' => $user_id,
                    'order_code' => $this->order->order_code
                ]);
                return;
            }

            // Gửi email thông báo
            Mail::to($user->email)->send(new \App\Mail\OrderStatusUpdateMail($this->order, $this->status));
            
            Log::info('Email thông báo cập nhật trạng thái đơn hàng đã được gửi', [
                'user_id' => $user_id,
                'user_email' => $user->email,
                'order_code' => $this->order->order_code,
                'status' => $this->status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email thông báo cập nhật trạng thái đơn hàng', [
                'user_id' => $user_id,
                'order_code' => $this->order->order_code,
                'error' => $e->getMessage()
            ]);
        }
    }
}
