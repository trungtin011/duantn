@extends('layouts.admin')

@section('title', 'Danh mục trợ giúp')

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
            <div class="w-full md:w-[223px] relative">
                <input id="searchInput" name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tên danh mục" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </div>

            <div class="flex gap-4 items-center h-full">
                <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                    <span>Trạng thái:</span>
                    <select name="status" id="statusFilter"
                        class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>
                <a href="{{ route('help-category.create') }}"
                    class="h-[44px] text-[15px] bg-blue-600 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm danh mục
                </a>
            </div>
        </div>

        <div id="categoryTableWrapper">
            @include('admin.help_category.partials.table', ['categories' => $categories])
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const wrapper = document.getElementById('categoryTableWrapper');

    function buildQuery() {
        const params = new URLSearchParams();
        const search = searchInput.value.trim();
        const status = statusFilter.value;
        if (search) params.set('search', search);
        if (status) params.set('status', status);
        return params.toString();
    }

    function attachPaginationHandlers() {
        wrapper.querySelectorAll('a.page-link').forEach(a => {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                const url = new URL(this.href);
                const params = url.searchParams;
                fetchAjax(params.toString());
            });
        });
    }

    let debounceTimer;
    function debounceFetch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchAjax(buildQuery()), 300);
    }

    function fetchAjax(query) {
        fetch(`{{ route('help-category.ajax') }}?${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            wrapper.innerHTML = html;
            attachPaginationHandlers();
            const newUrl = `{{ route('help-category.index') }}` + (query ? `?${query}` : '');
            window.history.replaceState({}, '', newUrl);
        });
    }

    searchInput.addEventListener('input', debounceFetch);
    statusFilter.addEventListener('change', () => fetchAjax(buildQuery()));
    attachPaginationHandlers();
});
</script>
@endpush
