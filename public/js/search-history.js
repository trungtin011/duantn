class SearchHistoryManager {
    constructor() {
        this.searchInput = document.getElementById('searchInput');
        this.searchSuggestions = document.getElementById('searchSuggestions');
        this.searchForm = document.getElementById('searchForm');
        this.debounceTimer = null;
        this.isLoading = false;
        
        this.init();
    }

    init() {
        if (!this.searchInput || !this.searchSuggestions) {
            console.warn('Search elements not found');
            return;
        }

        this.bindEvents();
        this.loadSearchHistory();
    }

    bindEvents() {
        // Xử lý input tìm kiếm
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearchInput(e.target.value);
        });

        // Xử lý focus vào input
        this.searchInput.addEventListener('focus', () => {
            this.showSearchHistory();
        });

        // Xử lý click ngoài để ẩn gợi ý
        document.addEventListener('click', (e) => {
            if (!this.searchForm.contains(e.target)) {
                this.hideSuggestions();
            }
        });

        // Xử lý submit form
        this.searchForm.addEventListener('submit', (e) => {
            this.handleSearchSubmit(e);
        });

        // Xử lý phím mũi tên và Enter
        this.searchInput.addEventListener('keydown', (e) => {
            this.handleKeydown(e);
        });
    }

    handleSearchInput(query) {
        // Clear debounce timer
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }

        // Set debounce timer
        this.debounceTimer = setTimeout(() => {
            if (query.trim().length > 0) {
                this.getSearchSuggestions(query);
            } else {
                this.showSearchHistory();
            }
        }, 300);
    }

    async getSearchSuggestions(query) {
        if (this.isLoading) return;

        try {
            this.isLoading = true;
            this.showLoadingState();

            // Lấy cả gợi ý từ lịch sử và sản phẩm
            const [historyResponse, productsResponse] = await Promise.all([
                fetch(`/api/search-history/suggestions?query=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                }),
                fetch(`/api/search-history/quick-search?query=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
            ]);

            if (!historyResponse.ok || !productsResponse.ok) {
                throw new Error(`HTTP error! status: ${historyResponse.status} or ${productsResponse.status}`);
            }

            const historyData = await historyResponse.json();
            const productsData = await productsResponse.json();
            
            if (historyData.success && productsData.success) {
                this.displaySuggestions(historyData.suggestions, query, productsData.products);
            }
        } catch (error) {
            console.error('Error getting search suggestions:', error);
            this.hideSuggestions();
        } finally {
            this.isLoading = false;
        }
    }

    async loadSearchHistory() {
        try {
            const response = await fetch('/api/search-history', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                this.searchHistory = data.history;
            }
        } catch (error) {
            console.error('Error loading search history:', error);
        }
    }

    showSearchHistory() {
        if (!this.searchHistory || this.searchHistory.length === 0) {
            this.hideSuggestions();
            return;
        }

        this.displaySuggestions(this.searchHistory, '', []);
    }

    displaySuggestions(suggestions, currentQuery, products = []) {
        if ((!suggestions || suggestions.length === 0) && (!products || products.length === 0)) {
            this.hideSuggestions();
            return;
        }

        let html = '<div class="p-2">';
        
        // Hiển thị sản phẩm gợi ý nếu có
        if (products && products.length > 0) {
            html += '<div class="text-xs text-gray-500 mb-2 px-2 font-medium">Sản phẩm gợi ý:</div>';
            products.forEach((product) => {
                const price = product.final_price || product.sale_price || product.price || 0;
                const priceText = price > 0 ? new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(price) : 'Liên hệ';
                
                html += `
                    <div class="suggestion-item flex items-center p-2 hover:bg-gray-50 cursor-pointer border-b border-gray-100" 
                         data-product-id="${product.id}" data-product-slug="${product.slug}">
                        <div class="flex items-center flex-1">
                            <div class="product-image mr-3">
                                ${product.image ? `<img src="${product.image}" alt="${product.name}">` : '<i class="fa fa-image"></i>'}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">${product.name}</div>
                                <div class="text-xs text-gray-500">${product.shop_name}</div>
                                <div class="text-sm font-semibold text-red-600">${priceText}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        // Hiển thị lịch sử tìm kiếm nếu có
        if (suggestions && suggestions.length > 0) {
            if (products && products.length > 0) {
                html += '<div class="border-t border-gray-200 my-2"></div>';
            }
            
            if (currentQuery && currentQuery.trim().length > 0) {
                html += '<div class="text-xs text-gray-500 mb-2 px-2 font-medium">Gợi ý từ lịch sử:</div>';
            } else {
                html += '<div class="text-xs text-gray-500 mb-2 px-2 font-medium">Lịch sử tìm kiếm:</div>';
            }

            suggestions.forEach((suggestion, index) => {
                const isHighlighted = currentQuery && suggestion.toLowerCase().includes(currentQuery.toLowerCase());
                const highlightClass = isHighlighted ? 'bg-blue-50' : '';
                
                html += `
                    <div class="suggestion-item flex items-center justify-between p-2 hover:bg-gray-50 cursor-pointer ${highlightClass} border-b border-gray-100" 
                         data-query="${suggestion}">
                        <div class="flex items-center flex-1">
                            <i class="fa fa-history text-gray-400 mr-2 text-xs"></i>
                            <span class="text-sm text-gray-900">${suggestion}</span>
                        </div>
                        <button class="remove-suggestion text-gray-400 hover:text-red-500 text-xs ml-2" 
                                data-query="${suggestion}" title="Xóa khỏi lịch sử">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                `;
            });
        }

        // Thêm nút xóa toàn bộ lịch sử nếu có lịch sử
        if (suggestions && suggestions.length > 0) {
            html += `
                <div class="border-t pt-2 mt-2">
                    <button class="clear-all-history text-xs text-red-500 hover:text-red-700 w-full text-center py-1">
                        Xóa toàn bộ lịch sử
                    </button>
                </div>
            `;
        }

        html += '</div>';

        this.searchSuggestions.innerHTML = html;
        this.searchSuggestions.classList.remove('hidden');
        
        // Bind events cho các suggestion items
        this.bindSuggestionEvents();
    }

    bindSuggestionEvents() {
        // Xử lý click vào suggestion (lịch sử)
        const historyItems = this.searchSuggestions.querySelectorAll('.suggestion-item[data-query]');
        historyItems.forEach(item => {
            item.addEventListener('click', (e) => {
                if (!e.target.closest('.remove-suggestion')) {
                    const query = item.dataset.query;
                    this.searchInput.value = query;
                    this.hideSuggestions();
                    this.searchForm.submit();
                }
            });
        });

        // Xử lý click vào sản phẩm gợi ý
        const productItems = this.searchSuggestions.querySelectorAll('.suggestion-item[data-product-id]');
        productItems.forEach(item => {
            item.addEventListener('click', (e) => {
                const productSlug = item.dataset.productSlug;
                if (productSlug) {
                    // Chuyển hướng đến trang chi tiết sản phẩm
                    window.location.href = `/customer/products/product_detail/${productSlug}`;
                }
            });
        });

        // Xử lý xóa từng suggestion
        const removeButtons = this.searchSuggestions.querySelectorAll('.remove-suggestion');
        removeButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.stopPropagation();
                const query = button.dataset.query;
                await this.removeSearchHistory(query);
            });
        });

        // Xử lý xóa toàn bộ lịch sử
        const clearAllButton = this.searchSuggestions.querySelector('.clear-all-history');
        if (clearAllButton) {
            clearAllButton.addEventListener('click', async (e) => {
                e.stopPropagation();
                await this.clearAllSearchHistory();
            });
        }
    }

    async removeSearchHistory(query) {
        try {
            const response = await fetch('/api/search-history/destroy', {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ query })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                // Cập nhật local search history
                this.searchHistory = data.history;
                
                // Nếu đang hiển thị lịch sử, cập nhật lại
                if (!this.searchSuggestions.classList.contains('hidden')) {
                    this.showSearchHistory();
                }
            }
        } catch (error) {
            console.error('Error removing search history:', error);
        }
    }

    async clearAllSearchHistory() {
        try {
            const response = await fetch('/api/search-history/clear', {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                this.searchHistory = [];
                this.hideSuggestions();
            }
        } catch (error) {
            console.error('Error clearing search history:', error);
        }
    }

    handleSearchSubmit(e) {
        const query = this.searchInput.value.trim();
        
        if (query.length > 0) {
            // Lưu lịch sử tìm kiếm
            this.saveSearchHistory(query);
        }
        
        // Ẩn gợi ý
        this.hideSuggestions();
    }

    async saveSearchHistory(query) {
        try {
            const response = await fetch('/api/search-history/store', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ query })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                // Cập nhật local search history
                this.searchHistory = data.history;
            }
        } catch (error) {
            console.error('Error saving search history:', error);
        }
    }

    handleKeydown(e) {
        const suggestions = this.searchSuggestions.querySelectorAll('.suggestion-item');
        const currentIndex = Array.from(suggestions).findIndex(item => 
            item.classList.contains('bg-blue-100')
        );

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.navigateSuggestions(suggestions, currentIndex, 1);
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.navigateSuggestions(suggestions, currentIndex, -1);
                break;
            case 'Enter':
                const selectedItem = this.searchSuggestions.querySelector('.suggestion-item.bg-blue-100');
                if (selectedItem) {
                    e.preventDefault();
                    const query = selectedItem.dataset.query;
                    this.searchInput.value = query;
                    this.hideSuggestions();
                    this.searchForm.submit();
                }
                break;
            case 'Escape':
                this.hideSuggestions();
                this.searchInput.blur();
                break;
        }
    }

    navigateSuggestions(suggestions, currentIndex, direction) {
        if (suggestions.length === 0) return;

        // Remove current selection
        suggestions.forEach(item => item.classList.remove('bg-blue-100'));

        let newIndex;
        if (currentIndex === -1) {
            newIndex = direction > 0 ? 0 : suggestions.length - 1;
        } else {
            newIndex = currentIndex + direction;
            if (newIndex < 0) newIndex = suggestions.length - 1;
            if (newIndex >= suggestions.length) newIndex = 0;
        }

        // Add new selection
        suggestions[newIndex].classList.add('bg-blue-100');
        suggestions[newIndex].scrollIntoView({ block: 'nearest' });
    }

    showLoadingState() {
        this.searchSuggestions.innerHTML = `
            <div class="p-4 text-center">
                <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-gray-900"></div>
                <div class="text-sm text-gray-600 mt-2">Đang tìm kiếm...</div>
            </div>
        `;
        this.searchSuggestions.classList.remove('hidden');
    }

    hideSuggestions() {
        this.searchSuggestions.classList.add('hidden');
    }
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new SearchHistoryManager();
});
