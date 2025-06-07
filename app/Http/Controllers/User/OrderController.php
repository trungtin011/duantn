<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::with(['items.product', 'items.variant', 'address', 'user'])->findOrFail($id);
        $orderItems = $order->items;
        $orderAddress = $order->address;

        foreach ($orderItems as $item) {
            if (!$item->product_image) {
                $image = ProductImage::where('variant_id', $item->variant_id)
                    ->orWhere(function ($query) use ($item) {
                        $query->where('product_id', $item->product_id)->whereNull('variant_id');
                    })
                    ->where('is_default', true)
                    ->first();
                $item->product_image = $image ? Storage::url($image->image_path) : 'https://via.placeholder.com/40';
            } else {
                $item->product_image = Storage::url($item->product_image);
            }
        }

        Log::info('Order Data:', [
            'order_id' => $id,
            'userID' => $order->userID,
            'order_code' => $order->order_code
        ]);
        Log::info('User Data:', [
            'user_exists' => !is_null($order->user_id),
            'user' => ($order->user_id ? $order->user->toArray() : 'No user associated'),
            'fullname' => ($order->userID ? $order->user->fullname ?? 'Null fullname' : 'N/A')
        ]);
        Log::info('Items Data:', [$orderItems->toArray()]);
        Log::info('Address Data:', [$orderAddress ? $orderItems->toArray() : null]);

        return view('user.order.orderDetail', compact('order', 'orderItems', 'orderAddress'));
    }
}