@extends('layouts.seller_home')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Chỉnh sửa chiến dịch quảng cáo</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / <a href="{{ route('seller.ads_campaigns.index') }}" class="admin-breadcrumb-link">Danh sách chiến dịch quảng cáo</a> / Chỉnh sửa</div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('seller.ads_campaigns.update', $campaign->id) }}" method="POST" id="adsCampaignForm" novalidate>
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tên chiến dịch:</label>
                <input type="text" class="form-input w-full border border-gray-300 rounded-md p-2" id="name" name="name" value="{{ old('name', $campaign->name) }}">
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                <p id="name_error" class="text-red-500 text-xs italic hidden"></p>
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Ngày bắt đầu:</label>
                <input type="datetime-local" class="form-input w-full border border-gray-300 rounded-md p-2" id="start_date" name="start_date" value="{{ old('start_date', $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('Y-m-d\TH:i') : '') }}">
                @error('start_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Ngày kết thúc:</label>
                <input type="datetime-local" class="form-input w-full border border-gray-300 rounded-md p-2" id="end_date" name="end_date" value="{{ old('end_date', $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d\TH:i') : '') }}">
                @error('end_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                <p id="end_date_error" class="text-red-500 text-xs italic hidden"></p>
            </div>
            <div class="mb-4 p-3 rounded-md border border-blue-200 bg-blue-50 text-blue-800 text-sm flex items-center gap-2">
                <i class="fas fa-wallet"></i>
                <span>Số dư ví hiện tại:</span>
                <span class="text-base font-bold">{{ number_format($walletBalance ?? 0, 0, ',', '.') }} VND</span>
            </div>
            <div class="mb-4">
                <label for="bid_amount" class="block text-gray-700 text-sm font-bold mb-2">Giá thầu (VNĐ):</label>
                <input type="number" step="0.01" min="0" max="{{ $walletBalance ?? 0 }}" class="form-input w-full border border-gray-300 rounded-md p-2" id="bid_amount" name="bid_amount" value="{{ old('bid_amount', $campaign->bid_amount ?? 0.00) }}">
                @error('bid_amount')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                <p id="bid_amount_error" class="text-red-500 text-xs italic hidden"></p>
            </div>
            
            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Cập nhật chiến dịch
                </button>
                <a href="{{ route('seller.ads_campaigns.index') }}" class="ml-4 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Hủy
                </a>
            </div>
        </form>
    </section>
    <script>
        (function() {
            const form = document.getElementById('adsCampaignForm');
            if (!form) return;

            const walletBalance = Number({{ (int) ($walletBalance ?? 0) }});
            const nameInput = document.getElementById('name');
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');
            const bidInput = document.getElementById('bid_amount');

            const err = (id, msg) => {
                const el = document.getElementById(id);
                if (!el) return;
                if (msg) {
                    el.textContent = msg;
                    el.classList.remove('hidden');
                } else {
                    el.textContent = '';
                    el.classList.add('hidden');
                }
            };

            form.addEventListener('submit', function(e) {
                let firstInvalid = null;
                ['name_error','end_date_error','bid_amount_error'].forEach(id => err(id, ''));

                if (!nameInput.value || nameInput.value.trim().length === 0) {
                    err('name_error', 'Vui lòng nhập tên chiến dịch.');
                    firstInvalid = firstInvalid || nameInput;
                }

                if (startInput.value && endInput.value) {
                    const s = new Date(startInput.value);
                    const eDate = new Date(endInput.value);
                    if (eDate < s) {
                        err('end_date_error', 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.');
                        firstInvalid = firstInvalid || endInput;
                    }
                }

                const bid = Number(bidInput.value);
                if (isNaN(bid) || bid <= 0) {
                    err('bid_amount_error', 'Giá thầu phải lớn hơn 0.');
                    firstInvalid = firstInvalid || bidInput;
                } else if (bid > walletBalance) {
                    err('bid_amount_error', 'Giá thầu không được vượt quá số dư ví hiện tại.');
                    firstInvalid = firstInvalid || bidInput;
                }

                if (firstInvalid) {
                    e.preventDefault();
                    firstInvalid.focus();
                }
            });
        })();
    </script>
@endsection 