<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ShopOrder;

class OrderStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shopOrder;
    public $status;
    public $statusText;

    public function __construct(ShopOrder $shopOrder, $status)
    {
        $this->shopOrder = $shopOrder;
        $this->status = $status;
        $this->statusText = $this->getStatusText($status);
    }

    public function build()
    {
        return $this->subject('Cập nhật trạng thái đơn hàng #' . $this->shopOrder->order_code)
            ->view('emails.order-status-update')
            ->with([
                'shopOrder' => $this->shopOrder,
                'status' => $this->status,
                'statusText' => $this->statusText,
                'order' => $this->shopOrder->order,
                'user' => $this->shopOrder->order->user,
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