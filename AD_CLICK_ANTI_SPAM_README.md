# 🚫 Hệ Thống Chống Spam Click Quảng Cáo

## 📋 Tổng Quan

Hệ thống này được thiết kế để ngăn chặn việc spam click quảng cáo, đảm bảo mỗi user chỉ được click 1 lần duy nhất cho mỗi chiến dịch quảng cáo của mỗi shop.

## ✨ Tính Năng Chính

### 🛡️ Chống Spam
- **Mỗi user chỉ click được 1 lần**: Sử dụng user ID hoặc session ID
- **Rate limiting theo IP**: Giới hạn 5 click/phút cho mỗi IP
- **Kiểm tra hành vi bất thường**: Phát hiện IP click quá nhiều trong thời gian ngắn
- **Cache optimization**: Sử dụng Redis/Cache để tối ưu performance

### 📊 Tracking & Analytics
- **Click tracking chi tiết**: Lưu thông tin user, IP, thời gian, user agent
- **Phân tích theo shop**: Track click theo từng shop và chiến dịch
- **Báo cáo real-time**: Hiển thị số liệu click và trạng thái

### 🔄 Quản Lý
- **Reset trạng thái**: Admin có thể reset click status
- **Session management**: Tự động lưu trạng thái đã click
- **Database optimization**: Index và foreign key constraints

## 🏗️ Kiến Trúc Hệ Thống

### Models
- **AdClick**: Model chính để lưu thông tin click
- **User**: Liên kết với click để track theo user
- **Shop**: Liên kết với click để track theo shop
- **AdsCampaign**: Liên kết với click để track theo chiến dịch

### Controllers
- **AdClickController**: Xử lý logic chính
  - `track()`: Track click và chống spam
  - `checkStatus()`: Kiểm tra trạng thái click
  - `resetStatus()`: Reset trạng thái (admin only)

### Middleware
- **AdClickTracking**: Middleware tự động track click
- **AdClickRateLimit**: Middleware chống rate limit

### Views
- **advertised_products.blade.php**: Hiển thị quảng cáo với trạng thái click
- **JavaScript**: Xử lý real-time và cập nhật UI

## 🚀 Cài Đặt & Sử Dụng

### 1. Chạy Migration
```bash
php artisan migrate
```

### 2. Cấu Hình Cache
Đảm bảo cache driver được cấu hình trong `.env`:
```env
CACHE_DRIVER=redis
# hoặc
CACHE_DRIVER=file
```

### 3. Routes
```php
// Ad Click Tracking Routes
Route::prefix('ad')->name('ad.')->group(function () {
    Route::get('/click', [AdClickController::class, 'track'])->name('click');
    Route::get('/status', [AdClickController::class, 'checkStatus'])->name('status');
    Route::post('/reset', [AdClickController::class, 'resetStatus'])->name('reset')->middleware('auth');
});
```

### 4. JavaScript
Include file `ad-click-tracker.js` vào layout:
```html
<script src="{{ asset('js/ad-click-tracker.js') }}"></script>
```

## 📱 Sử Dụng Trong View

### Hiển Thị Quảng Cáo Với Trạng Thái
```php
@php
    $userId = auth()->id() ?? session()->getId();
    $campaignId = $shopAds['all_campaigns']->first()['campaign']->id;
    $shopId = $shop->id;
    
    // Kiểm tra session trước
    $sessionKey = "ad_click_{$campaignId}_{$shopId}";
    $hasClicked = session()->has($sessionKey);
    
    // Nếu không có trong session, kiểm tra database
    if (!$hasClicked) {
        $hasClicked = \App\Models\AdClick::hasUserClicked($userId, $campaignId, $shopId);
    }
@endphp

@if(!$hasClicked)
    <a href="?ad_click_type=shop_detail&shop_id={{ $shopId }}&campaign_id={{ $campaignId }}" 
       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
        Chi tiết
    </a>
@else
    <span class="bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed">
        Đã xem
    </span>
@endif
```

### Link Quảng Cáo Sản Phẩm
```php
@if(!$hasClicked)
    <a href="?ad_click_type=product_detail&shop_id={{ $shopId }}&campaign_id={{ $campaignId }}&product_id={{ $product->id }}">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        <div class="bg-red-500 text-white text-xs px-1 py-0.5 rounded">
            Quảng cáo
        </div>
    </a>
@else
    <div class="relative">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="opacity-75">
        <div class="bg-gray-400 text-white text-xs px-1 py-0.5 rounded">
            Đã xem
        </div>
    </div>
@endif
```

## 🔧 API Endpoints

### Track Click
```
GET /ad/click?campaign_id={id}&shop_id={id}&click_type={type}&product_id={id}
```

**Parameters:**
- `campaign_id`: ID chiến dịch quảng cáo (required)
- `shop_id`: ID shop (required)
- `click_type`: Loại click (shop_detail/product_detail)
- `product_id`: ID sản phẩm (optional)

**Response:**
```json
{
    "success": true,
    "message": "Click quảng cáo đã được ghi nhận"
}
```

### Check Status
```
GET /ad/status?campaign_id={id}&shop_id={id}
```

**Response:**
```json
{
    "has_clicked": true,
    "message": "Đã xem quảng cáo"
}
```

### Reset Status (Admin Only)
```
POST /ad/reset
```

**Body:**
```json
{
    "campaign_id": 1,
    "shop_id": 1,
    "user_id": null  // null = reset tất cả, có giá trị = reset user cụ thể
}
```

## 🎯 Cách Hoạt Động

### 1. User Click Quảng Cáo
- JavaScript intercept click event
- Gọi API `/ad/status` để kiểm tra trạng thái
- Nếu chưa click → gọi API `/ad/click` để track
- Cập nhật UI thành "Đã xem"

### 2. Chống Spam
- **Rate Limit**: Giới hạn 5 click/phút cho mỗi IP
- **User Check**: Kiểm tra user đã click chưa
- **IP Check**: Kiểm tra IP có click quá nhiều không
- **Session**: Lưu trạng thái đã click vào session

### 3. Database Tracking
- Lưu thông tin click vào bảng `ad_clicks`
- Sử dụng cache để tối ưu performance
- Index để tối ưu query

## 🚨 Xử Lý Lỗi

### Rate Limit Exceeded
```json
{
    "error": "Quá nhiều yêu cầu! Vui lòng thử lại sau.",
    "retry_after": 60
}
```

### Already Clicked
```json
{
    "has_clicked": true,
    "message": "Bạn đã xem quảng cáo này rồi!"
}
```

### Suspicious Activity
```json
{
    "error": "Phát hiện hành vi bất thường!"
}
```

## 🔒 Bảo Mật

### Middleware Protection
- **Rate Limiting**: Chống spam theo IP
- **Authentication**: Một số endpoint yêu cầu đăng nhập
- **Authorization**: Chỉ admin mới được reset status

### Data Validation
- Validate tất cả input parameters
- Sanitize user input
- SQL injection protection

## 📈 Performance

### Cache Strategy
- **User Click Status**: Cache 1 giờ
- **IP Rate Limit**: Cache 1 phút
- **Click History**: Cache 24 giờ

### Database Optimization
- **Indexes**: Tối ưu query performance
- **Foreign Keys**: Đảm bảo data integrity
- **Batch Operations**: Xử lý nhiều record cùng lúc

## 🧪 Testing

### Unit Tests
```bash
php artisan test --filter=AdClickTest
```

### Manual Testing
1. Click quảng cáo lần đầu → Thành công
2. Click lại → Hiển thị "Đã xem"
3. Refresh trang → Vẫn hiển thị "Đã xem"
4. Đổi IP → Vẫn hiển thị "Đã xem" (theo user)

## 🔄 Maintenance

### Reset Cache
```bash
php artisan cache:clear
```

### Reset Database
```bash
php artisan migrate:refresh
```

### Monitor Logs
```bash
tail -f storage/logs/laravel.log
```

## 📞 Hỗ Trợ

Nếu gặp vấn đề hoặc cần hỗ trợ:
1. Kiểm tra logs trong `storage/logs/`
2. Kiểm tra cache configuration
3. Verify database connections
4. Contact development team

---

**Lưu ý**: Hệ thống này được thiết kế để bảo vệ shop khỏi spam click, đảm bảo tính công bằng và hiệu quả của quảng cáo.
