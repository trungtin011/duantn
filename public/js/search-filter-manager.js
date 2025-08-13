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

        // Bind handlers to preserve instance context across dynamic re-attachments
        this.handleSortButtonClick = this.handleSortButtonClick.bind(this);
        this.handlePriceSortChange = this.handlePriceSortChange.bind(this);
        this.handlePriceSuggestion = this.handlePriceSuggestion.bind(this);

        // Initialize only if required elements exist
        if (this.form && this.productResults) {
            this.init();
        }
    }

    init() {
        try {
            this.attachEventListeners();
            this.updateResetButtonVisibility();
            this.initMobileFilter();
            this.setupErrorHandling();
        } catch (error) {
            console.error('Error in init:', error);
        }
    }

    attachEventListeners() {
        try {
            // Attach event listeners for all filter types
            this.attachCheckboxListeners('input[name="category[]"]');
            this.attachCheckboxListeners('input[name="brand[]"]');
            this.attachCheckboxListeners('input[name="shop[]"]');
            this.attachRadioListeners('input[name="rating"]'); // Thêm event listener cho rating filter
            this.attachCheckboxListeners('.filter-checkbox');

            // Price suggestions
            const priceButtons = document.querySelectorAll('.price-suggestion');
            priceButtons.forEach(button => {
                button.addEventListener('click', this.handlePriceSuggestion);
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
                btn.addEventListener('click', this.handleSortButtonClick);
            });

            // Price sort select
            const priceSelect = document.getElementById('price-sort-select');
            if (priceSelect) {
                priceSelect.addEventListener('change', this.handlePriceSortChange);
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
        } catch (error) {
            console.error('Error in attachEventListeners:', error);
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

    handlePriceSuggestion(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const min = button.getAttribute('data-min') || '';
        const max = button.getAttribute('data-max') || '';

        const minInput = document.getElementById('price_min');
        const maxInput = document.getElementById('price_max');

        if (minInput) minInput.value = min;
        if (maxInput) maxInput.value = max;

        this.updateResults();
        this.updateResetButtonVisibility();
    }

    handlePriceSortChange(e) {
        try {
            if (e.target.value) {
                this.updateResults({ sort: e.target.value });
                this.updateResetButtonVisibility();
            }
        } catch (error) {
            console.error('Error in handlePriceSortChange:', error);
            // Fallback: reload page with sort parameter
            const url = new URL(window.location);
            url.searchParams.set('sort', e.target.value);
            window.location.href = url.toString();
        }
    }

    handleSortButtonClick(e) {
        try {
            e.preventDefault();
            const sort = e.currentTarget.getAttribute('data-sort');
            if (sort) {
                this.updateResults({ sort: sort });
                this.updateResetButtonVisibility();
            }
        } catch (error) {
            console.error('Error in handleSortButtonClick:', error);
            // Fallback: reload page with sort parameter
            const url = new URL(window.location);
            url.searchParams.set('sort', e.currentTarget.getAttribute('data-sort'));
            window.location.href = url.toString();
        }
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

            // Reset sort to default
            const sortInput = this.form.querySelector('input[name="sort"]');
            if (sortInput) {
                sortInput.value = 'relevance';
            }
        }

        const minInput = document.getElementById('price_min');
        const maxInput = document.getElementById('price_max');
        if (minInput) minInput.value = '';
        if (maxInput) maxInput.value = '';

        // Reset price sort select
        const priceSortSelect = document.getElementById('price-sort-select');
        if (priceSortSelect) {
            priceSortSelect.value = 'price_asc';
        }

        // Reset sort buttons
        const sortButtons = document.querySelectorAll('.sort-btn');
        sortButtons.forEach(btn => {
            btn.classList.remove('bg-gray-800', 'text-white', 'border-transparent');
            btn.classList.add('hover:bg-gray-50');
        });
        const relevanceBtn = document.querySelector('[data-sort="relevance"]');
        if (relevanceBtn) {
            relevanceBtn.classList.remove('hover:bg-gray-50');
            relevanceBtn.classList.add('bg-gray-800', 'text-white', 'border-transparent');
        }

        this.updateResults();
        this.updateResetButtonVisibility();
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
            this.updateResetButtonVisibility();

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

        // Cập nhật trạng thái nút reset sau khi cập nhật filters
        this.updateResetButtonVisibility();
    }

    updateSortButtons(sort) {
        if (!sort) return;
        const sortButtons = document.querySelectorAll('.sort-btn');
        sortButtons.forEach(btn => {
            btn.classList.remove('bg-gray-800', 'text-white', 'border-transparent');
            btn.classList.add('hover:bg-gray-50');
        });
        const activeBtn = document.querySelector(`[data-sort="${sort}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('hover:bg-gray-50');
            activeBtn.classList.add('bg-gray-800', 'text-white', 'border-transparent');
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
        try {
            this.retryCount++;
            if (this.retryCount <= this.maxRetries) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lỗi tải dữ liệu',
                        text: `Đang thử lại... (${this.retryCount}/${this.maxRetries})`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
                setTimeout(() => {
                    this.updateResults();
                }, 1000 * this.retryCount);
            } else {
                this.showErrorState();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể tải kết quả tìm kiếm',
                        text: 'Vui lòng thử lại.',
                    });
                }
            }
        } catch (err) {
            console.error('Error in handleError:', err);
        }
    }

    showErrorState() {
        try {
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
        } catch (error) {
            console.error('Error in showErrorState:', error);
        }
    }

    updateResetButtonVisibility() {
        try {
            const resetBtn = document.getElementById('reset-filters');
            if (!resetBtn) return;
            
            // Kiểm tra các filter có giá trị
            const hasActiveFilters = this.hasAnyActiveFilters();
                
            if (hasActiveFilters) {
                resetBtn.classList.remove('hidden');
            } else {
                resetBtn.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error in updateResetButtonVisibility:', error);
        }
    }

    hasAnyActiveFilters() {
        try {
            // Kiểm tra category filters
            const categoryCheckboxes = document.querySelectorAll('input[name="category[]"]:checked');
            if (categoryCheckboxes.length > 0) return true;

            // Kiểm tra brand filters
            const brandCheckboxes = document.querySelectorAll('input[name="brand[]"]:checked');
            if (brandCheckboxes.length > 0) return true;

            // Kiểm tra shop filters
            const shopCheckboxes = document.querySelectorAll('input[name="shop[]"]:checked');
            if (shopCheckboxes.length > 0) return true;

            // Kiểm tra rating filter - chỉ coi là active nếu giá trị khác rỗng
            const ratingRadio = document.querySelector('input[name="rating"]:checked');
            if (ratingRadio && ratingRadio.value !== '') return true;

            // Kiểm tra price filters
            const priceMin = document.getElementById('price_min');
            const priceMax = document.getElementById('price_max');
            
            if (priceMin && priceMin.value && priceMin.value.trim() !== '') return true;
            if (priceMax && priceMax.value && priceMax.value.trim() !== '') return true;

            // Kiểm tra sort khác với mặc định
            const currentSort = new URLSearchParams(window.location.search).get('sort');
            if (currentSort && currentSort !== 'relevance') return true;

            // Kiểm tra price sort select khác với mặc định
            const priceSortSelect = document.getElementById('price-sort-select');
            if (priceSortSelect && priceSortSelect.value !== 'price_asc') return true;

            return false;
        } catch (error) {
            console.error('Error in hasAnyActiveFilters:', error);
            return false;
        }
    }

    reattachEventListeners() {
        try {
            this.attachCheckboxListeners('input[name="category[]"]');
            this.attachCheckboxListeners('input[name="brand[]"]');
            this.attachCheckboxListeners('input[name="shop[]"]');
            this.attachRadioListeners('input[name="rating"]');
            this.attachCheckboxListeners('.filter-checkbox');
            this.attachPriceInputListeners();
            
            // Reattach price sort select listener
            const priceSelect = document.getElementById('price-sort-select');
            if (priceSelect) {
                priceSelect.removeEventListener('change', this.handlePriceSortChange);
                priceSelect.addEventListener('change', this.handlePriceSortChange);
            }
            
            // Reattach sort buttons listeners
            const sortButtons = document.querySelectorAll('.sort-btn');
            sortButtons.forEach(btn => {
                btn.removeEventListener('click', this.handleSortButtonClick);
                btn.addEventListener('click', this.handleSortButtonClick);
            });
            
            // Reattach price suggestion buttons listeners
            const priceButtons = document.querySelectorAll('.price-suggestion');
            priceButtons.forEach(button => {
                button.removeEventListener('click', this.handlePriceSuggestion);
                button.addEventListener('click', this.handlePriceSuggestion);
            });
            
            // Cập nhật trạng thái nút reset sau khi reattach
            this.updateResetButtonVisibility();
        } catch (error) {
            console.error('Error in reattachEventListeners:', error);
        }
    }

    initMobileFilter() {
        try {
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
        } catch (error) {
            console.error('Error in initMobileFilter:', error);
        }
    }

    setupErrorHandling() {
        try {
            window.addEventListener('unhandledrejection', (event) => {
                console.error('Unhandled promise rejection:', event.reason);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Có lỗi không mong muốn xảy ra',
                    });
                }
            });
            window.addEventListener('error', (event) => {
                console.error('Global error:', event.error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Có lỗi xảy ra',
                    });
                }
            });
        } catch (error) {
            console.error('Error in setupErrorHandling:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    try {
        window.searchFilterManager = new SearchFilterManager();
    } catch (error) {
        console.error('Failed to initialize SearchFilterManager:', error);
        // Fallback: show error message to user
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-state';
        errorDiv.innerHTML = `
            <div class="text-red-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Lỗi khởi tạo bộ lọc</h3>
            <p class="text-gray-600 mb-4">Không thể khởi tạo bộ lọc tìm kiếm. Vui lòng tải lại trang.</p>
            <button onclick="window.location.reload()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                Tải lại trang
            </button>
        `;
        
        // Insert error message into page
        const container = document.querySelector('.container') || document.body;
        if (container) {
            container.insertBefore(errorDiv, container.firstChild);
        }
    }
});