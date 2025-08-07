<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\ShopQaMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        // Tính số tin nhắn chưa đọc cho mỗi shop
        $unreadCounts = [];
        foreach ($shops as $shop) {
            // Lấy tin nhắn cuối cùng từ user (để biết user đã đọc đến đâu)
            $lastUserMessage = ShopQaMessage::where('shop_id', $shop->id)
                ->where('user_id', $user->id)
                ->where('sender_type', 'user')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($lastUserMessage) {
                // Đếm tin nhắn từ shop sau tin nhắn cuối cùng của user
                $unreadCount = ShopQaMessage::where('shop_id', $shop->id)
                    ->where('user_id', $user->id)
                    ->where('sender_type', 'shop')
                    ->where('created_at', '>', $lastUserMessage->created_at)
                    ->count();
            } else {
                // Nếu user chưa gửi tin nhắn nào, đếm tất cả tin nhắn từ shop
                $unreadCount = ShopQaMessage::where('shop_id', $shop->id)
                    ->where('user_id', $user->id)
                    ->where('sender_type', 'shop')
                    ->count();
            }
            
            $unreadCounts[$shop->id] = $unreadCount;
        }

        $shopProduct = null;
        if ($request->has('shop_id')) {
            $shopProduct = Shop::find($request->shop_id);
        }

        // Lấy sản phẩm nếu có product_id
        $productContext = null;
        if ($request->has('product_id')) {
            $productContext = \App\Models\Product::find($request->product_id);
        }

        // Lấy tin nhắn ban đầu nếu có shop_id
        $messages = collect();
        if ($request->has('shop_id') && $shopProduct) {
            $messages = ShopQaMessage::with('product:id,name,slug')
                ->where('shop_id', $shopProduct->id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('chat.index', compact('shops', 'shopProduct', 'productContext', 'messages', 'unreadCounts'));
    }

    // Lấy lịch sử chat với 1 shop
    public function messages($shop_id)
    {
        $user = Auth::user();
        if ($user->role != UserRole::CUSTOMER) abort(403);

        $shop = Shop::findOrFail($shop_id);

        $messages = ShopQaMessage::with('product:id,name,slug') // Eager load product
            ->where('shop_id', $shop_id)
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
            'message' => 'nullable|string|max:1000',
            'product_id' => 'nullable|exists:products,id', // Validate product_id
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validate image
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('chat_images', $imageName, 'public');
            $imageUrl = Storage::url($imagePath);
        }

        $msg = ShopQaMessage::create([
            'shop_id' => $shop_id,
            'user_id' => $user->id,
            'sender_type' => 'user',
            'message' => $request->message,
            'image_url' => $imageUrl,
            'product_id' => $request->product_id, // Lưu product_id
            'created_at' => now()
        ]);

        event(new MessageSent([
            'id' => $msg->id,
            'content' => $msg->message,
            'image_url' => $imageUrl,
            'created_at' => $msg->created_at->toDateTimeString(),
            'sender_type' => $msg->sender_type,
            'shop_id' => $msg->shop_id,
            'user_id' => $msg->user_id,
        ]));

        // Return properly formatted response
        return response()->json([
            'id' => $msg->id,
            'sender_type' => $msg->sender_type,
            'message' => $msg->message,
            'image_url' => $msg->image_url,
            'created_at' => $msg->created_at->toISOString(),
            'shop_id' => $msg->shop_id,
            'user_id' => $msg->user_id,
            'product_id' => $msg->product_id
        ]);
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

        // Lấy số tin nhắn chưa đọc cho mỗi khách hàng
        $unreadCounts = [];
        foreach ($customers as $customer) {
            $unreadCounts[$customer->id] = ShopQaMessage::where('shop_id', $shop->id)
                ->where('user_id', $customer->id)
                ->where('sender_type', 'user') // Tin nhắn từ khách hàng gửi đến shop
                ->whereNull('read_at')
                ->count();
        }

        $customerSelected = null;
        if ($request->has('customer_id')) {
            $customerSelected = User::find($request->customer_id);
        }

        return view('seller.chat.chatseller', compact('customers', 'customerSelected', 'shop', 'unreadCounts'));
    }

    public function sellerMessages($customer_id)
    {
        $user = Auth::user();
        if ($user->role != UserRole::SELLER) abort(403);

        $shop = Shop::where('ownerID', $user->id)->first();
        if (!$shop) abort(404, 'Shop not found');

        $customer = User::findOrFail($customer_id);

        // Đánh dấu tất cả tin nhắn từ khách hàng là đã đọc khi seller mở cuộc trò chuyện
        ShopQaMessage::where('shop_id', $shop->id)
            ->where('user_id', $customer_id)
            ->where('sender_type', 'user') // Chỉ đánh dấu tin nhắn từ khách hàng
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

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
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validate image
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('chat_images', $imageName, 'public');
            $imageUrl = Storage::url($imagePath);
        }

        $msg = ShopQaMessage::create([
            'shop_id' => $shop->id,
            'user_id' => $customer_id,
            'sender_type' => 'seller',
            'message' => $request->message,
            'image_url' => $imageUrl,
            'created_at' => now()
        ]);

        event(new MessageSent([
            'id' => $msg->id,
            'content' => $msg->message,
            'image_url' => $imageUrl,
            'created_at' => $msg->created_at->toDateTimeString(),
            'sender_type' => $msg->sender_type,
            'shop_id' => $msg->shop_id,
            'user_id' => $msg->user_id,
        ]));

        return response()->json($msg);
    }
}