<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Seller\Orders\ShippingController;

class OrderController extends Controller
{

    public function index()
    {
        $userId = Auth::id();
        $orders = Order::with(['items', 'shop_order'])
            ->where('userID', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.order.order_history', compact('orders'));
    }

    public function show($orderID)
    {
        $order = Order::with([
            'items.product.images',
            'items.variant',
            'address',
            'user'
        ])->findOrFail($orderID);
        $orderItems = $order->items;
        $orderAddress = $order->address;

        return view('user.order.orderDetail', compact('order', 'orderItems', 'orderAddress'));
    }

    public function cancelOrder($id)
    {
       $orderStatus = Order::where('id', $id)->with('shop_order')->first();
       
       $status = $this->checkStatus($orderStatus);
       if($status === 'pending' || $status === 'processing'){
        $orderStatus->shop_order->first()->update(['status' => 'cancelled_by_customer']);
       }
       elseif($status === 'shipped'){
        $GHN = new ShippingController();
        $cancel_order_GHN = $GHN->cancelOrderGHN($orderStatus);
        if($cancel_order_GHN){
            $orderStatus->shop_order->first()->update(['status' => 'cancelled_by_customer']);
            return redirect()->route('user.orders')->with('success', 'Đơn hàng đã được hủy thành công');
        }
        else{
            return redirect()->route('user.orders')->with('error', 'Đơn hàng không thể hủy');
        }
       }
    }

    public function checkStatus($orderStatus)
    {
        if ($orderStatus->order_status === 'pending') {
            return 'pending';
        }
        elseif ($orderStatus->order_status === 'processing') {
            return 'processing';
        }
        elseif ($orderStatus->order_status === 'shipped') {
            return 'shipped';
        }
    }
}
