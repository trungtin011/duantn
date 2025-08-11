@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Bài viết trợ giúp</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách bài viết trợ giúp</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('help-article.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tiêu đề bài viết" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('help-article.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('help-article.create') }}"
                    class="h-[44px] text-[15px] bg-blue-600 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm bài viết
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả bài viết" type="checkbox" />
                    </th>
                    <th class="py-3">Bài viết</th>
                    <th class="py-3">Danh mục</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach($articles as $item)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $item->title }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-md bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                                <i class="fas fa-file-alt text-white text-sm"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-semibold text-[13px]">
                                    {{ $item->title }}
                                </span>
                                <span class="text-[11px] text-gray-500">
                                    {{ Str::limit($item->content ?? '', 50) }}
                                </span>
                            </div>
                        </td>
                        <td class="py-4 text-[13px]">
                            <span class="inline-block bg-blue-100 text-blue-600 text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                {{ $item->category->title ?? 'Không có' }}
                            </span>
                        </td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $item->status == 'active' ? 'bg-green-100 text-green-600' : ($item->status == 'inactive' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600') }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $item->status == 'active' ? 'Hoạt động' : ($item->status == 'inactive' ? 'Không hoạt động' : 'Bản nháp') }}
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            <a href="{{ route('help-article.edit', $item->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <form action="{{ route('help-article.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Xóa {{ $item->title }}"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($articles->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 py-4">No articles found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    Hiển thị {{ $articles->count() }} articles trên {{ $articles->total() }} articles
                @else
                    Hiển thị {{ $articles->count() }} articles
                @endif
            </div>
            @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                {{ $articles->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </section>
@endsection
