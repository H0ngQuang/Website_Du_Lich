@include('clients.blocks.header')
@include('clients.blocks.banner')

<section class="container" style="margin-top:50px; margin-bottom: 100px">
    {{-- <h1 class="text-center booking-header">Tổng Quan Về Chuyến Đi</h1> --}}

    <form action="{{ route('create-booking') }}" method="post" class="booking-container">
        @csrf
        <!-- Contact Information -->
        <div class="booking-info">
            <h2 class="booking-header">Thông Tin Liên Lạc</h2>
            <div class="booking__infor">
                <div class="form-group">
                    <label for="username">Họ và tên*</label>
                    <input type="text" id="username" placeholder="Nhập Họ và tên" name="fullName" required>
                    <span class="error-message" id="usernameError"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" placeholder="sample@gmail.com" name="email" required>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="tel">Số điện thoại*</label>
                    <input type="number" id="tel" placeholder="Nhập số điện thoại liên hệ" name="tel"
                        required>
                    <span class="error-message" id="telError"></span>
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ*</label>
                    <input type="text" id="address" placeholder="Nhập địa chỉ liên hệ" name="address" required>
                    <span class="error-message" id="addressError"></span>
                </div>

            </div>


            <!-- Passenger Details -->
            <h2 class="booking-header">Hành Khách</h2>

            <div class="booking__quantity">
                <div class="form-group quantity-selector">
                    <label>Người lớn</label>
                    <div class="input__quanlity">
                        <button type="button" class="quantity-btn">-</button>
                        <input type="number" class="quantity-input" value="1" min="1" id="numAdults"
                            name="numAdults" data-price-adults="{{ $tour->priceAdult }}" readonly>
                        <button type="button" class="quantity-btn">+</button>
                    </div>
                </div>

                <div class="form-group quantity-selector">
                    <label>Trẻ em</label>
                    <div class="input__quanlity">
                        <button type="button" class="quantity-btn">-</button>
                        <input type="number" class="quantity-input" value="0" min="0" id="numChildren"
                            name="numChildren" data-price-children="{{ $tour->priceChild }}" readonly>
                        <button type="button" class="quantity-btn">+</button>
                    </div>
                </div>
            </div>
            <!-- Privacy Agreement Section -->
            <div class="privacy-section">
                <p>Bằng cách nhấp chuột vào nút "ĐỒNG Ý" dưới đây, Khách hàng đồng ý rằng các điều kiện điều khoản
                    này sẽ được áp dụng. Vui lòng đọc kỹ điều kiện điều khoản trước khi lựa chọn sử dụng dịch vụ của
                    Travela.</p>
                <div class="privacy-checkbox">
                    <input type="checkbox" id="agree" name="agree" required>
                    <label for="agree">Tôi đã đọc và đồng ý với <a href="#" target="_blank">Điều khoản thanh
                            toán</a></label>
                </div>
            </div>
            <!-- Payment Method -->
            <h2 class="booking-header">Phương Thức Thanh Toán</h2>

            <div class="payment-methods-container">
                <label class="payment-option" for="pay-office">
                    <input type="radio" name="payment" value="office-payment" id="pay-office" required>
                    <div class="payment-option-icon">
                        <img src="{{ asset('clients/assets/images/contact/icon.png') }}" alt="Office Payment">
                    </div>
                    <div class="payment-option-info">
                        <span class="payment-option-title">Thanh toán tại văn phòng</span>
                        <span class="payment-option-desc">Thanh toán trực tiếp tại văn phòng Travela</span>
                    </div>
                    <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                </label>

                <label class="payment-option" for="pay-momo">
                    <input type="radio" name="payment" value="momo-payment" id="pay-momo" required>
                    <div class="payment-option-icon">
                        <img src="{{ asset('clients/assets/images/booking/thanh-toan-momo.jpg') }}" alt="MoMo">
                    </div>
                    <div class="payment-option-info">
                        <span class="payment-option-title">Ví MoMo</span>
                        <span class="payment-option-desc">Thanh toán nhanh qua ví điện tử MoMo</span>
                    </div>
                    <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                    @if (!is_null($transIdMomo))
                        <input type="hidden" name="transactionIdMomo" value="{{ $transIdMomo }}">
                    @endif
                </label>

                <label class="payment-option" for="pay-paypal">
                    <input type="radio" name="payment" value="paypal-payment" id="pay-paypal" required>
                    <div class="payment-option-icon">
                        <img src="{{ asset('clients/assets/images/booking/cong-thanh-toan-paypal.jpg') }}" alt="PayPal">
                    </div>
                    <div class="payment-option-info">
                        <span class="payment-option-title">PayPal</span>
                        <span class="payment-option-desc">Thanh toán quốc tế an toàn qua PayPal</span>
                    </div>
                    <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                </label>

                <label class="payment-option" for="pay-vnpay">
                    <input type="radio" name="payment" value="vnpay-payment" id="pay-vnpay" required>
                    <div class="payment-option-icon">
                        <img src="{{ asset('clients/assets/images/booking/icon-vnpay.png') }}" alt="VNPay">
                    </div>
                    <div class="payment-option-info">
                        <span class="payment-option-title">VNPay</span>
                        <span class="payment-option-desc">Thanh toán qua cổng VNPay (ATM, Visa, QR)</span>
                    </div>
                    <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                    @if (isset($transIdVnpay) && !is_null($transIdVnpay))
                        <input type="hidden" name="transactionIdVnpay" value="{{ $transIdVnpay }}">
                    @endif
                </label>

                <label class="payment-option" for="pay-bank">
                    <input type="radio" name="payment" value="bank-transfer" id="pay-bank" required>
                    <div class="payment-option-icon">
                        <img src="{{ asset('clients/assets/images/booking/icon-bank-transfer.png') }}" alt="Bank Transfer">
                    </div>
                    <div class="payment-option-info">
                        <span class="payment-option-title">Chuyển khoản ngân hàng</span>
                        <span class="payment-option-desc">Chuyển khoản trực tiếp qua tài khoản ngân hàng</span>
                    </div>
                    <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                </label>
            </div>

            <!-- Bank Transfer Info (hidden by default) -->
            <div id="bank-transfer-info" class="bank-transfer-panel" style="display: none;">
                <div class="bank-transfer-header">
                    <i class="fas fa-university"></i>
                    <h4>Thông tin chuyển khoản</h4>
                </div>
                <div class="bank-transfer-details">
                    <div class="bank-detail-row">
                        <span class="bank-label">Ngân hàng:</span>
                        <span class="bank-value">Vietcombank (VCB)</span>
                    </div>
                    <div class="bank-detail-row">
                        <span class="bank-label">Số tài khoản:</span>
                        <span class="bank-value bank-account-number">1234567890</span>
                        <button type="button" class="btn-copy-bank" onclick="copyBankAccount()"><i class="fas fa-copy"></i></button>
                    </div>
                    <div class="bank-detail-row">
                        <span class="bank-label">Chủ tài khoản:</span>
                        <span class="bank-value">NGUYEN HONG QUANG</span>
                    </div>
                    <div class="bank-detail-row">
                        <span class="bank-label">Nội dung CK:</span>
                        <span class="bank-value bank-content">TRAVELA <span class="bank-tour-id">{{ $tour->tourId }}</span> <span class="bank-phone-placeholder">SDT</span></span>
                    </div>
                    <div class="bank-transfer-note">
                        <i class="fas fa-info-circle"></i>
                        <span>Vui lòng chuyển khoản đúng nội dung để chúng tôi xác nhận đơn hàng nhanh nhất.</span>
                    </div>
                </div>
            </div>

            <input type="hidden" name="payment_hidden" id="payment_hidden">
        </div>

        <!-- Order Summary -->
        <div class="booking-summary">
            <div class="summary-section">
                <div>
                    <p>Mã tour : {{ $tour->tourId }}</p>
                    <input type="hidden" name="tourId" id="tourId" value="{{ $tour->tourId }}" data-sale-percent="{{ $tour->sale_percent ?? 0 }}">
                    <h5 class="widget-title">{{ $tour->title }}</h5>
                    <p>Ngày khởi hành : {{ date('d-m-Y', strtotime($tour->startDate)) }}</p>
                    <p>Ngày kết thúc : {{ date('d-m-Y', strtotime($tour->endDate)) }}</p>
                    <p class="quantityAvailable">Số chỗ còn nhận : {{ $tour->quantity }}</p>
                </div>

                <div class="order-summary">
                    <div class="summary-item">
                        <span>Người lớn:</span>
                        <div>
                            <span class="quantity__adults">1</span>
                            <span>X</span>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Trẻ em:</span>
                        <div>
                            <span class="quantity__children">0</span>
                            <span>X</span>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div id="discount-wrap">
                        <div class="summary-item">
                            <span>Giảm giá:</span>
                            <div>
                                <span class="total-price">0 VNĐ</span>
                            </div>
                        </div>
                    </div>
                    <div class="summary-item total-price">
                        <span>Tổng cộng:</span>
                        <span>0 VNĐ</span>
                        <input type="hidden" class="totalPrice" name="totalPrice" value="">
                    </div>
                </div>
                <div class="order-coupon">
                    <input type="text" placeholder="Mã giảm giá" style="width: 65%;">
                    <button type="button" style="width: 30%" class="booking-btn btn-coupon" data-url="{{ route('apply-promotion') }}">Áp dụng</button>
                </div>

                <div id="paypal-button-container"></div>

                <button type="submit" class="booking-btn btn-submit-booking">Xác Nhận</button>

                <button id="btn-momo-payment" class="booking-btn btn-payment-action" style="display: none;"
                    data-urlmomo = "{{ route('createMomoPayment') }}">Thanh toán với Momo <img src="{{ asset('clients/assets/images/booking/icon-thanh-toan-momo.png') }}" alt="" style="width: 10%"></button>

                <button id="btn-vnpay-payment" class="booking-btn btn-payment-action" style="display: none;"
                    data-urlvnpay = "{{ route('createVnpayPayment') }}">Thanh toán với VNPay <img src="{{ asset('clients/assets/images/booking/icon-vnpay.png') }}" alt="" style="width: 10%"></button>

            </div>
        </div>
    </form>
</section>


@include('clients.blocks.footer')
