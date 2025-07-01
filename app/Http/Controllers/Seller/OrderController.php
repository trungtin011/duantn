<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Shop;
use App\Models\ShopOrderHistory;
use App\Models\ShopAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Enums\UserRole;
use App\Http\Controllers\Seller\Orders\ShippingController;
use App\Models\ShopOrder;

class OrderController extends Controller
{
    public function index()
    {
        $shopID = session()->get('current_shop_id');

        $orders = ShopOrder::where('shopID', $shopID)
            ->whereNotNull('code')
            ->with('order')
            ->paginate(10);

        return view('seller.order.index', compact('orders'));
    }

    public function show($code)
    {
        $shop_order = ShopOrder::where('code', $code)->with('order')->first();

        $orders = $shop_order->order;
        $current_shop_id = session('current_shop_id') ?? 1;
        $shop = Shop::where('id', $current_shop_id)->first();
        $shop_address = ShopAddress::where('shopID', $shop->id)->get();

        $order = $shop_order->order;
        $status = ShopOrderHistory::where('shop_order_id', $shop_order->id)->get();
        return view('seller.order.show', compact('order', 'shop', 'shop_order', 'shop_address', 'status'));
    }

    public function shippingOrder(Request $request, $id)
    {

            $request->validate([
                'shipping_provider' => 'nullable|string|in:GHN',
                'note' => 'nullable|string',
                'required_note' => 'nullable|string|in:CHOXEMHANGKHONGTHU,CHOTHUHANG,KHONGCHOXEMHANG',
                'payment_type' => 'nullable|string|in:1,2',
            ]);

            $permission_check = $this->permissionCheck();

            $shop_order = ShopOrder::where('orderID', $id)->with('shop')->first();
            $order = $shop_order->order;
            $id_shop_address = $request->shop_address;
            $shipping_provider = $request->shipping_provider;

            if($shipping_provider === 'GHN'){
                $shipping_controller = new ShippingController();
                $shipping_controller->createShippingOrder($shop_order, $order, $id_shop_address, $request->payment_type, $request->note, $request->required_note);
                
                if($shipping_controller){
                    return redirect()->route('seller.order.show', $order->order_code)
                        ->with('success', 'Tạo đơn hàng vận chuyển thành công');
                }
                else{
                    return redirect()->route('seller.order.show', $order->order_code)
                        ->with('error', 'Tạo đơn hàng vận chuyển thất bại');
                }
            }
    }

    private function permissionCheck(){
        $permission_check = false;
        
        $user = Auth::user();
        $checkRole = $user->role;
        if($checkRole === UserRole::SELLER){
            $shop = Shop::where('ownerID', $user->id)->first(); 
            $permission_check = $shop ? true : false;
        }
        elseif($checkRole === UserRole::ADMIN){
            $permission_check = true;
        }
        elseif($checkRole === UserRole::EMPLOYEE){
            $shop_id = session('current_shop_id');
            $checkEmployee = Shop::where('id', $shop_id)->with('employees')->get();
            if($checkEmployee){
                foreach($checkEmployee as $shop){
                    if($shop->employees->contains('userID', $user->id) && $shop->employees->contains('status', 'active')){
                        $permission_check = true;
                    }
                }
            }
            else{
                $permission_check = false;
            }
        }

        return $permission_check;
    }

    public function confirmOrder(Request $request, $id, $shop_id)
    {
        $order = ShopOrder::where('id', $id)->with('order')->first();
        $shop = Shop::findOrFail($shop_id);

        $order->status = 'confirmed';
        $order->save();

        $shop_order_history = new ShopOrderHistory();
        $shop_order_history->shop_order_id = $order->id;
        $shop_order_history->status = 'confirmed';
        $shop_order_history->description = 'Người bán đã xác nhận đơn hàng';
        $shop_order_history->save();
        if($shop_order_history){
            $orders = Order::where('id', $order->id)->first();
            $ord = Order::orderStatusUpdate($orders->id);

            return redirect()->back()->with('success', 'Đã nhận đơn hàng #'. $order->order_code);
        }
        else{   
            return redirect()->back()->with('error', 'Lỗi cập nhật trạng thái đơn hàng #'. $order->order_code);
        }
    }

    public function cancelOrder(Request $request)
    {
        $order = ShopOrder::where('id', $request->id)->with('order')->first();
        $shop = Shop::findOrFail($request->shop_id);

        $order_status = $request->status;

        if($order_status === 'confirmed'){
            $order->status = 'cancelled';
            $order->save();

            $shop_order_history = new ShopOrderHistory();
            $shop_order_history->shop_order_id = $order->id;
            $shop_order_history->status = 'cancelled';
            $shop_order_history->description = 'Người bán đã hủy đơn hàng';
            $shop_order_history->save();

            return redirect()->back()->with('success', 'Đã hủy đơn hàng #'. $order->order_code);
        }
        elseif($order_status === 'ready_to_pick' || $order_status === 'picked'){
            $shipping_controller = new ShippingController();
            $order_status = $shipping_controller->cancelOrderGHN($order);

            if($order_status){
                $order->status = 'cancelled';
                $order->save();

                $shop_order_history = new ShopOrderHistory();
                $shop_order_history->shop_order_id = $order->id;
                $shop_order_history->status = 'cancelled';
                $shop_order_history->description = 'Người bán đã hủy đơn hàng';
                $shop_order_history->save();

                return redirect()->back()->with('success', 'Đã hủy đơn hàng #'. $order->order_code);
            }
            else{
                return redirect()->back()->with('error', 'Lỗi hủy đơn hàng #'. $order->order_code);
            }
        }

        return redirect()->back()->with('success', 'Đã hủy đơn hàng #'. $order->order_code);
    }

    public function returnOrder(Request $request)
    {
        $order = ShopOrder::where('tracking_code', $request->code)->with('order')->first();
        $shipping_controller = new ShippingController();
        $shipping_controller->returnOrderGHN($order);

        if($shipping_controller){
            $order->status = 'refunded';
            $order->save();

            $shop_order_history = new ShopOrderHistory();
            $shop_order_history->shop_order_id = $order->id;
            $shop_order_history->status = 'refunded';
            $shop_order_history->description = 'Người bán đã trả đơn hàng';
            $shop_order_history->save();
        }
        else{
            return redirect()->back()->with('error', 'Lỗi trả đơn hàng #'. $order->order_code);
        }

        return redirect()->back()->with('success', 'Đã trả đơn hàng #'. $order->order_code);
    }

    public function trackingOrder(Request $request)
    {
        $shipping_controller = new ShippingController();
        $status_order = $shipping_controller->getDetailOrder($request->tracking_code);
        $order = ShopOrder::where('code', $request->tracking_code)->with('order')->first();
        $status = $status_order['data']['status'];
        $order_status = new ShopOrderHistory();
        $order_status->shop_order_id = $order->id;


        if($request->method_request === 'status_update'){
            if ($order->status !== $status) {
                $statusMapping = [
                    'picking' => [
                        'status' => 'picked',
                        'description' => 'Đơn vị vận chuyển chuẩn bị lấy hàng'
                    ],
                    'picked' => [
                        'status' => 'picked',
                        'description' => 'Đơn vị vận chuyển đã lấy hàng',
                        'update_order' => true
                    ],
                    'delivered' => [
                        'status' => 'delivered',
                        'description' => 'Đơn vị vận chuyển đã giao hàng',
                        'update_order' => true
                    ],
                    'storing' => [
                        'status' => 'shipping',
                        'description' => 'Đơn vị vận chuyển đang vận chuyển hàng',
                        'update_order' => true
                    ],
                    'transporting' => [
                        'status' => 'shipping',
                        'description' => 'Đơn vị vận chuyển đang vận chuyển hàng',
                        'update_order' => true
                    ],
                    'delivering' => [
                        'status' => 'shipping',
                        'description' => 'Đơn vị vận chuyển đang vận chuyển hàng',
                        'update_order' => true
                    ],
                    'cancelled' => [
                        'status' => 'cancelled',
                        'description' => 'Đơn vị vận chuyển đã hủy đơn hàng',
                        'update_order' => true
                    ]
                ];

                if (isset($statusMapping[$status])) {
                    $mapping = $statusMapping[$status];
                    
                    if (isset($mapping['update_order'])) {
                        $order->status = $mapping['status'];
                        $order->save();
                    }
                    
                    $order_status->status = $mapping['status'];
                    $order_status->description = $mapping['description'];
                    $order_status->save();
                }
            }
            
            return redirect()->route('seller.order.show', $order->order->order_code)
                ->with('success', 'Đã cập nhật trạng thái đơn hàng #' . $request->tracking_code);
        }
        elseif($request->method_request === 'get_order'){
            return redirect()->back()->with('order', $status_order);
        }
    }
}
?>
