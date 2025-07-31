@extends('layouts.seller_home')

@section('content')
    <div class="mt-[32px] mb-[24px]">
        <h1 class="font-semibold text-[28px]">Danh mục</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh mục</div>
    </div>
    @include('layouts.notification')
    <div class="row g-3">
        {{-- Left Column: Add/Edit Category Form --}}
        <div class="col-md-4">
            <div class="p-[24px] bg-white rounded-[8px]">
                <form action="{{ route('seller.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="categoryName" name="name"
                            placeholder="Tên danh mục" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    @foreach ($products as $product)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="product_ids[]"
                                value="{{ $product->id }}">
                            <label class="form-check-label">{{ $product->name }}</label>
                        </div>
                    @endforeach

                    <button type="submit"
                        class="bg-[#28BCF9] hover:bg-[#3DA5F7] text-white w-full py-2 px-4 rounded-md flex items-center justify-center transition-all duration-300">Thêm
                        danh mục</button>
                </form>
            </div>
        </div>

        {{-- Right Column: Category List Table --}}
        <div class="col-md-8">
            <div class="p-[24px] bg-white rounded-[8px]">
                <div class="table-responsive admin-table-container">
                    <table class="w-full text-xs text-left text-gray-400 border-gray-100">
                        <thead class="text-gray-300 font-semibold border-b border-gray-100">
                            <tr>
                                <th class="w-6 py-3 pr-6">
                                    <input id="select-all" class="w-[18px] h-[18px]" aria-label="Select all categories"
                                        type="checkbox" />
                                </th>
                                <th class="py-3 w-[50px]">ID</th>
                                <th class="py-3">Tên danh mục</th>
                                <th class="py-3">Mô tả</th>
                                <th class="py-3 text-right">Slug</th>
                                <th class="py-3 pr-6 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="py-4 pr-6">
                                        <input class="select-item w-[18px] h-[18px]"
                                            aria-label="Select {{ $category->name }}" type="checkbox" />
                                    </td>
                                    <td class="py-4 text-[13px]">#{{ $category->id }}</td>
                                    <td class="py-4 text-[13px]">
                                        <div class="flex items-center">
                                            <span>{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-[13px] truncate max-w-[50px]">{{ $category->description }}
                                    </td>
                                    <td class="py-4 text-[13px] text-right">{{ $category->slug }}</td>
                                    <td class="py-4 pr-6 flex items-center justify-end gap-2">
                                        <div
                                            class="bg-[#50cd89] hover:bg-[#16A34A] text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                                            <a href="{{ route('seller.categories.edit', $category->id) }}"
                                                class="transition-all duration-300">
                                                <i class="fas fa-pen" title="Sửa"></i>
                                            </a>
                                        </div>
                                        <form action="{{ route('seller.categories.destroy', $category->id) }}"
                                            method="POST" style="display: inline;"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="border hover:bg-[#F1416C] hover:text-white w-[37px] h-[35px] rounded-md flex items-center justify-center transition-all duration-300">
                                                <i title="Xóa">
                                                    <svg class="" width="13" height="13" viewBox="0 0 20 22"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19.0697 4.23C17.4597 4.07 15.8497 3.95 14.2297 3.86V3.85L14.0097 2.55C13.8597 1.63 13.6397 0.25 11.2997 0.25H8.67967C6.34967 0.25 6.12967 1.57 5.96967 2.54L5.75967 3.82C4.82967 3.88 3.89967 3.94 2.96967 4.03L0.929669 4.23C0.509669 4.27 0.209669 4.64 0.249669 5.05C0.289669 5.46 0.649669 5.76 1.06967 5.72L3.10967 5.52C8.34967 5 13.6297 5.2 18.9297 5.73C18.9597 5.73 18.9797 5.73 19.0097 5.73C19.3897 5.73 19.7197 5.44 19.7597 5.05C19.7897 4.64 19.4897 4.27 19.0697 4.23Z"
                                                            fill="currentColor"></path>
                                                        <path
                                                            d="M17.2297 7.14C16.9897 6.89 16.6597 6.75 16.3197 6.75H3.67975C3.33975 6.75 2.99975 6.89 2.76975 7.14C2.53975 7.39 2.40975 7.73 2.42975 8.08L3.04975 18.34C3.15975 19.86 3.29975 21.76 6.78975 21.76H13.2097C16.6997 21.76 16.8398 19.87 16.9497 18.34L17.5697 8.09C17.5897 7.73 17.4597 7.39 17.2297 7.14ZM11.6597 16.75H8.32975C7.91975 16.75 7.57975 16.41 7.57975 16C7.57975 15.59 7.91975 15.25 8.32975 15.25H11.6597C12.0697 15.25 12.4097 15.59 12.4097 16C12.4097 16.41 12.0697 16.75 11.6597 16.75ZM12.4997 12.75H7.49975C7.08975 12.75 6.74975 12.41 6.74975 12C6.74975 11.59 7.08975 11.25 7.49975 11.25H12.4997C12.9097 11.25 13.2497 11.59 13.2497 12C13.2497 12.41 12.9097 12.75 12.4997 12.75Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($categories->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center text-gray-400 py-4">Không có danh mục nào
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted text-sm">Hiển thị {{ $categories->count() }} trên {{ $categories->total() }}
                    </div>
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
