@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Nhập sản phẩm từ Excel</h1>
            <p>
                Tải xuống <a href="#" class="text-primary" download>mẫu file Excel</a> để nhập sản phẩm.
                Đảm bảo file đúng định dạng và chứa đầy đủ cột theo mẫu.
            </p>

            {{-- Thông báo thành công / lỗi --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Form nhập file --}}
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="excel_file" class="form-label">Chọn file Excel</label>
                    <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                    @error('excel_file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Nhập sản phẩm ngay</button>
            </form>
        </div>
    </div>
</div>
@endsection
