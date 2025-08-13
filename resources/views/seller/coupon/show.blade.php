@extends('layouts.seller')

@section('title', 'Chi tiết Coupon')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết Coupon: {{ $coupon->code }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('seller.coupon.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="{{ route('seller.coupon.edit', $coupon->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Thông tin cơ bản</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Mã:</strong></td>
                                    <td>{{ $coupon->code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tên:</strong></td>
                                    <td>{{ $coupon->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mô tả:</strong></td>
                                    <td>{{ $coupon->description ?: 'Không có mô tả' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $coupon->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($coupon->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin giảm giá</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Loại giảm giá:</strong></td>
                                    <td>{{ ucfirst($coupon->discount_type) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Giá trị giảm:</strong></td>
                                    <td>
                                        @if($coupon->discount_type == 'percentage')
                                            {{ $coupon->discount_value }}%
                                        @else
                                            {{ number_format($coupon->discount_value) }} VNĐ
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Giảm tối đa:</strong></td>
                                    <td>
                                        {{ $coupon->max_discount_amount ? number_format($coupon->max_discount_amount) . ' VNĐ' : 'Không giới hạn' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Giá trị đơn hàng tối thiểu:</strong></td>
                                    <td>
                                        {{ $coupon->min_order_amount ? number_format($coupon->min_order_amount) . ' VNĐ' : 'Không yêu cầu' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Hạn chế rank:</strong></td>
                                    <td>{{ ucfirst($coupon->rank_limit) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Thống kê sử dụng -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Thống kê sử dụng</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-ticket-alt"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tổng số lần sử dụng</span>
                                            <span class="info-box-number">{{ $usageStats['total_used'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-boxes"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Số lượng còn lại</span>
                                            <span class="info-box-number">
                                                @if($usageStats['remaining_quantity'] == 'unlimited')
                                                    Vô hạn
                                                @else
                                                    {{ $usageStats['remaining_quantity'] }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-percentage"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tỷ lệ sử dụng</span>
                                            <span class="info-box-number">{{ $usageStats['usage_percentage'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-{{ $usageStats['is_out_of_stock'] ? 'danger' : 'success' }}">
                                            <i class="fas fa-{{ $usageStats['is_out_of_stock'] ? 'exclamation-triangle' : 'check-circle' }}"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Trạng thái</span>
                                            <span class="info-box-number">
                                                {{ $usageStats['is_out_of_stock'] ? 'Hết số lượng' : 'Còn khả dụng' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin thời gian -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Thông tin thời gian</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Ngày bắt đầu:</strong></td>
                                    <td>{{ $coupon->start_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày kết thúc:</strong></td>
                                    <td>{{ $coupon->end_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Thời gian còn lại:</strong></td>
                                    <td>
                                        @if($usageStats['is_expired'])
                                            <span class="text-danger">Đã hết hạn</span>
                                        @else
                                            {{ now()->diffForHumans($coupon->end_date, ['parts' => 2]) }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin số lượng</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tổng số lượng:</strong></td>
                                    <td>{{ $coupon->quantity == 0 ? 'Vô hạn' : $coupon->quantity }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Giới hạn tổng:</strong></td>
                                    <td>{{ $coupon->max_uses_total ?: 'Không giới hạn' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Giới hạn mỗi user:</strong></td>
                                    <td>{{ $coupon->max_uses_per_user ?: 'Không giới hạn' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Danh sách user đã sử dụng -->
                    <div class="row">
                        <div class="col-12">
                            <h5>Danh sách user đã sử dụng</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Số lần sử dụng</th>
                                            <th>Ngày sử dụng gần nhất</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($usersUsed as $userUsage)
                                        <tr>
                                            <td>{{ $userUsage->user->name ?? 'N/A' }}</td>
                                            <td>{{ $userUsage->user->email ?? 'N/A' }}</td>
                                            <td>{{ $userUsage->used_count }}</td>
                                            <td>{{ $userUsage->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Chưa có user nào sử dụng coupon này</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($usersUsed->hasPages())
                                <div class="d-flex justify-content-center">
                                    {{ $usersUsed->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        console.log('Chi tiết coupon loaded');
    });
</script>
@endpush

