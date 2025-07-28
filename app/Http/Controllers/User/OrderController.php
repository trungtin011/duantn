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
use App\Models\OrderReviewImage;
use App\Models\OrderReviewVideo;


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
            ->with(['items.product.images', 'items.variant', 'items.shopOrder', 'items.combo.products.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pendingOrders = Order::where('userID', $user->id)
            ->where('order_status', 'pending')
            ->with(['items.product.images', 'items.variant', 'items.shopOrder', 'items.combo.products.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $processingOrders = Order::where('userID', $user->id)
            ->where('order_status', 'processing')
            ->with(['items.product.images', 'items.variant', 'items.shopOrder', 'items.combo.products.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shippedOrders = Order::where('userID', $user->id)
            ->where('order_status', 'shipped')
            ->with(['items.product.images', 'items.variant', 'items.shopOrder', 'items.combo.products.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $deliveredOrders = Order::where('userID', $user->id)
            ->where('order_status', 'delivered')
            ->with(['items.product.images', 'items.variant', 'items.shopOrder', 'items.combo.products.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cancelledOrders = Order::where('userID', $user->id)
            ->where('order_status', 'cancelled')
            ->with(['items.product.images', 'items.variant', 'items.shopOrder', 'items.combo.products.variant'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $refundedOrders = Order::where('userID', $user->id)
            ->where('order_status', 'refunded')
            ->with(['items.product.images', 'items.variant', 'items.shopOrder', 'items.combo.products.variant'])
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

    public function storeReview(Request $request)
    {
        Log::info('Bắt đầu xử lý đánh giá sản phẩm', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);
        try {
            $request->validate([
                'orderID' => 'required|exists:orders,id',
                'shopID' => 'required|exists:shops,id',
                'productID' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'video' => 'nullable|file|max:51200',
            ]);
            Log::info('Dữ liệu đánh giá hợp lệ', [
                'user_id' => Auth::id(),
                'orderID' => $request->orderID,
                'shopID' => $request->shopID,
                'productID' => $request->productID
            ]);

            $userId = Auth::id();

            $order = Order::where('id', $request->orderID)
                ->where('userID', $userId)
                ->where('order_status', 'delivered')
                ->firstOrFail();

            $shopOrderId = null;
            $shopOrder = \App\Models\ShopOrder::where('orderID', $request->orderID)
                ->where('shopID', $request->shopID)
                ->first();

            if ($shopOrder) {
                $itemOrder = \App\Models\ItemsOrder::where('orderID', $request->orderID)
                    ->where('shop_orderID', $shopOrder->id)
                    ->where('productID', $request->productID)
                    ->first();
                if ($itemOrder) {
                    $shopOrderId = $shopOrder->id;
                }
            }

            if (!$shopOrderId) {
                Log::warning('Không tìm thấy shop_order_id phù hợp cho đánh giá', [
                    'orderID' => $request->orderID,
                    'shopID' => $request->shopID,
                    'productID' => $request->productID,
                    'user_id' => $userId
                ]);
                return redirect()->back()->with('error', 'Không thể xác định đơn hàng shop để đánh giá.');
            }

            $existingReview = OrderReview::where('user_id', $userId)
                ->where('shop_order_id', $shopOrderId)
                ->where('product_id', $request->productID)
                ->first();

            if ($existingReview) {
                Log::warning('Người dùng đã cố gắng đánh giá lại sản phẩm', [
                    'user_id' => $userId,
                    'productID' => $request->productID,
                    'shop_order_id' => $shopOrderId
                ]);
                return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.');
            }

            $review = OrderReview::create([
                'user_id' => $userId,
                'product_id' => $request->productID,
                'shop_id' => $request->shopID,
                'shop_order_id' => $shopOrderId,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            Log::info('Tạo đánh giá mới thành công', [
                'review_id' => $review->id,
                'user_id' => $userId,
                'product_id' => $request->productID,
                'shop_order_id' => $shopOrderId
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('review_images', 'public');
                    $review->images()->create(['image_path' => $path]);
                    Log::info('Tải lên ảnh đánh giá mới', [
                        'image_path' => $path,
                        'review_id' => $review->id,
                        'user_id' => $userId
                    ]);
                }
            }

            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('review_videos', 'public');
                $review->videos()->create(['video_path' => $videoPath]);
                Log::info('Tải lên video đánh giá mới', [
                    'video_path' => $videoPath,
                    'review_id' => $review->id,
                    'user_id' => $userId
                ]);
            }

            $pointsAwarded = 0;
            $commentLength = Str::length($request->comment);
            $hasImages = $request->hasFile('images') && count($request->file('images')) > 0;
            $hasVideo = $request->hasFile('video');

            if ($commentLength >= 50) {
                if ($hasImages && $hasVideo) {
                    $pointsAwarded = 200;
                } elseif ($hasImages || $hasVideo) {
                    $pointsAwarded = 100;
                }
            }

            if ($pointsAwarded > 0) {
                PointTransaction::create([
                    'userID' => $userId,
                    'points' => $pointsAwarded,
                    'type' => 'order',
                    'description' => 'Thưởng điểm khi đánh giá sản phẩm đủ điều kiện',
                    'orderID' => $request->orderID,
                ]);
                Log::info('Thưởng xu cho người dùng khi đánh giá sản phẩm', [
                    'user_id' => $userId,
                    'product_id' => $request->productID,
                    'points' => $pointsAwarded
                ]);
            } else {
                Log::info('Đánh giá không đủ điều kiện nhận xu', [
                    'user_id' => $userId,
                    'product_id' => $request->productID
                ]);
            }

            Log::info('Đánh giá sản phẩm thành công', [
                'user_id' => $userId,
                'product_id' => $request->productID,
                'review_id' => $review->id
            ]);
            return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Lỗi xác thực khi đánh giá sản phẩm', [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Lỗi khi xử lý đánh giá sản phẩm', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
        }
    }
}
