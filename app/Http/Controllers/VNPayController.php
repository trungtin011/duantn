<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Http\Controllers\User\CheckoutController;

class VNPayController extends Controller
{

    
    public function vnpayPayment($order_code){
        $order = Order::where('order_code', $order_code)->first();
        if(!$order){
            return redirect()->route('checkout')->with('error', 'Không tìm thấy đơn hàng');
        }
        try {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = route('payment.vnpay.return');
            $vnp_TmnCode = "ES0CKUAY"; 
            $vnp_HashSecret = "C4Q3LIILB0NC7ZMK5DK9MASI28BN4RI7"; 

            $vnp_TxnRef = $order->order_code; //Mã đơn hàng
            $vnp_OrderInfo = "Thanh toan don hang " . $order->order_code;
            $vnp_OrderType = "billpayment";
            $vnp_Amount = (int)($order->total_price * 100);
            $vnp_Locale = 'vn';
            $vnp_IpAddr = request()->ip();
            $vnp_CreateDate = date('YmdHis');

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $vnp_CreateDate,
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

            Log::info('VNPay Payment Request:', [
                'order_code' => $order->order_code,
                'amount' => $vnp_Amount,
                'payment_url' => $vnp_Url
            ]); 

            return redirect()->away($vnp_Url);
        } catch (\Exception $e) {
            Log::error('VNPay Payment Exception:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout')->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán VNPay');
        }
    }

    public function vnpayReturn(Request $request){
        try {
            $vnp_HashSecret = "C4Q3LIILB0NC7ZMK5DK9MASI28BN4RI7"; 
            $vnp_SecureHash = $request->vnp_SecureHash;
            $inputData = array();
            foreach ($request->all() as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            
            if ($secureHash == $vnp_SecureHash) {
                if ($request->vnp_ResponseCode == '00') {
                    $order = Order::where('order_code', $request->vnp_TxnRef)->first();
                    if ($order) {
                        $order->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                        ]);
                        $data = [   
                            'provider' => 'VNPAY',
                            'method' => 'ATM',
                            'amount' => $request->vnp_Amount,
                            'currency' => 'VND',
                            'status' => 'success',
                            'transaction_id' => $request->vnp_TransactionNo,
                            'raw_response' => $request->all(),
                            'message' => 'Thanh toán thành công',
                        ];

                        $checkoutController = new CheckoutController();
                        $checkoutController->createPaymentTransaction($order, $data);
                        return redirect()->route('checkout.success', ['order_code' => $order->order_code]);
                    }
                } else {
                    $order = Order::where('order_code', $request->vnp_TxnRef)->first();
                    if ($order) {
                        return redirect()->route('checkout.failed', ['order_code' => $order->order_code]);
                    }
                }
            }
            return redirect()->route('checkout')->with('error', 'Xác thực thanh toán thất bại');
        } catch (\Exception $e) {
            Log::error('VNPay Return Exception:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout')->with('error', 'Đã xảy ra lỗi khi xử lý kết quả thanh toán');
        }
    }

    public function vnpayIpn(Request $request)
    {
        Log::info('VNPay IPN Request:', $request->all());
        try {
            $vnp_HashSecret = "C4Q3LIILB0NC7ZMK5DK9MASI28BN4RI7"; 
            $vnp_SecureHash = $request->vnp_SecureHash;
            $inputData = array();
            
            foreach ($request->all() as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }
            
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            
            if ($secureHash == $vnp_SecureHash) {
                if ($request->vnp_ResponseCode == '00') {
                    $order = Order::where('order_code', $request->vnp_TxnRef)->first();
                    if ($order) {
                        if ($order->payment_status !== 'paid') {
                            $order->update([
                                'payment_status' => 'paid',
                                'paid_at' => now(),

                            ]);
                            
                            $data = [
                                'provider' => 'VNPAY',
                                'method' => 'ATM',
                                'amount' => $request->vnp_Amount,
                                'currency' => 'VND',
                                'status' => 'success',
                                'transaction_id' => $request->vnp_TransactionNo,
                                'raw_response' => $request->all(),
                                'message' => 'Thanh toán thành công',
                            ];

                            $checkoutController = new CheckoutController();
                            $checkoutController->createPaymentTransaction($order, $data);
                        }
                        return response()->json(['RspCode' => '00', 'Message' => 'Success']);
                    } else {
                        Log::error('VNPay IPN - Order not found', ['order_code' => $request->vnp_TxnRef]);
                        return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
                    }
                } else {
                    $order = Order::where('order_code', $request->vnp_TxnRef)->first();
                    if ($order && $order->payment_status !== 'paid') {
                        $order->update([
                            'payment_status' => 'failed',
                            'order_status' => 'cancelled',
                            'note' => 'Thanh toán thất bại qua VNPay',
                            'cancel_reason' => 'Huỷ Thanh Toán'
                        ]);
                    }
                    return response()->json(['RspCode' => '00', 'Message' => 'Payment failed']);
                }
            } else {
                Log::error('VNPay IPN - Invalid signature', [
                    'received_signature' => $vnp_SecureHash,
                    'expected_signature' => $secureHash
                ]);
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }
        } catch (\Exception $e) {
            Log::error('VNPay IPN Exception:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
        }
    }

}
