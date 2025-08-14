@extends('layouts.seller_home')

@section('title', 'Rút tiền')

@section('content')
    @php
        \Log::info('DEBUG withdraw view', [
            'walletBalance' => $walletBalance ?? null,
            'availableBalance' => $availableBalance ?? null,
            'withdrawTransactions' => isset($withdrawTransactions) ? $withdrawTransactions->toArray() : null,
        ]);
    @endphp
    {{-- Flash message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md space-y-8">

        <h2 class="text-2xl font-bold text-gray-800 border-b pb-2">Rút tiền về ngân hàng</h2>

        {{-- Thông tin tài khoản ngân hàng chính --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <p><strong>Ngân hàng nhận tiền:</strong> {{ $defaultBank->bank?->code ?? 'N/A' }} -
                {{ $defaultBank->bank?->name ?? 'N/A' }}</p>
            <div><strong>Chủ TK:</strong> {{ $defaultBank->account_name ?? 'N/A' }}</div>
            <div><strong>Chưa chuyển:</strong> {{ number_format($untransferredRevenue ?? 0, 0, ',', '.') }} VND</div>
            <div><strong>Số dư ví hiện tại:</strong> {{ number_format($walletBalance ?? 0, 0, ',', '.') }} VND</div>
            <div><strong>Số dư khả dụng:</strong> {{ number_format($availableBalance ?? 0, 0, ',', '.') }} VND</div>
        </div>

        {{-- Nút thao tác doanh thu --}}
        <div class="flex flex-wrap gap-4">
            <form method="POST" action="{{ route('wallet.transfer.revenue') }}">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow">
                    + Chuyển doanh thu vào ví
                </button>
            </form>
        </div>

        {{-- Form rút tiền --}}
        <form method="POST" action="{{ route('seller.withdraw.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium mb-1">Ngân hàng nhận tiền:</label>
                <div class="bg-gray-100 p-4 rounded">
                    <p><strong>Ngân hàng nhận tiền:</strong> {{ $defaultBank->bank?->name ?? 'N/A' }}</p>
                    <p><strong>Số tài khoản:</strong> {{ $defaultBank->account_number ?? 'N/A' }}</p>
                    <p><strong>Chủ tài khoản:</strong> {{ $defaultBank->account_name ?? 'N/A' }}</p>
                </div>
                <input type="hidden" name="linked_bank_id" value="{{ $defaultBank->id }}">
            </div>

            <div>
                <label class="block font-medium mb-1">Số tiền muốn rút:</label>
                <input type="number" name="amount" id="amount" min="10000" class="w-full border rounded px-3 py-2"
                    required>
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" id="withdraw_all" class="form-checkbox">
                <label for="withdraw_all">Rút toàn bộ số dư khả dụng (trừ 600.000 VND giữ lại)</label>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                💸 Rút tiền
            </button>
        </form>

        {{-- Lịch sử rút tiền --}}
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-3">Lịch sử rút tiền</h3>
            @if ($withdrawTransactions->isEmpty())
                <p class="text-gray-500">Chưa có giao dịch rút tiền nào.</p>
            @else
                <ul class="divide-y divide-gray-200 max-h-60 overflow-y-auto">
                    @foreach ($withdrawTransactions as $tx)
                        <li class="py-3 text-sm">
                            <div class="font-medium">
                                {{ $tx->created_at->format('d/m/Y H:i') }} -
                                {{ number_format($tx->amount, 0, ',', '.') }} VND -
                                <span class="capitalize text-gray-600">{{ $tx->status }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- JS: Tự động rút toàn bộ số dư --}}
    <script>
        document.getElementById('withdraw_all').addEventListener('change', function() {
            const amountInput = document.getElementById('amount');
            if (this.checked) {
                const availableBalance = {{ $availableBalance ?? 0 }};
                amountInput.value = availableBalance;
                amountInput.readOnly = true;
            } else {
                amountInput.readOnly = false;
                amountInput.value = '';
            }
        });
    </script>
@endsection
