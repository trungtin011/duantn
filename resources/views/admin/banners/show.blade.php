@extends('layouts.admin')

@section('title', 'Chi tiết Banner')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Chi tiết Banner</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.banners.edit', $banner->id) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.banners.index') }}" 
                   class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Hình ảnh -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Hình ảnh Banner</h2>
                <div class="aspect-w-16 aspect-h-9 mb-4">
                    <img src="{{ $banner->image_url }}" 
                         alt="{{ $banner->title }}" 
                         class="w-full h-auto rounded-lg border border-gray-200">
                </div>
                <div class="text-sm text-gray-500 space-y-1">
                    <p><strong>Đường dẫn:</strong> {{ $banner->image_path }}</p>
                    <p><strong>Định dạng:</strong> {{ pathinfo($banner->image_path, PATHINFO_EXTENSION) }}</p>
                    @if($banner->image_width && $banner->image_height)
                        <p><strong>Kích thước:</strong> {{ $banner->image_width }} x {{ $banner->image_height }} px</p>
                        <p><strong>Tỷ lệ khung hình:</strong> {{ round($banner->image_width / $banner->image_height, 2) }}</p>
                    @endif
                    @if($banner->image_size)
                        <p><strong>Dung lượng:</strong> {{ $banner->image_size }}</p>
                    @endif
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin Banner</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->title }}</p>
                    </div>

                    @if($banner->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->description }}</p>
                    </div>
                    @endif

                    @if($banner->link_url)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link URL</label>
                        <a href="{{ $banner->link_url }}" target="_blank" 
                           class="mt-1 text-sm text-blue-600 hover:text-blue-800 break-all">
                            {{ $banner->link_url }}
                        </a>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                        <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $banner->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $banner->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Thứ tự</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày tạo</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày cập nhật</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    @if($banner->start_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày bắt đầu</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->start_date->format('d/m/Y H:i:s') }}</p>
                    </div>
                    @endif

                    @if($banner->end_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày kết thúc</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->end_date->format('d/m/Y H:i:s') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Responsive Settings -->
        @if($banner->responsive_settings)
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Cài đặt Responsive</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Desktop -->
                @if(isset($banner->responsive_settings['desktop']))
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-900 mb-3">Desktop</h3>
                    <div class="space-y-2 text-sm">
                        <div><strong>Font size tiêu đề:</strong> {{ $banner->responsive_settings['desktop']['title_font_size'] ?? '2rem' }}</div>
                        <div><strong>Font size phụ đề:</strong> {{ $banner->responsive_settings['desktop']['subtitle_font_size'] ?? '1rem' }}</div>
                        <div><strong>Vị trí nội dung:</strong> {{ $banner->responsive_settings['desktop']['content_position'] ?? 'center' }}</div>
                        <div><strong>Căn chỉnh text:</strong> {{ $banner->responsive_settings['desktop']['text_align'] ?? 'center' }}</div>
                        <div><strong>Màu tiêu đề:</strong> 
                            <span class="inline-block w-4 h-4 rounded border" style="background-color: {{ $banner->responsive_settings['desktop']['title_color'] ?? '#ffffff' }}"></span>
                            {{ $banner->responsive_settings['desktop']['title_color'] ?? '#ffffff' }}
                        </div>
                        <div><strong>Màu phụ đề:</strong> 
                            <span class="inline-block w-4 h-4 rounded border" style="background-color: {{ $banner->responsive_settings['desktop']['subtitle_color'] ?? '#f3f4f6' }}"></span>
                            {{ $banner->responsive_settings['desktop']['subtitle_color'] ?? '#f3f4f6' }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tablet -->
                @if(isset($banner->responsive_settings['tablet']))
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-green-900 mb-3">Tablet</h3>
                    <div class="space-y-2 text-sm">
                        <div><strong>Font size tiêu đề:</strong> {{ $banner->responsive_settings['tablet']['title_font_size'] ?? '1.5rem' }}</div>
                        <div><strong>Font size phụ đề:</strong> {{ $banner->responsive_settings['tablet']['subtitle_font_size'] ?? '0.875rem' }}</div>
                        <div><strong>Vị trí nội dung:</strong> {{ $banner->responsive_settings['tablet']['content_position'] ?? 'center' }}</div>
                        <div><strong>Căn chỉnh text:</strong> {{ $banner->responsive_settings['tablet']['text_align'] ?? 'center' }}</div>
                        <div><strong>Màu tiêu đề:</strong> 
                            <span class="inline-block w-4 h-4 rounded border" style="background-color: {{ $banner->responsive_settings['tablet']['title_color'] ?? '#ffffff' }}"></span>
                            {{ $banner->responsive_settings['tablet']['title_color'] ?? '#ffffff' }}
                        </div>
                        <div><strong>Màu phụ đề:</strong> 
                            <span class="inline-block w-4 h-4 rounded border" style="background-color: {{ $banner->responsive_settings['tablet']['subtitle_color'] ?? '#f3f4f6' }}"></span>
                            {{ $banner->responsive_settings['tablet']['subtitle_color'] ?? '#f3f4f6' }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Mobile -->
                @if(isset($banner->responsive_settings['mobile']))
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-purple-900 mb-3">Mobile</h3>
                    <div class="space-y-2 text-sm">
                        <div><strong>Font size tiêu đề:</strong> {{ $banner->responsive_settings['mobile']['title_font_size'] ?? '1.25rem' }}</div>
                        <div><strong>Font size phụ đề:</strong> {{ $banner->responsive_settings['mobile']['subtitle_font_size'] ?? '0.75rem' }}</div>
                        <div><strong>Vị trí nội dung:</strong> {{ $banner->responsive_settings['mobile']['content_position'] ?? 'center' }}</div>
                        <div><strong>Căn chỉnh text:</strong> {{ $banner->responsive_settings['mobile']['text_align'] ?? 'center' }}</div>
                        <div><strong>Màu tiêu đề:</strong> 
                            <span class="inline-block w-4 h-4 rounded border" style="background-color: {{ $banner->responsive_settings['mobile']['title_color'] ?? '#ffffff' }}"></span>
                            {{ $banner->responsive_settings['mobile']['title_color'] ?? '#ffffff' }}
                        </div>
                        <div><strong>Màu phụ đề:</strong> 
                            <span class="inline-block w-4 h-4 rounded border" style="background-color: {{ $banner->responsive_settings['mobile']['subtitle_color'] ?? '#f3f4f6' }}"></span>
                            {{ $banner->responsive_settings['mobile']['subtitle_color'] ?? '#f3f4f6' }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Thống kê -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Thống kê</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-eye text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600">Trạng thái hiện tại</p>
                            <p class="text-2xl font-semibold text-blue-900">
                                {{ $banner->isActive() ? 'Đang hoạt động' : 'Không hoạt động' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600">Thời gian tạo</p>
                            <p class="text-2xl font-semibold text-green-900">
                                {{ $banner->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-sort text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-600">Thứ tự hiển thị</p>
                            <p class="text-2xl font-semibold text-purple-900">{{ $banner->sort_order }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Thao tác</h2>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.banners.edit', $banner->id) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Chỉnh sửa
                </a>
                
                <button onclick="toggleStatus({{ $banner->id }})" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas {{ $banner->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                    {{ $banner->status === 'active' ? 'Tắt hoạt động' : 'Bật hoạt động' }}
                </button>
                
                <button onclick="deleteBanner({{ $banner->id }})" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-trash"></i>
                    Xóa
                </button>
                
                <a href="{{ route('admin.banners.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Xác nhận xóa</h3>
            <p class="text-gray-500 mb-6">Bạn có chắc chắn muốn xóa banner "{{ $banner->title }}" không?</p>
            <div class="flex justify-end gap-3">
                <button id="cancel-delete" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                    Hủy
                </button>
                <button id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Xóa
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let deleteBannerId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Modal xóa
    document.getElementById('cancel-delete').addEventListener('click', hideDeleteModal);
    document.getElementById('confirm-delete').addEventListener('click', confirmDelete);
});

// Toggle trạng thái banner
function toggleStatus(bannerId) {
    fetch(`/admin/banners/${bannerId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error toggling status:', error);
        showNotification('Có lỗi xảy ra!', 'error');
    });
}

// Xóa banner
function deleteBanner(bannerId) {
    deleteBannerId = bannerId;
    showDeleteModal();
}

// Hiển thị modal xóa
function showDeleteModal() {
    document.getElementById('delete-modal').classList.remove('hidden');
}

// Ẩn modal xóa
function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    deleteBannerId = null;
}

// Xác nhận xóa
function confirmDelete() {
    if (!deleteBannerId) return;
    
    fetch(`/admin/banners/${deleteBannerId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            hideDeleteModal();
            setTimeout(() => {
                window.location.href = '{{ route("admin.banners.index") }}';
            }, 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting banner:', error);
        showNotification('Có lỗi xảy ra!', 'error');
    });
}

// Hiển thị thông báo
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 'bg-blue-600'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
