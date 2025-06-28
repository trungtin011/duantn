<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /**
     * Display a listing of refund requests.
     */
    public function index()
    {
        $refundRequests = Order::where('order_status', 'refunded')
            ->with(['user', 'orderStatusHistory'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.refundeds.index', compact('refundRequests'));
    }

    /**
     * Show the details of a specific refund request.
     */
    public function show($id)
    {
        $order = Order::where('order_status', 'refunded')
            ->with(['user', 'orderStatusHistory', 'orderAddress', 'items'])
            ->findOrFail($id);

        return view('admin.refundeds.show', compact('order'));
    }

    /**
     * Approve or reject a refund request.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'note' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        if ($order->order_status !== 'refunded') {
            return redirect()->back()->with('error', 'Invalid refund request status.');
        }

        DB::beginTransaction();
        try {
            if ($request->action === 'approve') {
                $order->payment_status = 'refunded';
                $order->save();

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'refunded',
                    'description' => 'Refund approved',
                    'note' => $request->note,
                ]);
            } elseif ($request->action === 'reject') {
                $order->order_status = 'cancelled';
                $order->save();

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'cancelled',
                    'description' => 'Refund request rejected',
                    'note' => $request->note,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.refunds.index')->with('success', 'Refund request processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process refund request: ' . $e->getMessage());
        }
    }
}