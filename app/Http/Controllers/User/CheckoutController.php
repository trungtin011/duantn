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
                        $product = $comboProduct->product;
                        $variant = null;
                        $basePrice = null;
                        if ($product->is_variant == 1) {
                            $variant = $comboProduct->variant ?? null;
                            $basePrice = $variant ? ($variant->sale_price ?? $variant->price) : null;
                        } else {
                            $basePrice = $product->sale_price ?? $product->price;
                        }

                        $discountedPrice = $this->calculateComboDiscountedPrice($combo, $basePrice, $comboProduct);

                        $items[] = [
                            'product' => $product,
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
                $item = Product::find($selected['product_id']);
                $quantity = isset($selected['quantity']) ? $selected['quantity'] : 1;
                if ($item) {
                    $variant = null;
                    if ($item->is_variant && !empty($selected['variant_id'])) {
                        $variant = $item->variants()->where('id', $selected['variant_id'])->first();
                    }
                    $items[] = [
                        'product' => $item,
                        'quantity' => $quantity,
                        'variant' => $variant,
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
        $subtotal = 0;
        foreach ($items as $item) {
            if (!empty($item['is_combo']) && $item['is_combo'] && isset($item['combo_info']['price_in_combo'])) {
                $subtotal += $item['combo_info']['price_in_combo'] * $item['quantity'];
            } elseif (isset($item['product']->is_variant) && $item['product']->is_variant == 1) {
                $variant = $item['product']->variants->where('id', $item['variant_id'])->first();
                $subtotal += $variant->sale_price * $item['quantity'];
            } else {
                $subtotal += $item['product']->price * $item['quantity'];
            }
        }
        $shop_ids = array_unique($shop_ids);
        $shops = Shop::whereIn('id', $shop_ids)
        ->with('coupons')
        ->get();
        $user_addresses = UserAddress::where('userID', Auth::user()->id)->get();
        $user_points = Customer::where('userID', Auth::user()->id)->pluck('total_points')->first();
        return view('client.checkout', compact('user_addresses', 'items', 'shops', 'user_coupon', 'user_points','public_coupons','subtotal'));
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
            // Kiểm tra reCAPTCHA v3
            $recaptchaToken = $request->input('recaptcha_token');
            if (!$recaptchaToken) {
                Log::warning('Missing reCAPTCHA token', ['ip' => $request->ip()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu token reCAPTCHA, vui lòng thử lại.'
                ], 422);
            }

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (!$result['success'] || $result['score'] < config('services.recaptcha.threshold') || $result['action'] !== 'submit_order') {
                Log::warning('reCAPTCHA verification failed', [
                    'ip' => $request->ip(),
                    'score' => $result['score'] ?? null,
                    'action' => $result['action'] ?? 'unknown',
                    'errors' => $result['error-codes'] ?? []
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Xác minh thất bại, có khả năng là bot. Vui lòng thử lại.'
                ], 422);
            }

            Log::info('reCAPTCHA verification success', [
                'ip' => $request->ip(),
                'score' => $result['score'],
                'action' => $result['action']
            ]);

            // Validate dữ liệu đơn hàng
            $validate = $this->validateOrderData($request);
            if (!empty($validate)) {
                Log::warning('Order validation failed', ['errors' => $validate]);
                return response()->json(['success' => false, 'message' => $validate], 422);
            }

            // Kiểm tra spam
            if (Order::isOrderSpam(Auth::id())) {
                Log::warning('Order spam detected', ['user_id' => Auth::id()]);
                return response()->json(['success' => false, 'message' => 'Bạn đang spam đặt hàng, vui lòng thử lại sau 5p'], 422);
            }

            // Lấy danh sách sản phẩm
            $items = $this->getItems();
            Log::info('Cart items fetched', ['items' => $items]);
            if (empty($items)) {
                Log::warning('Empty cart', ['user_id' => Auth::id()]);
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 400);
            }

            // Lấy địa chỉ người dùng
            $user_address = UserAddress::where('id', $request->selected_address_id)
                ->where('userID', Auth::id())
                ->first();

            if (!$user_address) {
                Log::warning('Invalid address', ['user_id' => Auth::id(), 'address_id' => $request->selected_address_id]);
                return response()->json(['success' => false, 'message' => 'Địa chỉ không hợp lệ'], 400);
            }

            // Tạo đơn hàng
            $shopItems = [];
            foreach ($items as $item) {
                $shop_id = $item['product']->shopID ?? null;
                if ($shop_id) {
                    if (!isset($shopItems[$shop_id])) {
                        $shopItems[$shop_id] = [];
                    }
                    $item['shop_id'] = $shop_id;
                    $shopItems[$shop_id][] = $item;
                }
            }

            $order = $this->createOrder($shopItems, $user_address, $request);
            if (!$order) {
                Log::error('Failed to create order', ['user_id' => Auth::id()]);
                return response()->json(['success' => false, 'message' => 'Lỗi khi tạo đơn hàng'], 400);
            }

            Log::info('Order created', ['order_id' => $order->id, 'order_code' => $order->order_code]);

            // Lưu mã giảm giá
            $this->storeCouponsUsed($order, $request->coupons_code);

            // Xác định URL chuyển hướng
            $redirectUrl = null;
            if ($request->payment_method === 'COD') {
                $redirectUrl = route('checkout.success', ['order_code' => $order->order_code]);
            } elseif ($request->payment_method === 'MOMO') {
                $redirectUrl = route('checkout.momo.payment', ['order_code' => $order->order_code]);
            } elseif ($request->payment_method === 'VNPAY') {
                $redirectUrl = route('checkout.vnpay.payment', ['order_code' => $order->order_code]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'order' => $order,
                'redirectUrl' => $redirectUrl
            ], 200);
        } catch (\Exception $e) {
            Log::error('Order store error', [
                'message' => $e->getMessage(),
                'ip' => $request->ip(),
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function validateOrderData($request)
    {
        $errors = [];
        if(!empty($request->coupons_code)){
            foreach($request->coupons_code as $coupon_code){
                $coupon = Coupon::where('code', $coupon_code['code'])->where('shop_id', $coupon_code['shopId'])->first();
                if(!$coupon){
                    $errors[] = 'Mã giảm giá không hợp lệ';
                }
            }
        }
        $payment_methods = ['COD', 'MOMO', 'VNPAY'];
        if(!isset($request->payment_method) || !in_array($request->payment_method, $payment_methods)){
            $errors[] = 'Phương thức thanh toán không hợp lệ';
        }

        if(!isset($request->selected_address_id) || $request->selected_address_id == '' || $request->selected_address_id == null){
            $errors[] = 'Địa chỉ người dùng không hợp lệ';
        }
        if(!empty($request->discount_code)){
            $coupon = Coupon::where('code', $request->discount_code)->first();
            if(!$coupon){
                $errors[] = 'Mã giảm giá không hợp lệ';
            }
        }
        return $errors;
    }

    private function createOrder($shopItems, $user_address, $request)
    {
        $total_price = $request->total_amount;
        $shop_notes = $request->shop_notes ?? [];
        $used_points = $request->used_points ?? 0;

        $order = Order::create([
            'userID' => Auth::id(),
            'order_code' => 'DH' . '-' . strtoupper(substr(md5(uniqid()), 0, 5)) . '-' . time(),
            'total_price' => $total_price,
            'order_status' => 'pending',
            'shipping_fee' => $request->shipping_fee,
            'used_points' => $used_points,
            'coupon_discount' => $request->discount_amount ?? 0,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        foreach ($shopItems as $shop_id => $items) {
            $shop_order = ShopOrder::firstOrCreate([
                'shopID' => $shop_id,
                'orderID' => $order->id,
            ], [
                'code' => 'DHS-' . strtoupper(substr(md5(uniqid()), 0, 5)) . '-' . substr(time(), -3),
                'note' => $shop_notes[$shop_id] ?? '',
            ]);
        
            foreach ($items as $item) {
                $product = $item['product'];
                $quantity = (int) $item['quantity'];
                $variant_id = null;
                $variant_name = null;
                $unit_price = $product->sale_price ?? $product->price ?? 0;
                $combo_id = null;

                if (!empty($item['is_combo']) && !empty($item['combo_info'])) {
                    $combo_id = $item['combo_info']['combo_id'];
                    $unit_price = $item['combo_info']['price_in_combo'];
                } elseif ($product->is_variant ?? false) {
                    $variant = $product->variants->where('id', $item['variant_id'])->first();
                    $variant_id = $variant->id ?? null;
                    $variant_name = $variant->variant_name ?? null;
                    $unit_price = $variant->sale_price ?? $variant->price ?? $unit_price;
                }
        
                $item_total_price = $unit_price * $quantity;

                $product_image = optional($product->images->where('is_default', true)->first() ?? $product->images->first())->image_path ?? null;

                $itemOrderData = [
                    'orderID' => $order->id,
                    'shop_orderID' => $shop_order->id,
                    'productID' => $product->id,
                    'variantID' => $variant_id,
                    'combo_id' => $combo_id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'brand' => $product->brand ?? null,
                    'category' => $product->category ?? null,
                    'variant_name' => $variant_name,
                    'product_image' => $product_image,
                    'unit_price' => $unit_price,
                    'total_price' => $item_total_price,
                ];
        
                ItemsOrder::create($itemOrderData);
            }
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
            'address_type' => $user_address->address_type,
        ]);
        return $order;
    }
    
    private function storeCouponsUsed($order, $coupons_code): void
    {
        Log::info('storeCouponsUsed called', [
            'order_id' => $order->id ?? null,
            'coupons_code' => $coupons_code
        ]);

        foreach ($coupons_code as $coupon_code) {
            Log::info('Processing coupon for increment used_count', [
                'coupon_code' => $coupon_code['code'] ?? null,
                'coupon_id' => $coupon_code['id'] ?? null
            ]);
            $coupon = Coupon::where('code', $coupon_code['code'])->first();
            if ($coupon) {
                $coupon->used_count++;
                $coupon->save();
                Log::info('Coupon used_count incremented', [
                    'coupon_id' => $coupon->id,
                    'new_used_count' => $coupon->used_count
                ]);
            } else {
                Log::warning('Coupon not found for code', [
                    'coupon_code' => $coupon_code['code'] ?? null
                ]);
            }
        }

        foreach ($coupons_code as $coupon_code) {
            Log::info('Processing UserCouponUsed', [
                'user_id' => Auth::id(),
                'coupon_id' => $coupon_code['id'] ?? null
            ]);
            $coupon_used = UserCouponUsed::where('user_id', Auth::id())
                ->where('coupon_id', $coupon_code['id'])
                ->first();
            Log::info('Coupon used', [
                'coupon_used' => $coupon_used
            ]);
            if ($coupon_used) {
                $coupon_used->used_count++;
                $coupon_used->save();
              
            } else {
                UserCouponUsed::create([
                    'user_id' => Auth::id(),
                    'coupon_id' => $coupon_code['id'],
                    'order_id' => $order->id,
                    'used_count' => 1,
                ]);
                Log::info('UserCouponUsed created', [
                    'user_id' => Auth::id(),
                    'coupon_id' => $coupon_code['id'],
                    'order_id' => $order->id,
                    'used_count' => 1
                ]);
            }
        }
    }

    public function successPayment($order_code)
    {
        $order = Order::with(['address', 'items'])->where('order_code', $order_code)->first();

        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }

        if($order->payment_method == 'COD'){
            $order->payment_status = 'cod_paid';
            $order->save();
        }

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

        if ($order->shop_order && $order->shop_order->count()) {
            foreach ($order->shop_order as $shop_order) {
                event(new CreateOrderEvent($shop_order->shopID, $order));
            }
        }

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
            $customer = Customer::where('userID', Auth::id())->first();
                if (!$customer || !$customer->hasRankAtLeast($coupon->rank_limit)) {
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
