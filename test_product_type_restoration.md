# Test Plan: Product Type State Restoration

## Mục tiêu
Kiểm tra việc khôi phục trạng thái loại sản phẩm khi tải lại trang sau khi có lỗi validation.

## Các trường hợp test

### Test Case 1: Khôi phục trạng thái "Sản phẩm đơn"
1. Chọn "Sản phẩm đơn"
2. Điền form với dữ liệu không hợp lệ (ví dụ: để trống tên sản phẩm)
3. Submit form
4. Kiểm tra rằng radio button "Sản phẩm đơn" vẫn được chọn
5. Kiểm tra rằng tab "Chi tiết sản phẩm" được hiển thị
6. Kiểm tra rằng tab "Giá & Tồn kho" và "Vận chuyển" được hiển thị
7. Kiểm tra rằng tab "Thuộc tính & Biến thể" bị ẩn

### Test Case 2: Khôi phục trạng thái "Sản phẩm có biến thể"
1. Chọn "Sản phẩm có biến thể"
2. Thêm thuộc tính và tạo biến thể
3. Điền form với dữ liệu không hợp lệ (ví dụ: để trống SKU biến thể)
4. Submit form
5. Kiểm tra rằng radio button "Sản phẩm có biến thể" vẫn được chọn
6. Kiểm tra rằng tab "Chi tiết sản phẩm" được hiển thị
7. Kiểm tra rằng tab "Thuộc tính & Biến thể" được hiển thị
8. Kiểm tra rằng tab "Giá & Tồn kho" và "Vận chuyển" bị ẩn
9. Kiểm tra rằng các thuộc tính và biến thể đã tạo vẫn được giữ lại

### Test Case 3: Chuyển đổi giữa các loại sản phẩm
1. Chọn "Sản phẩm đơn"
2. Điền một số thông tin
3. Chuyển sang "Sản phẩm có biến thể"
4. Kiểm tra rằng các tab thay đổi đúng
5. Chuyển lại "Sản phẩm đơn"
6. Kiểm tra rằng các tab thay đổi đúng

### Test Case 4: Tải trang lần đầu
1. Truy cập trang tạo sản phẩm mới
2. Kiểm tra rằng "Sản phẩm đơn" được chọn mặc định
3. Kiểm tra rằng tab "Chi tiết sản phẩm" được hiển thị

## Các thay đổi đã thực hiện

### 1. Cập nhật radio buttons trong Blade template
- Thêm `{{ old('product_type', 'simple') === 'simple' ? 'checked' : '' }}` cho radio "Sản phẩm đơn"
- Thêm `{{ old('product_type') === 'variant' ? 'checked' : '' }}` cho radio "Sản phẩm có biến thể"

### 2. Cải thiện JavaScript
- Cập nhật hàm `restoreProductTypeState()` để khôi phục trạng thái đúng
- Thêm debug logging để theo dõi quá trình khôi phục
- Cải thiện event listener cho radio buttons
- Thêm multiple checks để đảm bảo trạng thái được khôi phục đúng

### 3. Đảm bảo tính nhất quán
- Thêm checks ở nhiều điểm khác nhau trong quá trình khởi tạo
- Sử dụng `setTimeout` để đảm bảo DOM đã sẵn sàng
- Trigger change events để cập nhật UI

## Kết quả mong đợi
- Khi có lỗi validation, loại sản phẩm được chọn trước đó sẽ được khôi phục
- Các tab và trạng thái UI sẽ được cập nhật đúng theo loại sản phẩm
- Dữ liệu đã nhập (thuộc tính, biến thể) sẽ được giữ lại
- Không có lỗi JavaScript trong console
