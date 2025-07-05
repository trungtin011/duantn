<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\OrderAddress;
use App\Models\ShopOrder;
use App\Models\ItemsOrder;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\VNPayController;
use App\Models\ShopAddress;
use App\Events\CreateOrderEvent;
use Illuminate\Support\Facades\Event;
use App\Http\Requests\CheckoutRequest;
use App\Models\PaymentTransaction;
use App\Models\CouponUser;


class CheckoutController extends Controller
{
    public function getItems()
    {
        $selected_products = session()->get('selected_products');
        
        if (empty($selected_products)) {
            return redirect()->back()->with('error', 'Không có sản phẩm được chọn');
        }

        $items = [];

        foreach ($selected_products as $product) {
            $item = Product::where('id', $product['product_id'])
                ->with(['variants' => function($query) use ($product) {
                    $query->where('id', $product['variant_id']);
                }])
                ->first();

            if ($item) {
                $items[] = [
                    'product' => $item,
                    'quantity' => $product['quantity']
                ];
            }
        }
        return $items;
    }

    public function index(Request $request)
    {   
        $user_coupon = CouponUser::where('user_id', Auth::user()->id)->first();
        $items = $this->getItems();
        $shop_ids = [];
        
        foreach ($items as $item) {
            $shop_ids[] = $item['product']->shopID;
        }
        $shop_ids = array_unique($shop_ids);
        $shops = Shop::whereIn('id', $shop_ids)->get();
        $user_addresses = UserAddress::where('userID', Auth::user()->id)->get();
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['product']->variants->first()->sale_price * $item['quantity'];
        }
        return view('client.checkout', compact('user_addresses', 'items', 'shops', 'user_coupon', 'subtotal'));
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
            ]);
            if(!$validated)
            {
                return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ'], 400);
            }
            $items = $this->getItems();
            $user = Auth::user();

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
            Log::info($order);
            return response()->json(['success' => true, 'message' => 'Đặt hàng thành công', 'order' => $order], 200);
        } catch (\Exception $e) {
            Log::error('Order store error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getTotalPrice($items)
    {
        $total_price = 0;
        foreach ($items as $item) {
            $total_price += $item['product']->variants->first()->sale_price * $item['quantity'];
        }
        return $total_price;
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
        $coupon = Coupon::where('code', $coupon_code)->first();
        $coupon_id = $coupon->id;
        $total_price = $this->getTotalPrice($items);
        $shop_notes = $request->shop_notes ?? [];
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
            $item_total_price = $item['product']->variants->first()->sale_price * $item['quantity'];

            $items_order = ItemsOrder::create([
                'orderID' => $order->id,
                'shop_orderID' => $shop_order->id,
                'productID' => $item['product']->id,
                'variantID' => $item['product']->variants->first()->id,
                'product_name' => $item['product']->name,
                'quantity' => $quantity,
                'brand' => $item['product']->brand,
                'category' => $item['product']->category,
                'variant_name' => $item['product']->variants->first()->variant_name,
                'product_image' => $item['product']->image,
                'unit_price' => $item['product']->variants->first()->sale_price,
                'total_price' => $item_total_price,
                'discount_amount' => $item['product']->variants->first()->discount_amount,
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

    private function MomoPayment($order)
    {
        try {
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';

            $requestId = time() . "";
            $amount = (int)($order->total_price);
            $orderId = $order->id . time();
            $redirectUrl = route('payment.momo.return');
            $ipnUrl = route('payment.momo.ipn');
            $orderInfo = "Thanh toán đơn hàng " . $order->order_code;
            $extraData = "$order->order_code";
            $requestType = "payWithATM";

            Log::info('MoMo Payment Request Details:', [
                'order_id' => $orderId,
                'order_code' => $order->id,
                'amount' => $amount,
                'request_id' => $requestId,
                'order_info' => $orderInfo
            ]);

            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            Log::info('MoMo Payment Hash Details:', [
                'raw_hash' => $rawHash,
                'signature' => $signature
            ]);

            $requestData = [
                'partnerCode' => $partnerCode,
                'accessKey' => $accessKey,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'requestType' => $requestType,
                'extraData' => $extraData,
                'signature' => $signature,
            ];

            Log::info('MoMo Payment Request Data:', $requestData);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($endpoint, $requestData);

            Log::info('MoMo Payment Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['payUrl']) && !empty($responseData['payUrl'])) {
                    Log::info('MoMo Payment Success - Redirecting to:', [
                        'pay_url' => $responseData['payUrl']
                    ]);

                    $order->update(['paid_at' => now()]);

                    return redirect()->away($responseData['payUrl']);
                }

                Log::warning('MoMo Payment Failed - No Pay URL');
                return redirect()->route('checkout')->with('error', 'Không thể lấy link thanh toán từ MoMo.');
            }

            $responseData = $response->json();
            Log::error('MoMo Payment Error:', [
                'error_message' => $responseData['message'] ?? 'Unknown error',
                'response_data' => $responseData
            ]);

            return redirect()->route('checkout')->with('error', $responseData['message'] ?? 'Không thể kết nối với MoMo.');
        } catch (\Exception $e) {
            Log::error('MoMo Payment Exception:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout')->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán.');
        }
    }

    public function momoReturn(Request $request)
    {
        try {
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

            $orderId = $request->orderId;
            $requestId = $request->requestId;
            $amount = $request->amount;
            $orderInfo = $request->orderInfo;
            $orderType = $request->orderType;
            $transId = $request->transId;
            $resultCode = $request->resultCode;
            $message = $request->message;
            $payType = $request->payType;
            $extraData = $request->extraData;
            $signature = $request->signature;

            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $request->responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;
            $expectedSignature = hash_hmac("sha256", $rawHash, $secretKey);

            if ($signature !== $expectedSignature) {
                return redirect()->route('checkout')->with('error', 'Xác thực thanh toán thất bại');
            }

            $orderCode = $request->extraData;
            $order = Order::where('order_code', $orderCode)->first();
            if (!$order) {
                return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
            }
            if ($resultCode == 0) {
                return redirect()->route('checkout.success', ['order_code' => $orderCode]);
            } else {
                return redirect()->route('checkout.failed', ['order_code' => $orderCode])->with('error', 'Thanh toán thất bại');
            }
        } catch (\Exception $e) {
            Log::error('MoMo Return Exception:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout')->with('error', 'Đã xảy ra lỗi khi xử lý kết quả thanh toán');
        }
    }

    public function momoIpn(Request $request)
    {
        try {
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

            $orderId = $request->orderId;
            $requestId = $request->requestId;
            $amount = $request->amount;
            $orderInfo = $request->orderInfo;
            $orderType = $request->orderType;
            $transId = $request->transId;
            $resultCode = $request->resultCode;
            $message = $request->message;
            $payType = $request->payType;
            $extraData = $request->extraData;
            $signature = $request->signature;

            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $request->responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;
            $expectedSignature = hash_hmac("sha256", $rawHash, $secretKey);

            if ($signature !== $expectedSignature) {
                Log::error('MoMo IPN - Invalid signature', [
                    'received_signature' => $signature,
                    'expected_signature' => $expectedSignature
                ]);
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }

            $order = Order::where('order_code', $extraData)->first();
            if (!$order) {
                Log::error('MoMo IPN - Order not found', ['order_code' => $extraData]);
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            if ($order->payment_status === 'paid') {
                return response()->json(['RspCode' => '00', 'Message' => 'Order already processed']);
            }

            if ($resultCode == 0) {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'order_status' => 'processing'
                ]);
                $data = [
                    'provider' => 'MOMO',
                    'method' => 'ATM',
                    'amount' => $amount,
                    'currency' => 'VND',
                    'status' => 'success',
                    'transaction_id' => $transId,
                    'raw_response' => $request->all(),
                    'message' => 'Thanh toán thành công',
                ];
                $this->createPaymentTransaction($order, $data);
                return response()->json(['RspCode' => '00', 'Message' => 'Success']);
            } else {
                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled',
                    'note' => 'Thanh toán thất bại qua MoMo',
                    'cancel_reason' => 'Huỷ Thanh Toán'
                ]);

                return response()->json(['RspCode' => '00', 'Message' => 'Payment failed']);
            }
        } catch (\Exception $e) {
            Log::error('MoMo IPN Exception:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
        }
    }

    public function successPayment($order_code)
    {
        $order = Order::where('order_code', $order_code)->with('address', 'items')->first();
        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }

        $product = Product::with(['variants' => function($query) use ($order) {
            $query->where('id', $order->items->first()->variantID);
        }])->find($order->items->first()->productID);
        $stock = $product->variants->first()->stock - $order->items->first()->quantity;
        $product->variants->first()->update([
            'stock' => $stock
        ]);
        session()->forget('checkout_items');

        foreach ($order->shop_order as $shop_order) {
            Log::info(' /////////////// Create Order Event /////////////// ', [
                'shop_id' => $shop_order->shopID,
                'order_id' => $order->id
            ]);
            event(new CreateOrderEvent($shop_order->shopID, $order));
        }

        $order_status_history = new OrderStatusHistory();
        $order_status_history->order_id = $order->id;
        $order_status_history->order_status = 'pending';
        $order_status_history->save();

        Cart::where('userID', Auth::user()->id)->delete();
        session()->forget('checkout_items');
        
        return view('user.checkout_status.success_payment', compact('order','product'));
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
