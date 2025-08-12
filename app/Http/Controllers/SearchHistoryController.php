<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SearchHistoryController extends Controller
{
    /**
     * Lưu lịch sử tìm kiếm vào session
     */
    public function store(Request $request)
    {
        $query = trim($request->input('query'));
        
        if (empty($query)) {
            return response()->json(['success' => false, 'message' => 'Query không được để trống']);
        }

        // Lấy lịch sử tìm kiếm hiện tại từ session
        $searchHistory = Session::get('search_history', []);
        
        // Loại bỏ query trùng lặp và thêm query mới vào đầu
        $searchHistory = array_filter($searchHistory, function($item) use ($query) {
            return strtolower($item) !== strtolower($query);
        });
        
        // Thêm query mới vào đầu mảng
        array_unshift($searchHistory, $query);
        
        // Giới hạn chỉ lưu 10 lịch sử gần nhất
        $searchHistory = array_slice($searchHistory, 0, 10);
        
        // Lưu vào session
        Session::put('search_history', $searchHistory);
        
        return response()->json([
            'success' => true, 
            'message' => 'Đã lưu lịch sử tìm kiếm',
            'history' => $searchHistory
        ]);
    }

    /**
     * Lấy lịch sử tìm kiếm từ session
     */
    public function index()
    {
        $searchHistory = Session::get('search_history', []);
        
        return response()->json([
            'success' => true,
            'history' => $searchHistory
        ]);
    }

    /**
     * Xóa một item khỏi lịch sử tìm kiếm
     */
    public function destroy(Request $request)
    {
        $query = $request->input('query');
        $searchHistory = Session::get('search_history', []);
        
        // Loại bỏ query khỏi lịch sử
        $searchHistory = array_filter($searchHistory, function($item) use ($query) {
            return $item !== $query;
        });
        
        // Lưu lại vào session
        Session::put('search_history', array_values($searchHistory));
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi lịch sử',
            'history' => $searchHistory
        ]);
    }

    /**
     * Xóa toàn bộ lịch sử tìm kiếm
     */
    public function clear()
    {
        Session::forget('search_history');
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ lịch sử tìm kiếm'
        ]);
    }

    /**
     * Tìm kiếm gợi ý dựa trên lịch sử
     */
    public function suggestions(Request $request)
    {
        $query = trim($request->input('query'));
        
        if (empty($query)) {
            return response()->json(['success' => true, 'suggestions' => []]);
        }

        $searchHistory = Session::get('search_history', []);
        $suggestions = [];
        
        // Tìm các gợi ý từ lịch sử
        foreach ($searchHistory as $historyItem) {
            if (stripos($historyItem, $query) !== false) {
                $suggestions[] = $historyItem;
            }
        }
        
        // Giới hạn 5 gợi ý
        $suggestions = array_slice($suggestions, 0, 5);
        
        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Tìm kiếm sản phẩm nhanh cho gợi ý
     */
    public function quickSearch(Request $request)
    {
        $query = trim($request->input('query'));
        
        if (empty($query)) {
            return response()->json(['success' => true, 'products' => []]);
        }

        try {
            // Tìm kiếm sản phẩm nhanh
            $products = \App\Models\Product::where('status', 'active')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->with(['images', 'shop', 'variants'])
                ->take(5)
                ->get()
                ->map(function ($product) {
                    // Xử lý ảnh sản phẩm
                    $image = null;
                    if ($product->images->isNotEmpty()) {
                        $mainImage = $product->images->where('is_default', true)->first();
                        if (!$mainImage) {
                            $mainImage = $product->images->first();
                        }
                        if ($mainImage) {
                            $image = \Illuminate\Support\Facades\Storage::url($mainImage->image_path);
                        }
                    }

                    // Xử lý giá sản phẩm - ưu tiên giá từ variants
                    $price = 0;
                    $salePrice = 0;
                    $finalPrice = 0;

                    if ($product->variants->isNotEmpty()) {
                        // Nếu có variants, lấy giá thấp nhất từ variants
                        $minPrice = $product->variants->min('price') ?? 0;
                        $minSalePrice = $product->variants->min('sale_price') ?? 0;
                        
                        $price = $minPrice;
                        $salePrice = $minSalePrice;
                        
                        // Nếu có giá khuyến mãi và nhỏ hơn giá gốc thì sử dụng giá khuyến mãi
                        if ($salePrice > 0 && $salePrice < $price) {
                            $finalPrice = $salePrice;
                        } else {
                            $finalPrice = $price;
                        }
                    } else {
                        // Nếu không có variants, sử dụng giá từ sản phẩm chính
                        $price = $product->price ?? 0;
                        $salePrice = $product->sale_price ?? 0;
                        
                        // Nếu có giá khuyến mãi và nhỏ hơn giá gốc thì sử dụng giá khuyến mãi
                        if ($salePrice > 0 && $salePrice < $price) {
                            $finalPrice = $salePrice;
                        } else {
                            $finalPrice = $price;
                        }
                    }

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'final_price' => $finalPrice,
                        'has_variants' => $product->variants->isNotEmpty(),
                        'image' => $image,
                        'shop_name' => $product->shop ? $product->shop->shop_name : 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'products' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Có lỗi xảy ra khi tìm kiếm sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }
}
