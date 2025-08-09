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
        <form action="{{ route('seller.ads_campaigns.update', $campaign->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tên chiến dịch:</label>
                <input type="text" class="form-input w-full border border-gray-300 rounded-md p-2" id="name" name="name" value="{{ old('name', $campaign->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
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
            </div>
            <div class="mb-4">
                <label for="bid_amount" class="block text-gray-700 text-sm font-bold mb-2">Giá thầu (VNĐ):</label>
                <input type="number" step="0.01" min="0" class="form-input w-full border border-gray-300 rounded-md p-2" id="bid_amount" name="bid_amount" value="{{ old('bid_amount', $campaign->bid_amount ?? 0.00) }}" required>
                @error('bid_amount')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Trạng thái:</label>
                <select class="form-input w-full border border-gray-300 rounded-md p-2" id="status" name="status">
                    <option value="pending" {{ old('status', $campaign->status) == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="ended" {{ old('status', $campaign->status) == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                    <option value="cancelled" {{ old('status', $campaign->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
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
@endsection 