# Hướng dẫn Sản phẩm nổi bật

## Tổng quan

Tính năng sản phẩm nổi bật đã được đơn giản hóa để xử lý trực tiếp trong controller. Hệ thống sẽ tự động chọn sản phẩm nổi bật dựa trên các tiêu chí cố định.

## Logic hiện tại

### Sản phẩm nổi bật
Kết hợp nhiều tiêu chí:
- Sản phẩm đã bán ít nhất 5 cái
- Sản phẩm mới trong 30 ngày gần đây
- Sản phẩm có đánh giá từ 4 sao trở lên
- Giới hạn hiển thị: 8 sản phẩm

### Sản phẩm bán chạy
- Chỉ hiển thị sản phẩm đã được bán ít nhất 1 cái
- Sắp xếp theo số lượng bán giảm dần
- Giới hạn hiển thị: 6 sản phẩm

### Shop Bán Chạy
- Chỉ hiển thị khi có shop đã bán được sản phẩm
- Nếu chưa có sản phẩm nào được bán, phần này sẽ bị ẩn hoàn toàn
- Hiển thị top shop theo doanh số bán hàng

## Cách tùy chỉnh

### Thay đổi logic trong controller

Để thay đổi logic, chỉnh sửa trực tiếp trong file `app/Http/Controllers/User/HomeController.php`:

```php
// Thay đổi số lượng sản phẩm nổi bật
->take(8) // Thay đổi số này

// Thay đổi tiêu chí sản phẩm bán chạy
->where('sold_quantity', '>', 0) // Thay đổi điều kiện này

// Thay đổi số lượng sản phẩm bán chạy
->take(6) // Thay đổi số này

// Thay đổi điều kiện hiển thị shop bán chạy
return $shop->products_count > 0 && ($shop->products_sum_sold_quantity ?? 0) > 0; // Chỉ lấy shops có products và đã bán được sản phẩm
```

## Lợi ích

1. **Đơn giản**: Logic xử lý trực tiếp trong controller, dễ hiểu và sửa đổi
2. **Hiệu quả**: Không cần file config phức tạp
3. **Nhanh**: Không cần đọc cấu hình từ file
4. **Dễ bảo trì**: Tất cả logic tập trung ở một nơi

## Lưu ý

- Để thay đổi logic, chỉ cần sửa trực tiếp trong controller
- Không cần clear cache khi thay đổi
- Các sản phẩm chỉ hiển thị khi có trạng thái 'active' và còn hàng
- Hệ thống sẽ tự động tính toán giá hiển thị cho sản phẩm có biến thể
