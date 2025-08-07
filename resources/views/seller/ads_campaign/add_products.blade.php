@extends('layouts.seller_home')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Thêm sản phẩm vào chiến dịch: {{ $campaign->name }}</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / <a href="{{ route('seller.ads_campaigns.index') }}" class="admin-breadcrumb-link">Danh sách chiến dịch quảng cáo</a> / Thêm sản phẩm</div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('seller.ads_campaigns.store_products', $campaign->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Chọn sản phẩm:</label>
                @error('product_ids')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                @error('product_ids.*')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($products as $product)
                        <div class="border border-gray-200 rounded-md p-3 flex items-center space-x-3">
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" id="product_{{ $product->id }}"
                                class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                {{ in_array($product->id, $selectedProductIds) ? 'checked' : '' }}>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                            <div>
                                <label for="product_{{ $product->id }}" class="text-sm font-medium text-gray-900">{{ $product->name }}</label>
                                <p class="text-xs text-gray-500">ID: {{ $product->id }} | Giá: {{ number_format($product->price) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500">Không có sản phẩm nào để thêm.</p>
                    @endforelse
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Thêm vào chiến dịch
                </button>
                <a href="{{ route('seller.ads_campaigns.index') }}" class="ml-4 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Hủy
                </a>
            </div>
        </form>
    </section>
@endsection 