<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ItemsOrder;
use App\Models\OrderAddress;
use App\Models\ShopOrder;
use App\Models\OrderReview;
use App\Models\ShopOrderHistory;
use App\Models\PlatformRevenueModel;
use App\Models\Customer;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Service\DeliveryProvider\GHNController;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng');
        }

        // Lấy danh sách sản phẩm đã đánh giá
        $reviewedProductIds = OrderReview::where('user_id', $user->id)
            ->pluck('product_id')
            ->toArray();

        // Lấy đơn hàng theo trạng thái
        $allOrders = Order::where('userID', $user->id)
            ->with(['items.product.images', 'items.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pendingOrders = Order::where('userID', $user->id)
            ->where('order_status', 'pending')
            ->with(['items.product.images', 'items.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $processingOrders = Order::where('userID', $user->id)
            ->where('order_status', 'processing')
            ->with(['items.product.images', 'items.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shippedOrders = Order::where('userID', $user->id)
            ->where('order_status', 'shipped')
            ->with(['items.product.images', 'items.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $deliveredOrders = Order::where('userID', $user->id)
            ->where('order_status', 'delivered')
            ->with(['items.product.images', 'items.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cancelledOrders = Order::where('userID', $user->id)
            ->where('order_status', 'cancelled')
            ->with(['items.product.images', 'items.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $refundedOrders = Order::where('userID', $user->id)
            ->where('order_status', 'refunded')
            ->with(['items.product.images', 'items.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.order.history', compact(
            'allOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'refundedOrders',
            'reviewedProductIds'
        ));
    }

    public function show($orderID)
    {
        $userId = Auth::id();
        $order = Order::with([
            'items.product.images' => function ($query) {
                $query->select('id', 'productID', 'image_path', 'is_default', 'display_order', 'variantID');
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
            ->whereNotNull('userID')->where('userID', $userId)
            ->findOrFail($orderID);

        $orderItems = $order->items;
        $orderAddress = $order->address;

        return view('user.order.detail', compact('order', 'orderItems', 'orderAddress'));
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

    public function cancel(Request $request, $orderId)
    {
        $userId = Auth::id();
        $order = Order::with('shopOrders')->whereNotNull('userID')->where('userID', $userId)->findOrFail($orderId);

        if (!in_array($order->order_status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        DB::beginTransaction();
        try {
            $order->update([
                'order_status' => 'cancelled',
                'cancel_reason' => $request->input('cancel_reason'),
                'cancelled_at' => now(),
            ]);

            foreach ($order->shopOrders as $shopOrder) {
                $shopOrder->update([
                    'status' => 'cancelled',
                    'note' => 'Hủy bởi khách hàng: ' . $request->input('cancel_reason'),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi hủy đơn hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi hủy đơn hàng: ' . $e->getMessage());
        }
    }

    public function reorder($orderID)
    {
        $userId = Auth::id();
        $originalOrder = Order::with(['items.product', 'items.variant', 'address', 'shopOrders'])
            ->whereNotNull('userID')->where('userID', $userId)
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

            // Tạo shop orders và items tuần tự
            $newShopOrderIds = [];
            foreach ($originalOrder->shopOrders as $shopOrder) {
                $newShopOrder = ShopOrder::create([
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
                ]);
                $newShopOrderIds[$shopOrder->id] = $newShopOrder->id;

                $items = $originalOrder->items->where('shop_orderID', $shopOrder->id);
                foreach ($items as $item) {
                    ItemsOrder::create([
                        'orderID' => $newOrder->id,
                        'shop_orderID' => $newShopOrder->id,
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
                    ]);
                }
            }

            DB::commit();

            // Hướng dẫn người dùng đến bước thanh toán
            return redirect()->route('user.order.detail', $newOrder->id)
                ->with('success', 'Đơn hàng đã được tạo lại thành công. Vui lòng tiến hành thanh toán để hoàn tất.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo lại đơn hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tạo lại đơn hàng: ' . $e->getMessage());
        }
    }

    public function refundOrder($tracking_code)
    {
        $order = ShopOrder::where('tracking_code', $tracking_code)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại');
        }

        $GHN_Controller = new GHNController();
        $refund = $GHN_Controller->refundOrder($tracking_code);

        if ($refund) {
            $order->status = 'refunded';
            $order->save();

            $history = new ShopOrderHistory();
            $history->shop_order_id = $order->id;
            $history->status = 'refunded';
            $history->description = 'Người mua đã hoàn trả đơn hàng';
            $history->save();

            return redirect()->back()->with('success', 'Đơn hàng đã được hoàn trả thành công');
        } else {
            return redirect()->back()->with('error', 'Đơn hàng không thể hoàn trả');
        }
    }

    public function completeOrderFromUser($code)
    {
        $order = ShopOrder::where('code', $code)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại');
        }

        $parentOrder = Order::find($order->orderID);

        // Cập nhật trạng thái đơn hàng
        $order->order_status = 'completed';
        $order->save();

        // Tính toán hoa hồng và doanh thu
        $totalAmount = $order->items->sum('total_price');
        $commissionRate = 0.05;
        $commissionAmount = $totalAmount * $commissionRate;
        $netRevenue = $totalAmount - $commissionAmount;

        // Lưu lịch sử đơn hàng
        ShopOrderHistory::create([
            'shop_order_id' => $order->id,
            'status' => 'completed',
            'description' => 'Hệ thống đã trừ hoa hồng 5%: ' . number_format($commissionAmount) . 'đ cho đơn hàng',
        ]);

        // Lưu doanh thu nền tảng
        PlatformRevenueModel::create([
            'shop_order_id'     => $order->id,
            'order_id'          => $parentOrder?->id,
            'shop_id'           => $order->shopID,
            'shop_name'         => optional($order->shop)->name,
            'payment_method'    => $parentOrder?->payment_method,
            'commission_rate'   => $commissionRate,
            'commission_amount' => $commissionAmount,
            'total_amount'      => $totalAmount,
            'net_revenue'       => $netRevenue,
            'status'            => 'paid',
            'confirmed_at'      => now(),
        ]);

        // Cập nhật điểm thưởng cho khách hàng
        $customer = Customer::where('userID', Auth::id())->first();
        if ($customer) {
            // Trừ điểm đã sử dụng
            $customer->total_points = max(0, $customer->total_points - (int) $order->used_points);

            // Xác định hệ số rank
            $rankPercent = match ($customer->rank) {
                'silver'   => 2,
                'gold'     => 3,
                'platinum' => 4,
                'diamond'  => 5,
                default    => 1,
            };

            // Tính điểm thưởng: mỗi 1.000.000đ được 10.000 điểm * hệ số rank
            $bonusPoints = round(floor($order->total_price / 1000000) * 10000 * $rankPercent);

            // Cộng điểm thưởng
            $customer->total_points += $bonusPoints;
            $customer->save();

            // Lưu lịch sử giao dịch điểm
            PointTransaction::create([
                'userID'      => Auth::id(),
                'orderID'     => $order->id,
                'points'      => $bonusPoints,
                'type'        => 'order',
                'description' => 'Đơn hàng #' . $order->order_code,
            ]);
        }

        return redirect()->back()->with('success', 'Đơn hàng đã được hoàn thành');
    }
}
