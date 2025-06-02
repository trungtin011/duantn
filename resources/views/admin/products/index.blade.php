@extends('layouts.admin')

@section('head')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
@endpush
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Products</h1>
    <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / Product List</div>
</div>

<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="input-group search-input-group" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search by product name">
            </div>
            <select class="form-select form-select-admin" style="width: 150px;">
                <option selected>Status: Active</option>
                <option>All</option>
                <option>Pending</option>
                <option>Disabled</option>
                <option>In Active</option>
                <option>Scheduled</option>
                <option>Low Stock</option>
                <option>Out of Stock</option>
            </select>
        </div>
        <a href="#" class="btn btn-admin-primary">Add Product</a>
    </div>

    <div class="table-responsive admin-table-container">
        <table class="table align-middle mb-0 admin-table">
            <thead class="admin-table-thead">
                <tr>
                    <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                    <th style="padding-left: 8px;">PRODUCT</th>
                    <th>SKU</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>RATING</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Whitetails Women's Open Sky</span>
                        </div>
                    </td>
                    <td>#479063DR</td>
                    <td>37</td>
                    <td>$171.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 5</td>
                    <td><span class="badge rounded-pill badge-admin badge-active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Simple Modern School Boys</span>
                        </div>
                    </td>
                    <td>#DF7B2B64</td>
                    <td>124</td>
                    <td>$98.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.5</td>
                    <td><span class="badge rounded-pill badge-admin badge-inactive">In Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Women's Essentials Convertible</span>
                        </div>
                    </td>
                    <td>#373F9567</td>
                    <td>05</td>
                    <td>$56.99</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4</td>
                    <td>
                         <span class="badge rounded-pill badge-admin badge-scheduled">Scheduled</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Calvin Klein Gabrianna Novelty</span>
                        </div>
                    </td>
                    <td>#C174E363</td>
                    <td>00</td>
                    <td>$340.18</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.8</td>
                    <td><span class="badge rounded-pill badge-admin badge-out-of-stock">Out Of Stock</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Tommy Hilfiger Women's Jaden</span>
                        </div>
                    </td>
                    <td>#26BC663E</td>
                    <td>57</td>
                    <td>$224.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.2</td>
                    <td><span class="badge rounded-pill badge-admin badge-active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Govicta Men's Shoes Leather</span>
                        </div>
                    </td>
                    <td>#AD6ACDB9</td>
                    <td>45</td>
                    <td>$47.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.7</td>
                    <td><span class="badge rounded-pill badge-admin badge-active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Legendary Whitetails Men's.</span>
                        </div>
                    </td>
                    <td>#3F6CBB65</td>
                    <td>78</td>
                    <td>$29.99</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.1</td>
                    <td><span class="badge rounded-pill badge-admin badge-scheduled">Scheduled</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr>
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" class="me-3 product-img-table">
                            <span>Backpack, School Bag.</span>
                        </div>
                    </td>
                    <td>#8054F82C</td>
                    <td>104</td>
                    <td>$99.99</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.9</td>
                    <td><span class="badge rounded-pill badge-admin badge-active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted text-sm">Showing 10 Product of 120</div>
        <nav aria-label="Pagination navigation">
            <ul class="pagination pagination-sm mb-0 pagination-admin">
                <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
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