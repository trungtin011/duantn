<?php

namespace App\Services;

use App\Models\ProductView;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductViewService
{
    /**
     * Ghi lại lượt xem sản phẩm
     * Mỗi user chỉ được tính 1 lần xem mỗi sản phẩm
     */
    public function recordView(Product $product, Request $request): bool
    {
        try {
            $userId = Auth::id();
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();

            // Kiểm tra xem user đã xem sản phẩm này chưa
            $existingView = ProductView::where('product_id', $product->id)
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
                ->when(!$userId, function ($query) use ($ipAddress) {
                    return $query->where('ip_address', $ipAddress);
                })
                ->first();

            // Nếu đã xem rồi thì không ghi lại
            if ($existingView) {
                // Cập nhật thời gian xem mới nhất
                $existingView->update([
                    'viewed_at' => now(),
                    'user_agent' => $userAgent,
                ]);
                return true;
            }

            // Ghi lại lượt xem mới
            ProductView::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'viewed_at' => now(),
            ]);

            // Xóa cache số lượt xem
            $this->clearViewCountCache($product->id);

            Log::info('Product view recorded', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to record product view', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Lấy số lượt xem của sản phẩm
     */
    public function getViewCount(Product $product): int
    {
        $cacheKey = "product_view_count_{$product->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($product) {
            return ProductView::where('product_id', $product->id)->count();
        });
    }

    /**
     * Lấy số lượt xem theo thời gian
     */
    public function getViewCountByTimeRange(Product $product, string $timeRange = 'all'): int
    {
        $cacheKey = "product_view_count_{$product->id}_{$timeRange}";
        
        return Cache::remember($cacheKey, 1800, function () use ($product, $timeRange) {
            $query = ProductView::where('product_id', $product->id);
            
            switch ($timeRange) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
                case 'all':
                default:
                    // Không áp dụng filter thời gian
                    break;
            }
            
            return $query->count();
        });
    }

    /**
     * Tính toán giá hiển thị cho sản phẩm
     */
    private function calculateDisplayPrices($product): array
    {
        if ($product->is_variant && $product->variants->isNotEmpty()) {
            // Lấy giá thấp nhất từ các biến thể
            $minPrice = $product->variants->min('price') ?? 0;
            $minSalePrice = $product->variants->min('sale_price') ?? 0;
            
            // Nếu có sale_price và nhỏ hơn price thì dùng sale_price
            if ($minSalePrice > 0 && $minSalePrice < $minPrice) {
                return [
                    'display_price' => $minSalePrice,
                    'display_original_price' => $minPrice
                ];
            } else {
                return [
                    'display_price' => $minPrice,
                    'display_original_price' => $minPrice
                ];
            }
        } else {
            // Sản phẩm đơn
            return [
                'display_price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
                'display_original_price' => $product->price
            ];
        }
    }

    /**
     * Lấy danh sách sản phẩm được xem nhiều nhất
     */
    public function getMostViewedProducts(int $limit = 10, string $timeRange = 'all'): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "most_viewed_products_{$timeRange}_{$limit}";
        
        return Cache::remember($cacheKey, 1800, function () use ($limit, $timeRange) {
            $query = ProductView::select('product_id')
                ->selectRaw('COUNT(*) as view_count')
                ->groupBy('product_id')
                ->orderByDesc('view_count');
            
            switch ($timeRange) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
                case 'all':
                default:
                    // Không áp dụng filter thời gian
                    break;
            }
            
            $productIds = $query->limit($limit)->pluck('product_id');
            
            return Product::whereIn('id', $productIds)
                ->where('status', 'active')
                ->with(['images', 'variants' => function($query) {
                    $query->select('id', 'productID', 'price', 'sale_price', 'stock', 'status')
                          ->where('status', 'active');
                }])
                ->get()
                ->map(function ($product) {
                    // Tính toán giá hiển thị dựa trên biến thể hoặc sản phẩm đơn
                    $prices = $this->calculateDisplayPrices($product);
                    $product->display_price = $prices['display_price'];
                    $product->display_original_price = $prices['display_original_price'];
                    
                    return $product;
                })
                ->sortBy(function ($product) use ($productIds) {
                    return array_search($product->id, $productIds->toArray());
                });
        });
    }

    /**
     * Lấy lịch sử xem sản phẩm của user
     */
    public function getUserViewHistory(int $userId, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = "user_view_history_{$userId}_{$limit}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId, $limit) {
            $productIds = ProductView::where('user_id', $userId)
                ->orderByDesc('viewed_at')
                ->limit($limit)
                ->pluck('product_id');
            
            return Product::whereIn('id', $productIds)
                ->where('status', 'active')
                ->with('images')
                ->get()
                ->sortBy(function ($product) use ($productIds) {
                    return array_search($product->id, $productIds->toArray());
                });
        });
    }

    /**
     * Xóa cache số lượt xem
     */
    private function clearViewCountCache(int $productId): void
    {
        $cacheKeys = [
            "product_view_count_{$productId}",
            "product_view_count_{$productId}_today",
            "product_view_count_{$productId}_week",
            "product_view_count_{$productId}_month",
            "most_viewed_products_all_10",
            "most_viewed_products_today_10",
            "most_viewed_products_week_10",
            "most_viewed_products_month_10",
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Lấy thống kê lượt xem theo ngày (7 ngày gần nhất)
     */
    public function getViewStatsByDay(Product $product, int $days = 7): array
    {
        $cacheKey = "product_view_stats_{$product->id}_{$days}_days";
        
        return Cache::remember($cacheKey, 3600, function () use ($product, $days) {
            $stats = [];
            $startDate = now()->subDays($days - 1)->startOfDay();
            
            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $count = ProductView::where('product_id', $product->id)
                    ->whereDate('viewed_at', $date)
                    ->count();
                
                $stats[] = [
                    'date' => $date->format('Y-m-d'),
                    'count' => $count,
                ];
            }
            
            return $stats;
        });
    }
}
