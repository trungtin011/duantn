<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $sellerId = Auth::user()->shop_id;

        $query = Order::with(['user', 'items'])->orderBy('created_at', 'desc');

        if ($sellerId !== null) {
            // Nếu có shopID thì lọc theo shopID
            $query->where('shopID', $sellerId);
        }

        $orders = $query->paginate(10);

        return view('seller.order.index', compact('orders'));
    }

    public function show($id)
    {
        $sellerId = Auth::user()->shop_id;

        $query = Order::with(['user', 'items', 'address', 'statusHistory']);

        if ($sellerId !== null) {
            $query->where('shopID', $sellerId);
        }

        $order = $query->findOrFail($id);

        return view('seller.order.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
                'description' => 'nullable|string',
                'shipping_provider' => 'nullable|string',
                'note' => 'nullable|string',
            ]);

            $sellerId = Auth::user()->shop_id;

            if ($sellerId !== null) {
                // Nếu có shopID thì lọc theo shopID
                $order = Order::where('shopID', $sellerId)->findOrFail($id);
            } else {
                // Nếu không có shopID thì tìm đơn hàng theo id
                $order = Order::findOrFail($id);
            }

            $order->order_status = $request->status;

            if ($request->status === 'cancelled') {
                $order->cancelled_at = now();
                $order->cancel_reason = $request->note;
            } elseif ($request->status === 'delivered') {
                $order->delivered_at = now();
            } elseif ($request->status === 'refunded') {
                $order->cancelled_at = now();
            }

            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'description' => $request->description,
                'shipping_provider' => $request->shipping_provider,
                'note' => $request->note,
            ]);

            return response()->json(['message' => 'Cập nhật trạng thái thành công!'], 200);
        } catch (\Throwable $e) {
            Log::error('Update order status error: '.$e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['message' => 'Có lỗi xảy ra, vui lòng thử lại!'], 500);
        }
    }
}
?>
