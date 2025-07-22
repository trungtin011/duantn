@extends('layouts.seller_home')
@section('content')
    <h2>Danh mục sản phẩm của Shop</h2>
    <a href="{{ route('seller.categories.create') }}" class="btn btn-success mb-3">+ Thêm danh mục</a>

    @foreach ($categories as $category)
        <div class="border p-3 mb-3">
            <h4>{{ $category->name }}</h4>
            <p>Sản phẩm: {{ $category->products->pluck('name')->join(', ') }}</p>
            <a href="{{ route('seller.categories.edit', $category) }}" class="btn btn-sm btn-primary">Sửa</a>
            <form action="{{ route('seller.categories.destroy', $category) }}" method="POST" style="display:inline-block">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Bạn có chắc muốn xoá?')" class="btn btn-sm btn-danger">Xoá</button>
            </form>
        </div>
    @endforeach
@endsection
