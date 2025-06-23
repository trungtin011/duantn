<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserAddress;
use App\Models\ShopAddress;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ShippingFeeController extends Controller
{
    protected $token;
    protected $shopId;
    protected $ghnApiUrl;

    public function __construct()
    {
        $this->token = config('services.ghn.token');
        $this->shopId = config('services.ghn.shop_id');
        $this->ghnApiUrl = config('services.ghn.url');
    }

    public function calculateShippingFee(Request $request)
    {
        Log::info($request->all());
        $userAddress = UserAddress::find($request->address_id);
        Log::info($userAddress);
        $shopAddress = ShopAddress::where('is_default', 1)->first();
        Log::info($shopAddress);
        if (!$userAddress || !$shopAddress) {
            return response()->json(['error' => 'Không tìm thấy địa chỉ'], 404);
        }

        $url = 'https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/preview';
        
        $requestData = [
            "payment_type_id" => 2,
            "note" => "Không có note",
            "required_note" => "KHONGCHOXEMHANG",
            "from_name" => "MinTien",
            "from_phone" => "0945520405",
            "from_address" => $shopAddress->first()->shop_address,
            "from_ward_name" => $shopAddress->first()->shop_ward,
            "from_district_name" => $shopAddress->first()->shop_district,
            "from_province_name" => $shopAddress->first()->shop_province,
            "to_name" => $userAddress->receiver_name,
            "to_phone" => $userAddress->receiver_phone,
            "to_address" => $userAddress->address,
            "to_ward_name" => $userAddress->ward,
            "to_district_name" => $userAddress->district,
            "to_province_name" => $userAddress->province,
            "cod_amount" => 0,
            "content" => "Không có nội dung",
            "length" => 12,
            "width" => 12,
            "height" => 12,
            "weight" => 1200,
            "cod_failed_amount" => 2000,                  
            "pick_station_id" => 1444,
            "deliver_station_id" => null,
            "insurance_value" => 10000,
            "service_type_id" => 2,
            "coupon" => null, 
            "pickup_time" => 1692840132,
            "pick_shift" => [2],
            "items" => [
                [
                    "name" => "Áo Polo",
                    "code" => "Polo123",
                    "quantity" => 1,
                    "price" => 200000,
                    "length" => 12,
                    "width" => 12,
                    "height" => 12,
                    "weight" => 1200,
                    "category" => [
                        "level1" => "Áo"
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Token' => '60dbcd6a-0c3f-11f0-9f28-eacfdef119b3',
            'Content-Type' => 'application/json',
            'shopId' => '196252',
        ])
        ->post($url, $requestData);
        Log::info($response->json());
        $expectedDeliveryTime = Carbon::parse($response->json()['data']['expected_delivery_time'])
        ->setTimezone('Asia/Ho_Chi_Minh')
        ->format('d/m/Y H:i');

        if ($response->status() == 200 ) {
            return response()->json([
                'shipping_fee' => $response->json()['data']['total_fee'],
                'expected_delivery_time' => $expectedDeliveryTime
            ],200);
        }
        
        
        return response()->json(['error' => 'Không thể tính phí vận chuyển'], 500);
    }
} 