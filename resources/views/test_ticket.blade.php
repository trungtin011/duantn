<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Ticket System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center mb-4">
                    <i class="fas fa-ticket-alt text-primary"></i>
                    Test Ticket System
                </h1>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Tạo Ticket mới</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.tickets.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Tiêu đề <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="subject" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Danh mục <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="category" required>
                                                    <option value="">Chọn danh mục</option>
                                                    <option value="technical">Kỹ thuật</option>
                                                    <option value="billing">Thanh toán</option>
                                                    <option value="general">Chung</option>
                                                    <option value="bug_report">Báo lỗi</option>
                                                    <option value="feature_request">Yêu cầu tính năng</option>
                                                    <option value="other">Khác</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="priority" class="form-label">Mức độ ưu tiên <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="priority" required>
                                                    <option value="">Chọn mức độ</option>
                                                    <option value="low">Thấp</option>
                                                    <option value="medium">Trung bình</option>
                                                    <option value="high">Cao</option>
                                                    <option value="urgent">Khẩn cấp</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Mô tả chi tiết <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" rows="4" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">File đính kèm (tùy chọn)</label>
                                        <input type="file" class="form-control" name="attachment"
                                            accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Gửi ticket
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Danh sách Ticket</h5>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('user.tickets.index') }}" class="btn btn-outline-primary w-100 mb-3">
                                    <i class="fas fa-list"></i> Xem tất cả ticket
                                </a>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Hướng dẫn:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Điền form bên trái để tạo ticket mới</li>
                                        <li>Click "Xem tất cả ticket" để quản lý ticket</li>
                                        <li>Hệ thống sẽ tự động tạo mã ticket</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
