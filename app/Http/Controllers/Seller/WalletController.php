<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopWallet;
use App\Models\ShopOrder;
use App\Models\ItemsOrder;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Bank;
use App\Models\LinkedBank;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use App\Services\CampaignBudgetEnforcer;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $sellers = Seller::whereHas('businessLicense', function ($query) {
            $query->whereNotNull('bank_name');
        })->with('businessLicense')->get();
        $shopId = Auth::user()->shop->id;

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

        // Balance sẽ được tính từ wallet thực tế, không phải từ orders

        if ($statusFilter !== 'all') {
            $transactions = $transactions->filter(fn($tran) => $tran['status'] === $statusFilter);
        }

        if ($transactionTypeFilter !== 'all') {
            $transactions = $transactions->filter(fn($tran) => $tran['transaction_type'] === $transactionTypeFilter);
        }

        if ($search) {
            $transactions = $transactions->filter(
                fn($tran) =>
                str_contains($tran['order_code'], $search)
            );
        }

        $shop = Auth::user()->shop;
        $wallet = $shop->wallet;

        // Đảm bảo wallet tồn tại
        if (!$wallet) {
            $wallet = ShopWallet::create([
                'shop_id' => $shop->id,
                'balance' => 0
            ]);
        }

        // Doanh thu đã chuyển: lấy từ bảng wallet_transactions (type = 'order' hoặc 'revenue' – hỗ trợ dữ liệu cũ)
        $transferredRevenue = 0;
        if ($wallet) {
            $transferredRevenue = (float) WalletTransaction::where('shop_wallet_id', $wallet->id)
                ->where('direction', 'in')
                ->whereIn('type', ['order', 'revenue'])
                ->sum('amount');
        }

        $balance = $wallet->balance ?? 0;
        $availableBalance = max(0, $balance - 600000);

        // Tổng doanh thu từ tất cả đơn đã hoàn thành (tính theo items_order giống logic chuyển vào ví)
        $completedOrders = ShopOrder::where('shopID', $shop->id)
            ->where('status', 'completed')
            ->with(['items.combo', 'items.product'])
            ->get();

        $totalCompletedRevenue = 0;
        foreach ($completedOrders as $order) {
            foreach ($order->items as $item) {
                if (!empty($item->combo_id) && $item->combo && isset($item->combo->price)) {
                    $totalCompletedRevenue += (int)($item->combo_quantity ?? 1) * (float)$item->combo->price;
                } else {
                    $totalCompletedRevenue += (float)$item->total_price;
                }
            }
        }

        // Paginated wallet transactions for table display with filters
        $typeFilter = $request->input('type', 'all');
        $statusParam = $request->input('status', 'all');
        $searchTerm = $request->input('search');

        $walletTransactions = collect();
        if ($wallet) {
            $txQuery = $wallet->transactions()->with('order')->orderByDesc('created_at');

            if ($startDate && $endDate) {
                $txQuery->whereBetween('created_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59',
                ]);
            }

            if ($typeFilter !== 'all') {
                $txQuery->where('type', $typeFilter);
            }

            if ($statusParam !== 'all') {
                $txQuery->where('status', $statusParam);
            }

            if (!empty($searchTerm)) {
                $txQuery->where(function($q) use ($searchTerm) {
                    $q->where('description', 'like', "%{$searchTerm}%")
                      ->orWhere('id', intval($searchTerm));
                });
            }

            $walletTransactions = $txQuery->paginate(10)->withQueryString();
        }

        // Doanh thu chưa chuyển = tổng completed - tổng đã chuyển (từ wallet_transactions)
        $untransferredRevenue = max(0, (float)$totalCompletedRevenue - (float)$transferredRevenue);

        // Legacy $transactions aggregation retained if needed elsewhere; UI table uses $walletTransactions

        $sellerId = Auth::user()->seller->id;
        $linkedBanks = LinkedBank::where('seller_id', $sellerId)
            ->with('bank')
            ->where('is_default', true)
            ->get();

        $defaultBank = LinkedBank::where('seller_id', $sellerId)
            ->where('is_default', true)
            ->with('bank')
            ->first();

        $banks = Bank::all();
        $linked = $linkedBanks->first(); // Lấy tài khoản đầu tiên nếu có


        return view('seller.wallet.index', compact(
            'sellers',
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
            'untransferredRevenue',
            'wallet',
            'walletTransactions',
            'linkedBanks',
            'banks',
            'defaultBank',
            'linked'
        ));
    }

    public function showWithdrawForm()
    {
        $seller = Auth::user()->seller;

        // Kiểm tra xem seller có linked bank nào không
        $hasLinkedBank = $seller->linkedBanks()->exists();

        if (!$hasLinkedBank) {
            return redirect()->route('wallet.index')->with('error', 'Vui lòng liên kết ngân hàng trước khi rút tiền.');
        }

        $wallet = Auth::user()->shop->wallet;
        $shopId = Auth::user()->shop->id;

        // 1. Doanh thu đã chuyển: sum theo wallet_transactions (type = 'order' hoặc 'revenue')
        $transferredRevenue = 0;
        if ($wallet) {
            $transferredRevenue = (float) WalletTransaction::where('shop_wallet_id', $wallet->id)
                ->where('direction', 'in')
                ->whereIn('type', ['order', 'revenue'])
                ->sum('amount');
        }

        // 2. Doanh thu chưa chuyển = tổng doanh thu của đơn completed - tổng đã chuyển
        $completedOrders = ShopOrder::where('shopID', $shopId)
            ->where('status', 'completed')
            ->with(['items.combo', 'items.product'])
            ->get();

        $totalCompletedRevenue = 0;
        foreach ($completedOrders as $order) {
            foreach ($order->items as $item) {
                if (!empty($item->combo_id) && $item->combo && isset($item->combo->price)) {
                    $totalCompletedRevenue += (int)($item->combo_quantity ?? 1) * (float)$item->combo->price;
                } else {
                    $totalCompletedRevenue += (float)$item->total_price;
                }
            }
        }

        $untransferredRevenue = max(0, (float)$totalCompletedRevenue - (float)$transferredRevenue);


        // Tổng doanh thu hoàn thành
        $totalRevenue = $transferredRevenue + $untransferredRevenue;

        $walletBalance = $wallet->balance ?? 0;
        $availableBalance = max(0, $walletBalance - 600000);

        $banks = Bank::all();
        $withdrawTransactions = collect();
        if ($wallet) {
            $withdrawTransactions = WalletTransaction::where('shop_wallet_id', $wallet->id)
                ->where('type', 'withdraw')
                ->orderByDesc('created_at')
                ->limit(10) // Giới hạn 10 giao dịch gần nhất
                ->get();
        }
        $sellerId = Auth::user()->seller->id;
        $linkedBanks = LinkedBank::where('seller_id', $seller->id)->with('bank')->get();
        $defaultBank = LinkedBank::where('seller_id', $sellerId)->where('is_default', true)->with('bank')->first();

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
            'linkedBanks',
            'defaultBank'
        ));
    }

    public function processWithdraw(Request $request)
    {
        $request->validate([
            'linked_bank_id' => 'required|exists:linked_banks,id',
            'amount' => 'required|numeric|min:10000',
        ]);


        $wallet = Auth::user()->shop->wallet;
        $amount = $request->amount;

        if ($amount > $wallet->balance) {
            return back()->with('error', 'Số dư ví không đủ.');
        }

        // Lấy thông tin ngân hàng đã liên kết
        $linkedBank = \App\Models\LinkedBank::where('id', $request->linked_bank_id)
            ->where('seller_id', Auth::user()->seller->id)
            ->with('bank')
            ->first();

        if (!$linkedBank) {
            return back()->with('error', 'Ngân hàng liên kết không hợp lệ.');
        }

        // Trừ tiền trong ví
        $wallet->decrement('balance', $amount);

        // Lưu lịch sử giao dịch rút tiền không dùng meta, lưu thông tin cơ bản
        WalletTransaction::create([
            'shop_wallet_id' => $wallet->id,
            'amount' => $amount,
            'direction' => 'out',
            'type' => 'withdraw',
            'description' => 'Yêu cầu rút tiền về ngân hàng: ' . ($linkedBank->bank->name ?? ''),
            'status' => 'pending', // Đánh dấu trạng thái chờ xử lý thay vì completed
            // Không lưu meta nữa
        ]);

        return redirect()->route('wallet.withdraw')->with('success', 'Rút tiền thành công.');
    }

    public function transferCompletedOrdersToWallet()
    {
        $shop = Auth::user()->shop;
        $wallet = $shop->wallet;

        // Đảm bảo wallet tồn tại
        if (!$wallet) {
            $wallet = ShopWallet::create([
                'shop_id' => $shop->id,
                'balance' => 0
            ]);
        }

        // Lấy các orders phù hợp với trạng thái
        $orders = ShopOrder::where('shopID', $shop->id)
            ->where('status', 'completed')
            ->where('is_revenue_transferred', false)
            ->with(['items'])
            ->get();

        $totalToTransfer = 0;

        foreach ($orders as $order) {
            $orderTotal = 0;
            foreach ($order->items as $item) {
                if (!empty($item->combo_id) && $item->combo && isset($item->combo->price)) {
                    $orderTotal += $item->combo->price * ($item->combo_quantity ?? 1);
                } else {
                    $orderTotal += $item->total_price;
                }
            }

            if ($orderTotal > 0) {
                // Tăng số dư theo từng đơn và tạo transaction có order_id
                $wallet->increment('balance', $orderTotal);

                WalletTransaction::create([
                    'shop_wallet_id' => $wallet->id,
                    'order_id' => $order->orderID, // tham chiếu tới bảng orders
                    'amount' => $orderTotal,
                    'direction' => 'in',
                    'type' => 'revenue',
                    'description' => 'Chuyển doanh thu đơn hàng #' . ($order->code ?? $order->orderID) . ' vào ví',
                    'status' => 'completed',
                ]);

                $totalToTransfer += $orderTotal;
            }

            $order->is_revenue_transferred = true;
            $order->save();
        }

        if ($totalToTransfer > 0) {
            // Kiểm tra và tự động kích hoạt lại các chiến dịch nếu đủ tiền
            CampaignBudgetEnforcer::enforceForShop($shop->id);

            return back()->with('success', 'Đã chuyển ' . number_format($totalToTransfer, 0, ',', '.') . ' VND vào ví.');
        }

        return back()->with('info', 'Không có đơn hàng hoàn thành nào cần chuyển.');
    }

    public function showLinkedBanks()
    {
        $sellerId = Auth::user()->seller->id;
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
        $isFirst = !LinkedBank::where('seller_id', Auth::user()->seller->id)->exists();

        LinkedBank::create([
            'seller_id' => Auth::user()->seller->id,
            'bank_id' => $request->bank_id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'is_default' => $isFirst,
        ]);

        return back()->with('success', 'Đã liên kết ngân hàng thành công.');
    }

    // Xóa ngân hàng liên kết
    public function deleteLinkedBank($id)
    {
        $linkedBank = LinkedBank::where('id', $id)
            ->where('seller_id', Auth::user()->seller->id)
            ->firstOrFail();

        $linkedBank->delete();

        return back()->with('success', 'Đã xóa ngân hàng liên kết.');
    }

    public function setDefaultLinkedBank($id)
    {
        $sellerId = Auth::user()->seller->id;

        // Reset tất cả ngân hàng về không mặc định
        LinkedBank::where('seller_id', $sellerId)->update(['is_default' => false]);

        // Đặt ngân hàng được chọn thành mặc định
        LinkedBank::where('id', $id)
            ->where('seller_id', $sellerId)
            ->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt ngân hàng mặc định.');
    }

    public function reverseTransferredRevenue()
    {
        $shop = Auth::user()->shop;
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
