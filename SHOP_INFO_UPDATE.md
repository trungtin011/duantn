# Cập nhật hiển thị thông tin Shop trong Advertised Products

## Tổng quan
Đã cập nhật hệ thống để hiển thị thông tin shop từ các bảng riêng biệt:
- **Tên shop và avatar**: Từ bảng `shops` (shop_name, shop_logo)
- **Số sao**: Tính toán từ bảng `order_reviews` (trung bình rating)
- **Số followers**: Đếm từ bảng `shop_followers`
- **Hiển thị nhiều shop**: Hỗ trợ hiển thị tối đa 3 shop quảng cáo
- **Nút "Xem thêm"**: Khi có nhiều shop, hiển thị modal với tất cả shop

## Các thay đổi đã thực hiện

### 1. Cập nhật HomeController
**File**: `app/Http/Controllers/User/HomeController.php`

- Thêm eager loading cho shop với `withCount('followers')`, `withCount('orderReviews')`, `withAvg('orderReviews', 'rating')`
- Tính toán thông tin thực tế từ các bảng riêng biệt
- Gán thông tin vào shop object trước khi trả về view

### 2. Cập nhật ProductController
**File**: `app/Http/Controllers/User/ProductController.php`

- Thêm eager loading tương tự cho shop trong phương thức search
- Cập nhật phương thức `addProductToShopAds()` để tính toán thông tin shop

### 3. Cập nhật View
**File**: `resources/views/partials/advertised_products.blade.php`

- Sử dụng `$shop->shop_name` thay vì `$shop->name`
- Sử dụng `$shop->shop_logo` thay vì `$shop->logo`
- Hiển thị rating và followers từ dữ liệu đã được tính toán
- Hiển thị shop đầu tiên làm chính
- Thêm nút "Xem thêm" khi có nhiều shop
- Modal hiển thị tất cả shop quảng cáo với giao diện responsive

## Cấu trúc dữ liệu

### Bảng shops
```sql
- shop_name: Tên shop
- shop_logo: Logo shop
- shop_rating: Rating (không sử dụng, thay bằng order_reviews)
- total_followers: Số followers (không sử dụng, thay bằng shop_followers)
```

### Bảng shop_followers
```sql
- shopID: ID của shop
- followerID: ID của user theo dõi
- followed_at: Thời gian theo dõi
```

### Bảng order_reviews
```sql
- shop_id: ID của shop
- rating: Đánh giá từ 1-5 sao
- comment: Bình luận
```

## Relationship trong Model Shop

```php
// Đếm số followers từ bảng shop_followers
public function followers()
{
    return $this->belongsToMany(User::class, 'shop_followers', 'shopID', 'followerID')
        ->withTimestamps();
}

// Lấy reviews từ bảng order_reviews
public function orderReviews()
{
    return $this->hasMany(OrderReview::class, 'shop_id');
}
```

## Cách sử dụng

### Trong Controller
```php
$shop = Shop::withCount('followers')
    ->withCount('orderReviews')
    ->withAvg('orderReviews', 'rating')
    ->find($shopId);

// Tính toán thông tin thực tế
$actualRating = $shop->order_reviews_avg_rating ?? 0;
$actualFollowers = $shop->followers_count ?? 0;

// Gán vào shop object
$shop->shop_rating = round($actualRating, 1);
$shop->total_followers = $actualFollowers;
```

### Trong View
```blade
<img src="{{ $shop->shop_logo ? Storage::url($shop->shop_logo) : asset('images/default_shop_logo.png') }}" />
<h3>{{ $shop->shop_name }}</h3>
<span>{{ number_format($shop->shop_rating, 1) }}</span>
<span>{{ number_format($shop->total_followers) }} Followers</span>
```

## Tính năng hiển thị nhiều shop

### Cách hoạt động
1. **Shop chính**: Hiển thị shop đầu tiên với đầy đủ thông tin và sản phẩm
2. **Nút "Xem thêm"**: Xuất hiện khi có nhiều hơn 1 shop quảng cáo
3. **Modal**: Hiển thị tất cả shop quảng cáo với giao diện compact

### Giao diện Modal
- **Header**: Tiêu đề "Tất cả shop quảng cáo" với nút đóng
- **Shop list**: Mỗi shop hiển thị:
  - Logo và tên shop
  - Rating và số followers
  - 4 sản phẩm quảng cáo
  - Nút "Chi tiết" để xem shop
- **Responsive**: Tự động điều chỉnh layout cho mobile

### JavaScript Functions
```javascript
// Mở modal
function showMoreAds() {
    document.getElementById('adsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Đóng modal
function closeAdsModal() {
    document.getElementById('adsModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
```

## Test Route
Đã thêm route test để kiểm tra thông tin shop:
```
GET /test-shop-info/{shopId}
```

Trả về JSON với thông tin đầy đủ của shop bao gồm:
- Thông tin cơ bản từ bảng shops
- Số followers từ bảng shop_followers
- Rating trung bình từ bảng order_reviews

## Lưu ý
- Đảm bảo các bảng `shop_followers` và `order_reviews` có dữ liệu
- Rating được làm tròn đến 1 chữ số thập phân
- Số followers được format với dấu phẩy ngăn cách hàng nghìn
- Logo shop được lưu trong storage với đường dẫn `shop_logos/`
- Hệ thống hiển thị tối đa 3 shop quảng cáo
- Modal có thể đóng bằng cách click bên ngoài hoặc nhấn ESC
- Giao diện responsive cho mobile và desktop
