@extends('layouts.admin')

@section('title', 'Chi tiết yêu cầu hoàn hàng')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/refunded.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Chi tiết yêu cầu hoàn hàng</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('admin.refunds.index') }}" class="admin-breadcrumb-link">Yêu cầu hoàn hàng</a> / Chi tiết
        </div>
    </div>

    @include('layouts.notification')

    <div class="row g-3">
        <div class="col-md-12">
            <div class="admin-card">
                <div class="card-header">
                    Đơn hàng #{{ $order->order_code }}
                </div>
                <div class="card-body">
                    <h5 class="mb-3">Thông tin đơn hàng</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Khách hàng:</strong> {{ $order->user->fullname ?? 'Khách vãng lai' }}</p>
                            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 2) }} VND</p>
                            <p><strong>Trạng thái thanh toán:</strong> {{ ucfirst($order->payment_status) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Trạng thái đơn hàng:</strong> {{ ucfirst($order->order_status) }}</p>
                            <p><strong>Thời gian yêu cầu:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <h5 class="mb-3">Sản phẩm trong đơn hàng</h5>
                    <div class="table-responsive admin-table-container">
                        <table class="w-full text-xs text-left text-gray-400 border-gray-100">
                            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                                <tr>
                                    <th class="py-3">Sản phẩm</th>
                                    <th class="py-3">Số lượng</th>
                                    <th class="py-3">Đơn giá</th>
                                    <th class="py-3">Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="py-4 text-[13px]">{{ $item->product_name }}</td>
                                        <td class="py-4 text-[13px]">{{ $item->quantity }}</td>
                                        <td class="py-4 text-[13px]">{{ number_format($item->unit_price, 2) }} VND</td>
                                        <td class="py-4 text-[13px]">{{ number_format($item->total_price, 2) }} VND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mb-3 mt-4">Lịch sử trạng thái</h5>
                    <ul class="list-group">
                        @foreach ($order->orderStatusHistory as $history)
                            <li class="list-group-item text-[13px]">
                                {{ $history->description }} ({{ $history->created_at->format('d/m/Y H:i') }})
                                @if ($history->note)
                                    <span class="text-muted">: {{ $history->note }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    @if ($order->payment_status !== 'refunded' && $order->order_status === 'refunded')
                        <h5 class="mb-3 mt-4">Xử lý yêu cầu hoàn hàng</h5>
                        <form action="{{ route('admin.refunds.update', $order->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Hành động</label>
                                <select name="action" class="form-control" required>
                                    <option value="approve">Phê duyệt</option>
                                    <option value="reject">Từ chối</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="4" placeholder="Nhập ghi chú nếu có"></textarea>
                            </div>
                            <button type="submit" class="bg-[#28BCF9] hover:bg-[#3DA5F7] text-white py-2 px-4 rounded-md transition-all duration-300">
                                Gửi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
        </div>
    </div>
@endsection