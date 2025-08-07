<?php

namespace App\Http\Controllers\Service\DeliveryProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GHNController extends Controller
{

    private $token;
    private $baseUrl;
    private $shop_id;
    private $refund_url;
    private $content_type;
    private $get_province_url;
    private $get_district_url;
    private $get_ward_url;

    public function __construct()
    {
        $this->token = config('services.ghn.token');
        $this->baseUrl = config('services.ghn.url');
        $this->shop_id = config('services.ghn.shop_id');
        $this->refund_url = config('services.ghn.return');
        $this->content_type = 'application/json';
    }

    public function refundOrder($tracking_code){
       $url = $this->refund_url;
       $response = Http::withHeaders([
        'Token' => $this->token,
        'Content-Type' => $this->content_type,  
        'ShopId' => $this->shop_id,
       ])
       ->post($url, [
        'order_codes' => $tracking_code
       ]);
       if($response->successful()){
            return true;
       }else{
            return false;
       }
    }

    /**
     * Lấy danh sách tỉnh/thành phố
     */
    public function getProvinces()
    {
        try {
            $url = $this->baseUrl . config('services.ghn.get_province_url');
            
            $response = Http::withHeaders([
                'Token' => $this->token,
                'Content-Type' => $this->content_type,
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'])) {
                    return response()->json([
                        'success' => true,
                        'data' => $data['data']
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách tỉnh/thành phố'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách tỉnh/thành phố: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách quận/huyện theo tỉnh/thành phố
     */
    public function getDistricts(Request $request)
    {
        try {
            $provinceId = $request->input('province_id');
            
            if (!$provinceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn tỉnh/thành phố'
                ], 400);
            }

            $url = $this->baseUrl . config('services.ghn.get_district_url');
            
            $response = Http::withHeaders([
                'Token' => $this->token,
                'Content-Type' => $this->content_type,
            ])->get($url, [
                'province_id' => $provinceId
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'])) {
                    return response()->json([
                        'success' => true,
                        'data' => $data['data']
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách quận/huyện'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách quận/huyện: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards(Request $request)
    {
        try {
            $districtId = $request->input('district_id');
            
            if (!$districtId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn quận/huyện'
                ], 400);
            }

            $url = $this->baseUrl . config('services.ghn.get_ward_url');
            
            $response = Http::withHeaders([
                'Token' => $this->token,
                'Content-Type' => $this->content_type,
            ])->get($url, [
                'district_id' => $districtId
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'])) {
                    return response()->json([
                        'success' => true,
                        'data' => $data['data']
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách phường/xã'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách phường/xã: ' . $e->getMessage()
            ], 500);
        }
    }
}
