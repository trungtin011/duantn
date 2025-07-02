<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class QrLoginController extends Controller
{
   public function showQrLogin()
{
    $token = Str::random(32);
    Cache::put('qr_login_token_' . $token, null, now()->addMinutes(2));

    $qrUrl = route('qr.confirm.form') . '?token=' . $token;

    $qr_svg = \QrCode::format('svg')->size(200)->generate($qrUrl); // Tạo mã SVG

    return view('auth.qr-login', [
        'qr_svg' => $qr_svg,
        'token' => $token,
    ]);
}


 public function generate()
{
    $token = Str::random(32);
    Cache::put('qr_login_token_' . $token, null, now()->addMinutes(2)); // token sống 2 phút

    $qrUrl = route('qr.confirm.form') . '?token=' . $token;

    return response()->json([
        'token' => $token,
        'qr_url' => $qrUrl,
        'qr_svg' => \QrCode::format('svg')->size(200)->generate($qrUrl),
    ]);
}


    public function confirm(Request $request)
    {
        $token = $request->token;
        $user = User::where('email', $request->email)->first(); // có thể thay bằng user_id hoặc mã đăng nhập

        if ($user) {
            Cache::put("qr_login_{$token}", $user->id, 120);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }

    public function poll(Request $request)
    {
        $token = $request->token;
        $userId = Cache::get("qr_login_{$token}");

        if ($userId) {
            Auth::loginUsingId($userId);
            Cache::forget("qr_login_{$token}");
            return response()->json(['authenticated' => true]);
        }

        return response()->json(['authenticated' => false]);
    }
}
