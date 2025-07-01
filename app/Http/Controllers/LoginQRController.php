<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginQRController extends Controller
{
    public function showQR()
    {
        $token = Str::random(40); // mã tạm
        Cache::put('qr_login_' . $token, null, 300); // lưu 5 phút
        return view('auth.qr_login', compact('token'));
    }

    public function handleScan(Request $request)
    {
        $token = $request->input('token');
        $user = auth()->user();

        if (!$user || !$token) {
            return response()->json(['success' => false], 401);
        }

        Cache::put('qr_login_' . $token, $user->id, 300);
        return response()->json(['success' => true]);
    }

    public function checkLogin(Request $request)
    {
        $token = $request->input('token');
        $userId = Cache::get('qr_login_' . $token);

        if ($userId) {
            Auth::loginUsingId($userId);
            Cache::forget('qr_login_' . $token);
            return response()->json(['authenticated' => true]);
        }

        return response()->json(['authenticated' => false]);
    }
}
