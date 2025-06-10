<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || (Auth::user()->role !== $role && !in_array(Auth::user()->role, UserRole::cases()))) {
            return redirect()->route('403');
        }

        return $next($request);
    }
}
