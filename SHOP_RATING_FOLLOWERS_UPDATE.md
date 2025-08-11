# Cập Nhật Hiển Thị Số Sao Và Follow Trong Advertised Products

## 📋 Tổng Quan
Đã cập nhật file `advertised_products.blade.php` để hiển thị thông tin số sao và số follow của shop dựa trên 2 bảng:
- **Số sao**: Tính từ bảng `order_reviews` (trung bình rating)
- **Số follow**: Đếm từ bảng `shop_followers`

## 🔧 Các Thay Đổi Đã Thực Hiện

### 1. Cập Nhật View - Shop Chính
**File**: `resources/views/partials/advertised_products.blade.php`

#### Trước:
```blade
<div>
    <h3 class="font-semibold text-gray-800">{{ $firstShop->shop_name }}</h3>
    <p class="text-sm text-gray-600">{{ $firstCampaignName }}</p>
</div>
```

#### Sau:
```blade
<div>
    <h3 class="font-semibold text-gray-800">{{ $firstShop->shop_name }}</h3>
    <div class="flex items-center space-x-4 text-sm text-gray-600">
        <p>{{ $firstCampaignName }}</p>
        <div class="flex items-center space-x-1">
            <i class="fas fa-star text-yellow-400 text-xs"></i>
            <span class="text-xs">{{ number_format($firstShop->order_reviews_avg_rating ?? 0, 1) }}</span>
            <span class="text-xs text-gray-500">({{ $firstShop->order_reviews_count ?? 0 }} đánh giá)</span>
        </div>
        <div class="flex items-center space-x-1">
            <i class="fas fa-heart text-red-400 text-xs"></i>
            <span class="text-xs">{{ number_format($firstShop->followers_count ?? 0) }} follow</span>
        </div>
    </div>
</div>
```

### 2. Cập Nhật View - Modal Shop
**File**: `resources/views/partials/advertised_products.blade.php`

#### Trước:
```blade
<div>
    <h3 class="font-semibold text-gray-800">{{ $shop->shop_name }}</h3>
    <p class="text-sm text-gray-600">{{ $campaignName }}</p>
</div>
```

#### Sau:
```blade
<div>
    <h3 class="font-semibold text-gray-800">{{ $shop->shop_name }}</h3>
    <div class="flex items-center space-x-3 text-xs text-gray-600">
        <span>{{ $campaignName }}</span>
        <div class="flex items-center space-x-1">
            <i class="fas fa-star text-yellow-400 text-xs"></i>
            <span>{{ number_format($shop->order_reviews_avg_rating ?? 0, 1) }}</span>
            <span class="text-gray-500">({{ $shop->order_reviews_count ?? 0 }})</span>
        </div>
        <div class="flex items-center space-x-1">
            <i class="fas fa-heart text-red-400 text-xs"></i>
            <span>{{ number_format($shop->followers_count ?? 0) }}</span>
        </div>
    </div>
</div>
```

## 🗄️ Cấu Trúc Database

### Bảng `shop_followers`
```sql
CREATE TABLE shop_followers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    shopID BIGINT NOT NULL,
    followerID BIGINT NOT NULL,
    notifications_enabled BOOLEAN DEFAULT 1,
    followed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (shopID) REFERENCES shops(id) ON DELETE CASCADE,
    FOREIGN KEY (followerID) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY shop_followers_unique (shopID, followerID)
);
```

### Bảng `order_reviews`
```sql
CREATE TABLE order_reviews (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    shop_order_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    shop_id BIGINT NOT NULL,
    rating TINYINT DEFAULT 0,
    comment TEXT NULL,
    seller_reply TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (shop_order_id) REFERENCES shop_order(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
);
```

## 🔗 Relationship Trong Model Shop

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

## 📊 Eager Loading Trong Controllers

### HomeController.php
```php
$advertisedProductsByShop = AdsCampaignItem::with([
    'product.defaultImage', 
    'product.shop' => function($query) {
        $query->withCount('followers')
              ->withCount('orderReviews')
              ->withAvg('orderReviews', 'rating');
    }, 
    'adsCampaign.shop'
])
```

### ProductController.php
```php
'product.shop' => function($query) {
    $query->withCount('followers')
          ->withCount('orderReviews')
          ->withAvg('orderReviews', 'rating');
}
```

## 🎨 Giao Diện Hiển Thị

### Shop Chính
- **Tên shop**: `{{ $firstShop->shop_name }}`
- **Số sao**: ⭐ `{{ number_format($firstShop->order_reviews_avg_rating ?? 0, 1) }}` (X đánh giá)
- **Số follow**: ❤️ `{{ number_format($firstShop->followers_count ?? 0) }}` follow

### Modal Shop
- **Tên shop**: `{{ $shop->shop_name }}`
- **Số sao**: ⭐ `{{ number_format($shop->order_reviews_avg_rating ?? 0, 1) }}` (X)
- **Số follow**: ❤️ `{{ number_format($shop->followers_count ?? 0) }}`

## 🎯 Kết Quả

### ✅ Hoàn Thành
1. **Hiển thị số sao**: Tính từ trung bình rating trong bảng `order_reviews`
2. **Hiển thị số follow**: Đếm từ bảng `shop_followers`
3. **Giao diện responsive**: Hiển thị tốt trên mobile và desktop
4. **Icon trực quan**: ⭐ cho rating, ❤️ cho follow
5. **Format số**: Sử dụng `number_format()` để hiển thị đẹp

### 📱 Responsive Design
- **Desktop**: Hiển thị đầy đủ thông tin với spacing rộng
- **Mobile**: Compact layout với text size nhỏ hơn
- **Modal**: Giao diện tối ưu cho danh sách nhiều shop

## 🔮 Hướng Phát Triển

1. **Thêm tooltip**: Hiển thị chi tiết khi hover
2. **Thêm link**: Click vào số follow để xem danh sách followers
3. **Thêm link**: Click vào số sao để xem chi tiết đánh giá
4. **Animation**: Thêm hiệu ứng khi load dữ liệu
5. **Cache**: Cache thông tin rating và follow để tăng performance

---

**Đã hoàn thành việc thêm số sao và follow vào advertised products!** ✅
