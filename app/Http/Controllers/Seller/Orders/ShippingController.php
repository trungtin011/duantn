<?php

namespace App\Http\Controllers\Seller\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\Order;
use App\Models\ShopAddress;
use App\Models\ShopOrderHistory;
use App\Events\OrderStatusUpdate;
use App\Models\ShopOrder;

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
    protected $detailOrderEndpoint;
    protected $returnEndpoint;

    public function __construct()
    {
        $this->token = config('services.ghn.token');
        $this->createEndpoint = config('services.ghn.create_order');
        $this->shopId = config('services.ghn.shop_id');
        $this->ghnApiUrl = config('services.ghn.url');
        $this->contentType = 'application/json';
        $this->trackingEndpoint = config('services.ghn.tracking');
        $this->cancelEndpoint = config('services.ghn.cancel');
        $this->detailOrderEndpoint = config('services.ghn.detail_order');
        $this->returnEndpoint = config('services.ghn.return');
    }

    public function createShippingOrder($shop_order, $orders, $id_shop_address, $payment_type_id, $note, $required_note)
    {

        if (!$orders) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
        }

        if ($orders->total_price > 50000000) {
            return redirect()->back()->with('error', 'Tạm thời đơn hàng ko được quá 50 triệu');
        }

        $shop_address = ShopAddress::where('id', $id_shop_address)->first();

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
                'from_name' => $shop_order->shop->shop_name,
                'from_phone' => $shop_order->shop->shop_phone,
                'from_address' => $shop_street_address,
                'from_ward_name' => $shop_ward_name,
                'from_district_name' => $shop_district_name,
                'from_province_name' => $shop_province_name,
                'client_order_code' => $shop_order->code,
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
        Log::info('Response data: ' . json_encode($responseData));
        if ($response->status() == 200) {
            $shop_order->update(['status' => 'ready_to_pick']);

            $expectedDateTime = $responseData['data']['expected_delivery_time'];
            $expectedDate = Carbon::parse($expectedDateTime)->format('Y-m-d H:i');
            $data = [
                'tracking_code' => $responseData['data']['order_code'],
                'expected_delivery_date' => $expectedDate,
                'shipping_fee' => $responseData['data']['fee']['main_service'],
            ];

            $shop_order->update($data);

            $shop_order_history = new ShopOrderHistory();
            $shop_order_history->shop_order_id = $shop_order->id;
            $shop_order_history->status = 'ready_to_pick';
            $shop_order_history->description = 'Người bán đã giao cho đơn vị vận chuyển';
            $shop_order_history->note = $note;
            $shop_order_history->save();
            Log::info('Đã lưu lịch sử trạng thái đơn hàng', [
                'shop_order_id' => $shop_order->id,
                'trạng thái' => 'ready_to_pick',
                'mô tả' => $shop_order_history->description,
                'ghi chú' => $note,
            ]);

            event(new OrderStatusUpdate($shop_order, 'ready_to_pick'));
            return true;
        } else {
            return redirect()->back()->with('error', 'Tạo đơn hàng vận chuyển thất bại');
        }
    }


    public function renderCreateShippingOrder($orders)
    {
        if (!$orders) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
        }

        return view('seller.orders.shipping.create', compact('orders'));
    }

    public function cancelOrderGHN($order)
    {
        $url = $this->ghnApiUrl . $this->cancelEndpoint;
        $response = Http::withHeaders([
            'Token' => $this->token,
            'Content-Type' => $this->contentType,
            'shopId' => $this->shopId,
        ])
            ->post($url, [
                'order_codes' => [$order->tracking_code],
            ]);
        if ($response->status() == 200) {
            event(new OrderStatusUpdate($order, 'cancelled'));
            return true;
        } else {
            return false;
        }
    }

    public function getDetailOrder($tracking_code)
    {
        $url = $this->ghnApiUrl . $this->detailOrderEndpoint;
        $response = Http::withHeaders([
            'Token' => $this->token,
            'Content-Type' => $this->contentType,
        ])->get($url, ['client_order_code' => $tracking_code]);

        $responseData = $response->json();
        if ($response->status() == 200) {
            return $responseData;
        } else {
            return false;
        }
    }

    public function returnOrderGHN($code)
    {

        $tracking_code = $code;

        if ($tracking_code == null) {
            return false;
        }

        $url = $this->ghnApiUrl . $this->returnEndpoint;
        $response = Http::withHeaders([
            'Token' => $this->token,
            'Content-Type' => $this->contentType,
        ])->post($url, ['order_codes' => [$tracking_code]]);

        if ($response->status() == 200) {

            $order = ShopOrder::where('tracking_code', $tracking_code)->first();
            $order->status = 'refunded';
            $order->save();

            $history = new ShopOrderHistory();
            $history->shop_order_id = $order->id;
            $history->status = 'refunded';
            $history->description = 'Đơn hàng đã được yêu cầu trả lại từ người bán';
            $history->save();

            event(new OrderStatusUpdate($order));
            return true;
        } else {
            return false;
        }
    }
}
