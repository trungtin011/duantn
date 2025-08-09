@extends('layouts.admin')

@section('title', 'Chi tiết lượt xem sản phẩm: ' . $product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye"></i> 
                        Chi tiết lượt xem sản phẩm: {{ $product->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.product-view-stats.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thông tin sản phẩm -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h5>Thông tin sản phẩm</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>ID:</strong></td>
                                    <td>{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tên sản phẩm:</strong></td>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>SKU:</strong></td>
                                    <td>{{ $product->sku }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Shop:</strong></td>
                                    <td>{{ $product->shop->shop_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        @if($product->status == 'active')
                                            <span class="badge badge-success">Hoạt động</span>
                                        @else
                                            <span class="badge badge-secondary">Không hoạt động</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid rounded" 
                                     style="max-width: 200px;">
                            @endif
                        </div>
                    </div>

                    <!-- Thống kê lượt xem -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Thống kê lượt xem</h5>
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ number_format($viewStats['total']) }}</h3>
                                            <p>Tổng lượt xem</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ number_format($viewStats['today']) }}</h3>
                                            <p>Lượt xem hôm nay</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-day"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ number_format($viewStats['week']) }}</h3>
                                            <p>Lượt xem tuần này</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-week"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ number_format($viewStats['month']) }}</h3>
                                            <p>Lượt xem tháng này</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ thống kê theo ngày -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Thống kê lượt xem 30 ngày gần nhất</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="dailyStatsChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thống kê theo User Agent -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Thống kê theo User Agent</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>User Agent</th>
                                                    <th>Số lượt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($userAgentStats as $stat)
                                                <tr>
                                                    <td>
                                                        <small class="text-muted">{{ Str::limit($stat->user_agent, 80) }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ number_format($stat->count) }}</span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center">Không có dữ liệu</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Biểu đồ User Agent</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="userAgentChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách lượt xem gần đây -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Lượt xem gần đây (50 lượt cuối)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Thời gian</th>
                                                    <th>User</th>
                                                    <th>IP Address</th>
                                                    <th>User Agent</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentViews as $view)
                                                <tr>
                                                    <td>{{ $view->viewed_at->format('d/m/Y H:i:s') }}</td>
                                                    <td>
                                                        @if($view->user)
                                                            <span class="badge badge-info">{{ $view->user->name }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">Guest</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <code>{{ $view->ip_address }}</code>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ Str::limit($view->user_agent, 60) }}</small>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                                </tr>
                                                @endforelse
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ thống kê theo ngày
    const dailyCtx = document.getElementById('dailyStatsChart').getContext('2d');
    const dailyStats = @json($dailyStats);
    
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyStats.map(item => item.date),
            datasets: [{
                label: 'Lượt xem',
                data: dailyStats.map(item => item.count),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Thống kê lượt xem theo ngày'
                }
            }
        }
    });

    // Biểu đồ User Agent
    const userAgentCtx = document.getElementById('userAgentChart').getContext('2d');
    const userAgentStats = @json($userAgentStats);
    
    new Chart(userAgentCtx, {
        type: 'doughnut',
        data: {
            labels: userAgentStats.map(item => Str::limit(item->user_agent, 30)),
            datasets: [{
                data: userAgentStats.map(item => item.count),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40',
                    '#FF6384',
                    '#C9CBCF'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Phân bố User Agent'
                }
            }
        }
    });
});
</script>
@endsection
