<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Shop;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for customer-shop chat
// customer listens to chat.{shop_id}.{user_id}
// seller listens to chat.{shop_id}.{user_id}
Broadcast::channel('chat.{shopId}.{userId}', function ($user, $shopId, $userId) {
    // If the authenticated user is the customer in this conversation
    if ($user->id == $userId && $user->role->value === UserRole::CUSTOMER->value) {
        return true;
    }

    // If the authenticated user is a seller and owns the shop in this conversation
    if ($user->role->value === UserRole::SELLER->value) {
        $shop = Shop::where('user_id', $user->id)->first();
        if ($shop && $shop->id == $shopId) {
            return true;
        }
    }

    return false;
});

// Private channel for seller shop notifications (general notifications for new messages)
// Seller listens to seller.shop.notifications.{shop_id}
Broadcast::channel('seller.shop.notifications.{shopId}', function ($user, $shopId) {
    if ($user->role->value === UserRole::SELLER->value) {
        $shop = Shop::where('user_id', $user->id)->first();
        return $shop && $shop->id == $shopId;
    }
    return false;
}); 

Broadcast::channel('user.{role}', function ($user, $role) {
    Log::info('Log tại channel user.' . $role);
    return (int) $user->role === (int) $role;
});

Broadcast::channel('shop.{role}', function ($user, $role) {
    return (int) $user->role === (int) $role;
});

Broadcast::channel('notifications.all', function () {
    return true;    
});

Broadcast::channel('order.created.{shop_id}', function ($user, $shop_id) {
    return (int)$user->shop->id === (int)$shop_id;
});

Broadcast::channel('order-status-update.{user_id}', function ($user, $user_id) {
    return (int)$user->id === (int)$user_id;
});
