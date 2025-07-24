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

        .bank-account {
            background: white;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .bank-title {
            font-weight: 600;
            margin-bottom: 12px;
        }

        .bank-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .bank-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bank-logo {
            width: 32px;
            height: 32px;
            background: #0066cc;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
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
            background: #d1f2eb;
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
    </style>


    <div class="main-container">

        <div class="content">
            <h1 class="page-title">Tổng Quan</h1>

            <div class="balance-card">
                <div class="balance-info">
                    <div>
                        <div class="balance-label">
                            Số dư Tu động có thể bật ⓘ
                        </div>
                        <div class="balance-amount">
                            <p>Số dư: đ {{ number_format($balance, 0, ',', '.') }}</p>
                        </div>
                        <div class="balance-sub">Số dư khả dụng <strong>đ
                                {{ number_format($availableBalance, 0, ',', '.') }}</strong> ⓘ</div>
                    </div>
                    <button class="top-up-btn">Yêu Cầu Thanh Toán</button>
                </div>
            </div>

            <div class="bank-account">
                <div class="bank-title">Tài khoản ngân hàng</div>
                @php
                    $seller = $sellers->first();
                    $bankName = $seller->businessLicense ? $seller->businessLicense->bank_name : 'Unknown';
                    $bankAccountName = $seller->businessLicense ? $seller->businessLicense->bank_account_name : 'Unknown';
                @endphp
                <div class="bank-info">
                    <div class="bank-logo">{{ $bankName }}</div>
                    <div>
                        <div style="font-weight: 500;">{{ $bankName }} - {{ $bankAccountName }}</div>
                        <div style="color: #666; font-size: 12px;">Đã kích hoạt</div>
                    </div>
                </div>
            </div>


            <div class="transactions-section">
                <div class="section-header">
                    <h2 class="section-title">Các giao dịch gần đây</h2>
                    <div class="date-filter">
                        <span>Thời gian phát sinh giao dịch</span>
                        <select style="border: 1px solid #e0e0e0; padding: 4px 8px; border-radius: 4px;">
                            <option>Trong vòng 3 tháng trước 03/01/2024 - 03/04/2024</option>
                        </select>
                    </div>
                </div>

                <form method="GET" action="{{ route('wallet.index') }}" id="walletFilterForm" class="mb-4 space-y-4">

                    <div class="transactions-section">
                        <!-- Phần tiêu đề và chọn ngày -->
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-bold">Các giao dịch gần đây</h2>
                            <div class="flex items-center gap-2">
                                <label>Thời gian:</label>
                                <input type="date" name="start_date" value="{{ $startDate }}"
                                    class="border px-2 py-1 rounded">
                                <span>—</span>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="border px-2 py-1 rounded">
                            </div>
                        </div>


                        <!-- Lọc dòng tiền -->
                        <div class="filter-tabs flex items-center gap-2 mt-2">
                            <label class="font-tab">Dòng tiền:</label>
                            <input type="hidden" name="status" id="statusInput" value="{{ $status }}">
                            <button type="button" class="filter-tab {{ $status == 'all' ? 'active' : '' }}"
                                data-value="all">Tất cả</button>
                            <button type="button" class="filter-tab {{ $status == 'Tiền vào' ? 'active' : '' }}"
                                data-value="Tiền vào">Tiền vào</button>
                            <button type="button" class="filter-tab {{ $status == 'Tiền ra' ? 'active' : '' }}"
                                data-value="Tiền ra">Tiền ra</button>
                        </div>

                        <!-- Lọc loại giao dịch -->
                        <div class="transaction-filters flex items-center gap-2 mt-2">
                            <label>Loại giao dịch:</label>
                            <input type="hidden" name="transaction_type" id="transactionTypeInput"
                                value="{{ request('transaction_type', 'all') }}">
                            <button type="button"
                                class="filter-btn {{ request('transaction_type') == 'Doanh Thu Từ Đơn Hàng' ? 'active' : '' }}"
                                data-value="Doanh Thu Từ Đơn Hàng">Doanh Thu Đơn Hàng</button>
                            <button type="button"
                                class="filter-btn {{ request('transaction_type') == 'Điều chỉnh' ? 'active' : '' }}"
                                data-value="Điều chỉnh">Điều chỉnh</button>
                            <button type="button"
                                class="filter-btn {{ request('transaction_type') == 'Cản trủ Số dư TK Shopee' ? 'active' : '' }}"
                                data-value="Cản trủ Số dư TK Shopee">Cản trủ Số dư TK Shopee</button>
                            <button type="button"
                                class="filter-btn {{ request('transaction_type') == 'Giá trị hoàn được ghi nhận' ? 'active' : '' }}"
                                data-value="Giá trị hoàn được ghi nhận">Giá trị hoàn được ghi nhận</button>
                            <button type="button"
                                class="filter-btn {{ request('transaction_type') == 'Rút Tiền' ? 'active' : '' }}"
                                data-value="Rút Tiền">Rút Tiền</button>
                        </div>


                        <!-- Tìm kiếm mã đơn hàng + nút áp dụng -->
                        <div class="flex justify-between items-center mt-4">
                            <div class="search-bar">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Áp dụng</button>
                                <a href="{{ route('wallet.index') }}" class="bg-gray-300 text-black px-3 py-1 rounded">Thiết
                                    lập lại</a>
                            </div>
                        </div>
                    </div>
                </form>


                <form method="GET" action="{{ route('wallet.index') }}" id="walletFilterForm" class="mb-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">Các giao dịch gần đây</h2>
                        <div class="flex items-center gap-2">
                            <label>Thời gian:</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="border px-2 py-1 rounded">
                            <span>—</span>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="border px-2 py-1 rounded">
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <label>Dòng tiền:</label>
                        <select name="status" class="border px-2 py-1 rounded">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="Tiền vào" {{ $status == 'Tiền vào' ? 'selected' : '' }}>Tiền vào</option>
                            <option value="Tiền ra" {{ $status == 'Tiền ra' ? 'selected' : '' }}>Tiền ra</option>
                        </select>

                        <label>Loại giao dịch:</label>
                        <select name="transaction_type" class="border px-2 py-1 rounded">
                            <option value="all" {{ request('transaction_type', 'all') == 'all' ? 'selected' : '' }}>Tất cả
                            </option>
                            <option value="Doanh Thu Từ Đơn Hàng" {{ request('transaction_type') == 'Doanh Thu Từ Đơn Hàng' ? 'selected' : '' }}>Doanh Thu Đơn Hàng</option>
                            <option value="Rút Tiền" {{ request('transaction_type') == 'Rút Tiền' ? 'selected' : '' }}>Rút
                                Tiền</option>
                            <!-- thêm các loại khác nếu có -->
                        </select>
                    </div>

                    <div class="flex justify-between items-center mt-2">
                        <div class="flex gap-2">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Áp dụng</button>
                            <a href="{{ route('wallet.index') }}" class="bg-gray-300 text-black px-3 py-1 rounded">Thiết lập
                                lại</a>
                        </div>
                    </div>
                    <div class="search-bar">
                        <input type="text" class="search-input" name="search" placeholder="Tìm kiếm đơn hàng"
                            value="{{ request('search') }}">
                        <button class="search-btn" type="submit">Nhập</button>
                    </div>
                </form>


                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Loại Giao Dịch | Mô Tả</th>
                            <th>Order ID</th>
                            <th>Dòng tiền</th>
                            <th>Số Tiền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr class="transaction-row">
                                <td>{{ $transaction['date'] }}</td>
                                <td>
                                    <div class="transaction-info">
                                        <div class="transaction-icon">💼</div>
                                        <div class="transaction-details">
                                            <div class="transaction-title">Doanh Thu từ Đơn Hàng #
                                                {{ $transaction['order_code'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><a href="#" class="transaction-id">{{ $transaction['order_code'] }}</a></td>
                                <td>{{ $transaction['status'] }}</td>
                                <td class="{{ strpos($transaction['amount'], '-') === 0 ? 'amount-negative' : '' }}">
                                    {{ $transaction['amount'] }}
                                </td>
                                <td><span class="status-completed">{{ $transaction['method'] }}</span></td>
                                <td><button class="action-btn">></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Add interactive functionality
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                this.style.borderColor = '#ee4d2d';
                this.style.color = '#ee4d2d';
                setTimeout(() => {
                    this.style.borderColor = '#e0e0e0';
                    this.style.color = '';
                }, 2000);
            });
        });

        // Add search functionality
        document.querySelector('.search-input').addEventListener('input', function (e) {
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
            row.addEventListener('mouseenter', function () {
                this.style.backgroundColor = '#f8f9fa';
            });

            row.addEventListener('mouseleave', function () {
                this.style.backgroundColor = '';
            });
        });

        // Add click handler for transaction IDs
        document.querySelectorAll('.transaction-id').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                alert('Xem chi tiết giao dịch: ' + this.textContent);
            });
        });
    </script>
@endsection
