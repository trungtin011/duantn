<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\ShopQaMessage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatController extends Controller
{
    // List các shop mà customer đã chat
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role != UserRole::CUSTOMER) abort(403);

        $shopIds = ShopQaMessage::where('user_id', $user->id)
            ->distinct()->pluck('shop_id');
        $shops = Shop::whereIn('id', $shopIds)->get();

        // Nếu có shop_id truyền vào (từ nút Nhắn tin), lấy shop này
        $shopProduct = null;
        if ($request->has('shop_id')) {
            $shopProduct = Shop::find($request->shop_id);
        }

        return view('chat.index', compact('shops', 'shopProduct'));
    }

    // Lấy lịch sử chat với 1 shop
    public function messages($shop_id)
    {
        $user = Auth::user();
        if ($user->role != UserRole::CUSTOMER) abort(403);

        $shop = Shop::findOrFail($shop_id);

        $messages = ShopQaMessage::where('shop_id', $shop_id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'shop' => [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name,
                'shop_logo_url' => $shop->shop_logo ? \Illuminate\Support\Facades\Storage::url($shop->shop_logo) : asset('images/default_shop_logo.png'),
            ],
            'messages' => $messages
        ]);
    }

    // Gửi tin nhắn
    public function send(Request $request, $shop_id)
    {
        $user = Auth::user();
        if ($user->role != UserRole::CUSTOMER) abort(403);

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $msg = ShopQaMessage::create([
            'shop_id' => $shop_id,
            'user_id' => $user->id,
            'sender_type' => 'user',
            'message' => $request->message,
            'created_at' => now()
        ]);

        event(new MessageSent([
            'id' => $msg->id,
            'content' => $msg->message,
            'created_at' => $msg->created_at->toDateTimeString(),
            'sender_type' => $msg->sender_type,
            'shop_id' => $msg->shop_id,
            'user_id' => $msg->user_id,
        ]));

        return response()->json($msg);
    }

    public function popup(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response('Vui lòng đăng nhập để chat!', 401);

        $shopIds = ShopQaMessage::where('user_id', $user->id)
            ->distinct()->pluck('shop_id');
        $shops = Shop::whereIn('id', $shopIds)->get();

        // Nếu có shop_id truyền vào (từ nút Nhắn tin), lấy shop này
        $shopProduct = null;
        if ($request->has('shop_id')) {
            $shopProduct = Shop::find($request->shop_id);
        }

        return view('chat.popup', compact('shops', 'shopProduct'));
    }

    public function sellerIndex(Request $request)
    {
        $user = Auth::user();
        if ($user->role != UserRole::SELLER) abort(403);

        $shop = Shop::where('ownerID', $user->id)->first();
        if (!$shop) abort(404, 'Shop not found');

        $customerIds = ShopQaMessage::where('shop_id', $shop->id)
            ->distinct()->pluck('user_id');
        $customers = User::whereIn('id', $customerIds)->get();

        $customerSelected = null;
        if ($request->has('customer_id')) {
            $customerSelected = User::find($request->customer_id);
        }

        return view('seller.chat.chatseller', compact('customers', 'customerSelected', 'shop'));
    }

    public function sellerMessages($customer_id)
    {
        $user = Auth::user();
        if ($user->role != UserRole::SELLER) abort(403);

        $shop = Shop::where('ownerID', $user->id)->first();
        if (!$shop) abort(404, 'Shop not found');

        $customer = User::findOrFail($customer_id);

        $messages = ShopQaMessage::where('shop_id', $shop->id)
            ->where('user_id', $customer_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'fullname' => $customer->fullname,
                'username' => $customer->username,
                'email' => $customer->email,
                'avatar_url' => $customer->avatar ? \Illuminate\Support\Facades\Storage::url($customer->avatar) : asset('images/default_avatar.png'),
            ],
            'messages' => $messages
        ]);
    }

    public function sellerSend(Request $request, $customer_id)
    {
        $user = Auth::user();
        if ($user->role != UserRole::SELLER) abort(403);

        $shop = Shop::where('ownerID', $user->id)->first();
        if (!$shop) abort(404, 'Shop not found');

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $msg = ShopQaMessage::create([
            'shop_id' => $shop->id,
            'user_id' => $customer_id,
            'sender_type' => 'seller',
            'message' => $request->message,
            'created_at' => now()
        ]);

        event(new MessageSent([
            'id' => $msg->id,
            'content' => $msg->message,
            'created_at' => $msg->created_at->toDateTimeString(),
            'sender_type' => $msg->sender_type,
            'shop_id' => $msg->shop_id,
            'user_id' => $msg->user_id,
        ]));

        return response()->json($msg);
    }
}