@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/404.css') }}">
@endpush
@section('content')
<div class="container">
    <div class="error-container">
        <div class="breadcrumb">
            <div class="breadcrumb-home">
                Trang chủ
            </div>
            <div class="breadcrumb-error">
                404 Error
            </div>
        </div>
        
        <div class="error-content">
            <div class="error-title">
                404 Not Found
            </div>
            <div class="error-message">
                Không tìm thấy trang bạn đã truy cập. Bạn có thể quay lại trang chủ.
            </div>
        </div>

        <!-- Button -->
        <div class="back-button">
            Quay lại trang chủ
        </div>
    </div>
</div>
@endsection
