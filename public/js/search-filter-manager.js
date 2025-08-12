// Search and Filter Manager Class - Optimized Version
class SearchFilterManager {
    constructor() {
        // Core elements
        this.form = document.getElementById('filter-form');
        this.productResults = document.getElementById('product-results');
        this.notificationContainer = null; // Không dùng nữa

        // State management
        this.currentRequest = null;
        this.isUpdating = false;
        this.priceTimeout = null;
        this.retryCount = 0;
        this.maxRetries = 3;
        this.requestTimeout = 30000; // 30 seconds
        this.debounceDelay = 500;

        // Initialize only if required elements exist
        if (this.form && this.productResults) {
            this.init();
        }
    }

    init() {
        this.attachEventListeners();
        this.updateResetButtonVisibility();
        this.initMobileFilter();
        this.setupErrorHandling();
    }

    attachEventListeners() {
        // Attach event listeners for all filter types
        this.attachCheckboxListeners('input[name="category[]"]');
        this.attachCheckboxListeners('input[name="brand[]"]');
        this.attachCheckboxListeners('input[name="shop[]"]');
        this.attachRadioListeners('input[name="rating"]'); // Thêm event listener cho rating filter
        this.attachCheckboxListeners('.filter-checkbox');

        // Price suggestions
        const priceButtons = document.querySelectorAll('.price-suggestion');
        priceButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handlePriceSuggestion(button);
            });
        });

        // Apply filters button
        const applyBtn = document.getElementById('apply-filters');
        if (applyBtn) {
            applyBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.updateResults();
            });
        }

        // Reset filters button
        const resetBtn = document.getElementById('reset-filters');
        if (resetBtn) {
            resetBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.resetFilters();
            });
        }

        // Sort buttons
        const sortButtons = document.querySelectorAll('.sort-btn');
        sortButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const sort = btn.getAttribute('data-sort');
                if (sort) {
                    this.updateResults({ sort: sort });
                }
            });
        });

        // Price sort select
        const priceSelect = document.getElementById('price-sort-select');
        if (priceSelect) {
            priceSelect.addEventListener('change', (e) => {
                if (e.target.value) {
                    this.updateResults({ sort: e.target.value });
                }
            });
        }

        // Price inputs with debounce
        this.attachPriceInputListeners();

        // Browser back/forward - simplified
        window.addEventListener('popstate', () => {
            window.location.reload();
        });

        // Handle form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.updateResults();
            });
        }
    }

    attachCheckboxListeners(selector) {
        const checkboxes = document.querySelectorAll(selector);
        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                this.updateResults();
                this.updateResetButtonVisibility();
            });
        });
    }

    attachRadioListeners(selector) {
        const radioButtons = document.querySelectorAll(selector);
        radioButtons.forEach(rb => {
            rb.addEventListener('change', () => {
                this.updateResults();
                this.updateResetButtonVisibility();
            });
        });
    }

    attachPriceInputListeners() {
        const priceInputs = ['#price_min', '#price_max'];
        priceInputs.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.addEventListener('input', () => {
                    this.updateResetButtonVisibility();
                    this.debouncePriceUpdate(() => {
                        if (element.value && element.value.trim() !== '') {
                            this.updateResults();
                        }
                    }, this.debounceDelay);
                });
            }
        });
    }

    handlePriceSuggestion(button) {
        const min = button.getAttribute('data-min') || '';
        const max = button.getAttribute('data-max') || '';

        const minInput = document.getElementById('price_min');
        const maxInput = document.getElementById('price_max');

        if (minInput) minInput.value = min;
        if (maxInput) maxInput.value = max;

        this.updateResults();
    }

    resetFilters() {
        if (this.form) {
            const checkboxes = this.form.querySelectorAll('.filter-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = false;
            });

            // Reset rating filter
            const ratingRadios = this.form.querySelectorAll('input[name="rating"]');
            ratingRadios.forEach(radio => {
                radio.checked = false;
            });
        }

        const minInput = document.getElementById('price_min');
        const maxInput = document.getElementById('price_max');
        if (minInput) minInput.value = '';
        if (maxInput) maxInput.value = '';

        this.updateResults();
    }

    debouncePriceUpdate(func, wait) {
        if (this.priceTimeout) {
            clearTimeout(this.priceTimeout);
        }
        this.priceTimeout = setTimeout(func, wait);
    }

    async updateResults(params = {}) {
        if (this.isUpdating) {
            console.log('Update already in progress, skipping...');
            return;
        }

        try {
            this.isUpdating = true;

            if (this.currentRequest && this.currentRequest.abort) {
                this.currentRequest.abort();
            }

            if (!this.form) {
                throw new Error('Filter form not found');
            }

            const formData = new FormData(this.form);

            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
                    formData.set(key, params[key]);
                }
            });

            const urlParams = new URLSearchParams(formData);
            // Build fetch URL with ajax=1
            const fetchParams = new URLSearchParams(urlParams);
            fetchParams.set('ajax', '1');
            const url = `${window.location.pathname}?${fetchParams.toString()}`;

            const controller = new AbortController();
            const timeoutId = setTimeout(() => {
                controller.abort();
            }, this.requestTimeout);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (!data || typeof data !== 'object') {
                throw new Error('Invalid response format');
            }

            if (data.error) {
                throw new Error(data.error);
            }

            // Push clean URL to history (without ajax=1)
            const displayParams = new URLSearchParams(urlParams);
            displayParams.delete('ajax');
            const displayUrl = `${window.location.pathname}?${displayParams.toString()}`;
            window.history.pushState({}, '', displayUrl);

            this.updateProductResults(data);
            this.updateFilters(data);
            this.updateSortButtons(params.sort || formData.get('sort'));
            this.updateProductCount(data.totalProducts);
            this.handleAutoScroll();

            // Không hiển thị thông báo thành công nữa
            this.retryCount = 0;

        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Error updating results:', error);
                this.handleError(error);
            }
        } finally {
            this.isUpdating = false;
            this.currentRequest = null;
        }
    }

    updateProductResults(data) {
        if (data && data.productList && this.productResults) {
            this.productResults.innerHTML = data.productList;
            this.productResults.classList.add('fade-in');
            this.reattachEventListeners();
        }
    }

    updateFilters(data) {
        const categoryContainer = document.getElementById('category-filters-container');
        if (categoryContainer && data && data.categoryFilters) {
            categoryContainer.innerHTML = data.categoryFilters;
            this.attachCheckboxListeners('input[name="category[]"]');
        }

        const brandContainer = document.getElementById('brand-filters-container');
        if (brandContainer && data && data.brandFilters) {
            brandContainer.innerHTML = data.brandFilters;
            this.attachCheckboxListeners('input[name="brand[]"]');
        }

        // Update shop filters
        const shopContainer = document.getElementById('shop-filters-container');
        if (shopContainer && data && data.shopFilters) {
            shopContainer.innerHTML = data.shopFilters;
            this.attachCheckboxListeners('input[name="shop[]"]');
        }

        // Update rating filters
        const ratingContainer = document.getElementById('rating-filters-container');
        if (ratingContainer && data && data.ratingFilters) {
            ratingContainer.innerHTML = data.ratingFilters;
            this.attachRadioListeners('input[name="rating"]');
        }
    }

    updateSortButtons(sort) {
        if (!sort) return;
        const sortButtons = document.querySelectorAll('.sort-btn');
        sortButtons.forEach(btn => {
            btn.classList.remove('bg-red-500', 'text-white');
            btn.classList.add('hover:bg-gray-100');
        });
        const activeBtn = document.querySelector(`[data-sort="${sort}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('hover:bg-gray-100');
            activeBtn.classList.add('bg-red-500', 'text-white');
        }
        const priceSelect = document.getElementById('price-sort-select');
        if (priceSelect && (sort === 'price_asc' || sort === 'price_desc')) {
            priceSelect.value = sort;
        }
    }

    updateProductCount(totalProducts) {
        const countElement = document.getElementById('product-count');
        if (countElement && totalProducts !== undefined) {
            countElement.textContent = totalProducts;
        }
    }

    handleAutoScroll() {
        const autoScrollToggle = document.getElementById('auto-scroll-toggle');
        if (autoScrollToggle && autoScrollToggle.checked && this.productResults) {
            this.productResults.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    handleError(error) {
        this.retryCount++;
        if (this.retryCount <= this.maxRetries) {
            Swal.fire({
                icon: 'warning',
                title: 'Lỗi tải dữ liệu',
                text: `Đang thử lại... (${this.retryCount}/${this.maxRetries})`,
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => {
                this.updateResults();
            }, 1000 * this.retryCount);
        } else {
            this.showErrorState();
            Swal.fire({
                icon: 'error',
                title: 'Không thể tải kết quả tìm kiếm',
                text: 'Vui lòng thử lại.',
            });
        }
    }

    showErrorState() {
        if (this.productResults) {
            this.productResults.innerHTML = `
                <div class="error-state">
                    <div class="text-red-500 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Có lỗi xảy ra</h3>
                    <p class="text-gray-600 mb-4">Không thể tải kết quả tìm kiếm. Vui lòng thử lại.</p>
                    <button onclick="window.location.reload()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Tải lại trang
                    </button>
                </div>
            `
        }
    }

    updateResetButtonVisibility() {
        const resetBtn = document.getElementById('reset-filters');
        if (!resetBtn) return;
        
        // Kiểm tra các filter có giá trị
        const hasActiveFilters =
            document.querySelectorAll('input[name="category[]"]:checked').length > 0 ||
            document.querySelectorAll('input[name="brand[]"]:checked').length > 0 ||
            document.querySelectorAll('input[name="shop[]"]:checked').length > 0 ||
            // Kiểm tra rating filter: chỉ coi là active nếu giá trị khác rỗng
            (document.querySelector('input[name="rating"]:checked') && document.querySelector('input[name="rating"]:checked').value !== '') ||
            (document.getElementById('price_min') && document.getElementById('price_min').value && document.getElementById('price_min').value.trim() !== '') ||
            (document.getElementById('price_max') && document.getElementById('price_max').value && document.getElementById('price_max').value.trim() !== '');
            
        if (hasActiveFilters) {
            resetBtn.classList.remove('hidden');
        } else {
            resetBtn.classList.add('hidden');
        }
    }

    reattachEventListeners() {
        this.attachCheckboxListeners('input[name="category[]"]');
        this.attachCheckboxListeners('input[name="brand[]"]');
        this.attachCheckboxListeners('input[name="shop[]"]');
        this.attachRadioListeners('input[name="rating"]');
        this.attachCheckboxListeners('.filter-checkbox');
        this.attachPriceInputListeners();
        const priceButtons = document.querySelectorAll('.price-suggestion');
        priceButtons.forEach(button => {
            button.removeEventListener('click', this.handlePriceSuggestion);
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handlePriceSuggestion(button);
            });
        });
    }

    initMobileFilter() {
        const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
        const filterContent = document.getElementById('filter-content');
        if (mobileFilterToggle && filterContent) {
            mobileFilterToggle.addEventListener('click', () => {
                const isHidden = filterContent.classList.contains('hidden');
                if (isHidden) {
                    filterContent.classList.remove('hidden');
                    const svg = mobileFilterToggle.querySelector('svg');
                    if (svg) svg.classList.add('rotate-180');
                } else {
                    filterContent.classList.add('hidden');
                    const svg = mobileFilterToggle.querySelector('svg');
                    if (svg) svg.classList.remove('rotate-180');
                }
            });
            filterContent.classList.add('hidden');
        }
    }

    setupErrorHandling() {
        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason);
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi không mong muốn xảy ra',
            });
        });
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi xảy ra',
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    try {
        new SearchFilterManager();
    } catch (error) {
        console.error('Failed to initialize SearchFilterManager:', error);
    }
});