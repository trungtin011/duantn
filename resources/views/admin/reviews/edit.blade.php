@extends('layouts.admin')

@section('content')
<h2>Sửa đánh giá</h2>
<form method="POST" action="{{ route('admin.reviews.update', $review->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Số sao</label>
        <select name="rating" class="form-select">
            @for($i=5;$i>=1;$i--)
                <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} sao</option>
            @endfor
        </select>
    </div>
    <div class="mb-3">
        <label>Bình luận</label>
        <textarea name="comment" class="form-control">{{ $review->comment }}</textarea>
    </div>
    <button class="btn btn-primary">Cập nhật</button>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Quay lại</a>
</form>
@endsection
