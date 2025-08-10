// Debug logging function
function debugLog(message, data = null) {
    if (typeof console !== 'undefined' && console.log) {
        if (data) {
            console.log(`[DEBUG] ${message}`, data);
        } else {
            console.log(`[DEBUG] ${message}`);
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    debugLog('Create Product JS loaded');

    // Tab Management
    initializeTabs();

    // Product Type Toggle
    initializeProductTypeToggle();

    // Image Preview + Temp upload
    initializeImagePreviews();
    try { restoreTempImages(); } catch (e) { console.warn('Restore temp images error', e); }

    // SEO Preview
    initializeSEOPreview();

    // Category and Brand Toggles
    initializeCategoryToggles();
    initializeBrandToggles();

    // Variant Management
    initializeVariantHandling();

    // Rehydrate from old inputs after a failed validation
    try { preloadOldData(); } catch (e) { console.warn('Preload old data error', e); }

    // Form Validation
    initializeFormValidation();
});

// Tab Management
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.add('hidden'));

            // Add active class to clicked button and show content
            button.classList.add('active');
            document.getElementById(`tab-${targetTab}`).classList.remove('hidden');
        });
    });
}

// Product Type Toggle
function initializeProductTypeToggle() {
    const productTypeRadios = document.querySelectorAll('input[name="product_type"]');

    productTypeRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            console.log('Product type changed to:', this.value);

            // Remove checked class from all radio buttons
            productTypeRadios.forEach(r => {
                r.classList.remove('checked');
            });

            // Add checked class to selected radio
            this.classList.add('checked');

            // Update tab visibility
            updateTabVisibility();
        });
    });

    // Initial setup
    const selectedType = document.querySelector('input[name="product_type"]:checked');
    if (selectedType) {
        selectedType.dispatchEvent(new Event('change'));
    }

    // Add click event to labels for better UX
    const radioLabels = document.querySelectorAll('input[name="product_type"] + span');
    radioLabels.forEach(label => {
        label.addEventListener('click', function () {
            const radio = this.previousElementSibling;
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        });
    });
}

function updateTabVisibility() {
    const productType = document.querySelector('input[name="product_type"]:checked')?.value;
    const tabButtons = document.querySelectorAll('.tab-button');

    tabButtons.forEach(button => {
        const tabName = button.getAttribute('data-tab');

        if (productType === 'simple') {
            // Hide attributes-variants tab for simple products
            if (tabName === 'attributes-variants') {
                button.style.display = 'none';
            } else {
                button.style.display = 'block';
            }
        } else if (productType === 'variant') {
            // Hide pricing-inventory tab for variant products
            if (tabName === 'pricing-inventory') {
                button.style.display = 'none';
            } else {
                button.style.display = 'block';
            }
        }
    });
}

// Image Preview
function initializeImagePreviews() {
    // Main image preview
    const mainImageInput = document.getElementById('mainImage');
    const mainImagePreview = document.getElementById('uploadIcon1');
    const mainImageTemp = document.getElementById('mainImageTemp');

    if (mainImageInput && mainImagePreview) {
        mainImageInput.addEventListener('change', async function (e) {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                this.value = '';
                return;
            }
            const formData = new FormData();
            formData.append('file', file);
            const res = await fetch('/api/upload-product-temp', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            const data = await res.json();
            if (data && data.success) {
                if (mainImageTemp) mainImageTemp.value = data.path;
                if (mainImagePreview) {
                    mainImagePreview.src = data.url;
                    mainImagePreview.classList.remove('hidden');
                }
            } else {
                alert('Tải ảnh tạm thất bại');
            }
        });
    }

    // Additional images preview
    const additionalImagesInput = document.getElementById('additionalImages');
    const additionalImagesPreview = document.getElementById('additionalImagesPreview');
    const additionalImagesTemp = document.getElementById('additionalImagesTemp');

    if (additionalImagesInput && additionalImagesPreview) {
        additionalImagesInput.addEventListener('change', async function (e) {
            const files = Array.from(e.target.files || []);
            if (!files.length) return;
            let tempList = [];
            for (const file of files) {
                if (file.size > 5 * 1024 * 1024) { alert('Kích thước ảnh phải nhỏ hơn 5Mb!'); continue; }
                const formData = new FormData();
                formData.append('file', file);
                const res = await fetch('/api/upload-product-temp', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
                const data = await res.json();
                if (data && data.success) {
                    tempList.push(data.path);
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'relative inline-block mr-2 mb-2';
                    imgContainer.innerHTML = `
                        <img src="${data.url}" class="w-20 h-20 object-cover rounded border">
                        <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs" onclick="this.parentElement.remove()">×</button>
                    `;
                    additionalImagesPreview.appendChild(imgContainer);
                }
            }
            if (additionalImagesTemp) {
                const current = safeJsonParse(additionalImagesTemp.value, []);
                additionalImagesTemp.value = JSON.stringify(current.concat(tempList));
            }
            // reset input to allow re-selecting same files later
            additionalImagesInput.value = '';
        });
    }
}

function restoreTempImages() {
    const mainImageTemp = document.getElementById('mainImageTemp');
    const mainImagePreview = document.getElementById('uploadIcon1');
    if (mainImageTemp && mainImageTemp.value && mainImagePreview) {
        mainImagePreview.src = `/storage/${mainImageTemp.value}`.replaceAll('//', '/');
        mainImagePreview.classList.remove('hidden');
    }

    const additionalImagesTemp = document.getElementById('additionalImagesTemp');
    const additionalImagesPreview = document.getElementById('additionalImagesPreview');
    if (additionalImagesTemp && additionalImagesTemp.value && additionalImagesPreview) {
        const list = safeJsonParse(additionalImagesTemp.value, []);
        list.forEach(p => {
            const img = document.createElement('img');
            img.src = `/storage/${p}`.replaceAll('//', '/');
            img.className = 'w-20 h-20 object-cover rounded border mr-2 mb-2 inline-block';
            additionalImagesPreview.appendChild(img);
        });
    }
}

function safeJsonParse(str, fallback) {
    try { return JSON.parse(str ?? ''); } catch { return fallback; }
}

// SEO Preview
function initializeSEOPreview() {
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
        if (!metaTitleEditedManually) {
            metaTitle.value = productName.value.slice(0, 60);
        }
        metaTitleCount.textContent = `${metaTitle.value.length}/60`;
        metaDescriptionCount.textContent = `${metaDescription.value.length}/160`;
        previewTitle.textContent = metaTitle.value || 'Tiêu đề sản phẩm';
        previewUrl.textContent = `https://Zynox.com/san-pham/${slugify(productName.value || 'san-pham')}`;
        previewDescription.textContent = metaDescription.value || 'Mô tả ngắn gọn về sản phẩm.';
    };

    if (productName && metaTitle && metaDescription) {
        productName.addEventListener('input', updateSEOPreview);
        metaTitle.addEventListener('input', () => {
            metaTitleEditedManually = true;
            updateSEOPreview();
        });
        metaDescription.addEventListener('input', updateSEOPreview);
        updateSEOPreview(); // Initial update
    }
}

// Category Toggles
function initializeCategoryToggles() {
    const toggleButtons = document.querySelectorAll('.toggle-sub-categories');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const categoryId = this.getAttribute('data-category-id');
            const subCategoryContainer = document.querySelector(`.sub-categories[data-category-id="${categoryId}"]`);
            const toggleIcon = this.querySelector('.toggle-icon path');

            if (subCategoryContainer && toggleIcon) {
                const isOpen = !subCategoryContainer.classList.contains('hidden');
                subCategoryContainer.classList.toggle('hidden', isOpen);
                toggleIcon.setAttribute('d', isOpen ? 'm8.25 4.5 7.5 7.5-7.5 7.5' : 'm8.25 19.5 7.5-7.5-7.5-7.5');
            }
        });
    });
}

// Brand Toggles
function initializeBrandToggles() {
    const toggleButtons = document.querySelectorAll('.toggle-sub-brands');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const brandId = this.getAttribute('data-brand-id');
            const subBrandContainer = document.querySelector(`.sub-brands[data-brand-id="${brandId}"]`);
            const toggleIcon = this.querySelector('.toggle-icon path');

            if (subBrandContainer && toggleIcon) {
                const isOpen = !subBrandContainer.classList.contains('hidden');
                subBrandContainer.classList.toggle('hidden', isOpen);
                toggleIcon.setAttribute('d', isOpen ? 'm8.25 4.5 7.5 7.5-7.5 7.5' : 'm8.25 19.5 7.5-7.5-7.5-7.5');
            }
        });
    });
}

// Variant Handling
function initializeVariantHandling() {
    const generateVariantsBtn = document.getElementById('generate-variants-btn');
    const addAttributeBtn = document.getElementById('add-attribute-btn');

    if (generateVariantsBtn) {
        generateVariantsBtn.addEventListener('click', generateVariants);
    }

    if (addAttributeBtn) {
        addAttributeBtn.addEventListener('click', addAttribute);
    }

    // Remove attribute buttons
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-attribute')) {
            e.target.closest('.attribute-row').remove();
        }
    });
}

// Global variables for variant handling
let attributeIndex = 0;

function generateVariants() {
    debugLog('Generating variants');
    const selects = document.querySelectorAll('[name^="attributes["][name$="[id]"]');
    const names = document.querySelectorAll('[name^="attributes["][name$="[name]"]');
    const values = document.querySelectorAll('[name^="attributes["][name$="[values]"]');
    const variantContainer = document.getElementById('variant-container');

    if (!variantContainer) {
        debugLog('Variant container not found');
        return;
    }

    let attributeData = [];
    let hasValidAttribute = false;

    selects.forEach((select, index) => {
        const attrId = select.value;
        let attrName = names[index].value.trim();
        const valuesArray = values[index].value
            .split(',')
            .map(v => v.trim())
            .filter(v => v);

        if (attrId === 'new' && !attrName) {
            showFieldError(names[index], `Vui lòng nhập tên thuộc tính cho thuộc tính ${index + 1}.`);
            return;
        }

        if (attrId !== 'new' && attrId) {
            const selectedAttribute = window.allAttributes.find(attr => attr.id == attrId);
            if (selectedAttribute) {
                attrName = selectedAttribute.name;
            }
        }

        if (attrName && valuesArray.length) {
            attributeData.push({
                name: attrName,
                values: valuesArray
            });
            hasValidAttribute = true;
        }
    });

    if (!hasValidAttribute) {
        showFieldError(document.querySelector('#generate-variants-btn'),
            'Vui lòng nhập ít nhất một thuộc tính hợp lệ với tên và giá trị.');
        debugLog('No valid attributes provided');
        return;
    }

    // Lưu dữ liệu biến thể cũ nếu có (trên DOM)
    const oldVariants = {};
    const existingVariants = variantContainer.querySelectorAll('.variant-item');
    existingVariants.forEach((variant, index) => {
        const variantName = variant.querySelector('input[name$="[name]"]')?.value;
        if (variantName) {
            oldVariants[variantName] = {};
            const inputs = variant.querySelectorAll('input[type="number"], input[type="text"]');
            inputs.forEach(input => {
                const fieldName = input.name.match(/\[([^\]]+)\]$/)?.[1];
                if (fieldName) {
                    oldVariants[variantName][fieldName] = input.value;
                }
            });
        }
    });

    variantContainer.innerHTML = '';
    const variants = getCombinations(attributeData.map(attr => attr.values));
    debugLog('Generated variants', variants);

    // Map dữ liệu biến thể preload từ server (sau khi submit lỗi)
    const preloadedMap = (window.preloadedVariantValues || {});

    variants.forEach((variant, index) => {
        const variantDiv = document.createElement('div');
        variantDiv.classList.add('p-6', 'border', 'border-gray-300', 'rounded-md', 'mb-6', 'bg-white',
            'relative', 'variant-item');

        const variantName = variant.join(' - ');
        const oldData = preloadedMap[variantName] || oldVariants[variantName] || {};

        let variantHTML = `
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-semibold">Biến thể ${index + 1}: ${variantName}</h5>
                <div class="flex space-x-3">
                    <button type="button" class="text-red-500 hover:text-red-600 remove-variant">Xóa</button>
                    <button type="button" class="toggle-variants" data-index="${index}" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="toggle-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="variant-content transition-all duration-300 ease-in-out hidden">
                <input type="hidden" name="variants[${index}][index]" value="${index}">
                <input type="hidden" name="variants[${index}][name]" value="${variantName}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá gốc</label>
                        <input type="number" name="variants[${index}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá gốc" value="${oldData.price || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
                        <input type="number" name="variants[${index}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá nhập" value="${oldData.purchase_price || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                        <input type="number" name="variants[${index}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá bán" value="${oldData.sale_price || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">SKU</label>
                        <input type="text" name="variants[${index}][sku]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-2 focus:ring-blue-500" placeholder="Nhập SKU" value="${oldData.sku || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho</label>
                        <input type="number" name="variants[${index}][stock_total]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập số lượng" value="${oldData.stock_total || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                        <input type="number" name="variants[${index}][length]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều dài" value="${oldData.length || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                        <input type="number" name="variants[${index}][width]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều rộng" value="${oldData.width || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                        <input type="number" name="variants[${index}][height]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều cao" value="${oldData.height || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                        <input type="number" name="variants[${index}][weight]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Trọng lượng" value="${oldData.weight || ''}">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Thuộc tính biến thể</label>
                    ${variant.map((value, attrIndex) => `
                                                <div class="flex items-center gap-4 mb-2">
                                                    <input type="text" name="variants[${index}][attributes][${attrIndex}][name]" value="${attributeData[attrIndex].name}" placeholder="Tên thuộc tính" class="w-1/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                                    <input type="text" name="variants[${index}][attributes][${attrIndex}][value]" value="${value}" placeholder="Giá trị thuộc tính" class="w-2/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                                </div>
                                            `).join('')}
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                    @if (session('error'))
                        <span class="text-sm text-red-500 block mb-3">Vui lòng chọn lại ảnh
                            biến thể do lỗi trước đó.</span>
                    @endif
                    <input type="file" name="variant_images[${index}][]"
                        multiple
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        accept="image/*"
                        onchange="previewVariantImage(event, ${index})">
                    <div id="preview-images-${index}"
                        class="mt-2 flex flex-wrap gap-2"></div>
                </div>
                </div>
            </div>
        `;
        variantDiv.innerHTML = variantHTML;
        variantContainer.appendChild(variantDiv);

        variantDiv.querySelector('.remove-variant').addEventListener('click', () => {
            debugLog('Removing variant', {
                index
            });
            variantDiv.remove();
            updateVariantIndices();
        });
    });

    initializeToggleButtons();
    updateVariantIndices();
    debugLog('Variants generated', {
        count: variants.length
    });
}

function addAttribute() {
    debugLog('Adding new attribute');
    const container = document.getElementById('attribute-container');
    const newAttribute = document.createElement('div');
    newAttribute.classList.add('flex', 'items-center', 'gap-4', 'mb-2', 'attribute-row');
    newAttribute.innerHTML = `
        <select name="attributes[${attributeIndex}][id]" class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select">
            <option value="" disabled selected>Chọn hoặc nhập thuộc tính</option>
            <option value="new">Tạo thuộc tính mới</option>
            ${window.allAttributes
            .filter(attr => attr.id && attr.name)
            .map(attr => `<option value="${attr.id}">${attr.name}</option>`)
            .join('')}
        </select>
        <input type="text" name="attributes[${attributeIndex}][name]" class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name hidden" placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
        <input type="text" name="attributes[${attributeIndex}][values]" class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)">
        <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
    `;
    container.appendChild(newAttribute);

    const newSelect = newAttribute.querySelector('.attribute-select');
    newSelect.addEventListener('change', function () {
        updateAttributeValues(this);
    });

    newAttribute.querySelector('.remove-attribute').addEventListener('click', () => {
        debugLog('Removing attribute row', {
            index: attributeIndex
        });
        newAttribute.remove();
        updateAttributeIndices();
    });

    newAttribute.querySelector('input[name$="[name]"]').addEventListener('input', function () {
        const names = Array.from(container.querySelectorAll('input[name$="[name]"]'))
            .map(input => input.value.trim().toLowerCase());
        if (names.filter(name => name === this.value.trim().toLowerCase()).length > 1) {
            alert('Tên thuộc tính đã tồn tại!');
            this.value = '';
        }
    });

    attributeIndex++;
}

// Add attribute row with preset data
function addAttributeWithData(attributeDataObj) {
    addAttribute();
    const container = document.getElementById('attribute-container');
    const lastRow = container.querySelector('.attribute-row:last-child');
    if (!lastRow) return;

    const select = lastRow.querySelector('select[name$="[id]"]');
    const nameInput = lastRow.querySelector('input[name$="[name]"]');
    const valuesInput = lastRow.querySelector('input[name$="[values]"]');

    if (!select || !nameInput || !valuesInput) return;

    const selectedId = attributeDataObj?.id ?? '';
    const providedName = attributeDataObj?.name ?? '';
    const providedValues = attributeDataObj?.values ?? '';

    if (selectedId && selectedId !== 'new') {
        select.value = selectedId;
        updateAttributeValues(select);
        if (providedValues) valuesInput.value = providedValues;
    } else {
        select.value = 'new';
        updateAttributeValues(select);
        nameInput.value = providedName;
        valuesInput.value = providedValues;
    }
}

// Preload from server old inputs after validation error
function preloadOldData() {
    const oldAttrs = (window.oldAttributesData || []);
    const oldVars = (window.oldVariantsData || []);

    // Build a map for variant values keyed by variant name
    window.preloadedVariantValues = {};
    if (Array.isArray(oldVars)) {
        oldVars.forEach(v => {
            if (v && typeof v === 'object' && v.name) {
                const { name, price, purchase_price, sale_price, sku, stock_total, length, width, height, weight } = v;
                window.preloadedVariantValues[name] = { price, purchase_price, sale_price, sku, stock_total, length, width, height, weight };
            }
        });
    }

    if (Array.isArray(oldAttrs) && oldAttrs.length) {
        oldAttrs.forEach(attr => addAttributeWithData(attr));
        // Auto generate variants using restored attributes
        generateVariants();
    }
}

// Helper functions for variant handling
function updateAttributeValues(select) {
    debugLog('Updating attribute values', { selectValue: select.value });
    const row = select.closest('.attribute-row');
    const nameInput = row.querySelector('.attribute-name');
    const valuesInput = row.querySelector('.attribute-values');

    if (!nameInput || !valuesInput) {
        debugLog('Missing nameInput or valuesInput');
        return;
    }

    if (select.value === 'new') {
        nameInput.classList.remove('hidden');
        nameInput.value = '';
        valuesInput.value = '';
        debugLog('Selected new attribute, showing name input');
    } else {
        nameInput.classList.add('hidden');
        const selectedAttribute = window.allAttributes.find(attr => attr.id == select.value);
        if (selectedAttribute) {
            nameInput.value = selectedAttribute.name;
            valuesInput.value = Array.isArray(selectedAttribute.values) ?
                selectedAttribute.values.join(', ') :
                (selectedAttribute.values || '');
            debugLog('Updated attribute values', {
                name: selectedAttribute.name,
                values: valuesInput.value
            });
        } else {
            nameInput.value = '';
            valuesInput.value = '';
            select.value = '';
            alert('Thuộc tính được chọn không hợp lệ. Vui lòng chọn lại.');
            debugLog('Invalid attribute selected', { selectValue: select.value });
        }
    }
}

function updateAttributeIndices() {
    debugLog('Updating attribute indices');
    const attributeItems = document.querySelectorAll('#attribute-container .attribute-row');
    attributeItems.forEach((item, index) => {
        item.querySelector('select[name$="[id]"]').name = `attributes[${index}][id]`;
        item.querySelector('input[name$="[name]"]').name = `attributes[${index}][name]`;
        item.querySelector('input[name$="[values]"]').name = `attributes[${index}][values]`;
    });
    attributeIndex = attributeItems.length;
}

function updateVariantIndices() {
    debugLog('Updating variant indices');
    const variantItems = document.querySelectorAll('#variant-container > .variant-item');
    variantItems.forEach((item, index) => {
        const label = item.querySelector('h5');
        const nameInput = item.querySelector('input[name$="[name]"]');
        label.textContent = `Biến thể ${index + 1}: ${nameInput.value}`;
        const inputs = item.querySelectorAll('input[name]');
        inputs.forEach(input => {
            let oldName = input.getAttribute('name');
            let newName = oldName.replace(/\[\d+\]/, `[${index}]`);
            input.setAttribute('name', newName);
        });
        const toggleButton = item.querySelector('.toggle-variants');
        if (toggleButton) {
            toggleButton.setAttribute('data-index', index);
        }
        const previewImages = item.querySelector('div[id^="preview-images-"]');
        if (previewImages) {
            previewImages.id = `preview-images-${index}`;
        }
        const fileInput = item.querySelector('input[type="file"]');
        if (fileInput) {
            fileInput.setAttribute('onchange', `previewVariantImage(event, ${index})`);
        }
    });
}

function initializeToggleButtons() {
    debugLog('Initializing toggle buttons');
    const toggleButtons = document.querySelectorAll('.toggle-variants');
    toggleButtons.forEach(button => {
        button.removeEventListener('click', handleToggleClick);
        button.addEventListener('click', handleToggleClick);
    });
}

function handleToggleClick() {
    const variantItem = this.closest('.variant-item');
    const variantContent = variantItem.querySelector('.variant-content');
    const toggleIcon = this.querySelector('.toggle-icon path');
    
    if (!variantContent || !toggleIcon) {
        debugLog('Missing variantContent or toggleIcon');
        return;
    }

    const isOpen = !variantContent.classList.contains('hidden');
    
    // Toggle visibility
    variantContent.classList.toggle('hidden', isOpen);
    
    // Update icon
    toggleIcon.setAttribute('d', isOpen ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7');
    
    debugLog('Toggling variant', { isOpen: !isOpen });
}

function showFieldError(field, message) {
    clearFieldError(field);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
    field.classList.add('border-red-500');
}

function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    field.classList.remove('border-red-500');
}

// Helper function to generate combinations of attribute values
function getCombinations(arrays) {
    debugLog('Generating combinations', { arrays });
    
    if (arrays.length === 0) {
        return [];
    }
    
    if (arrays.length === 1) {
        return arrays[0].map(item => [item]);
    }
    
    const [firstArray, ...restArrays] = arrays;
    const restCombinations = getCombinations(restArrays);
    
    const result = [];
    for (const item of firstArray) {
        for (const combination of restCombinations) {
            result.push([item, ...combination]);
        }
    }
    
    debugLog('Generated combinations', { count: result.length, result });
    return result;
}

// Form Validation
function initializeFormValidation() {
    const form = document.getElementById('product-form');

    if (form) {
        form.addEventListener('submit', function (e) {
            const productName = document.getElementById('product-name');
            const sku = document.querySelector('input[name="sku"]');
            const productType = document.querySelector('input[name="product_type"]:checked');

            let isValid = true;

            // Validate product name
            if (!productName.value.trim()) {
                alert('Vui lòng nhập tên sản phẩm.');
                isValid = false;
            }

            // Validate SKU
            if (!sku.value.trim()) {
                alert('Vui lòng nhập SKU sản phẩm.');
                isValid = false;
            }

            // Validate product type
            if (!productType) {
                alert('Vui lòng chọn loại sản phẩm.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
}

// Character counter for product name
function initializeCharacterCounter() {
    const nameInput = document.getElementById('product-name');
    const nameCharCount = document.getElementById('name-char-count');
    const nameLengthWarning = document.getElementById('name-length-warning');

    if (nameInput && nameCharCount) {
        const updateCharCount = () => {
            const length = nameInput.value.length;
            nameCharCount.textContent = length;

            // Change color when approaching limit
            if (length >= 90) {
                nameCharCount.classList.add('text-red-500');
                nameCharCount.classList.remove('text-yellow-500', 'text-gray-400');
            } else if (length >= 80) {
                nameCharCount.classList.add('text-yellow-500');
                nameCharCount.classList.remove('text-red-500', 'text-gray-400');
            } else {
                nameCharCount.classList.remove('text-yellow-500', 'text-red-500');
                nameCharCount.classList.add('text-gray-400');
            }

            if (nameLengthWarning) {
                if (length > 100) {
                    nameLengthWarning.classList.remove('hidden');
                    nameInput.classList.add('border-red-500');
                } else {
                    nameLengthWarning.classList.add('hidden');
                    nameInput.classList.remove('border-red-500');
                }
            }
        };

        // Update counter on page load
        updateCharCount();

        // Update counter on input
        nameInput.addEventListener('input', updateCharCount);
    }
}

// Initialize character counter
initializeCharacterCounter();

