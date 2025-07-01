<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\ShopOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $orders = Order::with(['items', 'shop_order'])
            ->where('userID', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.order.order_history', compact('orders'));
    }

    public function history()
    {
        $userId = Auth::id();

        // Lấy tất cả đơn hàng
        $Orders = Order::with(['items', 'shop_order'])
            ->where('userID', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $sub_orders = [];
        
        foreach ($Orders as $order) {
            $sub_orders[] = $order->shop_order;
        }

        return view('user.order.order_history', compact('Orders', 'sub_orders'));
    }

    public function show($orderID)
    {
        $userId = Auth::id();
        $order = Order::with([
            'items.product.images' => function ($query) {
                $query->select('id', 'productID', 'image_path', 'is_default', 'display_order');
            },
            'items.variant' => function ($query) {
                $query->select('id', 'productID', 'variant_name', 'price', 'sale_price');
            },
            'items.shopOrder.shop',
            'address',
            'user' => function ($query) {
                $query->select('id', 'fullname', 'email', 'phone');
            },
            'coupon'
        ])
            ->where('userID', $userId)
            ->findOrFail($orderID);

        $orderItems = $order->items;
        $orderAddress = $order->address;

        return view('user.order.detail', compact('order', 'orderItems', 'orderAddress'));
    }

    public function cancel(Request $request, $orderID)
    {
        $userId = Auth::id();
        $order = Order::where('userID', $userId)->findOrFail($orderID);

        // Check if order can be cancelled
        if (!in_array($order->order_status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
        }

        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update order status
            $order->update([
                'order_status' => 'cancelled',
                'cancel_reason' => $request->cancel_reason,
                'cancelled_at' => now(),
            ]);

            // Update shop orders
            $order->shopOrders()->update([
                'status' => 'cancelled_by_customer',
                'note' => 'Hủy bởi khách hàng: ' . $request->cancel_reason,
                'updated_at' => now(),
            ]);

            // Restore stock for each item
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                } else {
                    $item->product->increment('stock_total', $item->quantity);
                }
            }

            DB::commit();
            return redirect()->route('user.order.detail', $orderID)->with('success', 'Đơn hàng đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi hủy đơn hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi hủy đơn hàng. Vui lòng thử lại.');
        }
    }

    public function checkStatus($orderStatus)
    {
        if ($orderStatus->order_status === 'pending') {
            return 'pending';
        } elseif ($orderStatus->order_status === 'processing') {
            return 'processing';
        } elseif ($orderStatus->order_status === 'shipped') {
            return 'shipped';
        }
    }

    public function reorder($orderID)
    {
        $userId = Auth::id();
        $originalOrder = Order::with(['items.product', 'items.variant', 'address', 'shopOrders'])
            ->where('userID', $userId)
            ->findOrFail($orderID);

        // Kiểm tra trạng thái đơn hàng
        if (!in_array($originalOrder->order_status, ['cancelled'])) {
            return redirect()->back()->with('error', 'Chỉ có thể mua lại các đơn hàng đã hủy.');
        }

        DB::beginTransaction();
        try {
            // Kiểm tra tồn kho trước khi tạo lại
            foreach ($originalOrder->items as $item) {
                $product = $item->product;
                $variant = $item->variant;

                if ($variant) {
                    if ($variant->stock < $item->quantity) {
                        throw new \Exception("Sản phẩm '{$item->product_name}' (biến thể: {$variant->variant_name}) không đủ tồn kho.");
                    }
                } elseif ($product && $product->stock_total < $item->quantity) {
                    throw new \Exception("Sản phẩm '{$item->product_name}' không đủ tồn kho.");
                }
            }

            // Generate new order code
            $newOrderCode = 'ORDER-' . strtoupper(Str::random(8));

            // Create new order
            $newOrder = Order::create([
                'userID' => $userId,
                'order_code' => $newOrderCode,
                'total_price' => $originalOrder->total_price,
                'coupon_id' => $originalOrder->coupon_id,
                'coupon_discount' => $originalOrder->coupon_discount,
                'payment_method' => $originalOrder->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'order_note' => $originalOrder->order_note,
            ]);

            // Copy address
            if ($originalOrder->address) {
                OrderAddress::create([
                    'order_id' => $newOrder->id,
                    'receiver_name' => $originalOrder->address->receiver_name,
                    'receiver_phone' => $originalOrder->address->receiver_phone,
                    'receiver_email' => $originalOrder->address->receiver_email,
                    'address' => $originalOrder->address->address,
                    'province' => $originalOrder->address->province,
                    'district' => $originalOrder->address->district,
                    'ward' => $originalOrder->address->ward,
                    'zip_code' => $originalOrder->address->zip_code,
                    'note' => $originalOrder->address->note,
                    'address_type' => $originalOrder->address->address_type,
                ]);
            }

            // Tạo mảng shop orders và items để chèn hàng loạt
            $shopOrders = [];
            $orderItems = [];

            foreach ($originalOrder->shopOrders as $shopOrder) {
                $newShopOrder = [
                    'shopID' => $shopOrder->shopID,
                    'orderID' => $newOrder->id,
                    'shipping_provider' => $shopOrder->shipping_provider,
                    'shipping_fee' => $shopOrder->shipping_fee,
                    'tracking_code' => null,
                    'expected_delivery_date' => null,
                    'actual_delivery_date' => null,
                    'status' => 'pending',
                    'note' => $shopOrder->note,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $shopOrders[] = $newShopOrder;

                $items = $originalOrder->items->where('shop_orderID', $shopOrder->id);
                foreach ($items as $item) {
                    $orderItems[] = [
                        'orderID' => $newOrder->id,
                        'shop_orderID' => DB::getPdo()->lastInsertId(), // Cần xử lý sau khi chèn shop orders
                        'productID' => $item->productID,
                        'variantID' => $item->variantID,
                        'product_name' => $item->product_name,
                        'brand' => $item->brand,
                        'category' => $item->category,
                        'attribute_value' => $item->attribute_value,
                        'attribute_name' => $item->attribute_name,
                        'product_image' => $item->product_image,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                        'discount_amount' => $item->discount_amount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Chèn hàng loạt shop orders
            if (!empty($shopOrders)) {
                ShopOrder::insert($shopOrders);

                // Cập nhật shop_orderID cho order items sau khi chèn shop orders
                $lastShopOrderId = ShopOrder::where('orderID', $newOrder->id)->max('id');
                foreach ($orderItems as &$item) {
                    $item['shop_orderID'] = $lastShopOrderId - count($shopOrders) + 1; // Điều chỉnh ID
                }
                unset($item); // Xóa tham chiếu

                // Chèn hàng loạt order items
                if (!empty($orderItems)) {
                    OrderItem::insert($orderItems);
                }
            }

            DB::commit();

            // Hướng dẫn người dùng đến bước thanh toán
            return redirect()->route('user.orders.show', $newOrder->id)
                ->with('success', 'Đơn hàng đã được tạo lại thành công. Vui lòng tiến hành thanh toán để hoàn tất.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo lại đơn hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tạo lại đơn hàng: ' . $e->getMessage());
        }
    }
}
