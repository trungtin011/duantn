<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Enums\UserRole;
use App\Http\Controllers\Seller\Orders\ShippingController;

class OrderController extends Controller
{
    public function index()
    {
        $sellerId = Auth::user()->shop_id;
        
        $query = Order::with(['user', 'items'])->orderBy('created_at', 'desc');

        if ($sellerId !== null) {
            $query->where('shopID', $sellerId);
        }

        $orders = $query->paginate(10);

        return view('seller.order.index', compact('orders'));
    }

    public function show($id)
    {
        $sellerId = Auth::user()->shop_id;

        $query = Order::with(['user', 'items', 'address', 'statusHistory', 'shop_order']);

        if ($sellerId !== null) {
            $query->where('shopID', $sellerId);
        }
        
        $order = $query->findOrFail($id);
        $current_shop_id = session('current_shop_id') ?? 1;
        $shop = Shop::where('id', $current_shop_id)->with('addresses')->first();
        return view('seller.order.show', compact('order', 'shop'));
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

            $order = Order::where('id', $id)->with('shop_order')->first();

            $id_shop_address = $request->shop_address;
            $shipping_provider = $request->shipping_provider;

            if($shipping_provider === 'GHN'){
                $shipping_controller = new ShippingController();
                $shipping_controller->createShippingOrder($order, $id_shop_address, $request->payment_type, $request->note, $request->required_note);
                if($shipping_controller){
                    return redirect()->route('seller.order.show', $id)
                        ->with('success', 'Tạo đơn hàng vận chuyển thành công');
                }
                else{
                    return redirect()->route('seller.order.show', $id)
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

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
                'description' => 'nullable|string',
                'shipping_provider' => 'nullable|string',
                'note' => 'nullable|string',
            ]);

            $sellerId = Auth::user()->shop_id;

            if ($sellerId !== null) {
                // Nếu có shopID thì lọc theo shopID
                $order = Order::where('shopID', $sellerId)->findOrFail($id);
            } else {
                // Nếu không có shopID thì tìm đơn hàng theo id
                $order = Order::findOrFail($id);
            }

            $order->order_status = $request->status;

            if ($request->status === 'cancelled') {
                $order->cancelled_at = now();
                $order->cancel_reason = $request->note;
            } elseif ($request->status === 'delivered') {
                $order->delivered_at = now();
            } elseif ($request->status === 'refunded') {
                $order->cancelled_at = now();
            }

            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'description' => $request->description,
                'shipping_provider' => $request->shipping_provider,
                'note' => $request->note,
            ]);

            return response()->json(['message' => 'Cập nhật trạng thái thành công!'], 200);
        } catch (\Throwable $e) {
            Log::error('Update order status error: '.$e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['message' => 'Có lỗi xảy ra, vui lòng thử lại!'], 500);
        }
    }
}
?>
