<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\User\CheckoutController;

class MomoPaymentController extends Controller
{
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;
    protected $endpoint;
    protected $ipnUrl;
    protected $redirectUrl;
    
    public function __construct()
    {
        $this->partnerCode = 'MOMOBKUN20180529';
        $this->accessKey = 'klm05TvNBzhg7h7j';
        $this->secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $this->endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
        $this->ipnUrl = route('payment.momo.ipn');
        $this->redirectUrl = route('payment.momo.return');
    }
    
    public function momoPayment($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();
        if(!$order)
        {
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }
        
        try {   
            $partnerCode = $this->partnerCode;
            $accessKey = $this->accessKey;
            $secretKey = $this->secretKey;
            $endpoint = $this->endpoint;
            $ipnUrl = $this->ipnUrl;
            $redirectUrl = $this->redirectUrl;
            
            $requestId = time() . "";
            $amount = (int)($order->total_price);
            $orderId = $order->id . time();
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
            $responseData = $response->json();

            if ($response->successful()) {
                if (isset($responseData['payUrl']) && !empty($responseData['payUrl'])) {
                    Log::info('MoMo Payment Success - Redirecting to:', [
                        'pay_url' => $responseData['payUrl']
                    ]);

                    return redirect()->away($responseData['payUrl']);
                }

                Log::warning('MoMo Payment Failed - No Pay URL');
                return redirect()->route('checkout')->with('error', 'Không thể lấy link thanh toán từ MoMo.');
            }

            $responseData = $response->json();

            return redirect()->route('checkout')->with('error', $responseData['message'] ?? 'Không thể kết nối với MoMo.');


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
            $partnerCode = $this->partnerCode;
            $accessKey = $this->accessKey;
            $secretKey = $this->secretKey;

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
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
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

                $checkoutController = new CheckoutController();
                $checkoutController->createPaymentTransaction($order, $data);
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
        Log::info('MoMo IPN Request:', $request->all());
        try {
            $partnerCode = $this->partnerCode;
            $accessKey = $this->accessKey;
            $secretKey = $this->secretKey;

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

                $checkoutController = new CheckoutController();
                $checkoutController->createPaymentTransaction($order, $data);
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
}
