<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsSeller
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->shop) {
            return $next($request);
        }

        return redirect()->route('seller.register')->with('error', 'Bạn cần đăng ký cửa hàng để tiếp tục');
    }
}
