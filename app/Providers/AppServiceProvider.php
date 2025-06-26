<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Enums\UserRole;
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
                $notifications = Notification::where(function ($query) {
                    $query->where('receiver_shop_id', Auth::user()->shop->id)
                        ->orWhere(function ($q) {
                            $q->where('receiver_type', 'all')
                                ->orWhere('receiver_type', 'shop');
                        });
                })
                    ->whereIn('id', function ($query) {
                        $query->selectRaw('MIN(id)')
                            ->from('notifications')
                            ->groupBy('title', 'type', 'receiver_type');
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->groupBy('type');

                $view->with('groupedNotifications', $notifications);
            } else if (Auth::check() && Auth::user()->role === UserRole::CUSTOMER) {
                $notifications = Notification::where(function ($query) {
                    $query->where('receiver_user_id', Auth::id())
                        ->orWhere(function ($q) {
                            $q->where('receiver_type', 'all')
                                ->orWhere('receiver_type', UserRole::CUSTOMER);
                        });
                })
                    ->whereIn('id', function ($query) {
                        $query->selectRaw('MIN(id)')
                            ->from('notifications')
                            ->groupBy('title', 'type', 'receiver_type');
                    })
                    ->orderBy('created_at', 'desc')
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
