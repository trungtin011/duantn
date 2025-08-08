@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Quản lý Bình luận</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Bình luận</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('admin.comments.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo nội dung bình luận" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('admin.comments.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                        </select>
                    </div>
                </form>
                <form method="GET" action="{{ route('admin.comments.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Loại:</span>
                        <select name="type" id="typeFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                            <option value="post" {{ request('type') == 'post' ? 'selected' : '' }}>Bài viết</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả bình luận" type="checkbox" />
                    </th>
                    <th class="py-3">Bình luận</th>
                    <th class="py-3">Người bình luận</th>
                    <th class="py-3">Sản phẩm/Bài viết</th>
                    <th class="py-3">Ngày bình luận</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @forelse($comments as $comment)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn bình luận"
                                type="checkbox" />
                        </td>
                        <td class="py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-md bg-orange-100 flex items-center justify-center">
                                <i class="fas fa-comment text-orange-600 text-sm"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-semibold text-[13px]">
                                    {{ Str::limit($comment->content, 60) }}
                                </span>
                                <span class="text-[11px] text-gray-500">
                                    {{ $comment->type == 'product' ? 'Bình luận sản phẩm' : 'Bình luận bài viết' }}
                                </span>
                            </div>
                        </td>
                        <td class="py-4 text-[13px]">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $comment->user->fullname ?? 'Khách' }}</span>
                                <span class="text-[11px] text-gray-500">{{ $comment->user->email ?? 'Không có' }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-[13px]">
                            <span class="inline-block bg-blue-100 text-blue-600 text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                {{ $comment->product->name ?? $comment->post->title ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $comment->status == 'approved' ? 'bg-green-100 text-green-600' : ($comment->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $comment->status == 'approved' ? 'Đã duyệt' : ($comment->status == 'pending' ? 'Chờ duyệt' : 'Đã từ chối') }}
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            <a href="{{ route('admin.comments.show', $comment->id) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md focus:outline-none" title="Xem chi tiết">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            @if($comment->status == 'pending')
                                <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md focus:outline-none" title="Duyệt">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            @if($comment->status != 'rejected')
                                <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none" title="Từ chối">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Xóa bình luận"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none" title="Xóa">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-4">Không tìm thấy bình luận nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $comments->count() }} bình luận trên {{ $comments->total() }} bình luận
            </div>
            {{ $comments->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection 