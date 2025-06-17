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
                $notifications = Notification::where(function($query) {
                    $query->where('receiver_user_id', Auth::id())
                        ->orWhere(function($q) {
                            $q->where('receiver_type', 'all')
                                ->orWhere('receiver_type', 'users');
                        });
                })
                ->whereIn('id', function($query) {
                    $query->selectRaw('MIN(id)')
                        ->from('notification')
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
