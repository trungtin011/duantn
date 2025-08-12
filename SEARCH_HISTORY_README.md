# Hệ Thống Lịch Sử Tìm Kiếm (Search History System)

## Tổng Quan

Hệ thống này cho phép lưu trữ và hiển thị lịch sử tìm kiếm của người dùng mà không cần tạo bảng database mới. Tất cả dữ liệu được lưu trong session của Laravel.

## Tính Năng

### 1. Lưu Lịch Sử Tìm Kiếm
- Tự động lưu các từ khóa tìm kiếm vào session
- Giới hạn 10 lịch sử gần nhất
- Loại bỏ trùng lặp tự động
- Không lưu khi tìm kiếm bằng AJAX

### 2. Gợi Ý Tìm Kiếm
- Hiển thị lịch sử tìm kiếm khi focus vào ô tìm kiếm
- Gợi ý từ lịch sử dựa trên từ khóa hiện tại
- Tìm kiếm sản phẩm nhanh và hiển thị kết quả

### 3. Quản Lý Lịch Sử
- Xóa từng item khỏi lịch sử
- Xóa toàn bộ lịch sử
- Hiển thị lịch sử theo thứ tự gần nhất

### 4. Giao Diện Người Dùng
- Dropdown gợi ý đẹp mắt với CSS tùy chỉnh
- Hỗ trợ phím mũi tên để điều hướng
- Responsive design
- Dark mode support

## Cài Đặt

### 1. Files Cần Thiết

```
app/Http/Controllers/SearchHistoryController.php
public/js/search-history.js
public/css/search-suggestions.css
routes/web.php (đã cập nhật)
resources/views/layouts/app.blade.php (đã cập nhật)
app/Http/Controllers/User/ProductController.php (đã cập nhật)
```

### 2. Routes

Các routes sau đã được thêm vào `routes/web.php`:

```php
// API Search History - Lịch sử tìm kiếm
Route::prefix('api/search-history')->name('api.search-history.')->group(function () {
    Route::post('/store', [SearchHistoryController::class, 'store'])->name('store');
    Route::get('/', [SearchHistoryController::class, 'index'])->name('index');
    Route::delete('/destroy', [SearchHistoryController::class, 'destroy'])->name('destroy');
    Route::delete('/clear', [SearchHistoryController::class, 'clear'])->name('clear');
    Route::get('/suggestions', [SearchHistoryController::class, 'suggestions'])->name('suggestions');
    Route::get('/quick-search', [SearchHistoryController::class, 'quickSearch'])->name('quick-search');
});
```

### 3. JavaScript

File `public/js/search-history.js` đã được include vào `app.blade.php`:

```html
<script src="{{ asset('js/search-history.js') }}"></script>
```

### 4. CSS

File `public/css/search-suggestions.css` đã được include vào `app.blade.php`:

```html
<link rel="stylesheet" href="{{ asset('css/search-suggestions.css') }}">
```

## Sử Dụng

### 1. Tự Động

Hệ thống hoạt động tự động khi:
- Người dùng tìm kiếm sản phẩm (không phải AJAX)
- Focus vào ô tìm kiếm
- Nhập từ khóa tìm kiếm

### 2. API Endpoints

#### Lưu lịch sử tìm kiếm
```http
POST /api/search-history/store
Content-Type: application/json

{
    "query": "từ khóa tìm kiếm"
}
```

#### Lấy lịch sử tìm kiếm
```http
GET /api/search-history
```

#### Lấy gợi ý từ lịch sử
```http
GET /api/search-history/suggestions?query=từ khóa
```

#### Tìm kiếm sản phẩm nhanh
```http
GET /api/search-history/quick-search?query=từ khóa
```

#### Xóa item khỏi lịch sử
```http
DELETE /api/search-history/destroy
Content-Type: application/json

{
    "query": "từ khóa cần xóa"
}
```

#### Xóa toàn bộ lịch sử
```http
DELETE /api/search-history/clear
```

### 3. JavaScript API

```javascript
// Khởi tạo hệ thống
const searchManager = new SearchHistoryManager();

// Lưu lịch sử tìm kiếm
await searchManager.saveSearchHistory('từ khóa');

// Lấy lịch sử tìm kiếm
await searchManager.loadSearchHistory();

// Hiển thị gợi ý
searchManager.showSearchHistory();
```

## Tùy Chỉnh

### 1. CSS

File `public/css/search-suggestions.css` có thể được tùy chỉnh để thay đổi:
- Màu sắc
- Kích thước
- Font chữ
- Hiệu ứng hover
- Dark mode

### 2. JavaScript

File `public/js/search-history.js` có thể được tùy chỉnh để thay đổi:
- Số lượng gợi ý hiển thị
- Thời gian debounce
- Logic xử lý sự kiện
- Giao diện hiển thị

### 3. Controller

File `SearchHistoryController.php` có thể được tùy chỉnh để thay đổi:
- Logic lưu trữ
- Số lượng lịch sử tối đa
- Cách xử lý dữ liệu
- Thêm các tính năng mới

## Lưu Ý

### 1. Session Storage
- Dữ liệu được lưu trong session Laravel
- Session sẽ bị mất khi người dùng đóng trình duyệt
- Có thể cấu hình session driver trong `config/session.php`

### 2. Performance
- Sử dụng debounce để tránh gọi API quá nhiều
- Cache lịch sử tìm kiếm trong memory
- Giới hạn số lượng gợi ý hiển thị

### 3. Security
- Tất cả API endpoints đều có CSRF protection
- Validate input data
- Sanitize output data

## Troubleshooting

### 1. Lỗi Thường Gặp

#### JavaScript không hoạt động
- Kiểm tra console browser để xem lỗi
- Đảm bảo file `search-history.js` được load
- Kiểm tra các element ID có đúng không

#### API không trả về dữ liệu
- Kiểm tra routes có được định nghĩa đúng không
- Kiểm tra controller có được tạo đúng không
- Kiểm tra session có hoạt động không

#### CSS không được áp dụng
- Kiểm tra file CSS có được include đúng không
- Kiểm tra đường dẫn file CSS
- Clear cache browser

### 2. Debug

Sử dụng file `test_search_history.html` để test các chức năng:
- Test lưu lịch sử
- Test lấy lịch sử
- Test gợi ý tìm kiếm
- Test tìm kiếm nhanh

## Tương Lai

Có thể mở rộng hệ thống bằng cách:
- Lưu lịch sử vào database để persistent
- Thêm analytics cho tìm kiếm
- Tích hợp với hệ thống recommendation
- Thêm tính năng export/import lịch sử
- Hỗ trợ multiple languages
