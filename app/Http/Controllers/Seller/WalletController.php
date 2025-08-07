<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopWallet;
use App\Models\ShopOrder;
use App\Models\ItemOrder;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Bank;
use App\Models\LinkedBank;

use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $shopId = auth()->user()->shop->id;

        $latestOrderDate = ShopOrder::where('shopID', $shopId)->max('created_at');
        $defaultStartDate = now()->startOfYear()->addMonths(5)->format('Y-m-d');
        $defaultEndDate = now()->format('Y-m-d');

        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);
        $statusFilter = $request->input('status', 'all'); // 'all', 'Tiền vào', 'Tiền ra'
        $transactionTypeFilter = $request->input('transaction_type', 'all');
        $search = $request->input('search');

        $orders = ShopOrder::where('shopID', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.product', 'order'])
            ->get();

        $transactions = collect();
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($orders as $order) {
            $amount = $order->items->sum(function ($item) {
                return $item->quantity * $item->product->sale_price;
            });

            // Mapping trạng thái → dòng tiền & mô tả
            $statusMapping = [
                'completed' => ['moneyFlow' => 'Tiền vào', 'label' => 'Hoàn thành'],
                'refunded' => ['moneyFlow' => 'Tiền ra', 'label' => 'Hoàn tiền'],
                'cancelled' => ['moneyFlow' => 'Tiền ra', 'label' => 'Đơn bị hủy'],
                'returned' => ['moneyFlow' => 'Tiền ra', 'label' => 'Đơn trả lại'],
                'withdrawn' => ['moneyFlow' => 'Tiền ra', 'label' => 'Rút tiền'],
            ];

            if (!isset($statusMapping[$order->status])) {
                continue; // Trạng thái chưa hoàn tất hoặc chưa ảnh hưởng tài chính → bỏ qua
            }

            $moneyFlow = $statusMapping[$order->status]['moneyFlow'];
            $label = $statusMapping[$order->status]['label'];
            $transactionType = 'Doanh Thu Từ Đơn Hàng';

            // Tính tổng tiền vào/ra
            if ($moneyFlow === 'Tiền vào') {
                $totalIncome += $amount;
            } else {
                $totalExpense += $amount;
            }



            $transactions->push([
                'date' => $order->created_at->format('Y-m-d'),
                'buyer' => $transactionType,
                'order_id' => $order->order->id,
                'order_code' => $order->code,
                'amount' => $moneyFlow === 'Tiền vào' ? "+$amount" : "-$amount",
                'method' => $label,
                'status' => $moneyFlow,
                'transaction_type' => $transactionType,
            ]);
        }

        // Tính số dư thực tế
        $balance = $totalIncome - $totalExpense;
        $availableBalance = $balance - 600000; // Ví dụ giữ lại 600k

        // Lọc theo status (Tiền vào/ra)
        if ($statusFilter !== 'all') {
            $transactions = $transactions->filter(fn($tran) => $tran['status'] === $statusFilter);
        }

        // Lọc theo loại giao dịch
        if ($transactionTypeFilter !== 'all') {
            $transactions = $transactions->filter(fn($tran) => $tran['transaction_type'] === $transactionTypeFilter);
        }

        // Tìm kiếm theo mã đơn hàng
        if ($search) {
            $transactions = $transactions->filter(
                fn($tran) =>
                str_contains($tran['order_code'], $search)
            );
        }

        $sellers = Seller::whereHas('businessLicense', function ($query) {
            $query->whereNotNull('bank_name');
        })->with('businessLicense')->get();
        $shop = auth()->user()->shop;
        $wallet = $shop->wallet;

        // Tính doanh thu đã chuyển vào ví (doanh thu từ đơn đã `is_revenue_transferred = true`)
        $transferredOrders = ShopOrder::where('shopID', $shop->id)
            ->where('status', 'completed')
            ->where('is_revenue_transferred', true)
            ->with(['items.product'])
            ->get();

        $transferredRevenue = 0;
        foreach ($transferredOrders as $order) {
            $transferredRevenue += $order->items->sum(fn($item) => $item->quantity * $item->product->sale_price);
        }

        // Lấy số dư ví, nếu null thì mặc định là 0
        $balance = $wallet->balance ?? 0;
        $availableBalance = max(0, $balance - 600000);

        // Tổng doanh thu đơn đã hoàn thành (cả đã chuyển và chưa chuyển)
        $completedOrders = ShopOrder::where('shopID', $shop->id)
            ->where('status', 'completed')
            ->with(['items.product'])
            ->get();

        $totalCompletedRevenue = 0;
        foreach ($completedOrders as $order) {
            $totalCompletedRevenue += $order->items->sum(fn($item) => $item->quantity * $item->product->sale_price);
        }
        
        $withdrawTransactions = collect();
        if ($wallet) {
            $withdrawTransactions = $wallet->transactions()
                ->where('type', 'withdraw')
                ->orderByDesc('created_at')
                ->get();
        }

        // Gộp giao dịch rút tiền vào $transactions
        foreach ($withdrawTransactions as $tx) {
            $transactions->push([
                'date' => $tx->created_at->format('Y-m-d'),
                'buyer' => 'Rút tiền',
                'order_id' => null,
                'order_code' => $order->order->id,
                'amount' => '-' . number_format($tx->amount, 0, ',', '.'),
                'method' => 'Rút tiền',
                'status' => 'Tiền ra',
                'transaction_type' => 'Rút tiền',
            ]);
        }


        return view('seller.wallet.index', compact(
            'balance',
            'availableBalance',
            'transactions',
            'startDate',
            'endDate',
            'statusFilter',
            'transactionTypeFilter',
            'search',
            'sellers',
            'transferredRevenue',
            'totalCompletedRevenue',
            'wallet',
            'withdrawTransactions'
        ));
    }
    public function showWithdrawForm()
    {
        $seller = auth()->user()->seller;
        if (!$seller || !$seller->bank_name || !$seller->bank_account_name) {
            return redirect()->route('wallet.index')->with('error', 'Vui lòng cập nhật thông tin ngân hàng.');
        }

        $wallet = auth()->user()->shop->wallet;
        $shopId = auth()->user()->shop->id;

        // 1. Doanh thu đã chuyển
        $transferredOrders = ShopOrder::where('shopID', $shopId)
            ->where('status', 'completed')
            ->where('is_revenue_transferred', true)
            ->with(['items.product'])
            ->get();

        $transferredRevenue = 0;
        foreach ($transferredOrders as $order) {
            $transferredRevenue += $order->items->sum(fn($item) => $item->quantity * $item->product->sale_price);
        }

        // 2. Doanh thu chưa chuyển
        $untransferredOrders = ShopOrder::where('shopID', $shopId)
            ->where('status', 'completed')
            ->where('is_revenue_transferred', false)
            ->with(['items.product'])
            ->get();

        $untransferredRevenue = 0;
        foreach ($untransferredOrders as $order) {
            $untransferredRevenue += $order->items->sum(fn($item) => $item->quantity * $item->product->sale_price);
        }

        // Tổng doanh thu hoàn thành
        $totalRevenue = $transferredRevenue + $untransferredRevenue;

        $walletBalance = $wallet->balance ?? 0;
        $availableBalance = max(0, $walletBalance - 600000);

        $banks = Bank::all();
        $withdrawTransactions = collect();
        if ($wallet) {
            $withdrawTransactions = $wallet->transactions()
                ->where('type', 'withdraw')
                ->orderByDesc('created_at')
                ->get();
        }

        $linkedBanks = LinkedBank::where('seller_id', $seller->id)->with('bank')->get();

        return view('seller.wallet.withdraw', compact(
            'wallet',
            'walletBalance',
            'availableBalance',
            'seller',
            'withdrawTransactions',
            'totalRevenue',
            'transferredRevenue',
            'untransferredRevenue',
            'banks',
            'linkedBanks'
        ));
    }



    public function processWithdraw(Request $request)
    {
        $request->validate([
            'linked_bank_id' => 'required|exists:linked_banks,id',
            'amount' => 'required|numeric|min:10000',
        ]);


        $wallet = auth()->user()->shop->wallet;
        $amount = $request->amount;

        if ($amount > $wallet->balance) {
            return back()->with('error', 'Số dư ví không đủ.');
        }

        // Lấy thông tin ngân hàng đã liên kết
        $linkedBank = \App\Models\LinkedBank::where('id', $request->linked_bank_id)
            ->where('seller_id', auth()->user()->seller->id)
            ->with('bank')
            ->first();

        if (!$linkedBank) {
            return back()->with('error', 'Ngân hàng liên kết không hợp lệ.');
        }

        // Trừ tiền trong ví
        $wallet->decrement('balance', $amount);

        // Lưu lịch sử giao dịch rút tiền với thông tin ngân hàng
        WalletTransaction::create([
            'shop_wallet_id' => $wallet->id,
            'amount' => $amount,
            'direction' => 'out',
            'type' => 'withdraw',
            'description' => 'Rút về ngân hàng: ' . $linkedBank->bank->name,
            'status' => 'completed',
            'meta' => json_encode([
                'bank_id' => $linkedBank->bank_id,
                'account_number' => $linkedBank->account_number,
                'account_name' => $linkedBank->account_name,
            ]),
        ]);

        return redirect()->route('wallet.withdraw')->with('success', 'Rút tiền thành công.');
    }

    public function transferCompletedOrdersToWallet()
    {
        $shop = auth()->user()->shop;
        $wallet = $shop->wallet;

        // Lấy các đơn hàng chưa chuyển doanh thu
        $orders = ShopOrder::where('shopID', $shop->id)
            ->where('status', 'completed')
            ->where('is_revenue_transferred', false)
            ->with(['items.product'])
            ->get();

        $totalToTransfer = 0;

        foreach ($orders as $order) {
            $amount = $order->items->sum(fn($item) => $item->quantity * $item->product->sale_price);
            $totalToTransfer += $amount;

            // Đánh dấu đơn này đã chuyển
            $order->is_revenue_transferred = true;
            $order->save();
        }

        if ($totalToTransfer > 0) {
            // Cộng tiền vào ví
            $wallet->increment('balance', $totalToTransfer);

            // Ghi nhận giao dịch
            WalletTransaction::create([
                'shop_wallet_id' => $wallet->id,
                'amount' => $totalToTransfer,
                'direction' => 'in',
                'type' => 'revenue',
                'description' => 'Chuyển doanh thu từ đơn hàng hoàn thành vào ví',
                'status' => 'completed',
            ]);

            return back()->with('success', 'Đã chuyển ' . number_format($totalToTransfer, 0, ',', '.') . ' VND vào ví.');
        }

        return back()->with('info', 'Không có đơn hàng hoàn thành nào cần chuyển.');
    }

    public function showLinkedBanks()
    {
        $sellerId = auth()->user()->seller->id;
        $linkedBanks = LinkedBank::where('seller_id', $sellerId)->with('bank')->get();
        $banks = Bank::all(); // để chọn khi thêm

        return view('seller.wallet.linked_banks', compact('linkedBanks', 'banks'));
    }

    // Thêm ngân hàng liên kết
    public function storeLinkedBank(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);

        LinkedBank::create([
            'seller_id' => auth()->user()->seller->id,
            'bank_id' => $request->bank_id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        return back()->with('success', 'Đã liên kết ngân hàng thành công.');
    }

    // Xóa ngân hàng liên kết
    public function deleteLinkedBank($id)
    {
        $linkedBank = LinkedBank::where('id', $id)
            ->where('seller_id', auth()->user()->seller->id)
            ->firstOrFail();

        $linkedBank->delete();

        return back()->with('success', 'Đã xóa ngân hàng liên kết.');
    }
    public function reverseTransferredRevenue()
    {
        $shop = auth()->user()->shop;
        $wallet = $shop->wallet;

        // Lấy các đơn hàng đã chuyển doanh thu
        $transferredOrders = ShopOrder::where('shopID', $shop->id)
            ->where('status', 'completed')
            ->where('is_revenue_transferred', true)
            ->with(['items.product'])
            ->get();

        $totalToReverse = 0;

        foreach ($transferredOrders as $order) {
            $amount = $order->items->sum(fn($item) => $item->quantity * $item->product->sale_price);
            $totalToReverse += $amount;

            // Đặt lại trạng thái chưa chuyển
            $order->is_revenue_transferred = false;
            $order->save();
        }

        if ($totalToReverse > 0) {
            // Trừ tiền trong ví
            $wallet->decrement('balance', $totalToReverse);

            // Ghi lại giao dịch hoàn tác
            WalletTransaction::create([
                'shop_wallet_id' => $wallet->id,
                'amount' => $totalToReverse,
                'direction' => 'out',
                'type' => 'reverse_revenue',
                'description' => 'Hoàn tác chuyển doanh thu khỏi ví',
                'status' => 'completed',
            ]);

            return back()->with('success', 'Đã hoàn tác ' . number_format($totalToReverse, 0, ',', '.') . ' VND từ ví.');
        }

        return back()->with('info', 'Không có doanh thu nào đã chuyển cần hoàn tác.');
    }
}
