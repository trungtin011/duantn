@extends('layouts.admin')

@section('title', 'Tạo thông báo')

@push('styles')
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fc;
            --dark-text: #2d3748;
            --border-color: #e2e8f0;
        }
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-text);
        }
        
        .admin-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        

        
        .admin-breadcrumb {
            font-size: 0.9rem;
            color: #718096;
        }
        
        .admin-breadcrumb-link {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .admin-breadcrumb-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .notification-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
            border: none;
        }
        
        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-title i {
            background: rgba(67, 97, 238, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #4a5568;
        }
        
        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }
        
        .btn-admin-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-admin-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        
        .form-note {
            font-size: 0.85rem;
            color: #718096;
            margin-top: 6px;
        }
        
        .priority-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-low {
            background-color: #e9f7ef;
            color: #2ecc71;
        }
        
        .badge-normal {
            background-color: #ebf5ff;
            color: #3498db;
        }
        
        .badge-high {
            background-color: #fdecea;
            color: #e74c3c;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                margin: 15px auto;
                padding: 0 15px;
            }
            
            .notification-card {
                padding: 20px;
            }
            
            .card-title {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="admin-container">

        @if ($errors->any())
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Đã xảy ra lỗi!</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="notification-card">
            <h2 class="card-title">
                <i class="fas fa-bell"></i>
                Tạo thông báo mới
            </h2>
            
            <form action="{{ route('admin.notifications.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sender_id" value="1">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề thông báo" required>
                        <div class="form-note">Tiêu đề ngắn gọn, dễ hiểu</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="type" class="form-label">Loại thông báo</label>
                        <select name="type" id="type" class="form-select">
                            <option value="promotion">Khuyến mãi</option>
                            <option value="system">Hệ thống</option>
                            <option value="security">Bảo mật</option>
                        </select>
                        <div class="form-note">Chọn loại thông báo phù hợp với nội dung</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content" name="content" rows="5" placeholder="Nhập nội dung chi tiết thông báo" required></textarea>
                    <div class="form-note">Nội dung rõ ràng, đầy đủ thông tin</div>
                </div>

                <div class="form-group">
                    <label for="image_path" class="form-label">Ảnh đính kèm</label>
                    <input type="file" class="form-control" id="image_path" name="image_path" accept="image/*" onchange="previewImage(event)">
                    <div class="form-note">Ảnh đính kèm (nếu có)</div>
                    <div id="image_preview" style="margin-top: 10px;">
                        <img id="preview_img" src="#" alt="Xem trước ảnh" style="display: none; max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;" />
                    </div>
                </div>
                <script>
                    function previewImage(event) {
                        const input = event.target;
                        const preview = document.getElementById('preview_img');
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.style.display = 'block';
                            }
                            reader.readAsDataURL(input.files[0]);
                        } else {
                            preview.src = '#';
                            preview.style.display = 'none';
                        }
                    }
                </script>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="receiver_type" class="form-label">Loại người nhận</label>
                        <select name="receiver_type" id="receiver_type" class="form-select">
                            <option value="user">Người dùng</option>
                            <option value="shop">Cửa hàng</option>
                            <option value="admin">Quản trị viên</option>
                            <option value="all">Tất cả</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="priority" class="form-label">Mức độ ưu tiên</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="low">Thấp <span class="priority-badge badge-low">Thấp</span></option>
                            <option value="normal">Bình thường</option>
                            <option value="high">Cao</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="direct_to" class="form-label">Gửi trực tiếp đến mục tiêu</label>
                    <input type="text" class="form-control" id="direct_to" name="direct_to" placeholder="Nhập ID mục tiêu cụ thể (nếu có)">
                    <div class="form-note">Nhập ID mục tiêu cụ thể để gửi thông báo trực tiếp (để trống để gửi cho tất cả)</div>
                </div>
                
                <button type="submit" class="btn btn-admin-primary">
                    <i class="fas fa-plus-circle"></i>
                    Tạo thông báo
                </button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const prioritySelect = document.getElementById('priority');
            
            prioritySelect.addEventListener('change', function() {
                updatePriorityBadge();
            });
            
            updatePriorityBadge();
            
            function updatePriorityBadge() {
                const selectedOption = prioritySelect.options[prioritySelect.selectedIndex];
                const value = selectedOption.value;
                
                // Xóa các class badge cũ
                Array.from(prioritySelect.options).forEach(option => {
                    option.classList.remove('priority-badge', 'badge-low', 'badge-normal', 'badge-high');
                });
                
                if (value === 'low') {
                    selectedOption.classList.add('priority-badge', 'badge-low');
                } else if (value === 'normal') {
                    selectedOption.classList.add('priority-badge', 'badge-normal');
                } else if (value === 'high') {
                    selectedOption.classList.add('priority-badge', 'badge-high');
                }
            }
        });
    </script>
@endpush

