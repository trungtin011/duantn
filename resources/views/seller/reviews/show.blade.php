@extends('layouts.seller')

@section('head')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/review.css') }}">
@endpush
@endsection

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Chi tiết đánh giá</h2>

    <div class="card shadow">
        <div class="card-body">
            {{-- Thông tin sản phẩm --}}
            <h5 class="card-title">Sản phẩm được đánh giá</h5>
            <div class="d-flex align-items-center mb-3">
                <img src="{{ asset($review->product->images[0]->url ?? 'images/no-image.png') }}" 
                     alt="Ảnh sản phẩm" 
                     style="width: 100px; height: 100px; object-fit: cover;" 
                     class="me-3 border rounded">
                <div>
                    <h6 class="mb-1">{{ $review->product->name }}</h6>
                    <p class="mb-0 text-muted">Mã sản phẩm: {{ $review->product->id }}</p>
                    <p class="mb-0 text-muted">Shop: {{ $review->product->shop->name }}</p>
                </div>
            </div>

            <hr>

            {{-- Thông tin người mua và đánh giá --}}
            <h5 class="card-title">Thông tin đánh giá</h5>
            <p><strong>Người đánh giá:</strong> {{ $review->user->name ?? 'Người dùng ẩn danh' }}</p>
            <p><strong>Ngày đánh giá:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Số sao:</strong> 
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}">&#9733;</span>
                @endfor
                ({{ $review->rating }}/5)
            </p>
            <p><strong>Nội dung:</strong></p>
            <p class="border p-3 bg-light">{{ $review->comment }}</p>

            @if ($review->images && count($review->images))
                <p><strong>Hình ảnh đính kèm:</strong></p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($review->images as $image)
                        <img src="{{ asset($image->url) }}" alt="Review Image" style="width: 120px; height: 120px; object-fit: cover;" class="border rounded">
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('seller.reviews.index') }}" class="btn btn-secondary">Quay lại danh sách đánh giá</a>
    </div>
</div>
@endsection
