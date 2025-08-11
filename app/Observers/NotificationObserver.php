<?php

namespace App\Observers;

use App\Models\Notification;
use Illuminate\Support\Facades\Cache;

class NotificationObserver
{
    /**
     * Handle the Notification "created" event.
     */
    public function created(Notification $notification): void
    {
        // Clear cache cho tất cả users khi có thông báo mới
        $this->clearNotificationCache();
    }

    /**
     * Handle the Notification "updated" event.
     */
    public function updated(Notification $notification): void
    {
        // Clear cache khi thông báo được cập nhật
        $this->clearNotificationCache();
    }

    /**
     * Handle the Notification "deleted" event.
     */
    public function deleted(Notification $notification): void
    {
        // Clear cache khi thông báo bị xóa
        $this->clearNotificationCache();
    }

    /**
     * Clear notification cache for all users
     */
    private function clearNotificationCache(): void
    {
        // Clear cache cho tất cả users
        // Có thể cải thiện bằng cách lưu danh sách user IDs trong cache
        Cache::flush();
        
        // Hoặc clear specific cache keys nếu biết user IDs
        // foreach ($userIds as $userId) {
        //     Cache::forget('user_notifications_' . $userId);
        // }
    }
}
