<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductView;
use Carbon\Carbon;

class ProductViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::take(10)->get();
        $users = User::where('role', 'customer')->take(20)->get();
        
        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->info('Không có sản phẩm hoặc user để tạo dữ liệu mẫu');
            return;
        }

        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ];

        $ipAddresses = [
            '192.168.1.1',
            '192.168.1.2',
            '192.168.1.3',
            '10.0.0.1',
            '10.0.0.2',
            '172.16.0.1',
            '172.16.0.2'
        ];

        $createdCount = 0;

        foreach ($products as $product) {
            // Tạo lượt xem cho mỗi sản phẩm
            $viewCount = rand(5, 50); // Số lượt xem ngẫu nhiên từ 5-50
            
            for ($i = 0; $i < $viewCount; $i++) {
                $user = $users->random();
                $userAgent = $userAgents[array_rand($userAgents)];
                $ipAddress = $ipAddresses[array_rand($ipAddresses)];
                
                // Tạo thời gian xem ngẫu nhiên trong 30 ngày gần đây
                $viewedAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                // Kiểm tra xem đã có lượt xem này chưa
                $existingView = ProductView::where('product_id', $product->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                if (!$existingView) {
                    ProductView::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'ip_address' => $ipAddress,
                        'user_agent' => $userAgent,
                        'viewed_at' => $viewedAt,
                    ]);
                    
                    $createdCount++;
                }
            }
        }

        $this->command->info("Đã tạo {$createdCount} lượt xem sản phẩm mẫu");
    }
}
