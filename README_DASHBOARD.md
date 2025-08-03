# Dashboard Admin - Hệ thống E-commerce

## Tổng quan

Dashboard admin được xây dựng cho hệ thống e-commerce tương tự Shopee, sử dụng Laravel 12 và Chart.js để hiển thị các thống kê quan trọng.

## Tính năng

### 1. Thống kê tổng quan
- **Tổng doanh thu**: Hiển thị tổng doanh thu từ tất cả đơn hàng đã thanh toán
- **Tổng số đơn hàng**: Số lượng đơn hàng trong hệ thống
- **Tổng sản phẩm đang bán**: Số sản phẩm có trạng thái active
- **Tổng người dùng**: Số lượng khách hàng đã đăng ký
- **Tổng shop đang hoạt động**: Số shop có trạng thái active
- **Bộ lọc theo khoảng thời gian**: Cho phép so sánh dữ liệu từ ngày này đến ngày khác

### 2. Biểu đồ thống kê
- **Biểu đồ doanh thu theo tháng**: Line chart hiển thị doanh thu và số đơn hàng theo từng tháng
- **Biểu đồ trạng thái đơn hàng**: Doughnut chart phân bố đơn hàng theo trạng thái

### 3. Bảng dữ liệu
- **Sản phẩm bán chạy nhất**: Top 10 sản phẩm có số lượng bán cao nhất
- **Đơn hàng gần đây**: 10 đơn hàng mới nhất trong hệ thống

### 4. Thống kê nhanh
- Doanh thu tháng hiện tại
- Số đơn hàng mới trong tháng
- Số người dùng mới đăng ký
- Giá trị đơn hàng trung bình

## Cấu trúc file

```
app/
├── Http/Controllers/Admin/
│   └── DashboardController.php          # Controller chính
├── Services/
│   └── DashboardService.php             # Service xử lý logic
├── Helpers/
│   └── DashboardHelper.php              # Helper functions
└── Models/
    ├── Order.php                        # Model đơn hàng
    ├── Product.php                      # Model sản phẩm
    ├── User.php                         # Model người dùng
    └── Shop.php                         # Model shop

resources/views/admin/
└── dashboard.blade.php                  # View dashboard

routes/
└── web.php                              # Route: admin.dashboard
```

## Cách sử dụng

### 1. Truy cập dashboard
```
GET /admin/dashboard
```

### 2. Sử dụng DashboardService
```php
use App\Services\DashboardService;

$dashboardService = new DashboardService();

// Lấy dữ liệu cho khoảng thời gian cụ thể
$data = $dashboardService->getDashboardData('2024-01-01', '2024-12-31');

// Hoặc lấy dữ liệu mặc định (tháng hiện tại)
$data = $dashboardService->getDashboardData();

// Truy cập các thống kê
$totalRevenue = $data['totalRevenue'];
$totalOrders = $data['totalOrders'];
$topProducts = $data['topSellingProducts'];
$dateRange = $data['dateRange'];
```

### 3. Các helper functions

#### DashboardHelper::formatMoney($amount)
Format số tiền theo định dạng VNĐ
```php
echo DashboardHelper::formatMoney(1000000); // 1.000.000 VNĐ
```

#### DashboardHelper::formatPercentage($value)
Format phần trăm
```php
echo DashboardHelper::formatPercentage(15.5); // 15.5%
```

#### DashboardHelper::getOrderStatusLabel($status)
Lấy label tiếng Việt cho trạng thái đơn hàng
```php
echo DashboardHelper::getOrderStatusLabel('pending'); // Chờ xác nhận
```

#### DashboardHelper::getOrderStatusBadge($status)
Lấy class badge cho trạng thái đơn hàng
```php
echo DashboardHelper::getOrderStatusBadge('delivered'); // success
```

### 3. Bộ lọc theo khoảng thời gian

#### Sử dụng bộ lọc
```javascript
// Áp dụng bộ lọc
function applyDateFilter() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    const url = new URL(window.location);
    url.searchParams.set('start_date', startDate);
    url.searchParams.set('end_date', endDate);
    
    window.location.href = url.toString();
}

// Reset bộ lọc
function resetDateFilter() {
    const url = new URL(window.location);
    url.searchParams.delete('start_date');
    url.searchParams.delete('end_date');
    
    window.location.href = url.toString();
}
```

### 4. Hiệu ứng và Animation

#### AOS Animation
```html
<div data-aos="fade-up" data-aos-delay="100">
    <!-- Content with animation -->
</div>
```

#### Số liệu animation
```javascript
function animateNumbers() {
    const numberElements = document.querySelectorAll('.card-value');
    numberElements.forEach(element => {
        // Animate from 0 to final value
        animateNumber(element, 0, finalValue, 2000);
    });
}
```

### 5. Tùy chỉnh biểu đồ

#### Thêm biểu đồ mới
```javascript
const newChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Label 1', 'Label 2'],
        datasets: [{
            label: 'Dataset',
            data: [1, 2],
            backgroundColor: 'rgba(75, 192, 192, 0.2)'
        }]
    }
});
```

#### Tùy chỉnh màu sắc
```php
$colors = DashboardHelper::getChartColors();
```

## Tối ưu hiệu suất

### 1. Caching
Có thể thêm cache cho các query phức tạp:
```php
$totalRevenue = Cache::remember('total_revenue', 3600, function () {
    return Order::whereIn('payment_status', ['paid', 'cod_paid'])
        ->sum('total_price');
});
```

### 2. Eager Loading
Sử dụng eager loading để tránh N+1 query:
```php
$recentOrders = Order::with(['user:id,fullname,email', 'items.product:id,name'])
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
```

### 3. Database Indexing
Đảm bảo có index cho các cột thường query:
- `orders.created_at`
- `orders.payment_status`
- `orders.order_status`
- `products.status`
- `users.role`

## Mở rộng

### 1. Thêm thống kê mới
1. Thêm method trong `DashboardController`
2. Truyền data vào view
3. Hiển thị trong template

### 2. Thêm biểu đồ mới
1. Tạo canvas element trong view
2. Thêm JavaScript để khởi tạo Chart.js
3. Truyền data từ controller

### 3. Export dữ liệu
Có thể thêm tính năng export Excel/PDF:
```php
use Maatwebsite\Excel\Facades\Excel;

public function export()
{
    return Excel::download(new DashboardExport, 'dashboard.xlsx');
}
```

## Troubleshooting

### 1. Biểu đồ không hiển thị
- Kiểm tra console browser để xem lỗi JavaScript
- Đảm bảo Chart.js đã được load
- Kiểm tra data truyền vào biểu đồ

### 2. Query chậm
- Sử dụng `EXPLAIN` để phân tích query
- Thêm index cho các cột thường query
- Sử dụng cache cho data ít thay đổi

### 3. Memory limit
- Tối ưu query để giảm memory usage
- Sử dụng pagination cho bảng dữ liệu lớn
- Chunk data khi xử lý

## Changelog

### Version 1.0.0
- Dashboard cơ bản với thống kê tổng quan
- Biểu đồ doanh thu và trạng thái đơn hàng
- Bảng sản phẩm bán chạy và đơn hàng gần đây
- Helper functions để format dữ liệu 