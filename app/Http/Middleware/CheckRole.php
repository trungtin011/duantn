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
            Log::info('CheckRole: User not authenticated');
            return redirect()->route('403');
        }

        $user = Auth::user();

        Log::info('CheckRole', [
            'user_id' => $user->id,
            'user_role' => $user->role->value ?? 'null',
            'required_role' => $role,
            'is_banned' => $user->isBanned(),
        ]);

        if ($user->isBanned()) {
            Log::info('CheckRole: User is banned', ['user_id' => $user->id]);
            Auth::logout();
            return redirect()->route('403');
        }

        if ($user->role->value !== $role) {
            Log::info('CheckRole: Role mismatch', [
                'user_role' => $user->role->value ?? 'null',
                'required_role' => $role
            ]);
            return redirect()->route('403');
        }

        return $next($request);
    }
}
