<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\ShopQaMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;
use App\Enums\UserRole;

class ChatController extends Controller
{
    public function getShopsToChat()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get all shops
        $shops = Shop::all();

        return response()->json($shops->map(function ($shop) use ($user) {
            $lastMessage = ShopQaMessage::where('shop_id', $shop->id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            return [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name,
                'shop_logo' => $shop->shop_logo,
                'last_message' => $lastMessage ? $lastMessage->message : 'Bắt đầu chat',
            ];
        }));
    }

    public function getMessagesByShopId($shopId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $messages = ShopQaMessage::where('shop_id', $shopId)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages->map(function ($message) {
            $senderName = '';
            $senderAvatar = '';

            if ($message->sender_type === 'user') {
                $user = User::find($message->user_id);
                $senderName = $user ? $user->fullname : 'Unknown User';
                $senderAvatar = $user ? $user->avatar : null;
            } elseif ($message->sender_type === 'shop') {
                $shop = Shop::find($message->shop_id);
                $senderName = $shop ? $shop->shop_name : 'Unknown Shop';
                $senderAvatar = $shop ? $shop->shop_logo : null;
            }

            return [
                'id' => $message->id,
                'shop_id' => $message->shop_id,
                'user_id' => $message->user_id,
                'sender_type' => $message->sender_type,
                'message' => $message->message,
                'image_url' => $message->image_url,
                'created_at' => $message->created_at->toIso8601String(),
                'sender_name' => $senderName,
                'sender_avatar' => $senderAvatar,
            ];
        }));
    }

    public function getQaMessagesForSeller()
    {
        $user = Auth::user();
        if (!$user || !($user->role === UserRole::SELLER) || !($user->shop)) {
            return response()->json([], 403); // Forbidden if not a seller or no shop
        }

        $shopId = $user->shop->id;

        // Get distinct user_ids that have chatted with this shop
        $chattingUserIds = ShopQaMessage::where('shop_id', $shopId)
            ->distinct('user_id')
            ->pluck('user_id');

        $conversations = [];

        foreach ($chattingUserIds as $userId) {
            $customerUser = User::find($userId);
            if ($customerUser) {
                $lastMessage = ShopQaMessage::where('shop_id', $shopId)
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $conversations[] = [
                    'user_id' => $customerUser->id,
                    'user_name' => $customerUser->fullname ?? $customerUser->username,
                    'user_avatar' => $customerUser->avatar,
                    'shop_id' => $shopId,
                    'last_message' => $lastMessage ? $lastMessage->message : 'Bắt đầu chat',
                    'last_message_time' => $lastMessage ? $lastMessage->created_at->toIso8601String() : null,
                ];
            }
        }

        // Optionally, sort conversations by last message time
        usort($conversations, function ($a, $b) {
            return strtotime($b['last_message_time']) - strtotime($a['last_message_time']);
        });

        return response()->json($conversations);
    }

    public function getMessagesByShopIdForSeller($userId)
    {
        $seller = Auth::user();
        if (!$seller || !($seller->role === UserRole::SELLER) || !($seller->shop)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $shopId = $seller->shop->id;

        $messages = ShopQaMessage::where('shop_id', $shopId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages->map(function ($message) {
            $senderName = '';
            $senderAvatar = '';

            if ($message->sender_type === 'user') {
                $user = User::find($message->user_id);
                $senderName = $user ? $user->fullname : 'Unknown User';
                $senderAvatar = $user ? $user->avatar : null;
            } elseif ($message->sender_type === 'shop') {
                $shop = Shop::find($message->shop_id);
                $senderName = $shop ? $shop->shop_name : 'Unknown Shop';
                $senderAvatar = $shop ? $shop->shop_logo : null;
            }

            return [
                'id' => $message->id,
                'shop_id' => $message->shop_id,
                'user_id' => $message->user_id,
                'sender_type' => $message->sender_type,
                'message' => $message->message,
                'image_url' => $message->image_url,
                'created_at' => $message->created_at->toIso8601String(),
                'sender_name' => $senderName,
                'sender_avatar' => $senderAvatar,
            ];
        }));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,id', // user_id is now always required to identify the conversation partner
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        $senderType = Auth::user()->role === UserRole::SELLER ? 'shop' : 'user';

        $message = ShopQaMessage::create([
            'shop_id' => $request->shop_id,
            'user_id' => $request->user_id, // Use the user_id from the request, which is the conversation partner
            'sender_type' => $senderType,
            'message' => $request->message,
            'image_url' => $imagePath,
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'success', 'message' => 'Message sent!', 'data' => $message]);
    }
}
