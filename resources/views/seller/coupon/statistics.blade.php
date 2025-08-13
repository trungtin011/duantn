@extends('layouts.seller')

@section('title', 'Thống kê Coupon')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thống kê Coupon</h3>
                </div>
                <div class="card-body">
                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_coupons'] }}</h3>
                                    <p>Tổng số coupon</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['active_coupons'] }}</h3>
                                    <p>Coupon đang hoạt động</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['total_uses'] }}</h3>
                                    <p>Tổng số lần sử dụng</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $stats['out_of_stock_coupons'] }}</h3>
                                    <p>Coupon hết số lượng</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coupon được sử dụng nhiều nhất -->
                    @if($stats['most_used_coupon'])
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Coupon được sử dụng nhiều nhất</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Mã:</strong> {{ $stats['most_used_coupon']->code }}</p>
                                            <p><strong>Tên:</strong> {{ $stats['most_used_coupon']->name }}</p>
                                            <p><strong>Số lần sử dụng:</strong> {{ $stats['most_used_coupon']->used_count }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Giá trị giảm:</strong> 
                                                @if($stats['most_used_coupon']->discount_type == 'percentage')
                                                    {{ $stats['most_used_coupon']->discount_value }}%
                                                @else
                                                    {{ number_format($stats['most_used_coupon']->discount_value) }} VNĐ
                                                @endif
                                            </p>
                                            <p><strong>Ngày tạo:</strong> {{ $stats['most_used_coupon']->created_at->format('d/m/Y') }}</p>
                                            <p><strong>Trạng thái:</strong> 
                                                <span class="badge badge-{{ $stats['most_used_coupon']->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($stats['most_used_coupon']->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Coupon gần đây -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Coupon gần đây</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Mã</th>
                                                    <th>Tên</th>
                                                    <th>Giá trị giảm</th>
                                                    <th>Số lần sử dụng</th>
                                                    <th>Ngày tạo</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stats['recent_coupons'] as $coupon)
                                                <tr>
                                                    <td>{{ $coupon->code }}</td>
                                                    <td>{{ $coupon->name }}</td>
                                                    <td>
                                                        @if($coupon->discount_type == 'percentage')
                                                            {{ $coupon->discount_value }}%
                                                        @else
                                                            {{ number_format($coupon->discount_value) }} VNĐ
                                                        @endif
                                                    </td>
                                                    <td>{{ $coupon->used_count }}</td>
                                                    <td>{{ $coupon->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $coupon->status == 'active' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($coupon->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
    // Có thể thêm chart hoặc biểu đồ ở đây
    $(document).ready(function() {
        console.log('Thống kê coupon loaded');
    });
</script>
@endpush

