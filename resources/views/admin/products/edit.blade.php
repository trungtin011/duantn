@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Chỉnh sửa sản phẩm</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            id="product-form">
            @csrf
            @method('PUT')
            <!-- Meta Title -->
            <div class="form-group">
                <label>Tiêu đề SEO (Meta Title) <span id="meta-title-count">0/60</span></label>
                <input type="text" name="meta_title" id="meta-title" class="form-control"
                    value="{{ old('meta_title', $product->meta_title) }}" maxlength="60"
                    placeholder="Tiêu đề SEO (tối đa 60 ký tự)">
                <small class="form-text text-muted">Tiêu đề hiển thị trên công cụ tìm kiếm, nên chứa từ khóa chính.</small>
                @error('meta_title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Meta Description -->
            <div class="form-group">
                <label>Mô tả SEO (Meta Description) <span id="meta-description-count">0/160</span></label>
                <textarea name="meta_description" id="meta-description" class="form-control" maxlength="160"
                    placeholder="Mô tả ngắn gọn (tối đa 160 ký tự)">{{ old('meta_description', $product->meta_description) }}</textarea>
                <small class="form-text text-muted">Mô tả hiển thị dưới tiêu đề trên công cụ tìm kiếm.</small>
                @error('meta_description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Meta Keywords -->
            <div class="form-group">
                <label>Từ khóa SEO (Meta Keywords)</label>
                <input type="text" name="meta_keywords" id="meta-keywords" class="form-control"
                    value="{{ old('meta_keywords', $product->meta_keywords) }}"
                    placeholder="Từ khóa, cách nhau bằng dấu phẩy (ví dụ: áo thun, thời trang)">
                <small class="form-text text-muted">Danh sách từ khóa liên quan, tối đa 255 ký tự.</small>
                @error('meta_keywords')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Preview Meta -->
            <div class="form-group">
                <label>Xem trước SEO</label>
                <div id="seo-preview" class="card p-3" style="max-width: 600px; border: 1px solid #ddd;">
                    <h5 id="preview-title" class="text-primary mb-1">{{ $product->meta_title ?: 'Tiêu đề sản phẩm' }}</h5>
                    <p id="preview-url" class="text-success mb-1">https://example.com/san-pham/{{ $product->slug }}</p>
                    <p id="preview-description" class="text-muted">
                        {{ $product->meta_description ?: 'Mô tả ngắn gọn về sản phẩm.' }}</p>
                </div>
            </div>
            <!-- Tên sản phẩm -->
            <div class="form-group">
                <label>Tên sản phẩm</label>
                <input type="text" name="name" id="product-name" class="form-control"
                    value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Mô tả -->
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" id="product-description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Giá -->
            <div class="form-group">
                <label>Giá</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}"
                    required>
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Giá nhập -->
            <div class="form-group">
                <label>Giá nhập</label>
                <input type="number" name="purchase_price" class="form-control"
                    value="{{ old('purchase_price', $product->purchase_price) }}" required>
                @error('purchase_price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Giá bán -->
            <div class="form-group">
                <label>Giá bán</label>
                <input type="number" name="sale_price" class="form-control"
                    value="{{ old('sale_price', $product->sale_price) }}" required>
                @error('sale_price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Số lượng tồn kho -->
            <div class="form-group">
                <label>Số lượng tồn kho</label>
                <input type="number" name="stock_total" class="form-control"
                    value="{{ old('stock_total', $product->stock_total) }}" required>
                @error('stock_total')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- SKU -->
            <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                @error('sku')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Thương hiệu -->
            <div class="form-group">
                <label>Thương hiệu</label>
                <select name="brand" class="form-control" required>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->name }}"
                            {{ old('brand', $product->brand) == $brand->name ? 'selected' : '' }}>
                            {{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Danh mục -->
            <div class="form-group">
                <label>Danh mục</label>
                <select name="category" class="form-control" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}"
                            {{ old('category', $product->category) == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Danh mục phụ -->
            <div class="form-group">
                <label>Danh mục phụ</label>
                <input type="text" name="sub_category" class="form-control"
                    value="{{ old('sub_category', $product->sub_category) }}" required>
                @error('sub_category')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Hình ảnh -->
            <div class="form-group">
                <label>Hình ảnh</label>
                <input type="file" name="images[]" class="form-control" multiple id="image-input">
                <div class="mt-2">
                    @foreach ($product->images as $image)
                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt_text }}" width="100"
                            class="m-1">
                    @endforeach
                </div>
                @error('images.*')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Kích thước -->
            <div class="form-group">
                <label>Kích thước (cm, kg)</label>
                <input type="number" name="dimensions[length]" class="form-control" placeholder="Chiều dài"
                    value="{{ old('dimensions.length', $product->dimensions->length ?? '') }}">
                <input type="number" name="dimensions[width]" class="form-control" placeholder="Chiều rộng"
                    value="{{ old('dimensions.width', $product->dimensions->width ?? '') }}">
                <input type="number" name="dimensions[height]" class="form-control" placeholder="Chiều cao"
                    value="{{ old('dimensions.height', $product->dimensions->height ?? '') }}">
                <input type="number" name="dimensions[weight]" class="form-control" placeholder="Trọng lượng"
                    value="{{ old('dimensions.weight', $product->dimensions->weight ?? '') }}">
                @error('dimensions.*')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Biến thể -->
            <div class="form-group">
                <label>Biến thể sản phẩm</label>
                <div id="variants-container">
                    @foreach ($product->variants as $index => $variant)
                        <div class="variant-group mb-3">
                            <input type="text" name="variants[{{ $index }}][variant_name]"
                                class="form-control mb-2" placeholder="Tên biến thể (ví dụ: Màu Đỏ, Size M)"
                                value="{{ old('variants.' . $index . '.variant_name', $variant->variant_name) }}">
                            <input type="number" name="variants[{{ $index }}][price]" class="form-control mb-2"
                                placeholder="Giá" value="{{ old('variants.' . $index . '.price', $variant->price) }}">
                            <input type="number" name="variants[{{ $index }}][purchase_price]"
                                class="form-control mb-2" placeholder="Giá nhập"
                                value="{{ old('variants.' . $index . '.purchase_price', $variant->purchase_price) }}">
                            <input type="number" name="variants[{{ $index }}][sale_price]"
                                class="form-control mb-2" placeholder="Giá bán"
                                value="{{ old('variants.' . $index . '.sale_price', $variant->sale_price) }}">
                            <input type="number" name="variants[{{ $index }}][stock]" class="form-control mb-2"
                                placeholder="Số lượng tồn kho"
                                value="{{ old('variants.' . $index . '.stock', $variant->stock) }}">
                            <input type="text" name="variants[{{ $index }}][sku]" class="form-control mb-2"
                                placeholder="SKU biến thể"
                                value="{{ old('variants.' . $index . '.sku', $variant->sku) }}">
                            <button type="button" class="btn btn-danger remove-variant">Xóa biến thể</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-variant" class="btn btn-secondary">Thêm biến thể</button>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>

    <!-- Compressor.js -->
    <script src="https://unpkg.com/compressorjs@1.2.1/dist/compressor.min.js"></script>
    <script>
        document.getElementById('image-input').addEventListener('change', function(e) {
            const files = e.target.files;
            const dataTransfer = new DataTransfer();

            Array.from(files).forEach((file, index) => {
                new Compressor(file, {
                    quality: 0.6,
                    maxWidth: 1200,
                    maxHeight: 1200,
                    success(result) {
                        dataTransfer.items.add(new File([result], file.name, {
                            type: result.type
                        }));
                        if (dataTransfer.files.length === files.length) {
                            e.target.files = dataTransfer.files;
                        }
                    },
                    error(err) {
                        console.error('Lỗi nén ảnh:', err);
                    },
                });
            });
        });

        // Biến thể động
        document.getElementById('add-variant').addEventListener('click', function() {
            const container = document.getElementById('variants-container');
            const index = container.children.length;
            const variantHtml = `
                <div class="variant-group mb-3">
                    <input type="text" name="variants[${index}][variant_name]" class="form-control mb-2" placeholder="Tên biến thể (ví dụ: Màu Đỏ, Size M)">
                    <input type="number" name="variants[${index}][price]" class="form-control mb-2" placeholder="Giá">
                    <input type="number" name="variants[${index}][purchase_price]" class="form-control mb-2" placeholder="Giá nhập">
                    <input type="number" name="variants[${index}][sale_price]" class="form-control mb-2" placeholder="Giá bán">
                    <input type="number" name="variants[${index}][stock]" class="form-control mb-2" placeholder="Số lượng tồn kho">
                    <input type="text" name="variants[${index}][sku]" class="form-control mb-2" placeholder="SKU biến thể">
                    <button type="button" class="btn btn-danger remove-variant">Xóa biến thể</button>
                </div>`;
            container.insertAdjacentHTML('beforeend', variantHtml);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variant')) {
                e.target.closest('.variant-group').remove();
            }
        });

        // JavaScript cho Meta SEO
        const productName = document.getElementById('product-name');
        const productDescription = document.getElementById('product-description');
        const metaTitle = document.getElementById('meta-title');
        const metaDescription = document.getElementById('meta-description');
        const metaKeywords = document.getElementById('meta-keywords');
        const metaTitleCount = document.getElementById('meta-title-count');
        const metaDescriptionCount = document.getElementById('meta-description-count');
        const previewTitle = document.getElementById('preview-title');
        const previewUrl = document.getElementById('preview-url');
        const previewDescription = document.getElementById('preview-description');

        // Cập nhật số ký tự
        function updateCharCount(input, counter, maxLength) {
            const length = input.value.length;
            counter.textContent = `${length}/${maxLength}`;
            if (length > maxLength) {
                counter.style.color = 'red';
            } else {
                counter.style.color = 'black';
            }
        }

        // Cập nhật preview SEO
        function updateSeoPreview() {
            previewTitle.textContent = metaTitle.value || 'Tiêu đề sản phẩm';
            previewUrl.textContent =
                `https://example.com/san-pham/${productName.value ? productName.value.toLowerCase().replace(/\s+/g, '-') : '{{ $product->slug }}'}`;
            previewDescription.textContent = metaDescription.value || 'Mô tả ngắn gọn về sản phẩm.';
        }

        // Gợi ý Meta Title và Meta Description
        productName.addEventListener('input', function() {
            if (!metaTitle.value) {
                metaTitle.value = productName.value.slice(0, 60);
                updateCharCount(metaTitle, metaTitleCount, 60);
            }
            if (!metaDescription.value && productDescription.value) {
                metaDescription.value = productDescription.value.slice(0, 160);
                updateCharCount(metaDescription, metaDescriptionCount, 160);
            }
            updateSeoPreview();
        });

        productDescription.addEventListener('input', function() {
            if (!metaDescription.value) {
                metaDescription.value = productDescription.value.slice(0, 160);
                updateCharCount(metaDescription, metaDescriptionCount, 160);
            }
            updateSeoPreview();
        });

        metaTitle.addEventListener('input', function() {
            updateCharCount(metaTitle, metaTitleCount, 60);
            updateSeoPreview();
        });

        metaDescription.addEventListener('input', function() {
            updateCharCount(metaDescription, metaDescriptionCount, 160);
            updateSeoPreview();
        });

        metaKeywords.addEventListener('input', function() {
            if (metaKeywords.value.length > 255) {
                metaKeywords.value = metaKeywords.value.slice(0, 255);
            }
        });

        // Khởi tạo
        updateCharCount(metaTitle, metaTitleCount, 60);
        updateCharCount(metaDescription, metaDescriptionCount, 160);
        updateSeoPreview();
    </script>
@endsection
