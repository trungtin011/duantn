<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\ShopOrder;
use App\Models\Order;
use App\Mail\OrderStatusUpdateMail;
use App\Mail\CreateOrderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailHelper
{
    /**
     * Gửi email thông báo cập nhật trạng thái đơn hàng
     */
    public static function sendOrderStatusUpdateMail(ShopOrder $shopOrder, $status)
    {
        try {
            $user = $shopOrder->order->user;
            
            if (!$user || !$user->email) {
                Log::warning('Không thể gửi email: User không tồn tại hoặc không có email', [
                    'user_id' => $user->id ?? null,
                    'order_code' => $shopOrder->order_code
                ]);
                return false;
            }

            Mail::to($user->email)->send(new OrderStatusUpdateMail($shopOrder, $status));
            
            Log::info('Email thông báo cập nhật trạng thái đơn hàng đã được gửi', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'order_code' => $shopOrder->order_code,
                'status' => $status
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email thông báo cập nhật trạng thái đơn hàng', [
                'order_code' => $shopOrder->order_code,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Gửi email thông báo chung
     */
    public static function sendNotificationMail($userEmail, $subject, $view, $data = [])
    {
        try {
            if (!$userEmail) {
                Log::warning('Không thể gửi email: Email không hợp lệ', [
                    'email' => $userEmail
                ]);
                return false;
            }

            Mail::send($view, $data, function ($message) use ($userEmail, $subject) {
                $message->to($userEmail)
                        ->subject($subject);
            });
            
            Log::info('Email thông báo đã được gửi', [
                'email' => $userEmail,
                'subject' => $subject
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email thông báo', [
                'email' => $userEmail,
                'subject' => $subject,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Gửi email cho nhiều người dùng cùng lúc
     */
    public static function sendBulkNotificationMail($userEmails, $subject, $view, $data = [])
    {
        try {
            $validEmails = array_filter($userEmails);
            
            if (empty($validEmails)) {
                Log::warning('Không có email hợp lệ để gửi');
                return false;
            }

            Mail::send($view, $data, function ($message) use ($validEmails, $subject) {
                $message->to($validEmails)
                        ->subject($subject);
            });
            
            Log::info('Email thông báo hàng loạt đã được gửi', [
                'email_count' => count($validEmails),
                'subject' => $subject
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email thông báo hàng loạt', [
                'email_count' => count($userEmails),
                'subject' => $subject,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Gửi email thông báo đơn hàng bị hủy
     */
    public static function sendOrderCancelledMail(ShopOrder $shopOrder, $reason = null)
    {
        $data = [
            'shopOrder' => $shopOrder,
            'order' => $shopOrder->order,
            'user' => $shopOrder->order->user,
            'reason' => $reason
        ];

        return self::sendNotificationMail(
            $shopOrder->order->user->email,
            'Đơn hàng #' . $shopOrder->order_code . ' đã bị hủy',
            'emails.order-cancelled',
            $data
        );
    }

    /**
     * Gửi email thông báo đơn hàng đã giao thành công
     */
    public static function sendOrderDeliveredMail(ShopOrder $shopOrder)
    {
        $data = [
            'shopOrder' => $shopOrder,
            'order' => $shopOrder->order,
            'user' => $shopOrder->order->user
        ];

        return self::sendNotificationMail(
            $shopOrder->order->user->email,
            'Đơn hàng #' . $shopOrder->order_code . ' đã được giao thành công',
            'emails.order-delivered',
            $data
        );
    }

    /**
     * Gửi email thông báo đặt hàng thành công
     */
    public static function sendCreateOrderMail(Order $order, $status = 'pending')
    {
        try {
            $user = $order->user;
            
            if (!$user || !$user->email) {
                Log::warning('Không thể gửi email đặt hàng: User không tồn tại hoặc không có email', [
                    'user_id' => $user->id ?? null,
                    'order_code' => $order->order_code
                ]);
                return false;
            }

            Mail::to($user->email)->send(new CreateOrderMail($order, $status));
            
            Log::info('Email thông báo đặt hàng thành công đã được gửi', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'order_code' => $order->order_code,
                'status' => $status
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email thông báo đặt hàng', [
                'order_code' => $order->order_code,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Gửi email thông báo đặt hàng thành công (phiên bản đơn giản)
     */
    public static function sendSimpleCreateOrderMail($userEmail, $orderCode, $orderData = [])
    {
        $data = array_merge([
            'order_code' => $orderCode,
            'order_date' => now()->format('d/m/Y H:i'),
        ], $orderData);

        return self::sendNotificationMail(
            $userEmail,
            'Đặt hàng thành công - Đơn hàng #' . $orderCode,
            'emails.create-order-simple',
            $data
        );
    }
} 