<!-- resources/views/reviews/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh sách đánh giá</h1>
        @foreach($reviews as $review)
            <div>
                <strong>Đánh giá {{ $review->rating }} sao</strong>
                <p>{{ $review->comment }}</p>
            </div>
        @endforeach
    </div>
@endsection
