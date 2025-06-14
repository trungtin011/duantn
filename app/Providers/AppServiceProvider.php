<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share notifications to all views
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $notifications = \App\Models\Notification::where('receiver_user_id', Auth::id())
                    ->orWhere('receiver_type', 'all')
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
