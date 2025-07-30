<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('403');
        }
        $user = Auth::user();
        if ($user->isBanned()) {
            Auth::logout();
            return redirect()->route('403');
        }

        if ($user->role->value !== $role) {
            return redirect()->route('403');
        }

        return $next($request);
    }
}
