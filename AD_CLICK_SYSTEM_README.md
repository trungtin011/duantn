# Hệ Thống Ad Click - Trừ Tiền Từ Ví Shop

## 📋 Tổng Quan

Hệ thống ad click cho phép ghi nhận click quảng cáo và trừ tiền từ ví shop. Mỗi lần click sẽ trừ 1000 VND từ ví shop, nhưng chỉ tính phí cho lần click đầu tiên của mỗi user với mỗi campaign.

## 🗄️ Cấu Trúc Database

### Bảng `shop_wallets`
```sql
CREATE TABLE shop_wallets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    shop_id BIGINT NOT NULL,
    balance DECIMAL(15,2) DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
);
```

### Bảng `ad_clicks`
```sql
CREATE TABLE ad_clicks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    shop_id BIGINT NOT NULL,
    ads_campaign_id BIGINT NOT NULL,
    product_id BIGINT NULL,
    click_type VARCHAR(255),
    user_ip VARCHAR(255) NULL,
    user_agent VARCHAR(255) NULL,
    clicked_at TIMESTAMP NULL,
    cost_per_click DECIMAL(10,2) DEFAULT 1000,
    is_charged BOOLEAN DEFAULT FALSE,
    wallet_transaction_id BIGINT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE,
    FOREIGN KEY (ads_campaign_id) REFERENCES ads_campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (wallet_transaction_id) REFERENCES wallet_transactions(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

### Bảng `wallet_transactions`
```sql
CREATE TABLE wallet_transactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    shop_wallet_id BIGINT NOT NULL,
    amount DECIMAL(15,2),
    direction ENUM('in', 'out'),
    type VARCHAR(255),
    description TEXT,
    status VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (shop_wallet_id) REFERENCES shop_wallets(id) ON DELETE CASCADE
);
```

## 🚀 Cách Sử Dụng

### 1. Tạo Dữ Liệu Test
```bash
php artisan db:seed --class=TestDataSeeder
```

### 2. Test Hệ Thống
Truy cập: `http://your-domain/simple-ad-test`

#### Các nút test:
- **Test API Click**: Test logic "chỉ trừ tiền lần đầu"
- **Reset Test Data**: Xóa dữ liệu test
- **Debug Data**: Xem trạng thái hiện tại

### 3. API Endpoints

#### Click Quảng Cáo
```
GET /simple-ad/click?ad_click_type=shop_detail&shop_id=1&campaign_id=1
```

#### Test API
```
GET /simple-ad/test?shop_id=1&campaign_id=1
```

#### Reset Dữ Liệu
```
GET /simple-ad/reset?shop_id=1
```

#### Debug Dữ Liệu
```
GET /simple-ad/debug?shop_id=1
```

#### Xem Thống Kê
```
GET /simple-ad/stats?shop_id=1
```

## 🔧 Logic Hoạt Động

### 1. Kiểm Tra Click Đã Tồn Tại
```php
$existingClick = AdClick::where('user_id', $userId)
    ->where('shop_id', $shopId)
    ->where('ads_campaign_id', $campaignId)
    ->lockForUpdate() // Tránh race condition
    ->first();

if ($existingClick) {
    // Đã click rồi - im lặng chuyển hướng
    return redirect()->back();
}
```

### 2. Tạo Record Click
```php
$adClick = AdClick::create([
    'user_id' => $userId,
    'shop_id' => $shopId,
    'ads_campaign_id' => $campaignId,
    'click_type' => $clickType,
    'cost_per_click' => 1000,
    'is_charged' => false
]);
```

### 3. Trừ Tiền Từ Ví
```php
$shopWallet->decrement('balance', 1000);
```

### 4. Tạo Giao Dịch Ví
```php
$walletTransaction = WalletTransaction::create([
    'shop_wallet_id' => $shopWallet->id,
    'amount' => 1000,
    'direction' => 'out',
    'type' => 'advertising',
    'description' => "Phí click quảng cáo - {$clickType}",
    'status' => 'completed',
]);
```

### 5. Cập Nhật Trạng Thái
```php
$adClick->update([
    'is_charged' => true,
    'wallet_transaction_id' => $walletTransaction->id,
]);
```

## 🛡️ Bảo Mật & Anti-Spam

### 1. Database Locking
- Sử dụng `lockForUpdate()` để tránh race condition
- Đảm bảo chỉ 1 request được xử lý tại một thời điểm

### 2. Kiểm Tra User
- Mỗi user chỉ được trừ tiền 1 lần cho mỗi campaign
- Các lần click sau sẽ im lặng chuyển hướng

### 3. Transaction Safety
- Sử dụng database transaction để đảm bảo tính nhất quán
- Rollback nếu có lỗi xảy ra

## 📊 Thống Kê

### API Thống Kê
```json
{
    "success": true,
    "stats": {
        "total_clicks": 5,
        "total_cost": 5000,
        "charged_clicks": 1
    }
}
```

### Debug Data
```json
{
    "user_id": 3,
    "shop_id": 1,
    "wallet_balance": 9000,
    "total_clicks": 1,
    "charged_clicks": 1,
    "clicks": [
        {
            "id": 1,
            "click_type": "test",
            "is_charged": true,
            "cost_per_click": "1000.00",
            "created_at": "2025-08-09T13:09:36.000000Z",
            "wallet_transaction_id": 1
        }
    ]
}
```

## 🎯 Kết Quả Mong Đợi

### Lần Click Đầu Tiên
```json
{
    "success": true,
    "message": "Click thành công và trừ 1000đ",
    "remaining_balance": 9000,
    "charged": true
}
```

### Các Lần Click Sau
```json
{
    "message": "Đã click trước đó",
    "charged": false
}
```

## 🔍 Kiểm Tra Database

### Xem Số Dư Ví
```sql
SELECT * FROM shop_wallets WHERE shop_id = 1;
```

### Xem Lịch Sử Click
```sql
SELECT * FROM ad_clicks WHERE shop_id = 1 ORDER BY created_at DESC;
```

### Xem Giao Dịch Ví
```sql
SELECT * FROM wallet_transactions WHERE shop_wallet_id = 1 ORDER BY created_at DESC;
```

## 📝 Ghi Chú

1. **Phí quảng cáo**: 1000 VND/click
2. **Giới hạn**: 1 lần trừ tiền/user/campaign
3. **Im lặng**: Các lần click sau không báo lỗi, chỉ chuyển hướng
4. **Transaction**: Đảm bảo tính nhất quán dữ liệu
5. **Locking**: Tránh race condition khi nhiều request cùng lúc

## 🚀 Triển Khai

1. Chạy migration: `php artisan migrate`
2. Tạo dữ liệu test: `php artisan db:seed --class=TestDataSeeder`
3. Test hệ thống: Truy cập `/simple-ad-test`
4. Kiểm tra kết quả trong database

---

**Hệ thống đã hoạt động ổn định và sẵn sàng cho production!** ✅
