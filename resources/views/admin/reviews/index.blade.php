@extends('layouts.admin')

@section('head')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/review.css') }}">
@endpush
@endsection

@section('content')
<pre>@php dump($reviews) @endphp</pre>
<div class="admin-page-header">
    <h1 class="admin-page-title">Reviews</h1>
    <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / Reviews List</div>
</div>

{{-- Form lọc --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="shop_id" class="form-select">
            <option value="">-- Chọn shop --</option>
            @if(isset($shops))
                @foreach($shops as $id => $name)
                    <option value="{{ $id }}" {{ request('shop_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-auto">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="col-auto">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <div class="col-auto">
        <select name="rating" class="form-select">
            <option value="">-- Tất cả sao --</option>
            @for($i=5;$i>=1;$i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
            @endfor
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary">Lọc</button>
    </div>
</form>

<div class="admin-card mb-4">
    <div class="table-responsive admin-table-container">
        <table class="table align-middle mb-0 admin-table">
            <thead class="admin-table-thead">
                <tr>
                    <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                    <th>PRODUCT</th>
                    <th>CUSTOMER</th>
                    <th>RATING</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($reviews) && count($reviews))
                @foreach($reviews as $review)
                <tr>
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ optional($review->product->images->first())->image_path ? asset($review->product->images->first()->image_path) : 'https://via.placeholder.com/40' }}" alt="" class="me-3 product-img-table">
                            <span>{{ $review->product->name ?? '' }}</span>
                        </div>
                    </td>
                    <td>{{ $review->user->fullname ?? '' }}</td>
                    <td class="rating-stars">
                        @for($i=1;$i<=5;$i++)
                            <i class="fa-solid fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                        @endfor
                    </td>
                    <td>{{ $review->created_at ? $review->created_at->format('d/m/Y H:i') : '' }}</td>
                    <td>
                        <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xóa đánh giá này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="6" class="text-center text-muted">Không có đánh giá nào</td></tr>
            @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted text-sm">
            @if(isset($reviews) && $reviews->total())
                Hiển thị {{ $reviews->count() }} đánh giá / tổng {{ $reviews->total() }}
            @endif
        </div>
        @if(isset($reviews))
        <nav aria-label="Pagination navigation">
            {{ $reviews->withQueryString()->links() }}
        </nav>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection 