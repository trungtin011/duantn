<?php

namespace App\Helpers;

use Carbon\Carbon;

class DashboardHelper
{
    /**
     * Format số tiền
     */
    public static function formatMoney($amount)
    {
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }

    /**
     * Format phần trăm
     */
    public static function formatPercentage($value)
    {
        return number_format($value, 1) . '%';
    }

    /**
     * Format số lượng
     */
    public static function formatNumber($number)
    {
        return number_format($number, 0, ',', '.');
    }

    /**
     * Format ngày tháng
     */
    public static function formatDate($date, $format = 'd/m/Y H:i')
    {
        if ($date instanceof Carbon) {
            return $date->format($format);
        }
        return Carbon::parse($date)->format($format);
    }

    /**
     * Lấy label cho trạng thái đơn hàng
     */
    public static function getOrderStatusLabel($status)
    {
        $labels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Lấy badge color cho trạng thái đơn hàng
     */
    public static function getOrderStatusBadge($status)
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary'
        ];

        return $badges[$status] ?? 'secondary';
    }

    /**
     * Tính tăng trưởng
     */
    public static function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Lấy màu cho biểu đồ
     */
    public static function getChartColors()
    {
        return [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
            '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
        ];
    }
} 