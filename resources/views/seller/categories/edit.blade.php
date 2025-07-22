@extends('layouts.seller')
@section('content')
    <h2>Chỉnh sửa danh mục</h2>
    <form method="POST" action="{{ route('seller.categories.update', $category) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="name">Tên danh mục:</label>
            <input type="text" name="name" id="name" value="{{ $category->name }}" required class="form-control">
        </div>

        <div class="form-group mt-3">
            <label>Chọn sản phẩm:</label><br>
            @foreach ($products as $product)
                <div class="form-check">
                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                        {{ in_array($product->id, $selectedProducts) ? 'checked' : '' }}>
                    {{ $product->name }}
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
    </form>
@endsection
