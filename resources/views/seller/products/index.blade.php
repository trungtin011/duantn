@extends('layouts.seller_home')

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Sản phẩm</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách sản phẩm</div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" id="searchForm">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm sản phẩm" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                    <span>Trạng thái:</span>
                    <select name="status" id="statusFilter"
                        class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                        </option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Số lượng thấp
                        </option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng
                        </option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Lên lịch</option>
                    </select>
                </div>
                <button id="resetFilter" type="button"
                    class="border border-gray-300 text-xs text-white bg-red-500 px-3 py-2 rounded-md hover:bg-red-600 hover:text-white transition-colors"
                    style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
                <button id="delete-selected"
                    class="hidden bg-red-500 text-white px-4 py-2 rounded-md text-sm hover:bg-red-600">
                    Xóa đã chọn
                </button>
                <a href="{{ route('seller.products.create') }}"
                    class="h-[44px] text-[15px] bg-blue-500 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm sản phẩm
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" type="checkbox" />
                    </th>
                    <th class="py-3">Sản phẩm</th>
                    <th class="py-3">Mã sản phẩm</th>
                    <th class="py-3">Số lượng</th>
                    <th class="py-3">Giá</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @include('seller.products._table_body', ['products' => $products])
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $products->count() }} sản phẩm trên {{ $products->total() }} sản phẩm
            </div>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.select-item');
            const deleteSelectedButton = document.getElementById('delete-selected');
            const searchInput = document.querySelector('input[name="search"]');
            const statusFilter = document.getElementById('statusFilter');
            const searchForm = document.getElementById('searchForm');
            const tbody = document.querySelector('table tbody');
            const resetFilterButton = document.getElementById('resetFilter');

            // ✅ Prevent form submission
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitFilters();
                });
            }

            // ✅ Hiện/ẩn nút Xóa
            function toggleDeleteButton() {
                const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
                deleteSelectedButton.classList.toggle('hidden', !anyChecked);
            }

            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                toggleDeleteButton();
            });

            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    selectAllCheckbox.checked = Array.from(itemCheckboxes).every(c => c.checked);
                    toggleDeleteButton();
                });
            });

            // ✅ AJAX Filtering
            function submitFilters() {
                const params = new URLSearchParams();
                if (searchInput && searchInput.value) params.append('search', searchInput.value);
                if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);

                // Show/hide reset button
                const hasFilters = (searchInput && searchInput.value) || (statusFilter && statusFilter.value);
                if (hasFilters) {
                    resetFilterButton.style.display = 'flex';
                } else {
                    resetFilterButton.style.display = 'none';
                }

                fetch("{{ route('seller.products.ajax') }}?" + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        if (tbody) tbody.innerHTML = html;
                        // Update URL without full reload for UX
                        const currentUrl = new URL(window.location);
                        ['search', 'status'].forEach(key => currentUrl.searchParams.delete(key));
                        params.forEach((value, key) => currentUrl.searchParams.set(key, value));
                        window.history.replaceState({}, '', currentUrl.toString());
                    });
            }

            // Auto-submit on filter change
            if (statusFilter) {
                statusFilter.addEventListener('change', submitFilters);
            }

            // Debounced search
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(submitFilters, 500);
                });
            }

            // ✅ Reset Filter
            resetFilterButton.addEventListener('click', function() {
                // Clear filters
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';

                // Clear URL params
                const currentUrl = new URL(window.location);
                ['search', 'status'].forEach(key => currentUrl.searchParams.delete(key));
                window.history.replaceState({}, '', currentUrl.toString());

                // Hide reset button
                resetFilterButton.style.display = 'none';

                // Submit filters to refresh data
                submitFilters();
            });

            // Initial check for reset button visibility
            const hasInitialFilters = (searchInput && searchInput.value) || (statusFilter && statusFilter.value);
            if (hasInitialFilters) {
                resetFilterButton.style.display = 'flex';
            } else {
                resetFilterButton.style.display = 'none';
            }

            // ✅ Xóa hàng loạt bằng AJAX + SweetAlert
            deleteSelectedButton.addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll('.select-item:checked');
                if (selectedCheckboxes.length === 0) return;

                const ids = Array.from(selectedCheckboxes).map(cb => cb.closest('tr').dataset.productId);

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Xóa " + ids.length + " sản phẩm đã chọn!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa ngay',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('seller.products.destroyMultiple') }}", {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    ids: ids
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire('Thành công!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            })
                            .catch(err => {
                                Swal.fire('Lỗi!', 'Không thể xóa sản phẩm.', 'error');
                            });
                    }
                });
            });
        });
    </script>
@endsection
