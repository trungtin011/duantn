<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AdClickService;
use App\Models\AdClick;

class AdClickTracking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName();
        $ip = $request->ip();
        
        // Chỉ xử lý route ad.click
        if ($routeName === 'ad.click') {
            // Kiểm tra các tham số cần thiết
            if ($request->has('ad_click_type') && $request->has('shop_id') && $request->has('campaign_id')) {
                $clickType = $request->get('ad_click_type');
                $shopId = $request->get('shop_id');
                $campaignId = $request->get('campaign_id');
                
                // Kiểm tra rate limit
                if (!AdClick::checkRateLimit($ip, 5, 1)) {
                    session()->flash('ad_click_error', 'Quá nhiều yêu cầu! Vui lòng thử lại sau.');
                    return redirect()->back();
                }
                
                // Ghi nhận click và trừ tiền từ ví shop
                $result = AdClickService::recordClick(
                    $request,
                    $shopId,
                    $campaignId,
                    $request->get('product_id'),
                    $clickType
                );
                
                if ($result['success']) {
                    session()->flash('ad_click_success', 'Click quảng cáo đã được ghi nhận và trừ 1000đ từ ví shop!');
                    
                    // Lưu vào session để tránh click lại
                    $sessionKey = "ad_click_{$campaignId}_{$shopId}";
                    session()->put($sessionKey, true);
                } else {
                    session()->flash('ad_click_error', $result['message']);
                }
            }
        }
        
        // Tạm thời tắt rate limit cho việc test
        // $key = "ad_click_rate_{$ip}";
        // if (!cache()->has($key)) {
        //     cache()->put($key, 1, 60); // 1 phút
        // } else {
        //     $count = cache()->get($key);
        //     if ($count >= 100) { // Tăng giới hạn lên 100 request/phút
        //         return response()->json(['error' => 'Rate limit exceeded'], 429);
        //     }
        //     cache()->increment($key);
        // }
        
        return $next($request);
    }
}
