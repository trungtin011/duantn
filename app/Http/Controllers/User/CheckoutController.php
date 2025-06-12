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
use App\Models\OrderAddress;
use App\Models\OrderStatusHistory;
use App\Models\ShopOrder;
use App\Models\ItemsOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\VNPayController;
use App\Models\ShopAddress;

class CheckoutController extends Controller
{

    public function getItemsFromFlow($request)
    {
        $items = [];
        if ($request->flow_type == 'cart_to_checkout') {
            $cart = Cart::where('userID', Auth::user()->id)->get();
            foreach ($cart as $item) {
                $items[] = [
                    'product' => Product::with(['variants' => function ($query) use ($item) {
                        $query->where('id', $item->variantID);
                    }])->find($item->productID),
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                ];
            }
        }
        else if ($request->flow_type == 'instant_checkout') {
            $product = Product::where('id', $request->productID)->first();
            $variant = ProductVariant::where('id', $request->variantID)->first();
            $items = [
                'productID' => $product->id,
                'variantID' => $variant->id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total_price' => $request->total_price,
                'shopID' => $request->shopID,
            ];
        }

        session(['checkout_items' => $items]);
        return $items;
    }

    public function index(Request $request)
    {
        $checkout_items = session('checkout_items');
        if($checkout_items){
            $items = $checkout_items;
        }
        else{
            $items = $this->getItemsFromFlow($request);
        }
        $products = [];
        foreach($items as $item){
            $products[] = $item['product']->id;
        }
        $products = Product::whereIn('id', $products)->with('variants')->get();
        $user_addresses = UserAddress::where('userID', Auth::user()->id)->get();
        $shop_addresses = ShopAddress::whereIn('shopID', $products->pluck('shopID'))
            ->where('is_default', 1)
            ->get();
        $shops = Shop::whereIn('id', $products->pluck('shopID'))->get();
        $default_address = $shop_addresses->last();
        return view('client.checkout', compact('user_addresses','items','products','default_address','shops'));
    }

    public function store(Request $request)
    {
        $items = session('checkout_items');
        $user = Auth::user();

        if (empty($items)) {
            return redirect()->back()->with('error', 'Không có sản phẩm để đặt hàng');
        }
        $products = [];
        foreach($items as $item){
            $products[] = $item['product']->id;
        }
        $products = Product::whereIn('id', $products)->with('variants')->get();
        $user_address = UserAddress::where('id', $request->address)
            ->where('userID', $user->id)
            ->first();
        if (!$user_address) {
            return redirect()->back()->with('error', 'Địa chỉ không tồn tại');
        }
        $order = $this->createOrder($items, $user_address, $request, $products);
       
        switch ($request->payment) {
            case 'COD':
                $order = $this->CodPayment($order);
                break;
            case 'MOMO':
                return $this->MomoPayment($order);
                break;
            case 'VNPAY':
                $vnpayController = new VNPayController();
                return $vnpayController->VNpayPayment($order);
                break;
        }
        return redirect()->route('checkout')->with('message', 'Đặt hàng thành công');
    }

    private function getTotalPrice($items){
        $total_price = 0;
        foreach($items as $item){
            $total_price += $item['total_price'];
        }
        return $total_price;
    }

    private function createOrder($items, $user_address, $request, $products)
    {
        $total_price = $this->getTotalPrice($items);
        $order_Items = [];
        $shop_notes = json_decode($request->shop_notes, true);

        $order = Order::create([
            'userID' => Auth::id(),
            'shopID' => 1,
            'user_address' => $user_address->id,
            'total_price' => $total_price,
            'payment_method' => $request->payment,
            'order_code' => 'DH'. '-' .strtoupper(substr(md5(uniqid()), 0, 5)) . '-' . time(),
            'order_status' => 'pending',
            'note' => $request->order_note
        ]);
        
        foreach($products as $product){
            $shop_order = ShopOrder::create([
                'shopID' => $product->shopID,
                'orderID' => $order->id,
                'note' => $shop_notes[$product->shopID]
            ]);
            Log::info($shop_order);
            foreach($items as $item){
                if($item['product']->id == $product->id){
                    $quantity = $item['quantity'];
                    $total_price = $item['total_price'];
                }
            }
            $items_order = ItemsOrder::create([
            'orderID' => $order->id,
            'shop_orderID' => $shop_order->id,
            'productID' => $product->id,
            'variantID' => $product->variants->first()->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'brand' => $product->brand,
            'category' => $product->category,
            'sub_category' => $product->sub_category,
            'color' => $product->variants->first()->color,
            'size' => $product->variants->first()->size,
            'variant_name' => $product->variants->first()->variant_name,
            'product_image' => $product->image,
            'unit_price' => $product->variants->first()->price,
            'total_price' => $total_price,
            'discount_amount' => $product->variants->first()->discount_amount,
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
    
    private function CodPayment(){
    }

    private function MomoPayment($order){
        try {
            $partnerCode = 'MOMOBKUN20180529';                  
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';

            $requestId = time() . "";
            $amount = 100000;
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

    public function momoReturn(Request $request){
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

    public function momoIpn(Request $request){
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

    public function successPayment($order_code){
        $order = Order::where('order_code', $order_code)->with('address','items')->first();
        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }

        $product = Product::where('id', $order->items->first()->productID)->with('variants')->first();
        $stock = $product->variants->first()->stock - $order->items->first()->quantity;
        $product->variants->first()->update([
            'stock' => $stock
        ]);

        Cart::where('userID', Auth::user()->id)->delete();
        session()->forget('checkout_items');

        return view('user.checkout_status.success_payment', compact('order','product'));
    }

    public function failedPayment($order_code){
        $order = Order::where('order_code', $order_code)->first();
        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }

        $orderItems = ItemsOrder::where('orderID', $order->id)->get();
        $items = [];
        foreach($orderItems as $item) {
            $product = Product::with(['variants' => function($query) use ($item) {
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
        
}
