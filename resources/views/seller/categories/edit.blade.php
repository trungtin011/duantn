@extends('layouts.seller_home')

@section('content')
    <div class="mt-[32px] mb-[24px]">
        <h1 class="font-semibold text-[28px]">Chỉnh sửa danh mục</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / <a
                href="{{ route('seller.categories.index') }}" class="admin-breadcrumb-link">Danh mục</a> / Chỉnh sửa</div>
    </div>
    @include('layouts.notification')
    <div class="row g-3">
        {{-- Left Column: Add/Edit Category Form --}}
        <div class="col-md-6">
            <div class="p-[24px] bg-white rounded-[8px]">
                <form action="{{ route('seller.categories.update', $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="categoryName" name="name"
                            value="{{ old('name', $category->name) }}">
                        @error('name')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
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
                    <button type="submit"
                        class="bg-[#28BCF9] hover:bg-[#3DA5F7] text-white w-full py-2 px-4 rounded-md flex items-center justify-center transition-all duration-300">Cập
                        nhật danh mục</button>
                </form>
            </div>
        </div>


    </div>
@endsection
