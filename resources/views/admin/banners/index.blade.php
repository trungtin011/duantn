@extends('layouts.admin')

@section('title', 'Quản lý Banner')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Quản lý Banner</h1>
            <a href="{{ route('admin.banners.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Thêm Banner
            </a>
        </div>

        <!-- Bộ lọc -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" id="search" placeholder="Tìm theo tiêu đề..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select id="status-filter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả</option>
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Không hoạt động</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sắp xếp</label>
                    <select id="sort-by"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="sort_order">Thứ tự</option>
                        <option value="created_at">Ngày tạo</option>
                        <option value="title">Tiêu đề</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button id="reset-filters"
                        class="px-4 py-2 text-white bg-[#ef3248] border border-gray-300 rounded-md hover:bg-red-600 hidden">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bảng banner -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hình ảnh
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tiêu đề
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thứ tự
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody id="banners-table-body" class="bg-white divide-y divide-gray-200">
                        @include('admin.banners._table_body')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="banners-pagination" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                @include('admin.banners._pagination')
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Xác nhận xóa</h3>
                <p class="text-gray-500 mb-6">Bạn có chắc chắn muốn xóa banner này không?</p>
                <div class="flex justify-end gap-3">
                    <button id="cancel-delete"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
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
        let currentFilters = {};
        let deleteBannerId = null;

        // Khởi tạo
        document.addEventListener('DOMContentLoaded', function() {
            initializeFilters();
            initializeEventListeners();
        });

        // Khởi tạo bộ lọc
        function initializeFilters() {
            const urlParams = new URLSearchParams(window.location.search);
            currentFilters = {
                search: urlParams.get('search') || '',
                status: urlParams.get('status') || '',
                sort_by: urlParams.get('sort_by') || 'sort_order',
                sort_order: urlParams.get('sort_order') || 'asc'
            };

            // Cập nhật giá trị các input
            document.getElementById('search').value = currentFilters.search;
            document.getElementById('status-filter').value = currentFilters.status;
            document.getElementById('sort-by').value = currentFilters.sort_by;

            updateResetButton();
        }

        // Khởi tạo event listeners
        function initializeEventListeners() {
            // Bộ lọc
            document.getElementById('search').addEventListener('input', debounce(applyFilters, 500));
            document.getElementById('status-filter').addEventListener('change', applyFilters);
            document.getElementById('sort-by').addEventListener('change', applyFilters);

            // Nút reset
            document.getElementById('reset-filters').addEventListener('click', resetFilters);

            // Modal xóa
            document.getElementById('cancel-delete').addEventListener('click', hideDeleteModal);
            document.getElementById('confirm-delete').addEventListener('click', confirmDelete);
        }

        // Áp dụng bộ lọc
        function applyFilters() {
            currentFilters.search = document.getElementById('search').value;
            currentFilters.status = document.getElementById('status-filter').value;
            currentFilters.sort_by = document.getElementById('sort-by').value;

            updateResetButton();
            loadBanners();
        }

        // Reset bộ lọc
        function resetFilters() {
            currentFilters = {
                search: '',
                status: '',
                sort_by: 'sort_order',
                sort_order: 'asc'
            };

            document.getElementById('search').value = '';
            document.getElementById('status-filter').value = '';
            document.getElementById('sort-by').value = 'sort_order';

            updateResetButton();
            loadBanners();
        }

        // Cập nhật nút reset
        function updateResetButton() {
            const resetBtn = document.getElementById('reset-filters');
            const hasFilters = currentFilters.search || currentFilters.status || currentFilters.sort_by !== 'sort_order';

            if (hasFilters) {
                resetBtn.classList.remove('hidden');
            } else {
                resetBtn.classList.add('hidden');
            }
        }

        // Load banners bằng AJAX
        function loadBanners(page = 1) {
            const params = new URLSearchParams({
                ...currentFilters,
                page: page
            });

            fetch(`{{ route('admin.banners.index') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('banners-table-body').innerHTML = data.html;
                    document.getElementById('banners-pagination').innerHTML = data.pagination;

                    // Cập nhật URL
                    const url = new URL(window.location);
                    url.search = params.toString();
                    window.history.pushState({}, '', url);
                })
                .catch(error => {
                    console.error('Error loading banners:', error);
                    showNotification('Có lỗi xảy ra khi tải dữ liệu!', 'error');
                });
        }

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
                        loadBanners();
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
                        loadBanners();
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
            // Tạo thông báo tạm thời
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

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Pagination
        function loadPage(page) {
            loadBanners(page);
        }
    </script>
@endpush
