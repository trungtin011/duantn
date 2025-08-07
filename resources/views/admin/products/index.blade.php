@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Sản phẩm</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách sản phẩm</div>
    </div>

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('admin.products.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm theo tên sản phẩm" type="text" value="{{ request('search') }}" />
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="shop_id" value="{{ request('shop_id') }}">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Số lượng thấp</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Lên lịch</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Cửa hàng:</span>
                        <select name="shop_id" id="shopFilter"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả cửa hàng</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->shop_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button id="approve-selected"
                    class="hidden bg-green-500 text-white px-4 py-2 rounded-md text-sm hover:bg-green-600">
                    Duyệt đã chọn
                </button>
                <a href="{{ route('admin.products.select-shop') }}"
                    class="h-[44px] text-[15px] bg-blue-600 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm sản phẩm
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Chọn tất cả sản phẩm" type="checkbox" />
                    </th>
                    <th class="py-3">Sản phẩm</th>
                    <th class="py-3">Cửa hàng</th>
                    <th class="py-3">Mã sản phẩm</th>
                    <th class="py-3">Số lượng</th>
                    <th class="py-3">Giá</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($products as $product)
                    <tr data-product-id="{{ $product->id }}">
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $product->name }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 flex items-center gap-4">
                            <img alt="{{ $product->name }} product image" class="w-10 h-10 rounded-md object-cover"
                                height="40" src="{{ $product->image_url }}" width="40" />
                            <span class="font-semibold text-[13px]">
                                {{ $product->name }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">
                            {{ $product->shop->shop_name ?? 'N/A' }}
                        </td>
                        <td class="py-4 text-[13px]">{{ $product->sku }}</td>
                        <td class="py-4 text-[13px]">
                            {{ $product->stock_total }}
                            @if ($product->stock_total <= 5 && $product->stock_total > 0)
                                <span
                                    class="inline-block bg-orange-100 text-orange-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                    Số lượng thấp
                                </span>
                            @elseif ($product->stock_total == 0)
                                <span
                                    class="inline-block bg-red-100 text-red-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                    Hết hàng
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-[13px]">{{ number_format($product->sale_price, 2) }}</td>
                        <td class="py-4">
                            <span
                                class="inline-block 
                                {{ $product->status == 'active' 
                                    ? 'bg-green-100 text-green-600' 
                                    : ($product->status == 'pending' 
                                        ? 'bg-yellow-100 text-yellow-600' 
                                        : ($product->status == 'inactive' 
                                            ? 'bg-red-100 text-red-600' 
                                            : 'bg-blue-100 text-blue-600')) }} 
                                text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $product->status == 'pending' ? 'Chờ duyệt' : ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            @if($product->status == 'pending')
                                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" aria-label="Duyệt {{ $product->name }}"
                                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md focus:outline-none">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" aria-label="Từ chối {{ $product->name }}"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Xóa {{ $product->name }}"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($products->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-4">
                            @if(request('search') || request('status') || request('shop_id'))
                                Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại
                            @else
                                Không tìm thấy sản phẩm nào
                            @endif
                        </td>
                    </tr>
                @endif
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
            const approveSelectedButton = document.getElementById('approve-selected');
            const statusFilter = document.getElementById('statusFilter');
            const shopFilter = document.getElementById('shopFilter');

            // Hiện/ẩn nút Duyệt
            function toggleApproveButton() {
                const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
                approveSelectedButton.classList.toggle('hidden', !anyChecked);
            }

            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                toggleApproveButton();
            });

            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    selectAllCheckbox.checked = Array.from(itemCheckboxes).every(c => c.checked);
                    toggleApproveButton();
                });
            });

            // Tự động submit khi thay đổi filter
            function submitFilters() {
                const searchValue = document.querySelector('input[name="search"]').value;
                const statusValue = statusFilter.value;
                const shopValue = shopFilter.value;
                
                const params = new URLSearchParams();
                if (searchValue) params.append('search', searchValue);
                if (statusValue) params.append('status', statusValue);
                if (shopValue) params.append('shop_id', shopValue);
                
                window.location.href = "{{ route('admin.products.index') }}?" + params.toString();
            }

            statusFilter.addEventListener('change', submitFilters);
            shopFilter.addEventListener('change', submitFilters);

            // Duyệt hàng loạt
            approveSelectedButton.addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll('.select-item:checked');
                if (selectedCheckboxes.length === 0) return;

                const ids = Array.from(selectedCheckboxes).map(cb => cb.closest('tr').dataset.productId);

                if (confirm('Bạn có chắc muốn duyệt ' + ids.length + ' sản phẩm đã chọn?')) {
                    fetch("{{ route('admin.products.approveMultiple') }}", {
                        method: 'POST',
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
                        alert(data.message);
                        location.reload();
                    })
                    .catch(err => {
                        alert('Lỗi: Không thể duyệt sản phẩm.');
                    });
                }
            });
        });
    </script>
@endsection
