<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

use App\Models\{
    UserAddress,
    Cart,
    Product,
    Shop,
    Order,
    Coupon,
    OrderAddress,
    ShopOrder,
    ItemsOrder,
    OrderStatusHistory,
    PaymentTransaction,
    CouponUser,
    Customer,
    PointTransaction,
    Combo,
    UserCouponUsed
};

use App\Http\Controllers\VNPayController;
use App\Http\Requests\CheckoutRequest;
use App\Events\CreateOrderEvent;


class CheckoutController extends Controller
{
    
    private function calculateComboDiscountedPrice($combo, $basePrice, $comboProduct)
    {
        $discountedPrice = $basePrice;
        
        if ($combo->discount_type === 'percentage' && $combo->discount_value > 0) {
            $discountMultiplier = 1 - ($combo->discount_value / 100);
            $discountedPrice = $basePrice * $discountMultiplier;
        } elseif ($combo->discount_type === 'fixed' && $combo->discount_value > 0) {
            $totalComboBasePrice = 0;
            foreach ($combo->products as $cp) {
                $productPrice = $cp->variant ? ($cp->variant->sale_price ?? $cp->variant->price) : ($cp->product->sale_price ?? $cp->product->price);
                $totalComboBasePrice += $productPrice * $cp->quantity;
            }
            
            if ($totalComboBasePrice > 0) {
                $productBasePrice = $basePrice * $comboProduct->quantity;
                $discountRatio = $productBasePrice / $totalComboBasePrice;
                $productDiscount = $combo->discount_value * $discountRatio;
                $discountedPrice = $basePrice - ($productDiscount / $comboProduct->quantity);
            } else {
                $discountedPrice = $basePrice - ($combo->discount_value / count($combo->products));
            }
        }
        
        return max(0, $discountedPrice);
    }

    public function getItems()
    {
        $items = [];
        $selected_products = session()->get('selected_products');
        if (empty($selected_products)) {
            return false;
        }
        foreach ($selected_products as $selected) {
            if (!empty($selected['combo_id'])) {
                $combo = Combo::with('products.product.variants')->find($selected['combo_id']);
                if ($combo) {
                    foreach ($combo->products as $comboProduct) {
                        $combo_quantity = isset($selected['quantity']) ? $selected['quantity'] : 1;
                        $product_quantity_in_combo = $comboProduct->quantity ?? 1;
                        $total_quantity = $combo_quantity * $product_quantity_in_combo;
                        
                        // Tính giá đồng bộ với CartController
                        $variant = $comboProduct->variant;
                        $basePrice = $variant ? ($variant->sale_price ?? $variant->price) : ($comboProduct->product->sale_price ?? $comboProduct->product->price);
                        
                        // Áp dụng giảm giá đồng bộ với CartController
                        $discountedPrice = $this->calculateComboDiscountedPrice($combo, $basePrice, $comboProduct);
                        
                        $items[] = [
                            'product' => $comboProduct->product,
                            'quantity' => $total_quantity,
                            'variant' => $variant,
                            'combo_info' => [
                                'combo_id' => $combo->id,
                                'combo_quantity' => $combo_quantity,
                                'price_in_combo' => $discountedPrice, // Giá đã giảm
                                'original_price' => $basePrice, // Giá gốc
                                'combo_name' => $combo->combo_name,
                                'discount_type' => $combo->discount_type,
                                'discount_value' => $combo->discount_value,
                                'combo_base_price' => $combo->total_price,
                            ],
                            'is_combo' => true,
                        ];
                    }
                }
            } 
            else if (!empty($selected['product_id'])) {
                $item = Product::with(['variants' => function($query) use ($selected) {
                        if (!empty($selected['variant_id'])) {
                            $query->where('id', $selected['variant_id']);
                        }
                    }])
                    ->find($selected['product_id']);

                $quantity = isset($selected['quantity']) ? $selected['quantity'] : 1;
                if ($item) {
                    $items[] = [
                        'product' => $item,
                        'quantity' => $quantity,
                        'variant_id' => $selected['variant_id'] ?? null,
                        'is_combo' => false,
                    ];
                }
            }
        }
        return $items;
    }

    public function index(Request $request)
    {
        session()->forget('used_coupon_data'); 

        $customer = Customer::where('userID', Auth::user()->id)->first();
        $user_coupon = CouponUser::where('user_id', Auth::user()->id)->first();
        $public_coupons = $this->getAvailableCoupons($customer);

        $items = $this->getItems();
        if (!$items) {
            return redirect()->back()->with('error', 'Không có sản phẩm được chọn');
        }
        $shop_ids = [];
        foreach ($items as $item) {
            $shop_ids[] = $item['product']->shopID;
        }
        $shop_ids = array_unique($shop_ids);
        $shops = Shop::whereIn('id', $shop_ids)
        ->with('coupons')
        ->get();
        $user_addresses = UserAddress::where('userID', Auth::user()->id)->get();
        $user_points = Customer::where('userID', Auth::user()->id)->pluck('total_points')->first();
        return view('client.checkout', compact('user_addresses', 'items', 'shops', 'user_coupon', 'user_points','public_coupons'));
    }

    protected function getAvailableCoupons($customer)
    {
        $userRank = $customer->ranking ?? 'bronze';
        $ranks = ['bronze' => 1, 'silver' => 2, 'gold' => 3, 'diamond' => 4];
        $userRankValue = $ranks[$userRank] ?? 1;

        $isFirstOrder = Order::where('userID', Auth::user()->id)->count() == 0;

        $publicCoupons = Coupon::where('is_public', 1)
            ->where('shop_id', null)
            ->where('end_date', '>', now())
            ->where(function($query) use ($userRank, $ranks, $userRankValue, $isFirstOrder) {
                $query->where('rank_limit', 'all');
                foreach ($ranks as $rank => $value) {
                    if ($value <= $userRankValue) {
                        $query->orWhere('rank_limit', $rank);
                    }
                }
                if ($isFirstOrder) {
                    $query->orWhere('type_coupon', 'first_order');
                }
            })
            ->where('status', 'active')
            ->get();

        return $publicCoupons;
    }

    public function store(Request $request)
    {                
        try {
            $validated = $request->validate([
                'selected_address_id' => 'required|exists:user_addresses,id',
                'payment_method' => 'required|in:MOMO,VNPAY,COD,PAYPAL',
                'shop_notes' => 'nullable',
                'shipping_fee' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'discount_amount' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'used_points' => 'required|numeric',
            ]);
            if(!$validated)  {
                return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ'], 400);
            }

            $items = $this->getItems();
            $user = Auth::user();
            unset($items['subtotal']);
            if (empty($items)) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 400);
            }
            
            $user_address = UserAddress::where('id', $validated['selected_address_id'])
                ->where('userID', $user->id)
                ->first();

            if (!$user_address) {
                return response()->json(['success' => false, 'message' => 'Địa chỉ không tồn tại'], 400);
            }

            $order = $this->createOrder($items, $user_address, $request);

            $redirectUrl = null;
            if($request->payment_method === 'COD'){
                $redirectUrl = route('checkout.success', ['order_code' => $order->order_code]);
            }
            else if($request->payment_method === 'MOMO'){
                $redirectUrl = route('checkout.momo.payment', ['order_code' => $order->order_code]);
            }
            else if($request->payment_method === 'VNPAY'){
                $redirectUrl = route('checkout.vnpay.payment', ['order_code' => $order->order_code]);
            }

            return response()->json(['success' => true, 'message' => 'Đặt hàng thành công', 'order' => $order, 'redirectUrl' => $redirectUrl], 200);
        } catch (\Exception $e) {
            Log::error('Order store error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function validateOrderData($items, $user_address, $request)
    {
        $errors = [];

        // Validate items
        if (empty($items)) {
            $errors[] = 'Không có sản phẩm để đặt hàng';
        }

        foreach ($items as $item) {
            if (!isset($item['product']) || !$item['product']) {
                $errors[] = 'Thông tin sản phẩm không hợp lệ';
                continue;
            }

            if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                $errors[] = 'Số lượng sản phẩm không hợp lệ';
                continue;
            }

            if (!$item['product']->variants || $item['product']->variants->isEmpty()) {
                $errors[] = 'Sản phẩm không có biến thể';
                continue;
            }

            if (!isset($item['product']->shopID)) {
                $errors[] = 'Thông tin shop không hợp lệ';
                continue;
            }
        }

        // Validate user address
        if (!$user_address) {
            $errors[] = 'Địa chỉ người dùng không hợp lệ';
        }

        // Validate payment method
        $valid_payments = ['COD', 'MOMO', 'VNPAY'];
        if (!in_array($request->payment_method, $valid_payments)) {
            $errors[] = 'Phương thức thanh toán không hợp lệ';
        }

        return $errors;
    }

    private function createOrder($items, $user_address, $request)
    {
        $validation_errors = $this->validateOrderData($items, $user_address, $request);
        if (!empty($validation_errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $validation_errors
            ], 422);
        }
        
        $total_price = $request->total_amount;
        $shop_notes = $request->shop_notes ?? [];
        $used_points = $request->used_points;
        $order = Order::create([
            'userID' => Auth::id(),
            'user_address' => $user_address->id,
            'total_price' => $total_price,
            'payment_method' => $request->payment_method,
            'total_discount' => $request->total_discount ?? 0,
            'order_code' => 'DH' . '-' . strtoupper(substr(md5(uniqid()), 0, 5)) . '-' . time(),
            'order_status' => 'pending',
            'note' => $request->order_note,
            'used_points' => $used_points,
        ]);

        foreach ($items as $item) {
            $shop_order = ShopOrder::create([
                'shopID' => $item['product']->shopID,
                'orderID' => $order->id,
                'code' => 'DHS'. '-' .strtoupper(substr(md5(uniqid()), 0, 5)) . '-' . substr(time(), -3),
                'note' => $shop_notes[$item['product']->shopID] ?? '',
                'shipping_fee' => (int)$item['product']->shipping_fee,
            ]);
            
            $quantity = $item['quantity'];
            
            if (isset($item['is_combo']) && $item['is_combo'] && isset($item['combo_info'])) {
                $unit_price = $item['combo_info']['price_in_combo'];
                $original_price = $item['combo_info']['original_price'];
                $discount_amount = $original_price - $unit_price;
                $variant_id = $item['product']->variants->first()->id;
                $variant_name = $item['product']->variants->first()->variant_name ?? 'Không có biến thể';
            } else {
                if ($item['product']->variants && $item['product']->variants->count() > 0) {
                    $unit_price = $item['product']->variants->first()->sale_price;
                    $original_price = $item['product']->variants->first()->price;
                    $discount_amount = $item['product']->variants->first()->discount_amount ?? 0;
                    $variant_id = $item['product']->variants->first()->id;
                    $variant_name = $item['product']->variants->first()->variant_name;
                } else {
                    $unit_price = $item['product']->sale_price ?? $item['product']->price;
                    $original_price = $item['product']->price;
                    $discount_amount = $item['product']->sale_price ? ($item['product']->price - $item['product']->sale_price) : 0;
                    $variant_id = 0;
                    $variant_name = 'Không có biến thể';
                }
            }
            
            $item_total_price = $unit_price * $quantity;

            $items_order = ItemsOrder::create([
                'orderID' => $order->id,
                'shop_orderID' => $shop_order->id,
                'productID' => $item['product']->id,
                'variantID' => $variant_id,
                'product_name' => $item['product']->name,
                'quantity' => $quantity,
                'brand' => $item['product']->brand,
                'category' => $item['product']->category,
                'variant_name' => $variant_name,
                'product_image' => $item['product']->image,
                'unit_price' => $unit_price,
                'total_price' => $item_total_price,
                'discount_amount' => $discount_amount,
            ]);
        }

        $order_address = OrderAddress::create([
            'order_id' => $order->id,
            'receiver_name' => $user_address->receiver_name,
            'receiver_phone' => $user_address->receiver_phone,
            'receiver_email' => $user_address->receiver_email,
            'address' => $user_address->address,
            'province' => $user_address->province,
            'district' => $user_address->district,
            'ward' => $user_address->ward,
            'zip_code' => $user_address->zip_code,
            'note' => $request->order_note,
            'address_type' => $user_address->address_type,
        ]);


        return $order;
    }

    private function CodPayment($order)
    {
        return redirect()->route('checkout.success', ['order_code' => $order->order_code]);
    }

    public function successPayment($order_code)
    {
        $order = Order::with(['address', 'items'])->where('order_code', $order_code)->first();

        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Cập nhật tồn kho cho từng sản phẩm trong đơn hàng
        foreach ($order->items as $item) {
            $product = Product::with(['variants' => function ($query) use ($item) {
                $query->where('id', $item->variantID);
            }])->find($item->productID);

            if ($product && $product->variants->first()) {
                $variant = $product->variants->first();
                $newStock = max(0, $variant->stock - $item->quantity);
                $variant->update(['stock' => $newStock]);
            }
        }

        // Gửi event cho từng shop_order
        if ($order->shop_order && $order->shop_order->count()) {
            foreach ($order->shop_order as $shop_order) {
                Log::info('Create Order Event', [
                    'shop_id' => $shop_order->shopID,
                    'order_id' => $order->id
                ]);
                event(new CreateOrderEvent($shop_order->shopID, $order));
            }
        }

        // Lưu lịch sử trạng thái đơn hàng
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'order_status' => 'pending'
        ]);

        $orderProductIds = $order->items->pluck('productID')->toArray();
        $orderVariantIds = $order->items->pluck('variantID')->toArray();

        Cart::where('userID', Auth::id())
            ->whereIn('productID', $orderProductIds)
            ->where(function ($query) use ($orderVariantIds) {
                $query->whereNull('variantID')
                      ->orWhereIn('variantID', $orderVariantIds);
            })
            ->delete();
        session()->forget('checkout_items');

        $customer = Customer::where('userID', Auth::id())->first();
        if ($customer) {
            $customer->total_points = max(0, $customer->total_points - (int) $order->used_points);

            $rankPercent = match ($customer->rank) {
                'silver' => 2,
                'gold' => 3,
                'platinum' => 4,
                'diamond' => 5,
                default => 1,
            };
            $bonusPoints = round($order->total_price * $rankPercent / 100);

            $customer->total_points += $bonusPoints;
            $customer->save();

            PointTransaction::create([
                'userID' => Auth::id(),
                'orderID' => $order->id,
                'points' => $bonusPoints,
                'type' => 'order',
                'description' => 'Đơn hàng #' . $order->order_code,
            ]);
        }

        $products = [];
        foreach ($order->items as $item) {
            $product = Product::with(['variants' => function ($query) use ($item) {
                $query->where('id', $item->variantID);
            }])->find($item->productID);

            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $item->quantity,
                    'price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ];
            }
        }

        return view('user.checkout_status.success_payment', compact('order', 'product'));
    }

    public function failedPayment($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();
        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }

        $orderItems = ItemsOrder::where('orderID', $order->id)->get();
        $items = [];
        foreach ($orderItems as $item) {
            $product = Product::with(['variants' => function ($query) use ($item) {
                $query->where('id', $item->variantID);
            }])->find($item->productID);

            $items[] = [
                'product' => $product,
                'quantity' => $item->quantity,
                'price' => $item->unit_price,
                'total_price' => $item->total_price,
            ];
        }

        session(['checkout_items' => $items]);
        return redirect()->route('checkout')->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
    }    

    public function createPaymentTransaction($order, array $data)
    {
        if (!$order || empty($data)) {
            Log::warning('createPaymentTransaction: Thiếu order hoặc data', [
                'order' => $order,
                'data' => $data
            ]);
            return null;
        }

        $fields = [
            'user_id'        => Auth::id(),
            'order_id'       => $order->id,
            'provider'       => $data['provider'] ?? null,
            'method'         => $data['method'] ?? null,
            'amount'         => $data['amount'] ?? 0,
            'currency'       => $data['currency'] ?? 'VND',
            'status'         => $data['status'] ?? 'pending',
            'transaction_id' => $data['transaction_id'] ?? null,
            'raw_response'   => is_array($data['raw_response']) ? json_encode($data['raw_response']) : ($data['raw_response'] ?? null),
            'message'        => $data['message'] ?? null,
        ];

        try {
            $paymentTransaction = PaymentTransaction::create($fields);
            Log::info('Tạo payment transaction thành công', [
                'order_id' => $order->id,
                'transaction_id' => $fields['transaction_id']
            ]);
            return $paymentTransaction;
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo payment transaction', [
                'error' => $e->getMessage(),
                'fields' => $fields
            ]);
            return null;
        }
    }

    public function applyShopDiscount(Request $request)
    {
        $used_coupon_already = session('used_coupon_data');
        if($used_coupon_already) {
            if($request->coupon_code == $used_coupon_already['code']) {
                return response()->json(['error' => 'Bạn đã sử dụng mã giảm giá này, vui lòng chọn mã giảm giá khác'], 422);
            }
        }

        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if (!$coupon) {
            return response()->json(['error' => 'Mã giảm giá không hợp lệ'], 422);
        }

        $shop = Shop::find($request->shop_id);
        if (!$shop) {
            return response()->json(['error' => 'Shop không tồn tại'], 422);
        }

        if ($coupon->shop_id && $coupon->shop_id != $shop->id) {
            return response()->json(['error' => 'Mã giảm giá không áp dụng cho shop này'], 422);
        }

        if (!$coupon->is_active || $coupon->status != 'active') {
            return response()->json(['error' => 'Mã giảm giá không còn hiệu lực'], 422);
        }

        if ($coupon->max_uses_total !== null && $coupon->used_count >= $coupon->max_uses_total) {
            return response()->json(['error' => 'Mã giảm giá đã hết lượt sử dụng'], 422);
        }

        $now = now();
        if ($coupon->start_date && $now->lt($coupon->start_date)) {
            return response()->json(['error' => 'Mã giảm giá chưa bắt đầu'], 422);
        }
        if ($coupon->end_date && $now->gt($coupon->end_date)) {
            return response()->json(['error' => 'Mã giảm giá đã hết hạn'], 422);
        }

        if ($coupon->rank_limit && $coupon->rank_limit !== 'all') {
            $user = Auth::user();
            if (!$user || $user->rank < $coupon->rank_limit) {
                return response()->json(['error' => 'Bạn không đủ điều kiện sử dụng mã giảm giá này, ít nhất cần đạt cấp độ ' . $coupon->rank_limit], 422);
            }
        }
        
        if ($coupon->max_uses_per_user !== null) {
            $user = Auth::user();
            if ($user) {
                $userUsedCount = UserCouponUsed::where('user_id', $user->id)
                    ->where('coupon_id', $coupon->id)
                    ->first();
                if (!$userUsedCount) {
                    $userUsedCount = (object)['used_count' => 0];
                }
                if ($userUsedCount->used_count >= $coupon->max_uses_per_user) {
                    return response()->json(['error' => 'Bạn đã sử dụng hết số lần cho phép của mã này'], 422);
                }
            }
        }

        if ($coupon->min_order_amount !== null) {
            $orderAmount = $request->total_amount ?? null;
            if ($orderAmount === null || $orderAmount < $coupon->min_order_amount) {
                return response()->json(['error' => 'đơn hàng cần đạt tối thiểu ' . $coupon->min_order_amount . 'đ' . ' hiện tại là ' . $orderAmount . 'đ'], 422);
            }
        }

        $discount_value_coupon = $coupon->calculateDiscount((int)$request->total_amount);
        if($coupon->max_discount_amount !== null) {
            if($discount_value_coupon > $coupon->max_discount_amount) {
                $discount_value_coupon = $coupon->max_discount_amount;
            }
        }

        $used_coupon_data = [
            'id' => (int) $coupon->id,
            'code' => $coupon->code,
            'discount_value' => (int) $discount_value_coupon,
            'max_discount_amount' => $coupon->max_discount_amount !== null ? (int) $coupon->max_discount_amount : null,
            'min_order_amount' => $coupon->min_order_amount !== null ? (int) $coupon->min_order_amount : null,
            'max_uses_per_user' => $coupon->max_uses_per_user !== null ? (int) $coupon->max_uses_per_user : null,
            'max_uses_total' => $coupon->max_uses_total !== null ? (int) $coupon->max_uses_total : null,
            'rank_limit' => $coupon->rank_limit,
            'start_date' => $coupon->start_date,
            'end_date' => $coupon->end_date,
            'is_active' => (int) $coupon->is_active,    
            'status' => $coupon->status,
            'discount_type' => $coupon->discount_type,
            'type_coupon' => $coupon->type_coupon,
            'order_amount' => (int) $request->total_amount,
        ];
        session(['used_coupon_data' => $used_coupon_data]);
        return response()->json(['success' => true, 'used_coupon_data' => $used_coupon_data]);
    }
}
