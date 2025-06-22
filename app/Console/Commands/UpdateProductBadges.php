<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Review;
use DB;

class UpdateProductBadges extends Command
{
    protected $signature = 'products:update-badges';
    protected $description = 'Tự động gắn nhãn sản phẩm (Mới, Bán chạy, Hot)';

    public function handle()
    {
        $now = Carbon::now();

        // 1. Gắn nhãn "Mới" (trong vòng 3 ngày)
        Product::query()
            ->update(['is_new' => false]); // reset
        Product::where('created_at', '>=', $now->subDays(3))
            ->update(['is_new' => true]);

        // 2. Gắn nhãn "Bán chạy" (top sản phẩm bán nhiều trong 7 ngày)
        // $topSellingProductIds = OrderItem::where('created_at', '>=', $now->subDays(7))
        //     // ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
        //     // ->groupBy('product_id')
        //     // ->orderByDesc('total_sold')
        //     ->limit(5)
        //     ->pluck('product_id')
        //     ->toArray();

        // Product::query()->update(['is_best_seller' => false]); // reset
        // Product::whereIn('id', $topSellingProductIds)->update(['is_best_seller' => true]);

        // 3. Gắn nhãn "Hot" (nhiều lượt xem hoặc đánh giá 5★ gần đây)
        $hotProductIds = Review::where('created_at', '>=', $now->subDays(5))
            ->where('rating', 5)
            ->select('productID', DB::raw('COUNT(*) as five_star_count'))
            ->groupBy('productID')
            ->having('five_star_count', '>=', 4)
            ->pluck('productID')
            ->toArray();


        Product::query()->update(['is_hot' => false]); // reset
        Product::whereIn('id', $hotProductIds)->update(['is_hot' => true]);

        $this->info("Cập nhật nhãn thành công.");
    }
}

