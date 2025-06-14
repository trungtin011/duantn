@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4 class="mb-0">📢 Gửi Báo Cáo</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('report.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="report_type" class="form-label fw-semibold">🎯 Loại báo cáo</label>
                            <select name="report_type" id="report_type" class="form-select" required>
                                <option value="" disabled selected>-- Chọn loại --</option>
                                <option value="product_violation">Sản phẩm vi phạm</option>
                                <option value="shop_violation">Shop vi phạm</option>
                                <option value="user_violation">Người dùng vi phạm</option>
                                <option value="fake_product">Hàng giả</option>
                                <option value="copyright">Vi phạm bản quyền</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="report_content" class="form-label fw-semibold">📝 Nội dung báo cáo</label>
                            <textarea name="report_content" id="report_content" rows="4" class="form-control" placeholder="Mô tả chi tiết về vấn đề bạn gặp..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label fw-semibold">🚨 Mức độ ưu tiên</label>
                            <select name="priority" class="form-select" id="priority">
                                <option value="medium">Trung bình</option>
                                <option value="low">Thấp</option>
                                <option value="high">Cao</option>
                                <option value="urgent">Khẩn cấp</option>
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_anonymous" class="form-check-input" id="anonymous">
                            <label class="form-check-label" for="anonymous">Gửi báo cáo ẩn danh</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                📤 Gửi báo cáo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
