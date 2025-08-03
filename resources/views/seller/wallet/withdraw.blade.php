@extends('layouts.seller_home')

@section('title', 'Rút tiền')

@section('content')
    <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Rút tiền về ngân hàng</h2>
        <p><strong>Ngân hàng:</strong> {{ $seller->bank_name }}</p>
        <p><strong>Chủ TK:</strong> {{ $seller->bank_account_name }}</p>
        <p><strong>Chưa chuyển:</strong> {{ number_format($untransferredRevenue, 0, ',', '.') }} VND</p>
        <p><strong>Đã chuyển vào ví:</strong> {{ number_format($transferredRevenue, 0, ',', '.') }} VND</p>


        <p><strong>Số dư ví hiện tại:</strong> {{ number_format($walletBalance, 0, ',', '.') }} VND</p>

        <form method="POST" action="{{ route('wallet.transfer.revenue') }}" class="mb-4">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                Chuyển doanh thu từ đơn hoàn thành vào ví
            </button>
        </form>
        <form method="POST" action="{{ route('wallet.reverse.revenue') }}" class="mb-4">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                Hoàn tác doanh thu về ví
            </button>
        </form>


        <p><a href="{{ route('seller.linked-banks.index') }}" class="text-blue-500 underline">Quản lý ngân hàng liên kết</a>
        </p>

        <form method="POST" action="{{ route('seller.withdraw.store') }}">
            @csrf

            <label class="block mb-1">Chọn ngân hàng liên kết:</label>
            <select name="linked_bank_id" class="w-full border rounded px-3 py-2 mb-2" required>
                @foreach ($linkedBanks as $linked)
                    <option value="{{ $linked->id }}">
                        {{ $linked->bank->name }} - {{ $linked->account_number }} ({{ $linked->account_name }})
                    </option>
                @endforeach
            </select>

            <label class="block mb-1">Số tiền muốn rút:</label>
            <input type="number" name="amount" id="amount" class="w-full border rounded px-3 py-2 mb-2" min="10000"
                required>

            <label class="inline-flex items-center mt-2">
                <input type="checkbox" id="withdraw_all" class="mr-2">
                Rút toàn bộ số dư khả dụng (trừ 600.000 VND giữ lại)
            </label>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-3">Rút tiền</button>
        </form>

        <h3 class="text-lg font-semibold mt-6 mb-2">Lịch sử rút tiền</h3>
        @if ($withdrawTransactions->isEmpty())
            <p class="text-gray-500">Chưa có giao dịch rút tiền nào.</p>
        @else
            <ul class="list-disc pl-5">
                @foreach ($withdrawTransactions as $tx)
                    @php
                        $meta = json_decode($tx->meta, true);
                    @endphp
                    <li>
                        {{ $tx->created_at->format('d/m/Y H:i') }} -
                        {{ number_format($tx->amount, 0, ',', '.') }} VND -
                        {{ ucfirst($tx->status) }}<br>
                        {{ $meta['account_name'] ?? '---' }} - {{ $meta['account_number'] ?? '---' }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <script>
        document.getElementById('withdraw_all').addEventListener('change', function () {
            const amountInput = document.getElementById('amount');
            if (this.checked) {
                amountInput.value = "{{ $availableBalance }}";
                amountInput.readOnly = true;
            } else {
                amountInput.readOnly = false;
                amountInput.value = '';
            }
        });
    </script>
@endsection
