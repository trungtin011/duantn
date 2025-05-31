@extends('layouts.admin')

@section('head')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/categories.css') }}">
@endpush
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Category</h1>
    <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / Category List</div>
</div>

<div class="row g-3">
    {{-- Left Column: Add/Edit Category Form --}}
    <div class="col-md-4">
        <div class="admin-card">
            <h5 class="mb-3 admin-card-title">Upload Image</h5>
            <div class="border rounded-3 d-flex justify-content-center align-items-center mb-3" style="height: 150px; background-color: #f4f6fa;">
                <div class="text-center text-muted">
                    <img src="https://via.placeholder.com/60" alt="Image Placeholder" class="mb-2">
                    <p class="mb-0 text-sm">Image size must be less than 5Mb</p>
                </div>
            </div>
            <div class="mb-3">
                <label for="uploadImage" class="form-label">Upload Image</label>
                <input class="form-control" type="file" id="uploadImage">
            </div>
            <div class="mb-3">
                <label for="categoryName" class="form-label">Name</label>
                <input type="text" class="form-control" id="categoryName" placeholder="Name">
            </div>
            <div class="mb-3">
                <label for="categorySlug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="categorySlug" placeholder="Slug">
            </div>
            <div class="mb-3">
                <label for="parentCategory" class="form-label">Parent</label>
                <select class="form-select form-select-admin" id="parentCategory">
                    <option selected>Electronics</option>
                    <option>Option 1</option>
                    <option>Option 2</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="categoryDescription" class="form-label">Description</label>
                <textarea class="form-control" id="categoryDescription" rows="3" placeholder="Description Here"></textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="createAsParent">
                <label class="form-check-label" for="createAsParent">Create As Parent Category</label>
            </div>
            <button type="submit" class="btn btn-admin-primary">Add Category</button>
        </div>
    </div>

    {{-- Right Column: Category List Table --}}
    <div class="col-md-8">
        <div class="admin-card">
            <div class="table-responsive admin-table-container">
                <table class="table align-middle mb-0 admin-table">
                    <thead class="admin-table-thead">
                        <tr>
                            <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>SLUG</th>
                            <th>ITEMS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Sample Category Data --}}
                        <tr>
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" class="me-2 avatar-sm">
                                    <span>Fashion</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/fashion</td>
                            <td>78</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr>
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" class="me-2 avatar-sm">
                                    <span>Bags</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/bags</td>
                            <td>105</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr>
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" class="me-2 avatar-sm">
                                    <span>Shoes</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/shoes</td>
                            <td>25</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr>
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" class="me-2 avatar-sm">
                                    <span>Jackes</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/jackes</td>
                            <td>62</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action-icon"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr>
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" class="me-2 avatar-sm">
                                    <span>Shirts</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/shirts</td>
                            <td>45</td>
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
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection 