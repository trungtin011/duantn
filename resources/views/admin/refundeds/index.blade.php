@extends('layouts.admin')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/refunded.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Quản lý yêu cầu hoàn hàng</h1>
        <div class="admin-breadcrumb">
            <a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Yêu cầu hoàn hàng
        </div>
    </div>

    @include('layouts.notification')

    <div class="row g-3">
        {{-- Right Column: Refund Requests Table --}}
        <div class="col-md-12">
            <div class="admin-card">
                <div class="mb-3 flex justify-between items-center">
                    <form action="{{ route('admin.refunds.index') }}" method="GET" class="flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tìm kiếm yêu cầu hoàn hàng..." class="form-control me-2" style="width: 200px;">
                        <button type="submit"
                            class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded-md">Tìm kiếm</button>
                    </form>
                </div>

                <div class="table-responsive admin-table-container">
                    <table class="w-full text-xs text-left text-gray-400 border-gray-100">
                        <thead class="text-gray-300 font-semibold border-b border-gray-100">
                            <tr>
                                <th class="w-6 py-3 pr-6">
                                    <input id="select-all" class="w-[18px] h-[18px]" aria-label="Select all refund requests"
                                        type="checkbox" />
                                </th>
                                <th class="py-3 w-[50px]">ID</th>
                                <th class="py-3">Mã đơn hàng</th>
                                <th class="py-3">Khách hàng</th>
                                <th class="py-3">Tổng tiền</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="py-3">Thời gian yêu cầu</th>
                                <th class="py-3 pr-6 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                            @forelse ($refundRequests as $request)
                                <tr>
                                    <td class="py-4 pr-6">
                                        <input class="select-item w-[18px] h-[18px]"
                                            aria-label="Select {{ $request->order_code }}" type="checkbox" />
                                    </td>
                                    <td class="py-4 text-[13px]">#{{ $request->id }}</td>
                                    <td class="py-4 text-[13px]">{{ $request->order_code }}</td>
                                    <td class="py-4 text-[13px]">{{ $request->user->fullname ?? 'Guest' }}</td>
                                    <td class="py-4 text-[13px]">{{ number_format($request->total_price, 2) }} VND</td>
                                    <td class="py-4 text-[13px]">{{ ucfirst($request->order_status) }}</td>
                                    <td class="py-4 text-[13px]">{{ $request->updated_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-4 pr-6 flex items-center justify-end gap-2">
                                        <div
                                            class="bg-[#50cd89] hover:bg-[#16A34A] text-white w-[37px] h-[35px] rounded-md flex items-center justify-center">
                                            <a href="{{ route('admin.refunds.show', $request->id) }}"
                                                class="transition-all duration-300">
                                                <i class="fas fa-eye" title="Xem chi tiết"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-400 py-4">Không có yêu cầu hoàn hàng nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted text-sm">Hiển thị {{ $refundRequests->count() }} trên {{ $refundRequests->total() }}</div>
                    {{ $refundRequests->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Select All Checkbox Functionality
            document.getElementById('select-all').addEventListener('change', function() {
                document.querySelectorAll('.select-item').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Individual Checkbox Functionality
            document.querySelectorAll('.select-item').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        document.getElementById('select-all').checked = false;
                    }
                });
            });
        </script>
    @endpush
@endsection