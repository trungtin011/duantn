<?php

namespace App\Http\Controllers\Seller\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ShopAddress;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ShippingController extends Controller
{
    protected $token;
    protected $createEndpoint;
    protected $shopId;
    protected $ghnApiUrl;
    protected $contentType;
    protected $trackingEndpoint;
    protected $cancelEndpoint;

    public function __construct(){
        $this->token = config('services.ghn.token');
        $this->createEndpoint = config('services.ghn.create_order');
        $this->shopId = config('services.ghn.shop_id');
        $this->ghnApiUrl = config('services.ghn.url');
        $this->contentType = 'application/json';
        $this->trackingEndpoint = config('services.ghn.tracking');
        $this->cancelEndpoint = config('services.ghn.cancel');
    }

    public function createShippingOrder($orders, $id_shop_address, $payment_type_id, $note, $required_note){

        if(!$orders){
           return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
        }
        $shop_address = ShopAddress::find($id_shop_address);

        $shop_province_name = $shop_address->shop_province;
        $shop_district_name = $shop_address->shop_district;
        $shop_ward_name = $shop_address->shop_ward;
        $shop_street_address = $shop_address->shop_address;

        $receiver_province_name = $orders->address->province;
        $receiver_district_name = $orders->address->district;
        $receiver_ward_name =  $orders->address->ward;
        $receiver_street_address = $orders->address->address;

        
        $url = $this->ghnApiUrl . $this->createEndpoint;
    
        $response = Http::withHeaders([
            'Token' => $this->token,
            'Content-Type' => $this->contentType,
            'shopId' => $this->shopId,
        ])
        ->post($url, [
            'payment_type_id' => (int)$payment_type_id,
            'note' => $note,
            'required_note' => $required_note,
            'from_name' => $orders->shop->shop_name,
            'from_phone' => $orders->shop->shop_phone,
            'from_address' => $shop_street_address,
            'from_ward_name' => $shop_ward_name,
            'from_district_name' => $shop_district_name,
            'from_province_name' => $shop_province_name,
            'client_order_code' => $orders->order_code,
            'to_name' => $orders->address->receiver_name,
            'to_phone' => $orders->address->receiver_phone,
            'to_address' => $receiver_street_address,
            'to_ward_name' => $receiver_ward_name,
            'to_district_name' => $receiver_district_name,
            'to_province_name' => $receiver_province_name,
            'service_type_id' => 2,
            'service_id' => 533,
            'weight' => 1200,
            'height' => 10,
            'width' => 10,
            'length' => 10,
            'cod_amount' => (int)$orders->total_price,
            'items' => [
                [
                    'name' => 'sản phẩm test', 
                    'quantity' => 1,
                    'weight' => 1200, 
                    'length' => 10, 
                    'width' => 10,   
                    'height' => 10, 
                ]
            ],      
        ]);
        $responseData = $response->json();
        if ($response->status() == 200) {
            $orders->shop_order->first()->update(['status' => 'shipping']);
            $expectedDateTime = $responseData['data']['expected_delivery_time']; // "2025-06-17T16:59:59Z"
            $expectedDate = Carbon::parse($expectedDateTime)->format('Y-m-d H:i');
            $data = [
                'tracking_code' => $responseData['data']['order_code'],
                'expected_delivery_date' => $expectedDate,
            ];
            $orders->shop_order->first()->update($data);
            $orders->update(['order_status' => 'shipped']);
            Log::info('status: ' . $orders->order_status);
            return true;
        } else {
            return false;
        }
    }

    public function renderCreateShippingOrder($orders){
        if(!$orders){
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
        }

        return view('seller.orders.shipping.create', compact('orders'));
    }

    public function cancelOrderGHN($orders){
        $url = $this->ghnApiUrl . $this->cancelEndpoint;

        $response = Http::withHeaders([
            'Token' => $this->token,
            'Content-Type' => $this->contentType,
            'shopId' => $this->shopId,
        ])
        ->post($url, [
            'order_code' => $orders->shop_order->first()->tracking_code,
        ]);

        if($response->status() == 200){
            return true;
        }
        else{
            return false;
        }
    }
}
