<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopWallet;
use App\Models\ShopOrder;
use App\Models\ItemOrder;
use App\Models\Product;
use App\Models\Seller;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $shopId = auth()->user()->shop->id;

        // Tìm ngày có đơn hàng mới nhất của shop
        $latestOrderDate = \App\Models\ShopOrder::where('shopID', $shopId)->max('created_at');

        // Nếu không có đơn hàng nào thì lấy 30 ngày trước làm mặc định
        $defaultStartDate = $latestOrderDate
            ? \Carbon\Carbon::parse($latestOrderDate)->format('Y-m-d')
            : now()->subDays(30)->format('Y-m-d');

        // Ngày kết thúc là hôm nay
        $defaultEndDate = now()->format('Y-m-d');

        // Lấy từ request hoặc mặc định
        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);
        $status = $request->input('status', 'all');


        // Tính số dư hiện tại
        $totalIncome = ShopOrder::where('shopID', $shopId)
            ->where('status', 'completed')
            ->with([
                'items' => function ($query) {
                    $query->with('product');
                }
            ])
            ->get()
            ->sum(function ($order) {
                return $order->items->sum(function ($item) {
                    return $item->quantity * $item->product->sale_price;
                });
            });

        $totalRefund = ShopOrder::where('shopID', $shopId)
            ->where('status', 'refunded')
            ->with([
                'items' => function ($query) {
                    $query->with('product');
                }
            ])
            ->get()
            ->sum(function ($order) {
                return $order->items->sum(function ($item) {
                    return $item->quantity * $item->product->sale_price;
                });
            });

        $balance = $totalIncome - $totalRefund;
        $availableBalance = $balance - 600000; // Giả sử trừ 600k phí hoặc giữ lại

        // Lấy lịch sử giao dịch
        $transactions = ShopOrder::where('shopID', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.product', 'order'])
            ->get()
            ->map(function ($order) {
                $amount = $order->items->sum(function ($item) {
                    return $item->quantity * $item->product->sale_price;
                });
                $transactionType = $order->status === 'completed' ? 'Tiền vào' : 'Tiền ra';
                $status = $order->status === 'completed' ? 'Hoàn thành' : 'Hoàn hàng';
                $method = $order->status === 'completed' ? 'Hoàn tiền' : 'Hoàn hàng';

                return [
                    'date' => $order->created_at->format('Y-m-d'),
                    'buyer' => 'Doanh Thu Từ Đơn Hàng',
                    'order_id' => $order->order->id,
                    'order_code' => $order->code,
                    'amount' => $order->status === 'completed' ? "+$amount" : "-$amount",
                    'method' => $method,
                    'status' => $transactionType,
                ];
            });

        if ($status !== 'all') {
            $transactions = $transactions->where('status', $status);
        }
        $sellers = Seller::whereHas('businessLicense', function ($query) {
            $query->whereNotNull('bank_name');
        })->with('businessLicense')->get();

        return view('seller.wallet.index', compact('balance', 'availableBalance', 'transactions', 'startDate', 'endDate', 'status', 'sellers'));
    }
}
