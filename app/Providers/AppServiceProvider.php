<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Enums\UserRole;
use App\Models\NotificationReceiver;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        
        View::composer('layouts.app', function ($view) {
            $settings = DB::table('settings')->first();
            $view->with('settings', $settings);
        });

        Blade::component('order-block', 'user.order.components.order-block');
        view()->composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === UserRole::SELLER) {
                // Lấy danh sách receiver_id từ NotificationReceiver
                $receiver = NotificationReceiver::where(function ($query) {
                    $query->where('receiver_id', Auth::user()->shop->id)
                          ->orWhere(function ($q) {
                              $q->where('receiver_type', 'all')
                                ->orWhere('receiver_type', 'shop');
                          });
                })->pluck('notification_id'); // Giả sử NotificationReceiver có cột notification_id liên kết với notifications
            
                // Lấy danh sách notifications
                $notifications = Notification::whereIn('id', function ($query) use ($receiver) {
                    $query->selectRaw('MIN(id)') // Chỉ lấy id nhỏ nhất để tránh trùng lặp
                          ->from('notifications')
                          ->whereIn('id', $receiver) // So sánh với notification_id từ NotificationReceiver
                          ->where('receiver_type', 'shop')
                          ->groupBy('title', 'type', 'receiver_type');
                })
                ->where('receiver_type', 'shop')
                ->take(10)
                ->get()
                ->groupBy('type');
            
                $view->with('groupedNotifications', $notifications);
                
            } else if (Auth::check() && Auth::user()->role === UserRole::CUSTOMER) {
                $receiver = NotificationReceiver::where(function ($query) {
                    $query->where('receiver_id', Auth::id())
                          ->orWhere(function ($q) {
                              $q->where('receiver_type', 'all')
                                ->orWhere('receiver_type', 'user');
                          });
                })->pluck('notification_id');
            
                $notifications = Notification::whereIn('id', function ($query) use ($receiver) {
                    $query->selectRaw('MIN(id)')
                          ->from('notifications')
                          ->whereIn('id', $receiver)
                          ->where('receiver_type', 'user')
                          ->groupBy('title', 'type', 'receiver_type');
                })
                ->where('receiver_type', 'user')
                ->take(10)
                ->get()
                ->groupBy('type');
            
                $view->with('groupedNotifications', $notifications);
            } else {
                $view->with('groupedNotifications', collect());
            }
        });
    }
    
}
