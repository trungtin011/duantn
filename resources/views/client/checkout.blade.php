@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/client-checkout.css') }}">
@endpush

@section('content')
            <div class="roadmap">
                <span class="nothing">Tài khoản</span>
                <div class="line-e"></div>
                <span class="nothing">Tài khoản của tôi </span>
                <div class="line-e"></div>
                <span class="nothing">Sản phẩm</span>
                <div class="line-e"></div>
                <span class="nothing">Xem giỏ hàng</span>
                <div class="line-e"></div>
                <span class="nothing">Thanh toán</span>
            </div>
            <div class="frame-15">
                <span class="chi-tiet-hoa-don">Chi tiết hóa đơn </span>
            </div>
    <div class="main-container row">
        <div class="order-receiver-info col-12 col-md-6">
          
            <div class="information-section">
                <form action="/submit-address" method="POST" class="shipping-address">
                    <div class="select-address">
                        <div class="select-address-title">Chọn địa chỉ</div>
                        <select name="address" class="select-address-select">
                            <option value="1">51 Nguyễn Sinh Sắc, P.Tân Hoà, Thành phố Buôn Ma Thuột, ...</option>
                            <option value="2">26 Nguyễn Chánh, P.Tân Phong, Quận 7, Hồ Chí Minh</option>
                            <option value="3">123 Nguyễn Văn Cừ, P.4, Quận 5, Hồ Chí Minh</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                      <div class="form-group col-6">
                          <label class="form-label">
                              <span class="input-title">Tên Người Nhận</span><span class="asterisk">*</span>
                              <input type="text" class="form-input">
                          </label>
                      </div>

                      <div class="form-group col-6">
                          <label class="form-label">
                              <span class="input-title">Số điện thoại</span><span class="asterisk">*</span>
                              <input type="text" class="form-input">
                          </label>
                      </div>
                    </div>

                    <button type="button" class="button-submit" id="showAddressForm">Thêm địa chỉ</button>
                </form>
            </div>

            <div class="information-section create-address-form" style="display: none;">
                <form action="/create-address-form" method="POST" class="create-address-form">
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label class="form-label">
                                <span class="input-title">Tên Người Nhận</span><span class="asterisk">*</span>
                                <input type="text" class="form-input">
                            </label>
                        </div>
                        <div class="form-group col-6">
                            <label class="form-label">
                                <span class="input-title">Số điện thoại</span><span class="asterisk">*</span>
                                <input type="text" class="form-input">
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <span class="input-title">Loại Địa chỉ</span><span class="asterisk">*</span>
                            <select name="address-type" class="form-input">
                                <option value="home">Nhà</option>
                                <option value="office">Văn phòng</option>
                                <option value="other">Khác</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label class="form-label">
                                <span class="input-title">Thành Phố, Tỉnh</span><span class="asterisk">*</span>
                            </label>
                            <select name="city" id="city" class="form-input">
                                <option value="">Chọn Tỉnh/Thành phố</option>
                            </select>
                        </div>

                        <div class="form-group col-6">
                            <label class="form-label">
                                <span class="input-title">Quận, Huyện</span><span class="asterisk">*</span>
                            </label>
                            <select name="district" id="district" class="form-input" disabled>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-6">
                          <label class="form-label">
                              <span class="input-title">Phường, Xã</span><span class="asterisk">*</span>
                          </label>
                          <select name="ward" id="ward" class="form-input" disabled>
                              <option value="">Chọn Phường/Xã</option>
                          </select>
                      </div>
                      <div class="form-group col-6">
                            <label class="form-label">
                                <span class="input-title">Địa chỉ cụ thể</span><span class="asterisk">*</span>
                            </label>
                            <input type="text" class="form-input" name="street">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="input-title">Mã bưu điện</span>
                            <input type="text" class="form-input">
                        </label>
                    </div>

                    <label class="custom-checkbox">
                      <input type="checkbox" class="is-default-button">
                      <span class="checkmark"></span>
                      Đặt làm địa chỉ mặc định
                    </label>

                    <button type="button" class="button-submit">Thêm địa chỉ</button>
                </form>
            </div>
        </div>

        <div class="product-list col-12 col-md-6">
            <div class="product-section">
                <div class="product-item">
                    <div class="product-image"><img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-05-27/eyX7CyV5Jm.png" alt="Product Image"></div>
                    <div class="product-info">
                        <span class="product-name">LCD Monitor</span><span class="quantity">x1</span> <span class="dollar">$650</span>
                    </div>
                </div>
                <div class="product-item">
                    <div class="product-image"><img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-05-27/eyX7CyV5Jm.png" alt="Product Image"></div>
                    <div class="product-info">
                        <span class="product-name">H1 Gamepad</span><span class="quantity">x1</span> <span class="dollar">$1100</span>
                    </div>
                </div>
            </div>

            <div class="order-summary">
                <div class="order-summary-title">Tóm tắt đơn hàng</div>
                <div class="order-info">
                    <div class="order-info-item">
                        <div class="order-info-item-content">
                            <div class="order-sub-total">
                                <span class="total-cost">Tổng cộng:</span><span class="sumary-price">$1750</span>
                            </div>
                            <div class="underline-product-list"><div class="line-product-list"></div></div>
                        </div>
                        <div class="order-shipping-fee">
                            <span class="shipping-fee-title">Phí vận chuyển:</span><span class="shipping-fee-price">Miễn phí</span>
                        </div>
                    </div>
                    <div class="underline-product-list"><div class="line-product-list"></div></div>
                </div>
                <div class="frame-5d">
                    <span class="total-order">Tổng đơn:</span><span class="sumary-price">$1750</span>
                </div>
            </div>

            <form class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment" value="bank" />
                    <span class="radio-style"></span>
                    <span class="bank-payment">Thanh toán bằng ngân hàng</span>
                    <span class="bank-icons">
                        <div class="bkash"><div class="image"></div></div>
                        <div class="visa"><div class="image-62"></div></div>
                        <div class="mastercard"><div class="image-63"></div></div>
                        <div class="nagad"><div class="image-64"></div></div>
                    </span>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment" value="cash" />
                    <span class="radio-style"></span>
                    <span class="cash-payment">Thanh toán bằng tiền mặt</span>
                </label>
            </form>
 
            <div class="coupon-code">
                <label class="coupon-code-input">
                    <input type="text" class="apply-discount" placeholder="Mã giảm giá"></input>
                </label>
                <div class="coupon-code-button">
                    <button class="button-coupon">Áp dụng mã giảm giá</button>
                </div>  
            </div>
            <div class="order-submit-button-container">
                <button type="submit" class="order-submit-button">Đặt hàng</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showAddressFormBtn = document.getElementById('showAddressForm');  
            const createAddressForm = document.querySelector('.create-address-form');
            
            showAddressFormBtn.addEventListener('click', function() {
                createAddressForm.style.display = createAddressForm.style.display === 'none' ? 'block' : 'none';
            });

            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');

            const API_URL = 'https://provinces.open-api.vn/api/';

            async function fetchCities() {
                try {
                    const response = await fetch(API_URL + '?depth=1');
                    const cities = await response.json();
                    
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.code;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error fetching cities:', error);
                }
            }

            // Fetch districts
            async function fetchDistricts(cityCode) {
                try {
                    const response = await fetch(API_URL + `p/${cityCode}?depth=2`);
                    const city = await response.json();
                    
                    // Clear previous options
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    
                    city.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                    
                    districtSelect.disabled = false;
                } catch (error) {
                    console.error('Error fetching districts:', error);
                }
            }

            // Fetch wards
            async function fetchWards(districtCode) {
                try {
                    const response = await fetch(API_URL + `d/${districtCode}?depth=2`);
                    const district = await response.json();
                    
                    // Clear previous options
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    
                    district.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                    
                    wardSelect.disabled = false;
                } catch (error) {
                    console.error('Error fetching wards:', error);
                }
            }

            // Event listeners
            citySelect.addEventListener('change', function() {
                if (this.value) {
                    fetchDistricts(this.value);
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = true;
                } else {
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    districtSelect.disabled = true;
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = true;
                }
            });

            districtSelect.addEventListener('change', function() {
                if (this.value) {
                    fetchWards(this.value);
                } else {
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = true;
                }
            });

            // Initialize cities
            fetchCities();
        });
    </script>
    
@endpush