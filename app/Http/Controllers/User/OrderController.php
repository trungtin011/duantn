<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $orders = Order::with(['items'])
            ->where('userID', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.order.index', compact('orders'));
    }

    public function show($id)
    {
        $userId = Auth::id();
        $order = Order::with(['items', 'address', 'statusHistory'])
            ->where('userID', $userId)
            ->findOrFail($id);

        return view('user.order.show', compact('order'));
    }
}
?>