document.addEventListener('DOMContentLoaded', function () {
    // Khởi tạo Quill Editor (nếu có)
    const quillEditor = document.querySelector('#quill-editor .ql-container');
    if (quillEditor) {
        const quill = new Quill(quillEditor, {
            theme: 'snow',
            placeholder: 'Viết mô tả chi tiết...',
            modules: {
                toolbar: '#quill-editor .ql-toolbar'
            }
        });

        const productForm = document.getElementById('product-form');
        if (productForm) {
            productForm.onsubmit = function () {
                document.getElementById('description').value = quill.root.innerHTML;
            };
        }
    }

    // Xử lý SEO Preview và đồng bộ tên sản phẩm với meta title
    const productName = document.getElementById('product-name');
    const metaTitle = document.getElementById('meta-title');
    const metaTitleCount = document.getElementById('meta-title-count');
    const previewTitle = document.getElementById('preview-title');
    const previewUrl = document.getElementById('preview-url');
    const previewDescription = document.getElementById('preview-description');
    const metaDescription = document.getElementById('meta-description');
    const metaDescriptionCount = document.getElementById('meta-description-count');

    // Biến để theo dõi xem meta-title đã được chỉnh sửa thủ công hay chưa
    let metaTitleEditedManually = false;

    // Hàm slugify đơn giản
    function slugify(text) {
        return text
            .toLowerCase()
            .normalize("NFD") // Chuẩn hóa Unicode để tách dấu
            .replace(/[\u0300-\u036f]/g, "") // Loại bỏ dấu tiếng Việt
            .replace(/đ/g, "d") // Chuyển "đ" thành "d"
            .replace(/[^a-z0-9 -]/g, "") // Xóa ký tự không mong muốn
            .replace(/\s+/g, "-") // Chuyển khoảng trắng thành "-"
            .replace(/-+/g, "-"); // Xóa dấu "-" thừa
    }


    // Cập nhật nội dung xem trước SEO và đồng bộ meta title
    function updateSEOPreview() {
        if (!metaTitleEditedManually) {
            metaTitle.value = productName.value;
        }

        // Cập nhật số ký tự
        metaTitleCount.textContent = `${metaTitle.value.length}/60`;
        metaDescriptionCount.textContent = `${metaDescription.value.length}/160`;

        // Cập nhật nội dung xem trước
        previewTitle.textContent = metaTitle.value || productName.value || 'Tiêu đề sản phẩm';
        previewUrl.textContent = `https://Zynox.com/san-pham/${slugify(productName.value || 'san-pham')}`;
        previewDescription.textContent = metaDescription.value || 'Mô tả ngắn gọn về sản phẩm.';
    }


    // Thêm sự kiện khi nhập liệu vào product-name
    productName.addEventListener('input', function () {
        if (!metaTitleEditedManually) {
            metaTitle.value = productName.value;
            updateSEOPreview();
        }
    });

    // Thêm sự kiện khi nhập liệu vào meta-title
    metaTitle.addEventListener('input', function () {
        metaTitleEditedManually = true; // Đánh dấu meta-title đã được chỉnh sửa thủ công
        updateSEOPreview();
    });

    // Thêm sự kiện khi nhập liệu vào meta-description
    metaDescription.addEventListener('input', updateSEOPreview);

    // Khởi tạo lần đầu
    updateSEOPreview();

    productName.addEventListener('input', function () {
        console.log('Tên sản phẩm:', productName.value); // Debug
        if (!metaTitle.value) {
            metaTitle.value = productName.value;
            updateSEOPreview();
        }
    });

    // Xử lý biến thể
    let variantCount = 0;
    const addVariantButton = document.getElementById('add-variant');
    if (addVariantButton) {
        addVariantButton.addEventListener('click', function () {
            const container = document.getElementById('variants-container');
            const variantItem = document.createElement('div');
            variantItem.className = 'variant-item bg-gray-50 p-4 rounded-md mb-4';
            variantCount++;
            variantItem.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <h5 class="text-gray-700 font-medium">Biến thể #${variantCount + 1}</h5>
                    <button type="button" class="remove-variant text-red-500 hover:text-red-700">Xóa</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Tên biến thể <span class="text-red-500">*</span></label>
                        <input type="text" name="variants[${variantCount}][name]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tên biến thể (ví dụ: Đỏ - Nhỏ)" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá gốc <span class="text-red-500">*</span></label>
                        <input type="number" name="variants[${variantCount}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Giá gốc" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá nhập <span class="text-red-500">*</span></label>
                        <input type="number" name="variants[${variantCount}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Giá nhập" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá bán <span class="text-red-500">*</span></label>
                        <input type="number" name="variants[${variantCount}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Giá bán" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="variants[${variantCount}][sku]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="SKU biến thể" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span class="text-red-500">*</span></label>
                        <input type="number" name="variants[${variantCount}][stock]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Số lượng tồn kho" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Thuế VAT (%)</label>
                        <input type="number" name="variants[${variantCount}][vat_amount]" step="0.01" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phần trăm thuế VAT">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                        <input type="file" name="variants[${variantCount}][image]" class="variant-image-input w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*">
                        <div class="variant-image-preview mt-2 hidden">
                            <img src="" alt="Preview" class="w-24 h-auto rounded-md">
                            <button type="button" class="remove-image text-red-500 hover:text-red-700 text-sm mt-1">Xóa hình ảnh</button>
                        </div>
                    </div>
                </div>
            `;

            // Thêm attribute.blade.php vào variant-item
            fetch('/admin/products/partials/attribute')
                .then(response => response.text())
                .then(html => {
                    const attributeDiv = document.createElement('div');
                    attributeDiv.innerHTML = html;
                    variantItem.appendChild(attributeDiv);

                    // Cập nhật chỉ số cho các input trong attribute.blade.php
                    updateAttributeIndices(variantItem, variantCount);

                    container.appendChild(variantItem);

                    // Thêm sự kiện xóa biến thể
                    variantItem.querySelector('.remove-variant').addEventListener('click', function () {
                        container.removeChild(variantItem);
                        updateVariantLabels();
                        updateVariantSelects();
                    });

                    // Thêm sự kiện preview hình ảnh
                    addImagePreviewListener(variantItem.querySelector('.variant-image-input'));

                    // Thêm sự kiện thêm/xóa thuộc tính cho biến thể
                    addVariantAttributeListeners(variantItem);

                    // Cập nhật select box "Biến thể" trong attribute_values
                    updateVariantSelects();
                })
                .catch(error => console.error('Lỗi khi load attribute.blade.php:', error));
        });
    }

    // Xử lý thuộc tính
    let attributeCount = 0;
    const addAttributeButton = document.getElementById('add-attribute');
    if (addAttributeButton) {
        addAttributeButton.addEventListener('click', function () {
            const container = document.getElementById('attributes-container');
            const attributeItem = document.createElement('div');
            attributeItem.className = 'attribute-item bg-gray-50 p-4 rounded-md mb-4';
            attributeCount++;
            attributeItem.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <h5 class="text-gray-700 font-medium">Thuộc tính #${attributeCount + 1}</h5>
                    <button type="button" class="remove-attribute text-red-500 hover:text-red-700">Xóa</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Tên thuộc tính</label>
                        <input type="text" name="attributes[${attributeCount}][name]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tên thuộc tính">
                    </div>
                </div>
            `;
            container.appendChild(attributeItem);

            attributeItem.querySelector('.remove-attribute').addEventListener('click', function () {
                container.removeChild(attributeItem);
                updateAttributeLabels();
            });
        });
    }

    // Xử lý giá trị thuộc tính
    let attributeValueCount = 0;
    const addAttributeValueButton = document.getElementById('add-attribute-value');
    if (addAttributeValueButton) {
        addAttributeValueButton.addEventListener('click', function () {
            const container = document.getElementById('attribute-values-container');
            const attributeValueItem = document.createElement('div');
            attributeValueItem.className = 'attribute-value-item bg-gray-50 p-4 rounded-md mb-4';
            attributeValueCount++;
            attributeValueItem.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <h5 class="text-gray-700 font-medium">Giá trị #${attributeValueCount + 1}</h5>
                    <button type="button" class="remove-attribute-value text-red-500 hover:text-red-700">Xóa</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Thuộc tính</label>
                        <select name="attribute_values[${attributeValueCount}][attribute_id]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn thuộc tính</option>
                            @foreach (\App\Models\Attribute::all() as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá trị</label>
                        <input type="text" name="attribute_values[${attributeValueCount}][value]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Giá trị">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Biến thể</label>
                        <select name="attribute_values[${attributeValueCount}][product_variant_id]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn biến thể</option>
                        </select>
                    </div>
                </div>
            `;
            container.appendChild(attributeValueItem);

            // Cập nhật danh sách biến thể trong select box
            updateVariantSelects();

            attributeValueItem.querySelector('.remove-attribute-value').addEventListener('click', function () {
                container.removeChild(attributeValueItem);
                updateAttributeValueLabels();
            });
        });
    }

    // Thêm sự kiện thêm/xóa thuộc tính cho biến thể
    function addVariantAttributeListeners(variantItem) {
        let variantAttributeCount = 0;
        const container = variantItem.querySelector('.variant-attributes-container');
        const addButton = variantItem.querySelector('.add-variant-attribute');

        addButton.addEventListener('click', function () {
            const variantIndex = Array.from(document.querySelectorAll('.variant-item')).indexOf(variantItem);
            const attributeItem = document.createElement('div');
            attributeItem.className = 'variant-attribute-item bg-gray-100 p-4 rounded-md mb-4';
            variantAttributeCount++;
            attributeItem.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <h6 class="text-gray-700 font-medium">Thuộc tính #${variantAttributeCount + 1}</h6>
                    <button type="button" class="remove-variant-attribute text-red-500 hover:text-red-700">Xóa</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Thuộc tính</label>
                        <select name="variants[${variantIndex}][attributes][${variantAttributeCount}][attribute_id]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn thuộc tính</option>
                            @foreach (\App\Models\Attribute::all() as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá trị</label>
                        <input type="text" name="variants[${variantIndex}][attributes][${variantAttributeCount}][value]" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Giá trị thuộc tính">
                    </div>
                </div>
            `;
            container.appendChild(attributeItem);

            attributeItem.querySelector('.remove-variant-attribute').addEventListener('click', function () {
                container.removeChild(attributeItem);
                updateVariantAttributeLabels(variantItem);
            });
        });

        // Thêm sự kiện xóa cho các thuộc tính ban đầu
        variantItem.querySelectorAll('.remove-variant-attribute').forEach(button => {
            button.addEventListener('click', function () {
                const attributeItem = button.closest('.variant-attribute-item');
                container.removeChild(attributeItem);
                updateVariantAttributeLabels(variantItem);
            });
        });
    }

    // Cập nhật nhãn và chỉ số thuộc tính của biến thể
    function updateVariantAttributeLabels(variantItem) {
        const variantIndex = Array.from(document.querySelectorAll('.variant-item')).indexOf(variantItem);
        const attributeItems = variantItem.querySelectorAll('.variant-attribute-item');
        attributeItems.forEach((item, index) => {
            const label = item.querySelector('h6');
            label.textContent = `Thuộc tính #${index + 1}`;
            item.querySelector('select[name$="[attribute_id]"]').name = `variants[${variantIndex}][attributes][${index}][attribute_id]`;
            item.querySelector('input[name$="[value]"]').name = `variants[${variantIndex}][attributes][${index}][value]`;
        });
    }

    // Cập nhật nhãn biến thể và chỉ số
    function updateVariantLabels() {
        const variantItems = document.querySelectorAll('.variant-item');
        variantItems.forEach((item, index) => {
            const label = item.querySelector('h5');
            label.textContent = `Biến thể #${index + 1}`;
            item.querySelector('input[name$="[name]"]').name = `variants[${index}][name]`;
            item.querySelector('input[name$="[price]"]').name = `variants[${index}][price]`;
            item.querySelector('input[name$="[purchase_price]"]').name = `variants[${index}][purchase_price]`;
            item.querySelector('input[name$="[sale_price]"]').name = `variants[${index}][sale_price]`;
            item.querySelector('input[name$="[sku]"]').name = `variants[${index}][sku]`;
            item.querySelector('input[name$="[stock]"]').name = `variants[${index}][stock]`;
            item.querySelector('input[name$="[vat_amount]"]').name = `variants[${index}][vat_amount]`;
            item.querySelector('input[name$="[image]"]').name = `variants[${index}][image]`;

            // Cập nhật chỉ số trong attribute.blade.php
            updateAttributeIndices(item, index);

            // Cập nhật chỉ số thuộc tính của biến thể
            updateVariantAttributeLabels(item);
        });
        variantCount = variantItems.length;
    }

    // Cập nhật chỉ số cho các input trong attribute.blade.php
    function updateAttributeIndices(variantItem, index) {
        variantItem.querySelectorAll('input[name$="[discount_type]"]').forEach(input => {
            input.name = `variants[${index}][discount_type]`;
        });
        variantItem.querySelector('input[name$="[length]"]').name = `variants[${index}][length]`;
        variantItem.querySelector('input[name$="[width]"]').name = `variants[${index}][width]`;
        variantItem.querySelector('input[name$="[height]"]').name = `variants[${index}][height]`;
        variantItem.querySelector('input[name$="[weight]"]').name = `variants[${index}][weight]`;
        variantItem.querySelector('input[name$="[shipping_cost]"]').name = `variants[${index}][shipping_cost]`;
    }

    // Cập nhật nhãn thuộc tính
    function updateAttributeLabels() {
        const attributeItems = document.querySelectorAll('.attribute-item');
        attributeItems.forEach((item, index) => {
            const label = item.querySelector('h5');
            label.textContent = `Thuộc tính #${index + 1}`;
            item.querySelector('input[name$="[name]"]').name = `attributes[${index}][name]`;
        });
        attributeCount = attributeItems.length;
    }

    // Cập nhật nhãn giá trị thuộc tính
    function updateAttributeValueLabels() {
        const attributeValueItems = document.querySelectorAll('.attribute-value-item');
        attributeValueItems.forEach((item, index) => {
            const label = item.querySelector('h5');
            label.textContent = `Giá trị #${index + 1}`;
            item.querySelector('select[name$="[attribute_id]"]').name = `attribute_values[${index}][attribute_id]`;
            item.querySelector('input[name$="[value]"]').name = `attribute_values[${index}][value]`;
            item.querySelector('select[name$="[product_variant_id]"]').name = `attribute_values[${index}][product_variant_id]`;
        });
        attributeValueCount = attributeValueItems.length;
    }

    // Cập nhật danh sách biến thể trong select box "Biến thể"
    function updateVariantSelects() {
        const variantItems = document.querySelectorAll('.variant-item');
        const variantSelects = document.querySelectorAll('select[name$="[product_variant_id]"]');
        const variantNames = Array.from(variantItems).map(item => {
            const nameInput = item.querySelector('input[name$="[name]"]');
            return nameInput ? nameInput.value : '';
        });

        variantSelects.forEach(select => {
            const currentValue = select.value;
            select.innerHTML = '<option value="">Chọn biến thể</option>';
            variantNames.forEach((name, index) => {
                if (name) {
                    const option = document.createElement('option');
                    option.value = index;
                    option.text = name;
                    if (currentValue == index) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                }
            });
        });
    }

    // Xử lý preview hình ảnh
    function addImagePreviewListener(input) {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const previewContainer = input.nextElementSibling;
            const previewImage = previewContainer.querySelector('img');

            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    previewImage.src = event.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);

                new Compressor(file, {
                    quality: 0.6,
                    maxWidth: 1200,
                    maxHeight: 1200,
                    success(result) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(new File([result], file.name, { type: result.type }));
                        input.files = dataTransfer.files;
                    },
                    error(err) {
                        console.error('Lỗi nén ảnh:', err);
                    },
                });
            }
        });

        const previewContainer = input.nextElementSibling;
        const removeImageButton = previewContainer.querySelector('.remove-image');
        removeImageButton.addEventListener('click', function () {
            input.value = '';
            previewContainer.classList.add('hidden');
            previewImage.src = '';
        });
    }

    // Khởi tạo preview cho các input hình ảnh biến thể hiện có
    document.querySelectorAll('.variant-image-input').forEach(input => {
        addImagePreviewListener(input);
    });

    // Khởi tạo sự kiện thêm/xóa thuộc tính cho các biến thể ban đầu
    document.querySelectorAll('.variant-item').forEach(item => {
        addVariantAttributeListeners(item);
    });

    // Cập nhật danh sách biến thể khi tên biến thể thay đổi
    document.querySelectorAll('input[name$="[name]"]').forEach(input => {
        input.addEventListener('input', updateVariantSelects);
    });

    // Khởi tạo danh sách biến thể ban đầu
    updateVariantSelects();

    // Xử lý preview ảnh chính, thay thế uploadIcon1
    function handleMainImagePreview(inputId, iconId) {
        const input = document.getElementById(inputId);
        const uploadIcon = document.getElementById(iconId);

        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                    input.value = '';
                    uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                    uploadIcon.alt = 'Upload Icon';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    uploadIcon.src = event.target.result;
                    uploadIcon.alt = 'Uploaded Image';
                };
                reader.readAsDataURL(file);
            } else {
                uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                uploadIcon.alt = 'Upload Icon';
            }
        });

        input.addEventListener('click', function () {
            if (input.files.length === 0) {
                uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                uploadIcon.alt = 'Upload Icon';
            }
        });
    }

    // Xử lý preview nhiều hình ảnh, hiển thị tất cả trong additionalImagesPreview
    function handleAdditionalImagesPreview(inputId, iconContainerId, previewContainerId) {
        const input = document.getElementById(inputId);
        const uploadIconContainer = document.getElementById(iconContainerId);
        const previewContainer = document.getElementById(previewContainerId);

        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files.length > 0) {
                uploadIconContainer.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                previewContainer.innerHTML = '';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                            input.value = '';
                            uploadIconContainer.classList.remove('hidden');
                            previewContainer.classList.add('hidden');
                            previewContainer.innerHTML = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (event) {
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'relative';

                            const img = document.createElement('img');
                            img.src = event.target.result;
                            img.alt = 'Preview';
                            img.className = 'w-24 h-24 object-cover rounded-md border';

                            const removeBtn = document.createElement('button');
                            removeBtn.textContent = 'Xóa';
                            removeBtn.className = 'absolute top-0 right-0 text-red-500 hover:text-red-700 text-sm bg-white rounded-full px-2';
                            removeBtn.onclick = function () {
                                previewContainer.removeChild(imgContainer);
                                const dataTransfer = new DataTransfer();
                                const remainingFiles = Array.from(input.files).filter((_, index) => index !== i);
                                remainingFiles.forEach(file => dataTransfer.items.add(file));
                                input.files = dataTransfer.files;
                                if (remainingFiles.length === 0) {
                                    uploadIconContainer.classList.remove('hidden');
                                    previewContainer.classList.add('hidden');
                                }
                            };

                            imgContainer.appendChild(img);
                            imgContainer.appendChild(removeBtn);
                            previewContainer.appendChild(imgContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            } else {
                uploadIconContainer.classList.remove('hidden');
                previewContainer.classList.add('hidden');
                previewContainer.innerHTML = '';
            }
        });

        input.addEventListener('click', function () {
            if (input.files.length === 0) {
                uploadIconContainer.classList.remove('hidden');
                previewContainer.classList.add('hidden');
                previewContainer.innerHTML = '';
            }
        });
    }

    // Khởi tạo preview cho phần ảnh chính
    handleMainImagePreview('mainImage', 'uploadIcon1');

    // Khởi tạo preview cho phần nhiều hình ảnh
    handleAdditionalImagesPreview('additionalImages', 'uploadIconContainer2', 'additionalImagesPreview');
});

function addAttribute() {
    let container = document.getElementById('attribute-container');
    let newAttribute = document.createElement('div');
    newAttribute.classList.add('mb-4', 'flex', 'items-center', 'gap-4');

    newAttribute.innerHTML = `
        <input type="text" name="attributes[][name]" placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)"
            class="w-1/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input type="text" name="attributes[][values]" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng)"
            class="w-2/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <button type="button" class="ml-3 bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">
            Xóa
        </button>
    `;

    container.appendChild(newAttribute);

    // Gắn sự kiện xóa ngay khi thuộc tính được thêm
    newAttribute.querySelector('.remove-attribute').addEventListener('click', function () {
        newAttribute.remove();
    });
}


function generateVariants() {
    let attributes = document.querySelectorAll('[name="attributes[][name]"]');
    let values = document.querySelectorAll('[name="attributes[][values]"]');
    let variantContainer = document.getElementById('variant-container');
    variantContainer.innerHTML = '';

    let attributeValues = [];
    attributes.forEach((attr, index) => {
        let valuesArray = values[index].value.split(',').map(v => v.trim());
        if (valuesArray.length) attributeValues.push(valuesArray);
    });

    let variants = getCombinations(attributeValues);

    variants.forEach((variant, index) => {
        let variantDiv = document.createElement('div');
        variantDiv.classList.add('p-6', 'border', 'border-gray-300', 'rounded-md', 'mb-6', 'bg-white');

        variantDiv.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-semibold">Biến thể ${index + 1}: ${variant.join(' - ')}</h5>
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600"
                    onclick="removeVariant(this)">Xóa</button>
            </div>
            <input type="hidden" name="variants[${index}][name]" value="${variant.join(' - ')}">

            <!-- Dữ liệu sản phẩm -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-medium">Giá gốc</label>
                    <input type="number" name="variants[${index}][price]" step="0.01" 
                        class="w-full border p-3 rounded-md focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Giá nhập</label>
                    <input type="number" name="variants[${index}][purchase_price]" step="0.01" 
                        class="w-full border p-3 rounded-md focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Giá bán</label>
                    <input type="number" name="variants[${index}][sale_price]" step="0.01" 
                        class="w-full border p-3 rounded-md focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">SKU</label>
                    <input type="text" name="variants[${index}][sku]" 
                        class="w-full border p-3 rounded-md focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Số lượng tồn kho</label>
                    <input type="number" name="variants[${index}][stock_total]" 
                        class="w-full border p-3 rounded-md focus:ring-blue-500">
                </div>
            </div>

            <!-- Hình ảnh biến thể -->
            <div class="mt-3">
                <label class="block text-gray-700 font-medium">Ảnh biến thể ${index + 1}</label>
                <input type="file" name="variant_images[${index}][]" multiple accept="image/*"
                    class="w-full border p-2 rounded-md focus:ring-blue-500"
                    onchange="previewVariantImage(event, ${index})">
                <div id="preview-images-${index}" class="mt-2 flex flex-wrap gap-2"></div>
            </div>
        `;

        variantContainer.appendChild(variantDiv);
    });
}

function getCombinations(arr) {
    return arr.reduce((acc, val) => acc.flatMap(a => val.map(v => [...a, v])), [[]]);
}

function previewVariantImage(event, index) {
    let previewContainer = document.getElementById(`preview-images-${index}`);
    previewContainer.innerHTML = '';

    Array.from(event.target.files).forEach((file, fileIndex) => {
        let reader = new FileReader();
        reader.onload = function (e) {
            let imgContainer = document.createElement('div');
            imgContainer.classList.add('relative');

            imgContainer.innerHTML = `
                <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-md border border-gray-300">
                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md"
                    onclick="removeImage(this)">✖</button>
            `;
            previewContainer.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    });
}

function removeVariant(element) {
    element.closest('.p-4').remove();
}

function removeImage(element) {
    element.parentElement.remove();
}

document.getElementById('select-all').addEventListener('change', function () {
    let checkboxes = document.querySelectorAll('.select-item');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

document.getElementById('statusFilter').addEventListener('change', function () {
    this.form.submit(); // Gửi form ngay khi thay đổi trạng thái
});