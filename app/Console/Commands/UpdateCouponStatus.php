<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coupon;
use Carbon\Carbon;

class UpdateCouponStatus extends Command
{

    protected $signature = 'coupon:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái coupon hết hạn và hết số lượng';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu cập nhật trạng thái coupon...');

        // Cập nhật coupon hết hạn
        $expiredCount = Coupon::where('end_date', '<', now())
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        $this->info("Đã cập nhật {$expiredCount} coupon hết hạn.");

        // Cập nhật coupon hết số lượng
        $outOfStockCount = Coupon::where('status', 'active')
            ->whereRaw('(quantity > 0 AND used_count >= quantity) OR (max_uses_total > 0 AND used_count >= max_uses_total)')
            ->update(['status' => 'inactive']);

        $this->info("Đã cập nhật {$outOfStockCount} coupon hết số lượng.");

        // Cập nhật coupon sắp hết hạn (còn 7 ngày)
        $expiringSoonCount = Coupon::where('end_date', '<=', Carbon::now()->addDays(7))
            ->where('end_date', '>', now())
            ->where('status', 'active')
            ->where('is_active', 1)
            ->get()
            ->each(function ($coupon) {
                // Có thể gửi thông báo cho seller ở đây
                $this->line("Coupon {$coupon->code} sẽ hết hạn vào {$coupon->end_date}");
            })
            ->count();

        $this->info("Có {$expiringSoonCount} coupon sắp hết hạn trong 7 ngày tới.");

        $this->info('Hoàn thành cập nhật trạng thái coupon!');
    }
}

