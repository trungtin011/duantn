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
}
