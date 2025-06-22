<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Models\User;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('403');
        }

        $user = Auth::user();

        // Kiểm tra nếu người dùng bị khóa
        if ($user->isBanned()) {
            Auth::logout(); // Đăng xuất người dùng nếu họ bị khóa
            return redirect()->route('403');
        }

        // Kiểm tra vai trò
        if ($user->role !== $role && !in_array($user->role, UserRole::cases())) {
            return redirect()->route('403');
        }

        return $next($request);
    }
}
