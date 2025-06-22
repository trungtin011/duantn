<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Đăng ký các command tùy chỉnh (Artisan)
     */
    protected $commands = [
        // Ví dụ: \App\Console\Commands\UpdateProductLabels::class,
    ];

    /**
     * Lịch chạy các lệnh Artisan (cron job)
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('products:update-badges')->daily();
    }

    /**
     * Đăng ký các file command tự động
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
