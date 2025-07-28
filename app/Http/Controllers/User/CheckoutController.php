<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

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
    Combo
};

use App\Http\Controllers\VNPayController;
use App\Http\Requests\CheckoutRequest;
use App\Events\CreateOrderEvent;


class CheckoutController extends Controller
{
    /**
     * Calculate discounted price for combo products
     */
    private function calculateComboDiscountedPrice($combo, $basePrice, $comboProduct)
    {
        $discountedPrice = $basePrice;
        
        if ($combo->discount_type === 'percentage' && $combo->discount_value > 0) {
            // Giảm giá theo phần trăm
            $discountMultiplier = 1 - ($combo->discount_value / 100);
            $discountedPrice = $basePrice * $discountMultiplier;
        } elseif ($combo->discount_type === 'fixed' && $combo->discount_value > 0) {
            // Giảm giá cố định - phân bổ theo tỷ lệ giá sản phẩm trong combo
            $totalComboBasePrice = 0;
            foreach ($combo->products as $cp) {
                $productPrice = $cp->variant ? ($cp->variant->sale_price ?? $cp->variant->price) : ($cp->product->sale_price ?? $cp->product->price);
                $totalComboBasePrice += $productPrice * $cp->quantity;
            }
            
            if ($totalComboBasePrice > 0) {
                // Tính tỷ lệ giá của sản phẩm này trong combo
                $productBasePrice = $basePrice * $comboProduct->quantity;
                $discountRatio = $productBasePrice / $totalComboBasePrice;
                $productDiscount = $combo->discount_value * $discountRatio;
                $discountedPrice = $basePrice - ($productDiscount / $comboProduct->quantity);
            } else {
                // Fallback: chia đều giảm giá cho tất cả sản phẩm
                $discountedPrice = $basePrice - ($combo->discount_value / count($combo->products));
            }
        }
        
        // Đảm bảo giá không âm
        return max(0, $discountedPrice);
    }

    public function getItems()
    {
        $items = [];
        $selected_products = session()->get('selected_products');
        if (empty($selected_products)) {
            return false;
        }
        $subtotal = 0;
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
                $subtotal += $selected['subtotal'];
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
                $subtotal += $selected['subtotal'];
            }
        }
        $items['subtotal'] = $subtotal;
        return $items;
    }

    public function index(Request $request)
    {   
        $user_coupon = CouponUser::where('user_id', Auth::user()->id)->first();
        $items = $this->getItems();
        if (!$items) {
            return redirect()->back()->with('error', 'Không có sản phẩm được chọn');
        }
        $subtotal = $items['subtotal'];
        unset($items['subtotal']);
        $shop_ids = [];
        foreach ($items as $item) {
            $shop_ids[] = $item['product']->shopID;
        }
        $shop_ids = array_unique($shop_ids);
        $shops = Shop::whereIn('id', $shop_ids)->get();
        $user_addresses = UserAddress::where('userID', Auth::user()->id)->get();
        $user_points = Customer::where('userID', Auth::user()->id)->pluck('total_points')->first();
        return view('client.checkout', compact('user_addresses', 'items', 'shops', 'user_coupon', 'subtotal', 'user_points'));
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
        
        $coupon_code = $request->discount_code;
        $coupon = Coupon::where('code', $coupon_code)->first() ?? null;
        $coupon_id = $coupon ? $coupon->id : null;
        $total_price = $request->total_amount;
        $shop_notes = $request->shop_notes ?? [];
        $used_points = $request->used_points;
        $order = Order::create([
            'userID' => Auth::id(),
            'user_address' => $user_address->id,
            'total_price' => $total_price,
            'payment_method' => $request->payment_method,
            'order_code' => 'DH' . '-' . strtoupper(substr(md5(uniqid()), 0, 5)) . '-' . time(),
            'order_status' => 'pending',
            'coupon_id' => $coupon_id,
            'coupon_discount' => $request->discount_amount,
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
                // Sử dụng giá combo đã được tính sẵn
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

}