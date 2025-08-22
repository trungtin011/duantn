# Test Final: Product Type State Restoration

## Các thay đổi đã thực hiện

### 1. Blade Template (create.blade.php)
- ✅ Cập nhật radio buttons với `old()` helper:
  ```php
  {{ old('product_type', 'simple') === 'simple' ? 'checked' : '' }}
  {{ old('product_type') === 'variant' ? 'checked' : '' }}
  ```

### 2. JavaScript
- ✅ Thêm khởi tạo ngay từ đầu trong `DOMContentLoaded`:
  ```javascript
  const productType = '{{ old('product_type', 'simple') }}';
  const radioButton = document.querySelector(`input[name="product_type"][value="${productType}"]`);
  if (radioButton) {
      radioButton.checked = true;
  }
  ```

### 3. Test Route
- ✅ Tạo route test: `/test-product-type`
- ✅ Simulate old input data với `product_type = 'variant'`

## Cách test

### Test Case 1: Khôi phục trạng thái "variant"
1. Truy cập: `http://localhost:8000/test-product-type`
2. Kiểm tra rằng radio button "Sản phẩm có biến thể" được chọn
3. Kiểm tra rằng tab "Thuộc tính & Biến thể" được hiển thị
4. Kiểm tra rằng tab "Giá & Tồn kho" và "Vận chuyển" bị ẩn

### Test Case 2: Khôi phục trạng thái "simple"
1. Truy cập: `http://localhost:8000/seller/products/create`
2. Kiểm tra rằng radio button "Sản phẩm đơn" được chọn (mặc định)
3. Kiểm tra rằng tab "Chi tiết sản phẩm" được hiển thị

### Test Case 3: Test với validation error
1. Chọn "Sản phẩm có biến thể"
2. Điền form với lỗi (để trống tên sản phẩm)
3. Submit form
4. Kiểm tra rằng radio button vẫn được chọn đúng

## Debug Commands

### Kiểm tra console log
```javascript
// Mở Developer Tools > Console
// Tìm các log:
// - "Initial product type from old()"
// - "Radio button set to"
```

### Kiểm tra HTML
```html
<!-- Kiểm tra radio buttons có thuộc tính checked đúng không -->
<input type="radio" name="product_type" value="variant" checked>
```

## Kết quả mong đợi

- ✅ Radio button được chọn đúng theo `old('product_type')`
- ✅ Các tab được hiển thị/ẩn đúng theo loại sản phẩm
- ✅ Không có lỗi JavaScript trong console
- ✅ Form hoạt động bình thường sau khi khôi phục trạng thái

## Troubleshooting

Nếu vẫn không hoạt động:

1. **Kiểm tra session data**:
   ```php
   dd(session('_old_input'));
   ```

2. **Kiểm tra Blade rendering**:
   ```php
   {{ dd(old('product_type')) }}
   ```

3. **Kiểm tra JavaScript**:
   ```javascript
   console.log('Product type:', '{{ old('product_type', 'simple') }}');
   ```

4. **Kiểm tra DOM**:
   ```javascript
   console.log('Radio buttons:', document.querySelectorAll('input[name="product_type"]'));
   ```
