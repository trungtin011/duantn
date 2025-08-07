@extends('layouts.seller_home')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Tạo chiến dịch quảng cáo mới</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / <a href="{{ route('seller.ads_campaigns.index') }}" class="admin-breadcrumb-link">Danh sách chiến dịch quảng cáo</a> / Tạo mới</div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('seller.ads_campaigns.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tên chiến dịch:</label>
                <input type="text" class="form-input w-full border border-gray-300 rounded-md p-2" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Ngày bắt đầu:</label>
                <input type="datetime-local" class="form-input w-full border border-gray-300 rounded-md p-2" id="start_date" name="start_date" value="{{ old('start_date') }}">
                @error('start_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Ngày kết thúc:</label>
                <input type="datetime-local" class="form-input w-full border border-gray-300 rounded-md p-2" id="end_date" name="end_date" value="{{ old('end_date') }}">
                @error('end_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Lưu chiến dịch
                </button>
                <a href="{{ route('seller.ads_campaigns.index') }}" class="ml-4 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Hủy
                </a>
            </div>
        </form>
    </section>
@endsection 