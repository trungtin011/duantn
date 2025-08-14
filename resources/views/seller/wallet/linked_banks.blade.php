@extends('layouts.seller_home')

@section('title', 'Ngân hàng liên kết')

@section('content')
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
    <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Ngân hàng liên kết</h2>

        @foreach ($linkedBanks as $linked)
            <div class="p-2 border rounded mb-2 flex justify-between items-center">
                <div>
                    <p>
                        {{ $linked->bank->name }} - {{ $linked->account_number }}
                        @if ($linked->is_default)
                            <span class="text-green-600 font-semibold">(Mặc định)</span>
                        @endif
                    </p>
                    <p>{{ $linked->account_name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if (!$linked->is_default)
                        <form method="POST" action="{{ route('seller.linked-banks.set-default', $linked->id) }}">
                            @csrf
                            <button class="text-blue-500 hover:underline">Đặt mặc định</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('seller.linked-banks.destroy', $linked->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:underline">Xóa</button>
                    </form>
                </div>
            </div>
        @endforeach

        <h3 class="text-lg font-semibold mt-6 mb-2">Thêm ngân hàng</h3>
        <form method="POST" action="{{ route('seller.linked-banks.store') }}" class="mt-4 space-y-2">
            @csrf
            <select name="bank_id" required class="w-full border rounded px-3 py-2">
                @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->name }} ({{ $bank->code }})</option>
                @endforeach
            </select>
            <input type="text" name="account_number" placeholder="Số tài khoản" required
                class="w-full border rounded px-3 py-2">
            <input type="text" name="account_name" placeholder="Tên chủ tài khoản" required
                class="w-full border rounded px-3 py-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Liên kết</button>
        </form>
    </div>
@endsection
