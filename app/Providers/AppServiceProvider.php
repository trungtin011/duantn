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
        // Load helper functions
        require_once app_path('Http/Helpers.php');
    }

    public function boot(): void
    {


        View::composer('layouts.app', function ($view) {
            $settings = DB::table('settings')->first();
            $view->with('settings', $settings);
        });

        Blade::component('order-block', 'user.order.components.order-block');
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                if ($user->role === UserRole::ADMIN) {
                    // Logic cho ADMIN - có thể xem tất cả thông báo
                    $notifications = Notification::where(function ($query) {
                        $query->where('receiver_type', 'admin')
                              ->orWhere('receiver_type', 'all');
                    })
                    ->where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->groupBy('type');
                    
                    $view->with('groupedNotifications', $notifications);
                    
                } else if ($user->role === UserRole::SELLER) {
                    $receiver = NotificationReceiver::where(function ($query) {
                        $query->where('receiver_id', Auth::user()->shop->id)
                              ->orWhere(function ($q) {
                                  $q->where('receiver_type', 'all')
                                    ->orWhere('receiver_type', 'shop');
                              })
                              ->where('is_read', 0);
                    })->pluck('notification_id'); 
                
                    $notifications = Notification::whereIn('id', function ($query) use ($receiver) {
                        $query->selectRaw('MIN(id)') 
                              ->from('notifications')
                              ->whereIn('id', $receiver) 
                              ->where('receiver_type', 'shop')
                              ->groupBy('title', 'type', 'receiver_type');
                    })
                    ->where('receiver_type', 'shop')
                    ->orderBy('created_at', 'desc') // mới nhất lên đầu

                    ->take(10)
                    ->get()
                    ->groupBy('type');
                
                    $view->with('groupedNotifications', $notifications);
                    
                } else if ($user->role === UserRole::CUSTOMER) {
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
                    ->orderBy('created_at', 'desc') // mới nhất lên đầu
                    ->take(10)
                    ->get()
                    ->groupBy('type');
                
                    $view->with('groupedNotifications', $notifications);
                    
                } else if ($user->role === UserRole::EMPLOYEE) {
                    // Logic cho EMPLOYEE - tương tự như ADMIN nhưng có thể giới hạn hơn
                    $notifications = Notification::where(function ($query) {
                        $query->where('receiver_type', 'employee')
                              ->orWhere('receiver_type', 'all');
                    })
                    ->where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->groupBy('type');
                    
                    $view->with('groupedNotifications', $notifications);
                    
                } else {
                    $view->with('groupedNotifications', collect());
                }
            } else {
                $view->with('groupedNotifications', collect());
            }
        });
    }
}
