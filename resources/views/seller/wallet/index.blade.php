@extends('layouts.seller_home')

@section('title', 'Ví shop')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ee4d2d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .main-container {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
        }

        .balance-card {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .balance-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .balance-label {
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .balance-amount {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .balance-sub {
            color: #666;
            font-size: 14px;
            margin-top: 8px;
        }

        .top-up-btn {
            background: #ee4d2d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .top-up-btn:hover {
            background: #d73527;
        }

        .transactions-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
        }

        .date-filter {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #666;
        }

        .filter-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .filter-tab {
            padding: 12px 16px;
            background: none;
            border: none;
            font-size: 14px;
            color: #666;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .filter-tab.active {
            color: #ee4d2d;
            border-bottom-color: #ee4d2d;
        }

        .transaction-filters {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 6px 12px;
            border: 1px solid #e0e0e0;
            background: white;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            border-color: #ee4d2d;
            color: #ee4d2d;
        }

        .transactions-summary {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .transactions-count {
            color: #333;
            font-weight: 500;
        }

        .total-change {
            color: #00b894;
            font-weight: 500;
        }

        .search-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-btn {
            padding: 8px 16px;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th {
            text-align: left;
            padding: 12px;
            font-weight: 500;
            color: #666;
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
        }

        .transactions-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .transaction-row {
            transition: background 0.2s;
        }

        .transaction-row:hover {
            background: #f8f9fa;
        }

        .transaction-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e3f2fd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .transaction-info {
            display: flex;
            align-items: center;
        }

        .transaction-details {
            flex: 1;
        }

        .transaction-title {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .transaction-subtitle {
            color: #666;
            font-size: 12px;
        }

        .transaction-id {
            color: #0066cc;
            text-decoration: none;
            font-weight: 500;
        }

        .transaction-id:hover {
            text-decoration: underline;
        }

        .amount-positive {
            color: #00b894;
            font-weight: 600;
        }

        .amount-negative {
            color: #e17055;
            font-weight: 600;
        }

        .status-completed {
            color: #00b894;
            /* xanh */
            background: #d1f2eb;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-refunded {
            color: #e74c3c;
            /* đỏ */
            background: #fddede;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }


        .action-btn {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .action-btn:hover {
            background: #f0f0f0;
        }

        .setup-btn {
            background: none;
            border: 1px solid #ee4d2d;
            color: #ee4d2d;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .setup-btn:hover {
            background: #ee4d2d;
            color: white;
        }

        .overview-container {
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #fafafa;
        }

        .overview-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 0 0 24px 0;
        }

        .main-content {
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }

        /* Balance Section */
        .balance-section {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .balance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .balance-title {
            font-size: 16px;
            color: #666;
            font-weight: 400;
        }

        .auto-withdraw {
            font-size: 14px;
            color: #666;
        }

        .balance-display {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .currency {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .amount {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-right: auto;
        }

        .withdraw-btn {
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
        }

        .withdraw-btn:hover {
            background-color: #ff5252;
        }

        /* Bank Section */
        .bank-section {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 38px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .bank-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .bank-title {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .view-more {
            color: #1a73e8;
            text-decoration: none;
            font-size: 14px;
        }

        .view-more:hover {
            text-decoration: underline;
        }

        .bank-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 1px solid #e8e8e8;
            border-radius: 6px;
        }

        .bank-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1565c0, #1976d2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .bank-icon {
            font-size: 20px;
            filter: brightness(0) invert(1);
        }

        .bank-details {
            flex: 1;
        }

        .bank-name {
            font-size: 14px;
            color: #1a73e8;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .bank-meta {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 2px;
        }

        .account-stars {
            color: #666;
            font-size: 13px;
        }

        .account-number {
            color: #666;
            font-size: 13px;
        }

        .bank-status {
            font-size: 12px;
            color: #666;
        }

        .bank-badge {
            flex-shrink: 0;
        }

        .default-tag {
            background-color: #e8f5e8;
            color: #2e7d2e;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                gap: 20px;
            }

            .balance-display {
                flex-wrap: wrap;
                gap: 12px;
            }

            .withdraw-btn {
                width: 100%;
                margin-top: 8px;
            }
        }
    </style>


    <div class="main-container">

        <div class="content">
            <h1 class="page-title">Tổng Quan</h1>
            <div class="flex gap-6 items-start">
                <div class="balance-card w-1/2">
                    <div class="balance-info">
                        <div>
                            <div class="balance-label">
                                Số dư Tự động có thể bật ⓘ
                            </div>
                            <div class="balance-amount">
                                <p>Số dư: đ {{ number_format($balance ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="balance-sub">
                                Số dư khả dụng <strong>đ {{ number_format($availableBalance ?? 0, 0, ',', '.') }}</strong> ⓘ
                            </div>
                            <a href="{{ route('wallet.withdraw') }}">
                                <button
                                    class="top-up-btn bg-red-500 text-white px-4 py-2 rounded {{ !$linked ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ !$linked ? 'disabled' : '' }}>
                                    Yêu Cầu Thanh Toán
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cột 2: Ngân hàng liên kết -->
                <div class="bank-section w-1/2">
                    <div class="bank-header">
                        <span class="bank-title">Tài khoản ngân hàng</span>
                        <a href="{{ route('seller.linked-banks.index') }}" class="view-more">Xem thêm ></a>
                    </div>

                    @forelse($linkedBanks as $linked)
                        <div class="bank-info">
                            <div class="bank-logo">
                                <span class="bank-icon">🏦</span>
                            </div>
                            <div class="bank-details">
                                <div class="bank-name">{{ $linked->bank?->code ?? 'N/A' }} -
                                    {{ $linked->bank?->name ?? 'N/A' }}</div>
                                <div class="bank-meta">
                                    <span class="account-stars"></span>
                                    <span class="account-number">
                                        {{ substr_replace($linked->account_number, '****', 0, 4) }}
                                    </span>
                                </div>
                                <div class="bank-status">
                                    {{ $linked->account_name }}
                                </div>
                            </div>
                            <div class="bank-badge">
                                @if ($linked->is_default)
                                    <span class="default-tag">Mặc định</span>
                                @else
                                    <form method="POST"
                                        action="{{ route('seller.linked-banks.set-default', $linked->id) }}">
                                        @csrf
                                        <button type="submit" class="text-blue-500 underline text-sm">Đặt mặc định</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 mt-2">Bạn chưa liên kết ngân hàng nào.</p>
                    @endforelse
                </div>
            </div>


            <div class="transactions-section">
                <form method="GET" action="{{ route('wallet.index') }}" class="mb-4 space-y-4" id="walletFilterForm">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">Lịch sử giao dịch ví</h2>
                        <div class="flex items-center gap-2">
                            <label>Thời gian:</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="border px-2 py-1 rounded">
                            <span>—</span>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="border px-2 py-1 rounded">
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mt-2">
                        <label>Loại giao dịch:</label>
                        <select name="type" class="border px-2 py-1 rounded">
                            <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Nạp tiền</option>
                            <option value="withdraw" {{ request('type') == 'withdraw' ? 'selected' : '' }}>Rút tiền</option>
                            <option value="revenue" {{ request('type') == 'revenue' ? 'selected' : '' }}>Doanh thu đơn hàng</option>
                            <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Hoàn tiền</span>
                        </select>
                        <label>Trạng thái:</label>
                        <select name="status" class="border px-2 py-1 rounded">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Thành công</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                        </select>
                    </div>
                    <div class="flex justify-end mt-2 gap-2">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Áp dụng</button>
                        <a href="{{ route('wallet.index') }}" class="bg-gray-300 text-black px-3 py-1 rounded">Thiết lập lại</a>
                    </div>
                    <div class="search-bar">
                        <input type="text" class="search-input" name="search" placeholder="Tìm kiếm mã giao dịch, ghi chú"
                            value="{{ request('search') }}">
                        <button class="search-btn" type="submit">Nhập</button>
                    </div>
                </form>
                <div class="transactions-summary">
                    <span class="transactions-count">
                        {{ $wallet && $wallet->transactions ? $wallet->transactions->count() : 0 }} giao dịch
                    </span>
                    <span>(Tổng số tiền: <span class="total-change">
                        {{ $wallet && $wallet->transactions ? number_format($wallet->transactions->sum('amount'), 0, ',', '.') : 0 }} VND
                    </span>)</span>
                </div>
                <table class="transactions-table w-full mt-4 border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Ngày</th>
                            <th class="p-2 border">Mã giao dịch</th>
                            <th class="p-2 border">Loại giao dịch</th>
                            <th class="p-2 border">Số tiền</th>
                            <th class="p-2 border">Trạng thái</th>
                            <th class="p-2 border">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($walletTransactions ?? [] as $tx)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="p-2">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="p-2 font-mono text-xs">{{ $tx->display_code }}</td>
                                <td class="p-2">
                                    @switch($tx->type)
                                        @case('deposit')
                                            <span class="text-blue-600">Nạp tiền</span>
                                            @break
                                        @case('withdraw')
                                            <span class="text-red-600">Rút tiền</span>
                                            @break
                                        @case('revenue')
                                            <span class="text-green-600">Doanh thu đơn hàng</span>
                                            @break
                                        @case('refund')
                                            <span class="text-yellow-600">Hoàn tiền</span>
                                            @break
                                        @case('advertising')
                                        @case('advertising_bid')
                                            <span class="text-purple-600">Quảng cáo</span>
                                            @break
                                        @default
                                            <span>{{ ucfirst($tx->type) }}</span>
                                    @endswitch
                                </td>
                                <td class="p-2 font-bold {{ $tx->direction === 'out' ? 'text-red-500' : 'text-green-500' }}">
                                    {{ $tx->direction === 'out' ? '-' : '+' }}{{ number_format($tx->amount, 0, ',', '.') }} VND
                                </td>
                                <td class="p-2">
                                    @if ($tx->status == 'completed')
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">Thành công</span>
                                    @elseif ($tx->status == 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">Đang xử lý</span>
                                    @elseif ($tx->status == 'rejected')
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm">Từ chối</span>
                                    @elseif ($tx->status == 'cancelled')
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm">Đã hủy</span>
                                    @elseif ($tx->status == 'processing')
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">Đang xử lý</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm">{{ ucfirst($tx->status) }}</span>
                                    @endif
                                </td>
                                <td class="p-2">{{ $tx->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">
                                    Không có giao dịch nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(($walletTransactions && method_exists($walletTransactions, 'links')))
                    <div class="mt-4">
                        {{ $walletTransactions->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Add interactive functionality
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.borderColor = '#ee4d2d';
                this.style.color = '#ee4d2d';
                setTimeout(() => {
                    this.style.borderColor = '#e0e0e0';
                    this.style.color = '';
                }, 2000);
            });
        });

        // Add search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.transaction-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add hover effects for transaction rows
        document.querySelectorAll('.transaction-row').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });

            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Add click handler for transaction IDs
        document.querySelectorAll('.transaction-id').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Xem chi tiết giao dịch: ' + this.textContent);
            });
        });
    </script>
@endsection
