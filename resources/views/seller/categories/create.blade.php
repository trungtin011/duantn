@extends('layouts.seller_home')
@section('content')
    <h2>Thêm danh mục mới</h2>
    <form method="POST" action="{{ route('seller.categories.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Tên danh mục:</label>
            <input type="text" name="name" id="name" required class="form-control">
        </div>
        <button type="submit" class="btn btn-success mt-3">Tạo danh mục</button>
    </form>
@endsection
