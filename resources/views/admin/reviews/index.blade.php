@extends('layouts.admin')

@section('head')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/review.css') }}">
@endpush
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Reviews</h1>
    <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / Reviews List</div>
</div>

<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="input-group search-input-group" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search by product name">
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <select class="form-select form-select-admin" style="width: 150px;">
                <option selected>Rating: 5 Star</option>
                <option>All</option>
                <option>5 Star</option>
                <option>4 Star</option>
                <option>3 Star</option>
                <option>2 Star</option>
                <option>1 Star</option>
            </select>
            {{-- The "Add Product" button is not in the Reviews image, removing it --}}
            {{-- <a href="#" class="btn btn-primary" style="font-weight:500;font-size:1em;padding: 10px 24px; border-radius: 8px;">Add Product</a> --}}
        </div>
    </div>

    <div class="table-responsive admin-table-container">
        <table class="table align-middle mb-0 admin-table">
            <thead class="admin-table-thead">
                <tr>
                    <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                    <th>PRODUCT</th>
                    <th>CUSTOMER</th>
                    <th>RATING</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                {{-- Sample Review Data --}}
                <tr>
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Whitetails Women's Open Sky</span>
                        </div>
                    </td>
                    <td>Shahnewaz Sakil</td>
                    <td class="rating-stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </td>
                    <td>Jan 21, 2023 08:10 AM</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>School Bag for kids</span>
                        </div>
                    </td>
                    <td>Steve Smith</td>
                    <td class="rating-stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </td>
                    <td>Jan 25, 2023 10:30 AM</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Minimal Shoes for women</span>
                        </div>
                    </td>
                    <td>Stella Thomas</td>
                    <td class="rating-stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </td>
                    <td>Feb 01, 2023 11:20 PM</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Whitetails Women's Open Sky</span>
                        </div>
                    </td>
                    <td>Salim Rana</td>
                    <td class="rating-stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </td>
                    <td>Feb 05, 2023 05:50 PM</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted text-sm">Showing 10 Product of 120</div>
        <nav aria-label="Pagination navigation">
            <ul class="pagination pagination-sm mb-0 pagination-admin">
                <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item active"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">&gt;</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection 