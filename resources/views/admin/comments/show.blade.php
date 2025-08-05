@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chi tiết Bình luận</h1>
        <div class="admin-breadcrumb">
            <a href="#" class="admin-breadcrumb-link">Home</a> / 
            <a href="{{ route('admin.comments.index') }}" class="admin-breadcrumb-link">Bình luận</a> / 
            Chi tiết
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Thông tin bình luận</h2>
            <a href="{{ route('admin.comments.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Thông tin bình luận -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Nội dung bình luận</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung:</label>
                        <div class="bg-white p-4 rounded-md border">
                            <p class="text-gray-900">{{ $comment->content }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái:</label>
                            <span class="inline-block {{ $comment->status == 'approved' ? 'bg-green-100 text-green-600' : ($comment->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }} text-sm font-semibold px-3 py-1 rounded-md">
                                {{ $comment->status == 'approved' ? 'Đã duyệt' : ($comment->status == 'pending' ? 'Chờ duyệt' : 'Đã từ chối') }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Đánh giá:</label>
                            <div class="flex items-center">
                                @if($comment->rating)
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ $comment->rating }}/5</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">Không có đánh giá</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày tạo:</label>
                        <p class="text-gray-900">{{ $comment->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cập nhật lần cuối:</label>
                        <p class="text-gray-900">{{ $comment->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- Thông tin người bình luận -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Thông tin người bình luận</h3>
                
                <div class="space-y-4">
                    @if($comment->user)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên:</label>
                            <p class="text-gray-900">{{ $comment->user->fullname }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
                            <p class="text-gray-900">{{ $comment->user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username:</label>
                            <p class="text-gray-900">{{ $comment->user->username }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ngày tham gia:</label>
                            <p class="text-gray-900">{{ $comment->user->created_at->format('d/m/Y') }}</p>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Người bình luận:</label>
                            <p class="text-gray-900">Khách</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thông tin sản phẩm/bài viết -->
        <div class="mt-6 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">
                {{ $comment->product ? 'Thông tin sản phẩm' : 'Thông tin bài viết' }}
            </h3>
            
            @if($comment->product)
                <div class="flex items-center space-x-4">
                    <img src="{{ $comment->product->image_url }}" 
                         alt="{{ $comment->product->name }}" 
                         class="w-16 h-16 rounded-md object-cover">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $comment->product->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $comment->product->sku }}</p>
                        <p class="text-sm text-gray-600">{{ number_format($comment->product->sale_price, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            @elseif($comment->post)
                <div class="flex items-center space-x-4">
                    <img src="{{ $comment->post->photo ? asset($comment->post->photo) : asset('backend/img/thumbnail-default.jpg') }}" 
                         alt="{{ $comment->post->title }}" 
                         class="w-16 h-16 rounded-md object-cover">
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $comment->post->title }}</h4>
                        <p class="text-sm text-gray-600">{{ Str::limit($comment->post->summary, 100) }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Hành động -->
        <div class="mt-6 flex justify-end space-x-4">
            @if($comment->status == 'pending')
                <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>Duyệt
                    </button>
                </form>
            @endif

            @if($comment->status != 'rejected')
                <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>Từ chối
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" 
                  onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Xóa
                </button>
            </form>
        </div>
    </section>
@endsection 