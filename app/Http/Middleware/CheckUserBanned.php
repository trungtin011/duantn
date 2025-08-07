<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserBanned
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->status && $user->status->value === 'banned') {
            Auth::logout();
            return response()->view('error.user_banned', [], 403);
        }
        return $next($request);
    }
}
