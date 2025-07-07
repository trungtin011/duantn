@extends('layouts.app')

@section('title', 'Hồ sơ người bán')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Hồ sơ người bán</h2>

    <div class="mb-6">
        <p><strong>Tên người bán:</strong> {{ $seller->name }}</p>
        <p><strong>Email:</strong> {{ $seller->email }}</p>
        <p><strong>Số sản phẩm:</strong> {{ $seller->products->count() }}</p>
    </div>

    <h3 class="text-xl font-semibold mb-3 text-gray-700">Danh sách sản phẩm</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($seller->products as $product)
        <a href="{{ route('product.show', $product->slug) }}" class="block border rounded hover:shadow p-2">
            <img src="{{ asset($product->images->first()->image_path ?? 'images/default.jpg') }}"
                class="h-40 w-full object-cover rounded mb-2">
            <div class="text-sm font-medium text-gray-700 truncate">{{ $product->name }}</div>
            <div class="text-red-600 font-bold text-sm">
                {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
