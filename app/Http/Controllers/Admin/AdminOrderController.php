<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shopOrders.shop', 'address', 'items']);

        if ($request->search) {
            $query->where('order_code', 'like', "%{$request->search}%")
                ->orWhere(function ($q) use ($request) {
                    $q->whereHas('user', fn($subQ) => $subQ->where('fullname', 'like', "%{$request->search}%"))
                        ->orWhereNull('userID');
                });
        }

        // Lọc trạng thái theo shopOrders.status
        if ($request->status) {
            $query->whereHas('shopOrders', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Lọc theo shopID trong shopOrders
        if ($request->shop_id) {
            $query->whereHas('shopOrders', function ($q) use ($request) {
                $q->where('shopID', $request->shop_id);
            });
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        // Filter by a specific date
        if ($request->filter_date) {
            $query->whereDate('created_at', $request->filter_date);
        }

        $orders = $query->paginate(10);
        $shops = \App\Models\Shop::where('shop_status', 'active')->get();

        return view('admin.orders.index', compact('orders', 'shops'));
    }

    public function show($id)
    {
        $order = Order::with(['items', 'user', 'address', 'statusHistory'])->findOrFail($id);
        Log::info('Order Items:', ['items' => $order->items]);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'cancel_reason' => 'required_if:status,cancelled',
            'description' => 'nullable|string',
            'shipping_provider' => 'nullable|string',
            'note' => 'nullable|string'
        ]);

        $order = Order::findOrFail($id);
        $order->order_status = $request->status;
        if ($request->status == 'cancelled') {
            $order->cancel_reason = $request->cancel_reason;
            $order->cancelled_at = now();
        } elseif ($request->status == 'delivered') {
            $order->delivered_at = now();
        }
        $order->save();

        // Debug
        Log::info('Description received:', ['description' => $request->description]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'description' => $request->description,
            'shipping_provider' => $request->shipping_provider,
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công'
        ]);
    }

    public function refund($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'refunded',
            'order_status' => 'refunded'
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'refunded',
            'description' => 'Hoàn tiền cho đơn hàng'
        ]);

        return redirect()->back()->with('success', 'Hoàn tiền thành công');
    }

    public function report(Request $request)
    {
        $stats = Order::selectRaw('order_status, COUNT(*) as total_orders, SUM(total_price) as total_revenue')
            ->groupBy('order_status')
            ->get();

        return view('admin.orders.report', compact('stats'));
    }

    public function ajaxList(Request $request)
    {
        $query = Order::with(['user', 'shopOrders.shop', 'address', 'items']);

        if ($request->search) {
            $query->where('order_code', 'like', "%{$request->search}%")
                ->orWhere(function ($q) use ($request) {
                    $q->whereHas('user', fn($subQ) => $subQ->where('fullname', 'like', "%{$request->search}%"))
                        ->orWhereNull('userID');
                });
        }
        if ($request->status) {
            $query->whereHas('shopOrders', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        if ($request->shop_id) {
            $query->whereHas('shopOrders', function ($q) use ($request) {
                $q->where('shopID', $request->shop_id);
            });
        }
        // Thêm lọc ngày
        if ($request->filter_date) {
            $query->whereDate('created_at', $request->filter_date);
        }

        $orders = $query->paginate(10);

        return view('admin.orders._table_body', compact('orders'))->render();
    }
}
