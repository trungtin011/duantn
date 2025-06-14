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
        $order = Order::findOrFail($id);
        $order->update(['order_status' => 'processing']);   
        return redirect()->back()->with('success', 'Đã nhận đơn hàng #'. $order->order_code);
    }
}
?>
