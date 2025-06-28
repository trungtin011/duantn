// public/js/product.js

let attributeIndex = document.querySelectorAll('#attribute-container .flex').length;

function addAttribute() {
    const container = document.getElementById('attribute-container');
    const newAttribute = document.createElement('div');
    newAttribute.classList.add('flex', 'items-center', 'gap-4');
    newAttribute.innerHTML = `
        <input type="text" name="attributes[${attributeIndex}][name]" placeholder="Tên thuộc tính (VD: Màu sắc)"
            class="w-1/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input type="text" name="attributes[${attributeIndex}][values]" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)"
            class="w-2/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
    `;
    container.appendChild(newAttribute);
    newAttribute.querySelector('.remove-attribute').addEventListener('click', () => {
        newAttribute.remove();
        updateAttributeIndices();
    });
    newAttribute.querySelector('input[name$="[name]"]').addEventListener('input', function () {
        const names = Array.from(container.querySelectorAll('input[name$="[name]"]')).map(input => input.value.trim().toLowerCase());
        if (names.filter(name => name === this.value.trim().toLowerCase()).length > 1) {
            alert('Tên thuộc tính đã tồn tại!');
            this.value = '';
        }
    });
    attributeIndex++;
}

function updateAttributeIndices() {
    const attributeItems = document.querySelectorAll('#attribute-container .flex');
    attributeItems.forEach((item, index) => {
        item.querySelector('input[name$="[name]"]').name = `attributes[${index}][name]`;
        item.querySelector('input[name$="[values]"]').name = `attributes[${index}][values]`;
    });
    attributeIndex = attributeItems.length;
}

function generateVariants() {
    const attributeContainer = document.getElementById('attribute-container');
    const attributes = attributeContainer.querySelectorAll('input[name$="[name]"]');
    const values = attributeContainer.querySelectorAll('input[name$="[values]"]');
    const variantContainer = document.getElementById('variant-container');
    variantContainer.innerHTML = '';

    let attributeData = [];
    let hasValidAttribute = false;

    attributes.forEach((attr, index) => {
        const attrName = attr.value.trim();
        const valuesArray = values[index].value.split(',').map(v => v.trim()).filter(v => v);
        if (attrName && valuesArray.length) {
            attributeData.push({ name: attrName, values: valuesArray });
            hasValidAttribute = true;
        }
    });

    if (!hasValidAttribute) {
        alert('Vui lòng nhập ít nhất một thuộc tính hợp lệ.');
        return;
    }

    const variants = getCombinations(attributeData.map(attr => attr.values));

    variants.forEach((variant, index) => {
        const variantDiv = document.createElement('div');
        variantDiv.classList.add('p-4', 'border', 'border-gray-300', 'rounded-md', 'mb-4', 'bg-white');
        let variantHTML = `
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-semibold">Biến thể ${index + 1}: ${variant.join(' - ')}</h5>
                <div class="flex gap-2">
                    <button type="button" class="toggle-variant-content text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="toggle-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-variant">Xóa</button>
                </div>
            </div>
            <div class="variant-content">
                <input type="hidden" name="variants[${index}][index]" value="${index}">
                <input type="hidden" name="variants[${index}][name]" value="${variant.join(' - ')}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá gốc</label>
                        <input type="number" name="variants[${index}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá gốc" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
                        <input type="number" name="variants[${index}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá nhập" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                        <input type="number" name="variants[${index}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá bán" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">SKU</label>
                        <input type="text" name="variants[${index}][sku]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập SKU" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho</label>
                        <input type="number" name="variants[${index}][stock_total]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập số lượng" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                        <input type="number" name="variants[${index}][length]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều dài">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                        <input type="number" name="variants[${index}][width]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều rộng">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                        <input type="number" name="variants[${index}][height]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều cao">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                        <input type="number" name="variants[${index}][weight]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Trọng lượng">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                    <input type="file" name="variant_images[${index}][]" multiple class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" onchange="previewVariantImage(event, ${index})">
                    <div id="preview-images-${index}" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
            </div>
        `;
        variant.forEach((value, attrIndex) => {
            variantHTML += `
                <input type="hidden" name="variants[${index}][attributes][${attrIndex}][name]" value="${attributeData[attrIndex].name}">
                <input type="hidden" name="variants[${index}][attributes][${attrIndex}][value]" value="${value}">
            `;
        });
        variantDiv.innerHTML = variantHTML;
        variantContainer.appendChild(variantDiv);

        variantDiv.querySelector('.remove-variant').addEventListener('click', () => {
            variantDiv.remove();
            updateVariantIndices();
        });

        const toggleButton = variantDiv.querySelector('.toggle-variant-content');
        const toggleIcon = toggleButton.querySelector('.toggle-icon path');
        const variantContent = variantDiv.querySelector('.variant-content');
        toggleButton.addEventListener('click', () => {
            variantContent.classList.toggle('hidden');
            toggleIcon.setAttribute('d', variantContent.classList.contains('hidden') ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7');
        });
    });

    updateVariantIndices();
}

function updateVariantIndices() {
    const variantItems = document.querySelectorAll('#variant-container > div');
    variantItems.forEach((item, index) => {
        const label = item.querySelector('h5');
        const nameInput = item.querySelector('input[name$="[name]"]');
        label.textContent = `Biến thể ${index + 1}: ${nameInput.value}`;
        const inputs = item.querySelectorAll('input[name]');
        inputs.forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
        });
        const fileInput = item.querySelector('input[type="file"]');
        fileInput.setAttribute('onchange', `previewVariantImage(event, ${index})`);
        item.querySelector('div[id^="preview-images-"]').id = `preview-images-${index}`;
    });
}

function getCombinations(arr) {
    return arr.reduce((acc, val) => acc.flatMap(a => val.map(v => [...a, v])), [[]]);
}

function previewVariantImage(event, index) {
    const previewContainer = document.getElementById(`preview-images-${index}`);
    previewContainer.innerHTML = '';
    Array.from(event.target.files).forEach(file => {
        if (file.size > 5 * 1024 * 1024) {
            alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
            event.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            const imgContainer = document.createElement('div');
            imgContainer.classList.add('relative', 'w-24', 'h-24');
            imgContainer.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover rounded-md border border-gray-300">
                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full" onclick="this.parentElement.remove()">✖</button>
            `;
            previewContainer.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    });
}

function handleMainImagePreview(inputId, iconId) {
    const input = document.getElementById(inputId);
    const uploadIcon = document.getElementById(iconId);

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                input.value = '';
                uploadIcon.classList.add('hidden');
                return;
            }
            const reader = new FileReader();
            reader.onload = event => {
                uploadIcon.src = event.target.result;
                uploadIcon.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            uploadIcon.classList.add('hidden');
        }
    });
}

function handleAdditionalImagesPreview(inputId, previewContainerId) {
    const input = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewContainerId);

    input.addEventListener('change', function (e) {
        previewContainer.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = event => {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('relative', 'w-24', 'h-24');
                imgContainer.innerHTML = `
                    <img src="${event.target.result}" class="w-full h-full object-cover rounded-md border border-gray-300">
                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full" onclick="this.parentElement.remove()">✖</button>
                `;
                previewContainer.appendChild(imgContainer);
            };
            reader.readAsDataURL(file);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: '#description',
        height: 300,
        plugins: 'image link lists table',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image table',
        images_upload_url: '{{ route("seller.upload.image") }}',
        file_picker_types: 'image',
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                const file = this.files[0];
                const reader = new FileReader();
                reader.onload = () => {
                    const id = 'blobid' + new Date().getTime();
                    const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },
        setup: editor => editor.on('change', () => editor.save())
    });

    const productForm = document.getElementById('product-form');
    productForm.addEventListener('submit', () => tinymce.triggerSave());

    const productName = document.getElementById('product-name');
    const metaTitle = document.getElementById('meta-title');
    const metaTitleCount = document.getElementById('meta-title-count');
    const previewTitle = document.getElementById('preview-title');
    const previewUrl = document.getElementById('preview-url');
    const previewDescription = document.getElementById('preview-description');
    const metaDescription = document.getElementById('meta-description');
    const metaDescriptionCount = document.getElementById('meta-description-count');

    let metaTitleEditedManually = false;

    const slugify = text => text
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g, "d")
        .replace(/[^a-z0-9 -]/g, "")
        .replace(/\s+/g, "-")
        .replace(/-+/g, "-");

    const updateSEOPreview = () => {
        if (!metaTitleEditedManually) metaTitle.value = productName.value.slice(0, 60);
        metaTitleCount.textContent = `${metaTitle.value.length}/60`;
        metaDescriptionCount.textContent = `${metaDescription.value.length}/160`;
        previewTitle.textContent = metaTitle.value || 'Tiêu đề sản phẩm';
        previewUrl.textContent = `https://Zynox.com/san-pham/${slugify(productName.value || 'san-pham')}`;
        previewDescription.textContent = metaDescription.value || 'Mô tả ngắn gọn về sản phẩm.';
    };

    productName.addEventListener('input', updateSEOPreview);
    metaTitle.addEventListener('input', () => {
        metaTitleEditedManually = true;
        updateSEOPreview();
    });
    metaDescription.addEventListener('input', updateSEOPreview);
    updateSEOPreview();

    handleMainImagePreview('mainImage', 'uploadIcon1');
    handleAdditionalImagesPreview('additionalImages', 'additionalImagesPreview');

    const toggleButton = document.getElementById('toggle-variants');
    const toggleIcon = document.getElementById('toggle-icon').querySelector('path');
    const variantContent = document.getElementById('variant-content');

    toggleButton.addEventListener('click', () => {
        variantContent.classList.toggle('hidden');
        toggleIcon.setAttribute('d', variantContent.classList.contains('hidden') ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7');
    });
});