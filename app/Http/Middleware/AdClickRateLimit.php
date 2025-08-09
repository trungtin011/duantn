<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AdClickRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ áp dụng cho route quảng cáo
        if ($request->is('ad/*') || $request->has('campaign_id')) {
            $ip = $request->ip();
            $key = "ad_click_rate_{$ip}";
            
            // Giới hạn 5 click/phút cho mỗi IP
            if (Cache::has($key) && Cache::get($key) >= 5) {
                return response()->json([
                    'error' => 'Quá nhiều yêu cầu! Vui lòng thử lại sau.',
                    'retry_after' => Cache::get($key . '_time', 60)
                ], 429);
            }
            
            // Tăng counter
            if (!Cache::has($key)) {
                Cache::put($key, 1, 60); // 1 phút
                Cache::put($key . '_time', 60, 60);
            } else {
                Cache::increment($key);
            }
        }
        
        return $next($request);
    }
}
