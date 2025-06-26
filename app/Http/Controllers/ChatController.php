<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\ShopQaMessage;
use App\Models\User;
use App\Models\Notification;
use App\Models\AutoChatSetting;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSent;
use App\Enums\UserRole;

class ChatController extends Controller
{
    /**
     * Lấy danh sách shop để khách hàng chat
     */
    public function getShopsToChat()
    {
        $user = Auth::user();

        if (!$user || $user->role !== UserRole::CUSTOMER) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Lấy danh sách shop mà user đã nhắn tin
        $shopIds = ShopQaMessage::where('user_id', $user->id)
            ->distinct()
            ->pluck('shop_id');

        $shops = Shop::whereIn('id', $shopIds)->get();

        return response()->json($shops->map(function ($shop) use ($user) {
            $lastMessage = ShopQaMessage::where('shop_id', $shop->id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Kiểm tra trạng thái online của shop
            $isOnline = DB::table('sessions')
                ->where('user_id', $shop->ownerID)
                ->where('last_activity', '>=', now()->subMinutes(5))
                ->exists();

            return [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name ?? '',
                'shop_logo' => $shop->shop_logo ? Storage::url($shop->shop_logo) : null,
                'last_message' => $lastMessage ? ($lastMessage->message ?: '(Ảnh)') : 'Bắt đầu chat',
                'last_message_time' => $lastMessage ? $lastMessage->created_at->toIso8601String() : null,
                'is_online' => $isOnline,
            ];
        }));
    }

    /**
     * Lấy danh sách khách hàng đã nhắn tin với shop (dành cho seller)
     */
    public function getUsersToChat(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== UserRole::SELLER) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $shop = Shop::where('ownerID', $user->id)->first();
        if (!$shop) {
            return response()->json(['error' => 'Shop not found'], 404);
        }

        // Lấy danh sách user đã nhắn tin với shop
        $userIds = ShopQaMessage::where('shop_id', $shop->id)
            ->distinct()
            ->pluck('user_id');

        $users = User::whereIn('id', $userIds)->get();

        return response()->json($users->map(function ($user) use ($shop) {
            $lastMessage = ShopQaMessage::where('shop_id', $shop->id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Kiểm tra trạng thái online của user
            $isOnline = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '>=', now()->subMinutes(5))
                ->exists();

            return [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'user_name' => $user->fullname ?? $user->username,
                'user_avatar' => $user->avatar ? Storage::url($user->avatar) : null,
                'last_message' => $lastMessage ? ($lastMessage->message ?: '(Ảnh)') : 'Bắt đầu chat',
                'last_message_time' => $lastMessage ? $lastMessage->created_at->toIso8601String() : null,
                'is_online' => $isOnline,
            ];
        }));
    }

    /**
     * Lấy tin nhắn giữa user và shop
     */
    public function getMessagesByShopId($shopId, Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $perPage = $request->input('per_page', 20);
        $query = ShopQaMessage::where('shop_id', $shopId);

        if ($user->role === UserRole::CUSTOMER) {
            $query->where('user_id', $user->id);
        } elseif ($user->role === UserRole::SELLER) {
            $shop = Shop::where('ownerID', $user->id)->first();
            if (!$shop || $shop->id != $shopId) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        return response()->json($messages->map(function ($message) {
            $senderName = '';
            $senderAvatar = '';
            $product = null;

            if ($message->sender_type === 'user') {
                $user = User::find($message->user_id);
                $senderName = $user ? ($user->fullname ?? $user->username) : 'Unknown User';
                $senderAvatar = $user ? ($user->avatar ? Storage::url($user->avatar) : null) : null;
            } elseif ($message->sender_type === 'shop') {
                $shop = Shop::find($message->shop_id);
                $senderName = $shop ? ($shop->shop_name ?? 'Unknown Shop') : 'Unknown Shop';
                $senderAvatar = $shop ? ($shop->shop_logo ? Storage::url($shop->shop_logo) : null) : null;
            }

            if ($message->product_id) {
                $product = Product::find($message->product_id);
                $product = $product ? [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image ? Storage::url($product->image) : null,
                ] : null;
            }

            return [
                'id' => $message->id,
                'shop_id' => $message->shop_id,
                'user_id' => $message->user_id,
                'sender_type' => $message->sender_type,
                'message' => $message->message,
                'image_url' => $message->image_url ? Storage::url($message->image_url) : null,
                'product' => $product,
                'created_at' => $message->created_at->toIso8601String(),
                'sender_name' => $senderName,
                'sender_avatar' => $senderAvatar,
            ];
        }));
    }

    /**
     * Gửi tin nhắn
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'nullable|exists:products,id',
        ]);

        // Kiểm tra quyền gửi tin nhắn
        if ($user->role === UserRole::SELLER) {
            $shop = Shop::where('ownerID', $user->id)->first();
            if (!$shop || $shop->id != $request->shop_id) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } elseif ($user->role !== UserRole::CUSTOMER || $user->id != $request->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        $senderType = $user->role === UserRole::SELLER ? 'shop' : 'user';

        $message = ShopQaMessage::create([
            'shop_id' => $request->shop_id,
            'user_id' => $request->user_id,
            'sender_type' => $senderType,
            'message' => $request->message,
            'image_url' => $imagePath,
            'product_id' => $request->product_id,
        ]);

        // Tạo thông báo
        $shop = Shop::find($request->shop_id);
        $notificationData = [
            'sender_id' => $user->id,
            'type' => 'new_message',
            'status' => 'unread',
            'title' => 'Tin nhắn mới',
        ];

        if ($senderType === 'user') {
            $notificationData['receiver_shop_id'] = $request->shop_id;
            $notificationData['receiver_type'] = 'shop';
            $notificationData['content'] = "Bạn có tin nhắn mới từ {$user->username}";
        } else {
            $notificationData['receiver_user_id'] = $request->user_id;
            $notificationData['receiver_type'] = 'user';
            $notificationData['content'] = "Shop {$shop->shop_name} đã trả lời bạn";
        }

        Notification::create($notificationData);

        // Kiểm tra trả lời tự động
        if ($senderType === 'user') {
            $autoChatSettings = AutoChatSetting::where('user_id', $shop->ownerID)->first();
            if ($autoChatSettings && $autoChatSettings->auto_reply_enabled) {
                $autoMessage = ShopQaMessage::create([
                    'shop_id' => $request->shop_id,
                    'user_id' => $request->user_id,
                    'sender_type' => 'shop',
                    'message' => 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ trả lời sớm nhất có thể.',
                ]);
                broadcast(new MessageSent($autoMessage))->toOthers();
            }
        }

        // Broadcast tin nhắn
        broadcast(new MessageSent($message))->toOthers();

        // Đảm bảo image_url là URL đầy đủ
        if ($message->image_url) {
            $message->image_url = Storage::url($message->image_url);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent!',
            'data' => [
                'id' => $message->id,
                'shop_id' => $message->shop_id,
                'user_id' => $message->user_id,
                'sender_type' => $message->sender_type,
                'message' => $message->message,
                'image_url' => $message->image_url,
                'product_id' => $message->product_id,
                'created_at' => $message->created_at->toIso8601String(),
                'sender_name' => $senderType === 'user' ? ($user->fullname ?? $user->username) : $shop->shop_name,
                'sender_avatar' => $senderType === 'user' ? ($user->avatar ? Storage::url($user->avatar) : null) : ($shop->shop_logo ? Storage::url($shop->shop_logo) : null),
            ],
        ]);
    }
}