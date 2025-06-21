<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        $userId = Auth::id();
        $orders = Order::with(['items'])
            ->where('userID', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.order.index', compact('orders'));
    }

    public function show($orderID)
    {
        $order = Order::with([
            'items.product.images',
            'items.variant',
            'address',
            'user'
        ])->findOrFail($orderID);
        $orderItems = $order->items;
        $orderAddress = $order->address;

        return view('user.order.detail', compact('order', 'orderItems', 'orderAddress'));
    }

    public function history()
    {
        $userId = Auth::id();

        // Lấy tất cả đơn hàng
        $allOrders = Order::with(['items', 'shop'])
            ->where('userID', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Lấy đơn hàng theo từng trạng thái
        $processingOrders = Order::with(['items'])
            ->where('userID', $userId)
            ->where('order_status', 'processing')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pendingOrders = Order::with(['items'])
            ->where('userID', $userId)
            ->where('order_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shippedOrders = Order::with(['items'])
            ->where('userID', $userId)
            ->where('order_status', 'shipped')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $deliveredOrders = Order::with(['items'])
            ->where('userID', $userId)
            ->where('order_status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cancelledOrders = Order::with(['items'])
            ->where('userID', $userId)
            ->where('order_status', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $refundedOrders = Order::with(['items'])
            ->where('userID', $userId)
            ->where('order_status', 'refunded')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.order.history', compact(
            'allOrders',
            'processingOrders',
            'pendingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'refundedOrders'
        ));
    }
}
