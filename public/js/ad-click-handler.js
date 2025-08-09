/**
 * Ad Click Handler - Xử lý click quảng cáo và trừ phí
 */
class AdClickHandler {
    constructor() {
        this.init();
    }

    init() {
        // Bind click events cho tất cả quảng cáo
        this.bindAdClickEvents();
        
        // Bind click events cho banner quảng cáo
        this.bindBannerClickEvents();
    }

    /**
     * Bind click events cho quảng cáo sản phẩm
     */
    bindAdClickEvents() {
        // Quảng cáo sản phẩm
        document.addEventListener('click', (e) => {
            const adElement = e.target.closest('[data-ad-campaign]');
            if (adElement) {
                e.preventDefault();
                this.handleAdClick(adElement, 'product');
            }
        });

        // Quảng cáo shop
        document.addEventListener('click', (e) => {
            const shopAdElement = e.target.closest('[data-shop-ad]');
            if (shopAdElement) {
                e.preventDefault();
                this.handleAdClick(shopAdElement, 'shop');
            }
        });
    }

    /**
     * Bind click events cho banner quảng cáo
     */
    bindBannerClickEvents() {
        // Banner quảng cáo
        document.addEventListener('click', (e) => {
            const bannerElement = e.target.closest('[data-banner-ad]');
            if (bannerElement) {
                e.preventDefault();
                this.handleAdClick(bannerElement, 'banner');
            }
        });
    }

    /**
     * Xử lý click quảng cáo
     */
    async handleAdClick(element, clickType) {
        try {
            const campaignId = element.dataset.adCampaign || element.dataset.shopAd || element.dataset.bannerAd;
            const shopId = element.dataset.shopId;
            const productId = element.dataset.productId;
            const targetUrl = element.href || element.dataset.targetUrl;

            if (!campaignId) {
                console.error('Thiếu thông tin quảng cáo:', { campaignId, shopId });
                return;
            }
            
            // Nếu là banner quảng cáo (shop_id = 0), không cần kiểm tra shop_id
            if (clickType === 'banner' && shopId == 0) {
                // Banner quảng cáo không cần shop_id
            } else if (!shopId) {
                console.error('Thiếu shop_id cho quảng cáo:', { campaignId, shopId });
                return;
            }

            // Hiển thị loading
            this.showLoading(element);

            // Gọi API để track click và trừ phí
            const response = await this.trackAdClick({
                campaign_id: campaignId,
                shop_id: shopId,
                product_id: productId,
                click_type: clickType
            });

            if (response.success) {
                // Hiển thị thông báo thành công
                this.showSuccessMessage(element, response.cost);
                
                // Chuyển hướng sau khi xử lý xong
                setTimeout(() => {
                    if (targetUrl) {
                        window.location.href = targetUrl;
                    }
                }, 1000);
            } else {
                // Hiển thị thông báo lỗi
                this.showErrorMessage(element, response.message);
            }

        } catch (error) {
            console.error('Lỗi xử lý click quảng cáo:', error);
            this.showErrorMessage(element, 'Có lỗi xảy ra khi xử lý quảng cáo');
        } finally {
            // Ẩn loading
            this.hideLoading(element);
        }
    }

    /**
     * Gọi API track click quảng cáo
     */
    async trackAdClick(data) {
        try {
            const response = await fetch('/ad/click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Lỗi gọi API track click:', error);
            throw error;
        }
    }

    /**
     * Hiển thị loading
     */
    showLoading(element) {
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'ad-click-loading';
        loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        loadingDiv.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            font-size: 14px;
        `;
        
        element.style.position = 'relative';
        element.appendChild(loadingDiv);
    }

    /**
     * Ẩn loading
     */
    hideLoading(element) {
        const loadingDiv = element.querySelector('.ad-click-loading');
        if (loadingDiv) {
            loadingDiv.remove();
        }
    }

    /**
     * Hiển thị thông báo thành công
     */
    showSuccessMessage(element, cost) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'ad-click-success';
        messageDiv.innerHTML = `<i class="fas fa-check-circle"></i> Đã ghi nhận click quảng cáo! (Phí: ${cost} VNĐ)`;
        messageDiv.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            font-size: 14px;
            white-space: nowrap;
        `;
        
        element.appendChild(messageDiv);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 3000);
    }

    /**
     * Hiển thị thông báo lỗi
     */
    showErrorMessage(element, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'ad-click-error';
        messageDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        messageDiv.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(239, 68, 68, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            font-size: 14px;
            white-space: nowrap;
        `;
        
        element.appendChild(messageDiv);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 3000);
    }

    /**
     * Lấy thông tin chi phí quảng cáo
     */
    async getAdCost(campaignId) {
        try {
            const response = await fetch(`/ad/cost?campaign_id=${campaignId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Lỗi lấy thông tin chi phí quảng cáo:', error);
            throw error;
        }
    }
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.adClickHandler = new AdClickHandler();
});

// Export để sử dụng ở nơi khác
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AdClickHandler;
}
