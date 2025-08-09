@extends('layouts.admin')

@section('title', 'Thống kê lượt xem sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thống kê lượt xem sản phẩm</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.product-view-stats.export') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($totalViews) }}</h3>
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
                                    <h3>{{ number_format($todayViews) }}</h3>
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
                                    <h3>{{ number_format($weekViews) }}</h3>
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
                                    <h3>{{ number_format($monthViews) }}</h3>
                                    <p>Lượt xem tháng này</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ thống kê theo ngày -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Thống kê lượt xem 7 ngày gần nhất</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="dailyStatsChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top sản phẩm được xem nhiều nhất -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Top sản phẩm được xem nhiều nhất</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Sản phẩm</th>
                                                    <th>Shop</th>
                                                    <th>Lượt xem</th>
                                                    <th>Hôm nay</th>
                                                    <th>Tuần này</th>
                                                    <th>Tháng này</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topViewedProducts as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($item['product']->images->count() > 0)
                                                                <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}" 
                                                                     alt="{{ $item['product']->name }}" 
                                                                     class="img-thumbnail mr-2" 
                                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                            @endif
                                                            <div>
                                                                <strong>{{ $item['product']->name }}</strong>
                                                                <br>
                                                                <small class="text-muted">SKU: {{ $item['product']->sku }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item['product']->shop->shop_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ number_format($item['view_count']) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">{{ number_format($item['product']->today_views ?? 0) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">{{ number_format($item['product']->week_views ?? 0) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">{{ number_format($item['product']->month_views ?? 0) }}</span>
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

                    <!-- Bộ lọc và tìm kiếm -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.product-view-stats.index') }}" method="GET" class="form-inline">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ $search }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('admin.product-view-stats.index') }}" method="GET" class="form-inline justify-content-end">
                                @if($search)
                                    <input type="hidden" name="search" value="{{ $search }}">
                                @endif
                                <select name="time_range" class="form-control mr-2">
                                    <option value="all" {{ $timeRange == 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
                                    <option value="today" {{ $timeRange == 'today' ? 'selected' : '' }}>Hôm nay</option>
                                    <option value="week" {{ $timeRange == 'week' ? 'selected' : '' }}>Tuần này</option>
                                    <option value="month" {{ $timeRange == 'month' ? 'selected' : '' }}>Tháng này</option>
                                </select>
                                <select name="sort_by" class="form-control mr-2">
                                    <option value="view_count" {{ $sortBy == 'view_count' ? 'selected' : '' }}>Sắp xếp theo lượt xem</option>
                                    <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Sắp xếp theo tên</option>
                                    <option value="today_views" {{ $sortBy == 'today_views' ? 'selected' : '' }}>Sắp xếp theo lượt xem hôm nay</option>
                                </select>
                                <select name="sort_order" class="form-control mr-2">
                                    <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                                    <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                                </select>
                                <button type="submit" class="btn btn-secondary">Lọc</button>
                            </form>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Shop</th>
                                    <th>Lượt xem</th>
                                    <th>Hôm nay</th>
                                    <th>Tuần này</th>
                                    <th>Tháng này</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail mr-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->shop->shop_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ number_format($product->view_count) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ number_format($product->today_views) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{ number_format($product->week_views) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ number_format($product->month_views) }}</span>
                                    </td>
                                    <td>
                                        @if($product->status == 'active')
                                            <span class="badge badge-success">Hoạt động</span>
                                        @else
                                            <span class="badge badge-secondary">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.product-view-stats.show', $product->id) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links() }}
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
    const ctx = document.getElementById('dailyStatsChart').getContext('2d');
    
    const dailyStats = @json($dailyStats);
    
    new Chart(ctx, {
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
});
</script>
@endsection
