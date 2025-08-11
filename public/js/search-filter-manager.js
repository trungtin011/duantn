// Search and Filter Manager Class - Optimized Version
class SearchFilterManager {
    constructor() {
        // Core elements
        this.form = document.getElementById('filter-form');
        this.productResults = document.getElementById('product-results');
        this.notificationContainer = document.getElementById('notification-container');
        
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
        // Category and brand checkboxes - with null checks
        this.attachCheckboxListeners('input[name="category[]"]');
        this.attachCheckboxListeners('input[name="brand[]"]');
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
            // Simple reload instead of complex state management
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
        // Reset checkboxes
        if (this.form) {
            const checkboxes = this.form.querySelectorAll('.filter-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
        }

        // Reset price inputs
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
        // Prevent multiple concurrent updates
        if (this.isUpdating) {
            console.log('Update already in progress, skipping...');
            return;
        }

        try {
            this.isUpdating = true;

            // Cancel previous request if exists
            if (this.currentRequest && this.currentRequest.abort) {
                this.currentRequest.abort();
            }

            // Prepare form data with null checks
            if (!this.form) {
                throw new Error('Filter form not found');
            }

            const formData = new FormData(this.form);
            
            // Add additional parameters
            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
                    formData.set(key, params[key]);
                }
            });

            // Create URL params
            const urlParams = new URLSearchParams(formData);
            const url = `${window.location.pathname}?${urlParams.toString()}`;

            // Create abort controller for timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => {
                controller.abort();
            }, this.requestTimeout);

            // Make request with proper error handling
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
            
            // Validate response data
            if (!data || typeof data !== 'object') {
                throw new Error('Invalid response format');
            }
            
            // Check for server-side errors
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Update URL
            window.history.pushState({}, '', url);

            // Update results with null checks
            this.updateProductResults(data);
            this.updateFilters(data);
            this.updateSortButtons(params.sort || formData.get('sort'));
            this.updateProductCount(data.totalProducts);
            this.handleAutoScroll();
            this.showNotification('Kết quả đã được cập nhật', 'success');

            // Reset retry count on success
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
            
            // Re-attach event listeners for new elements
            this.reattachEventListeners();
        }
    }

    updateFilters(data) {
        // Update category filters with null checks
        const categoryContainer = document.getElementById('category-filters-container');
        if (categoryContainer && data && data.categoryFilters) {
            categoryContainer.innerHTML = data.categoryFilters;
            // Re-attach event listeners for new category checkboxes
            this.attachCheckboxListeners('input[name="category[]"]');
        }

        // Update brand filters with null checks
        const brandContainer = document.getElementById('brand-filters-container');
        if (brandContainer && data && data.brandFilters) {
            brandContainer.innerHTML = data.brandFilters;
            // Re-attach event listeners for new brand checkboxes
            this.attachCheckboxListeners('input[name="brand[]"]');
        }
    }

    updateSortButtons(sort) {
        if (!sort) return;

        // Reset all sort buttons
        const sortButtons = document.querySelectorAll('.sort-btn');
        sortButtons.forEach(btn => {
            btn.classList.remove('bg-red-500', 'text-white');
            btn.classList.add('hover:bg-gray-100');
        });

        // Set active button
        const activeBtn = document.querySelector(`[data-sort="${sort}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('hover:bg-gray-100');
            activeBtn.classList.add('bg-red-500', 'text-white');
        }

        // Update price sort select
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
            this.showNotification(`Lỗi tải dữ liệu, đang thử lại... (${this.retryCount}/${this.maxRetries})`, 'warning');
            setTimeout(() => {
                this.updateResults();
            }, 1000 * this.retryCount);
        } else {
            this.showErrorState();
            this.showNotification('Không thể tải kết quả tìm kiếm. Vui lòng thử lại.', 'error');
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
            `;
        }
    }

    showNotification(message, type = 'info') {
        if (!this.notificationContainer || !message) return;

        const notification = document.createElement('div');
        notification.className = `notification ${this.getNotificationClass(type)}`;
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                ${this.getNotificationIcon(type)}
                <span class="text-sm">${message}</span>
            </div>
        `;

        this.notificationContainer.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    getNotificationClass(type) {
        const classes = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        return classes[type] || classes.info;
    }

    getNotificationIcon(type) {
        const icons = {
            success: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
            error: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
            warning: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
            info: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        };
        return icons[type] || icons.info;
    }

    updateResetButtonVisibility() {
        const resetBtn = document.getElementById('reset-filters');
        if (!resetBtn) return;

        const hasActiveFilters =
            document.querySelectorAll('input[name="category[]"]:checked').length > 0 ||
            document.querySelectorAll('input[name="brand[]"]:checked').length > 0 ||
            (document.getElementById('price_min') && document.getElementById('price_min').value) ||
            (document.getElementById('price_max') && document.getElementById('price_max').value);

        if (hasActiveFilters) {
            resetBtn.classList.remove('hidden');
        } else {
            resetBtn.classList.add('hidden');
        }
    }

    reattachEventListeners() {
        // Re-attach all event listeners for new elements
        this.attachCheckboxListeners('input[name="category[]"]');
        this.attachCheckboxListeners('input[name="brand[]"]');
        this.attachCheckboxListeners('.filter-checkbox');
        this.attachPriceInputListeners();
        
        // Re-attach price suggestion buttons
        const priceButtons = document.querySelectorAll('.price-suggestion');
        priceButtons.forEach(button => {
            // Remove existing listeners to prevent duplicates
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

            // Hide filter content on mobile by default
            filterContent.classList.add('hidden');
        }
    }

    setupErrorHandling() {
        // Handle unhandled promise rejections
        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason);
            this.showNotification('Có lỗi không mong muốn xảy ra', 'error');
        });

        // Handle global errors
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
            this.showNotification('Có lỗi xảy ra', 'error');
        });
    }
}

// Initialize when DOM is ready with error handling
document.addEventListener('DOMContentLoaded', () => {
    try {
        new SearchFilterManager();
    } catch (error) {
        console.error('Failed to initialize SearchFilterManager:', error);
    }
});
