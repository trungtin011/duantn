<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notifications.all', function ($user) {
    return true;    
});

Broadcast::channel('order.created.{shop_id}', function ($user, $shop_id) {
    return (int)$user->shop->id === (int)$shop_id;
});
