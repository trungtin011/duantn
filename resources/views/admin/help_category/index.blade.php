@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Danh mục trợ giúp</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách danh mục trợ giúp</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('help-category.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tên danh mục" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('help-category.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('help-category.create') }}"
                    class="h-[44px] text-[15px] bg-blue-600 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm danh mục
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả danh mục" type="checkbox" />
                    </th>
                    <th class="py-3">Danh mục</th>
                    <th class="py-3">Danh mục cha</th>
                    <th class="py-3">Thứ tự</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach($categories as $cat)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $cat->title }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-md bg-gradient-to-r from-purple-400 to-pink-500 flex items-center justify-center">
                                @if($cat->icon)
                                    <img src="{{ Storage::url($cat->icon) }}" alt="icon" class="w-6 h-6 object-cover">
                                @else
                                    <i class="fas fa-folder text-white text-sm"></i>
                                @endif
                            </div>
                            <span class="font-semibold text-[13px]">
                                {{ $cat->title }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">
                            <span class="inline-block bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                {{ $cat->parent->title ?? 'Gốc' }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $cat->sort_order }}</td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $cat->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $cat->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            <a href="{{ route('help-category.edit', $cat->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <form action="{{ route('help-category.destroy', $cat->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Xóa {{ $cat->title }}"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($categories->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-4">No categories found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    Hiển thị {{ $categories->count() }} categories trên {{ $categories->total() }} categories
                @else
                    Hiển thị {{ $categories->count() }} categories
                @endif
            </div>
            @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $categories->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </section>
@endsection
