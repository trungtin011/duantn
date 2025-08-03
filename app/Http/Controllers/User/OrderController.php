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

class OrderController extends Controller
{
    public function getParentOrdersByStatus($status)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $perPage = 5;

        $statuses = [
            'pending'    => 'Chưa thanh toán',
            'paid'       => 'Đơn hàng',
            'processing' => 'Đang tiến hành',
            'completed'  => 'Hoàn thành',
            'cancelled'  => 'Đơn hủy',
            'returned'   => 'Trả hàng/Hoàn tiền',
        ];

        $reviewedProductIds = OrderReview::where('user_id', $user->id)
            ->pluck('product_id')
            ->toArray();

        if ($status == 'pending') {
            $orders = Order::where('userID', $user->id)
                ->where('order_status', $status)
                ->where('payment_status', 'pending')
                ->with(['shopOrders.items.product.images', 'shopOrders.items.variant', 'shopOrders.items.combo.products.variant', 'shopOrders.shop'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        } elseif ($status == 'paid') {
            $orders = Order::where('userID', $user->id)
                ->where('order_status', 'pending')
                ->whereIn('payment_status', ['paid', 'cod_paid'])
                ->with(['shopOrders.items.product.images', 'shopOrders.items.variant', 'shopOrders.items.combo.products.variant', 'shopOrders.shop'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        } else {
            $orders = Order::where('userID', $user->id)
                ->where('order_status', $status)
                ->where('payment_status', '!=', 'failed')
                ->with(['shopOrders.items.product.images', 'shopOrders.items.variant', 'shopOrders.items.combo.products.variant', 'shopOrders.shop'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        }
        $html = view('user.order.components.parent-order-list', compact(
            'orders',
            'reviewedProductIds',
            'statuses'
        ))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $orders->links()->toHtml(),
            'total' => $orders->total()
        ]);
    }

    public function parentOrder()
    {
        Log::info('OrderController@parentOrder called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('user.order.parent-order', compact('user'));
    }

    public function show($shopOrderID)
    {
        Log::info('OrderController@show called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        $userId = Auth::id();

        // Lấy danh sách sản phẩm đã đánh giá
        $reviewedProductIds = OrderReview::where('user_id', $user->id)
            ->pluck('product_id')
            ->toArray();

        $shopOrder = \App\Models\ShopOrder::with([
            'items.product.images' => function ($query) {
                $query->select('id', 'productID', 'image_path', 'is_default', 'display_order', 'variantID');
            },
            'items.variant' => function ($query) {
                $query->select('id', 'productID', 'variant_name', 'price', 'sale_price');
            },
            'items.combo.products.variant',
            'shop',
            'order.address',
            'order.user' => function ($query) {
                $query->select('id', 'fullname', 'email', 'phone');
            },
        ])
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('userID', $userId);
            })
            ->findOrFail($shopOrderID);

        // Lấy thông tin order cha
        $order = $shopOrder->order;

        // Lấy các item của shop order này
        $orderItems = $shopOrder->items;

        // Lấy địa chỉ giao hàng từ order cha
        $orderAddress = $order->address ?? null;

        return view('user.order.detail', [
            'order' => $shopOrder,
            'orderItems' => $orderItems,
            'orderAddress' => $orderAddress,
            'parentOrder' => $order,
            'user' => $user,
            'reviewedProductIds' => $reviewedProductIds,
        ]);
    }

    public function showParent($orderCode)
    {
        Log::info('OrderController@showParent called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        $userId = Auth::id();

        // Lấy danh sách sản phẩm đã đánh giá
        $reviewedProductIds = OrderReview::where('user_id', $user->id)
            ->pluck('product_id')
            ->toArray();

        $parentOrder = Order::with([
            'shopOrders.items.product.images' => function ($query) {
                $query->select('id', 'productID', 'image_path', 'is_default', 'display_order', 'variantID');
            },
            'shopOrders.items.variant' => function ($query) {
                $query->select('id', 'productID', 'variant_name', 'price', 'sale_price');
            },
            'shopOrders.items.combo.products.variant',
            'shopOrders.shop',
            'address',
            'user' => function ($query) {
                $query->select('id', 'fullname', 'email', 'phone');
            },
        ])
            ->where('userID', $userId)
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $orderAddress = $parentOrder->address ?? null;

        $statuses = [
            'pending'    => 'Đang chờ xử lý',
            'processing' => 'Đang tiến hành',
            'completed'  => 'Hoàn thành',
            'cancelled'  => 'Đơn hủy',
        ];

        return view('user.order.parent-detail', [
            'parentOrder' => $parentOrder,
            'orderAddress' => $orderAddress,
            'statuses' => $statuses,
            'user' => $user,
            'reviewedProductIds' => $reviewedProductIds,
        ]);
    }

    public function checkStatus($orderStatus)
    {
        if ($orderStatus->order_status === 'pending') {
            return 'pending';
        } elseif ($orderStatus->order_status === 'processing') {
            return 'processing';
        } elseif ($orderStatus->order_status === 'completed') {
            return 'completed';
        } elseif ($orderStatus->order_status === 'cancelled') {
            return 'cancelled';
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

    protected function validateReorderItems($items)
    {
        foreach ($items as $item) {
            $product = $item->product;
            $variant = $item->variant;

            if (!$product) {
                throw new \Exception("Sản phẩm '{$item->product_name}' không còn tồn tại.");
            }

            if ($variant) {
                if (!$variant) {
                    throw new \Exception("Biến thể sản phẩm '{$item->product_name}' không còn tồn tại.");
                }
                if ($variant->stock < $item->quantity) {
                    throw new \Exception("Sản phẩm '{$item->product_name}' (biến thể: {$variant->variant_name}) không đủ tồn kho.");
                }
            } elseif ($product->stock_total < $item->quantity) {
                throw new \Exception("Sản phẩm '{$item->product_name}' không đủ tồn kho.");
            }
        }
    }

    public function reorder($orderID)
    {
        $userId = Auth::id();

        $originalOrder = Order::with([
            'items.product',
            'items.variant',
            'address',
            'shopOrders.items'
        ])
            ->whereNotNull('userID')
            ->where('userID', $userId)
            ->findOrFail($orderID);

        try {
            $this->validateReorderItems($originalOrder->items);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $newTotalPrice = $originalOrder->items->sum(function ($item) {
            $product = $item->product;
            $variant = $item->variant;
            if ($variant) {
                $price = $variant->price_after_discount ?? $variant->price;
            } elseif ($product) {
                $price = $product->price_after_discount ?? $product->price;
            } else {
                $price = 0;
            }
            return $price * $item->quantity;
        });

        DB::beginTransaction();
        try {
            $newOrderCode = 'DH-' . strtoupper(substr(md5(uniqid()), 0, 5)) . '-' . time();

            $newOrder = Order::create([
                'userID'         => $userId,
                'order_code'     => $newOrderCode,
                'total_price'    => $newTotalPrice,
                'payment_method' => 'cod',
                'shipping_fee'   => $originalOrder->shipping_fee,
                'payment_status' => 'pending',
                'order_status'   => 'pending',
                'order_note'     => $originalOrder->order_note,
            ]);

            if ($originalOrder->address) {
                $address = $originalOrder->address;
                OrderAddress::create([
                    'order_id'      => $newOrder->id,
                    'receiver_name' => $address->receiver_name,
                    'receiver_phone' => $address->receiver_phone,
                    'receiver_email' => $address->receiver_email,
                    'address'       => $address->address,
                    'province'      => $address->province,
                    'district'      => $address->district,
                    'ward'          => $address->ward,
                    'zip_code'      => $address->zip_code,
                    'note'          => $address->note,
                    'address_type'  => $address->address_type,
                ]);
            }

            foreach ($originalOrder->shopOrders as $shopOrder) {
                $newShopOrder = ShopOrder::create([
                    'shopID'                 => $shopOrder->shopID,
                    'orderID'                => $newOrder->id,
                    'tracking_code'          => null,
                    'expected_delivery_date' => null,
                    'actual_delivery_date'   => null,
                    'status'                 => 'pending',
                    'note'                   => $shopOrder->note,
                ]);

                $items = $shopOrder->items ?? $originalOrder->items->where('shop_orderID', $shopOrder->id);

                foreach ($items as $item) {
                    $product = $item->product;
                    $variant = $item->variant;

                    if ($variant) {
                        $unitPrice = $variant->price_after_discount ?? $variant->price;
                    } elseif ($product) {
                        $unitPrice = $product->price_after_discount ?? $product->price;
                    } else {
                        $unitPrice = 0;
                    }
                    $totalPrice = $unitPrice * $item->quantity;

                    ItemsOrder::create([
                        'orderID'        => $newOrder->id,
                        'shop_orderID'   => $newShopOrder->id,
                        'productID'      => $item->productID,
                        'variantID'      => $item->variantID,
                        'product_name'   => $item->product_name,
                        'brand'          => $item->brand,
                        'category'       => $item->category,
                        'attribute_value' => $item->attribute_value,
                        'attribute_name' => $item->attribute_name,
                        'product_image'  => $item->product_image,
                        'quantity'       => $item->quantity,
                        'unit_price'     => $unitPrice,
                        'total_price'    => $totalPrice,
                    ]);
                }
            }
            DB::commit();
            return redirect()
                ->route('user.order.parent-detail', $newOrder->order_code)
                ->with('success', 'Đơn hàng đã được tạo lại thành công. Vui lòng tiến hành thanh toán để hoàn tất.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo lại đơn hàng: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tạo lại đơn hàng: ' . $e->getMessage());
        }
    }

    public function refund(Request $request, $orderID)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập');
        }

        $request->validate([
            'refund_reason' => 'required|string|max:500',
        ]);

        try {
            $shopOrder = ShopOrder::where('id', $orderID)->first();

            if (!$shopOrder) {
                return response()->json(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            }
            Log::info($shopOrder->status);
            if ($shopOrder->status !== 'delivered') {
                return response()->json(['success' => false, 'message' => 'Chỉ có thể yêu cầu trả hàng khi đơn hàng đã được giao']);
            }

            $hasError = false;

            foreach ($shopOrder->items as $item) {
                try {
                    if ($item->combo_id && $item->combo) {
                        $item->combo->increment('quantity', $item->combo_quantity);
                    } elseif ($item->variantID && $item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    } elseif ($item->productID) {
                        $item->product->increment('stock_total', $item->quantity);
                    }
                } catch (\Exception $e) {
                    $hasError = true;
                    break;
                }
            }

            if ($hasError) {
                return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi trả lại số lượng sản phẩm']);
            }

            $totalAmount = 0;
            foreach ($shopOrder->items as $item) {
                $price = $item->price_after_discount ?? $item->price;
                $totalAmount += $price * $item->quantity;
            }

            $pointsToAdd = floor($totalAmount / 1000);
            $customer = Customer::where('userID', Auth::id())->first();
            if ($pointsToAdd > 0) {
                $customer->total_points += $pointsToAdd;
                $customer->save();

                PointTransaction::create([
                    'userID' => $user->id,
                    'orderID' => $shopOrder->id,
                    'points' => $pointsToAdd,
                    'type' => 'refund',
                    'description' => 'Cộng điểm hoàn trả đơn hàng #' . $shopOrder->id,
                ]);
            }

            $shopOrder->status = 'returned';
            $shopOrder->save();

            ShopOrderHistory::create([
                'shop_order_id' => $shopOrder->id,
                'status' => 'returned',
                'description' => 'Yêu cầu trả hàng từ khách : ' . $request->refund_reason,
            ]);

            return response()->json(['success' => true, 'message' => 'Yêu cầu trả hàng đã được gửi thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi gửi yêu cầu trả hàng: ' . $e->getMessage()]);
        }
    }

    public function confirmReceived($orderID)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }
        try {
            $shopOrder = ShopOrder::where('id', $orderID)->first();

            if (!$shopOrder) {
                return response()->json(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
            }

            if ($shopOrder->status !== 'delivered') {
                return response()->json(['success' => false, 'message' => 'Chỉ có thể xác nhận nhận hàng khi đơn hàng đã được giao']);
            }

            $customer = Customer::where('userID', Auth::id())->first();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Khách hàng không tồn tại']);
            }

            $customer->total_orders += 1;
            $customer->total_spent += $shopOrder->order->total_price;
            $customer->save();

            $shopOrder->status = 'completed';
            $shopOrder->save();

            $shop = $shopOrder->shop;
            $shop->total_sales += $shopOrder->items->sum('total_price');
            $shop->save();

            ShopOrderHistory::create([
                'shop_order_id' => $shopOrder->id,
                'status' => 'completed',
                'description' => 'Khách hàng đã xác nhận nhận hàng',
            ]);

            foreach ($shopOrder->items as $item) {
                if ($item->product) {
                    $item->product->increment('sold_quantity', $item->quantity);
                }
            }

            $order = $shopOrder->order;
            if ($order && $order->userID) {
                $customer = Customer::where('userID', $order->userID)->first();
                if ($customer) {
                    $totalAmount = $shopOrder->items->sum('total_price');
                    $pointRate = 0.01;
                    $earnedPoints = floor($totalAmount * $pointRate);

                    $rankPercent = match ($customer->rank) {
                        'diamond' => 3,
                        'platinum' => 2.5,
                        'gold' => 2,
                        'silver' => 1.5,
                        default => 1,
                    };
                    $earnedPoints = floor($earnedPoints * $rankPercent);

                    $customer->total_points += $earnedPoints;
                    $customer->save();

                    PointTransaction::create([
                        'userID' => $order->userID,
                        'orderID' => $order->id,
                        'points' => $earnedPoints,
                        'type' => 'order',
                        'description' => 'Nhận điểm khi xác nhận đã nhận hàng cho đơn #' . $shopOrder->id,
                    ]);
                }
            }

            Order::orderStatusUpdate($order->id);

            $platform_revenues = PlatformRevenueModel::where('shop_order_id', $shopOrder->id)->first();
            if (!$platform_revenues) {
                $platform_revenues = new PlatformRevenueModel();
                $platform_revenues->shop_order_id = $shopOrder->id;
                $platform_revenues->order_id = $order->id;
                $platform_revenues->shop_id = $shopOrder->shopID;
                $platform_revenues->shop_name = $shopOrder->shop->shop_name;
                $platform_revenues->payment_method = $shopOrder->order->payment_method;
                $platform_revenues->commission_rate = 0.05;
                $platform_revenues->commission_amount = $shopOrder->items->sum('total_price') * $platform_revenues->commission_rate;
                $platform_revenues->total_amount = $shopOrder->items->sum('total_price');
                $platform_revenues->net_revenue = $shopOrder->items->sum('total_price') - $platform_revenues->commission_amount;
                $platform_revenues->status = 'paid';
                $platform_revenues->confirmed_at = now();
                $platform_revenues->save();
            }

            return response()->json(['success' => true, 'message' => 'Đã xác nhận nhận hàng thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi xác nhận nhận hàng: ' . $e->getMessage()]);
        }
    }

    public function storeReview(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $request->validate([
            'orderID' => 'required|exists:shop_order,id',
            'shopID' => 'required|exists:shops,id',
            'productID' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|file|max:10240',
        ]);

        try {
            // Kiểm tra xem sản phẩm đã được đánh giá chưa
            $existingReview = OrderReview::where('user_id', $user->id)
                ->where('product_id', $request->productID)
                ->where('shop_order_id', $request->orderID)
                ->first();

            if ($existingReview) {
                return response()->json(['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này rồi']);
            }

            // Kiểm tra đơn hàng đã hoàn thành chưa
            $shopOrder = ShopOrder::where('id', $request->orderID)
                ->whereHas('order', function ($query) use ($user) {
                    $query->where('userID', $user->id);
                })
                ->first();

            if (!$shopOrder || $shopOrder->status !== 'completed') {
                return response()->json(['success' => false, 'message' => 'Chỉ có thể đánh giá sản phẩm từ đơn hàng đã hoàn thành']);
            }

            DB::beginTransaction();

            // Tạo đánh giá
            $review = OrderReview::create([
                'user_id' => $user->id,
                'product_id' => $request->productID,
                'shop_id' => $request->shopID,
                'shop_order_id' => $request->orderID,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // Xử lý upload hình ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews/images', 'public');

                    // Lưu vào bảng order_review_images
                    \App\Models\OrderReviewImage::create([
                        'review_id' => $review->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // Xử lý upload video
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('reviews/videos', 'public');

                // Lưu vào bảng order_review_videos
                \App\Models\OrderReviewVideo::create([
                    'review_id' => $review->id,
                    'video_path' => $videoPath,
                ]);
            }

            // Tính toán điểm thưởng
            $points = 0;
            $commentLength = strlen($request->comment);
            $hasImages = $request->hasFile('images') && count($request->file('images')) > 0;
            $hasVideo = $request->hasFile('video');

            if ($commentLength >= 50) {
                if ($hasImages && $hasVideo) {
                    $points = 200;
                } elseif ($hasImages || $hasVideo) {
                    $points = 100;
                }
            }

            // Cộng điểm cho user nếu có
            if ($points > 0) {
                $customer = Customer::where('userID', $user->id)->first();
                if ($customer) {
                    $customer->total_points += $points;
                    $customer->save();

                    PointTransaction::create([
                        'userID' => $user->id,
                        'orderID' => $request->orderID,
                        'points' => $points,
                        'type' => 'bonus',
                        'description' => 'Nhận điểm từ đánh giá sản phẩm #' . $request->productID,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được gửi thành công!' . ($points > 0 ? " Bạn đã nhận được {$points} xu!" : '')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo đánh giá: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi đánh giá: ' . $e->getMessage()]);
        }
    }
}
