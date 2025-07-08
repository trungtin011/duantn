<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class CreateOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;
    public $statusText;

    public function __construct(Order $order, $status = 'pending')
    {
        $this->order = $order;
        $this->status = $status;
        $this->statusText = $this->getStatusText($status);
    }

    public function build()
    {
        return $this->subject('Đặt hàng thành công - Đơn hàng #' . $this->order->order_code)
            ->view('emails.create-order')
            ->with([
                'order' => $this->order,
                'status' => $this->status,
                'statusText' => $this->statusText,
                'user' => $this->order->user,
                'items' => $this->order->items,
            ]);
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'ready_to_pick' => 'Sẵn sàng lấy hàng',
            'picked' => 'Đã lấy hàng',
            'shipping' => 'Đang vận chuyển',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã hoàn trả',
        ];

        return $statusMap[$status] ?? $status;
    }
} 