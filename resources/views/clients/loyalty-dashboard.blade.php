@include('clients.blocks.header')
<section class="page-banner-two rel z-1">
    <div class="container-fluid">
        <hr class="mt-0">
        <div class="container">
            <div class="banner-inner pt-15 pb-25">
                <h2 class="page-title mb-10" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">
                    Khách hàng thân thiết</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-20" data-aos="fade-right" data-aos-delay="200"
                        data-aos-duration="1500" data-aos-offset="50">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<style>
    .loyalty-section { padding: 60px 0 100px; }
    .tier-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(102,126,234,0.3);
    }
    .tier-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .tier-card.bronze { background: linear-gradient(135deg, #d4a373 0%, #a0522d 100%); box-shadow: 0 20px 60px rgba(160,82,45,0.3); }
    .tier-card.silver { background: linear-gradient(135deg, #94a3b8 0%, #475569 100%); box-shadow: 0 20px 60px rgba(71,85,105,0.3); }
    .tier-card.gold { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 20px 60px rgba(245,158,11,0.3); }
    .tier-card.platinum { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); box-shadow: 0 20px 60px rgba(99,102,241,0.3); }

    .tier-icon { font-size: 64px; margin-bottom: 16px; display: block; }
    .tier-name {
        font-size: 28px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 8px;
    }
    .tier-points {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 24px;
    }
    .progress-wrapper { margin-top: 20px; }
    .progress-bar-custom {
        height: 8px;
        background: rgba(255,255,255,0.2);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
    }
    .progress-fill {
        height: 100%;
        background: #fff;
        border-radius: 4px;
        transition: width 1s ease;
    }
    .progress-label {
        font-size: 13px;
        opacity: 0.85;
        display: flex;
        justify-content: space-between;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 30px;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .stat-icon { font-size: 32px; margin-bottom: 8px; }
    .stat-value { font-size: 24px; font-weight: 700; color: #1e293b; }
    .stat-label { font-size: 14px; color: #64748b; margin-top: 4px; }

    .section-title-loyalty {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 3px solid #6366f1;
        display: inline-block;
    }

    .promo-card {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 2px dashed #10b981;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }
    .promo-card:hover { transform: scale(1.02); }
    .promo-code {
        background: #10b981;
        color: #fff;
        padding: 8px 24px;
        border-radius: 8px;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 3px;
        display: inline-block;
        margin: 8px 0;
    }
    .promo-discount { font-size: 18px; font-weight: 600; color: #047857; }
    .promo-meta { font-size: 13px; color: #6b7280; margin-top: 8px; }

    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .history-table thead th {
        background: #f1f5f9;
        padding: 14px 16px;
        font-weight: 600;
        color: #475569;
        font-size: 14px;
        text-align: left;
    }
    .history-table thead th:first-child { border-radius: 12px 0 0 0; }
    .history-table thead th:last-child { border-radius: 0 12px 0 0; }
    .history-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
    }
    .history-table tbody tr:hover { background: #f8fafc; }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-badge.completed { background: #dcfce7; color: #166534; }
    .status-badge.confirmed { background: #dbeafe; color: #1e40af; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
    .status-badge.cancelled { background: #fee2e2; color: #991b1b; }

    .recent-view-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        margin-bottom: 12px;
        transition: transform 0.2s ease;
    }
    .recent-view-card:hover { transform: translateX(4px); }
    .recent-view-card .rv-info h6 { margin: 0 0 4px; color: #1e293b; }
    .recent-view-card .rv-meta { font-size: 13px; color: #94a3b8; }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .tier-card { padding: 24px; }
    }
</style>

<section class="loyalty-section">
    <div class="container">
        {{-- Tier Card --}}
        <div class="row justify-content-center mb-50" data-aos="fade-up" data-aos-duration="1500">
            <div class="col-lg-8">
                <div class="tier-card {{ $loyaltyInfo->loyalty_tier }}">
                    @php
                        $tierIcons = ['bronze' => '🥉', 'silver' => '🥈', 'gold' => '🥇', 'platinum' => '💎'];
                        $tierNames = ['bronze' => 'Đồng', 'silver' => 'Bạc', 'gold' => 'Vàng', 'platinum' => 'Kim cương'];
                    @endphp
                    <span class="tier-icon">{{ $tierIcons[$loyaltyInfo->loyalty_tier] ?? '🥉' }}</span>
                    <div class="tier-name">{{ $tierNames[$loyaltyInfo->loyalty_tier] ?? 'Đồng' }}</div>
                    <div class="tier-points">{{ number_format($loyaltyInfo->points) }} điểm tích lũy</div>

                    @if($nextTierInfo['next'])
                    <div class="progress-wrapper">
                        <div class="progress-label">
                            <span>Tiến độ nâng hạng {{ $tierNames[$nextTierInfo['next']] ?? '' }}</span>
                            <span>{{ $nextTierInfo['progress'] }}%</span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill" style="width: {{ $nextTierInfo['progress'] }}%;"></div>
                        </div>
                        <div class="progress-label mt-2">
                            <span>Còn {{ number_format($nextTierInfo['remaining']) }} VND</span>
                            <span>{{ number_format($nextTierInfo['threshold']) }} VND</span>
                        </div>
                    </div>
                    @else
                    <p style="opacity:0.85;">🎉 Bạn đã đạt hạng cao nhất! Cảm ơn bạn đã đồng hành cùng Travela.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-grid mb-50" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value">{{ number_format($loyaltyInfo->total_spent, 0, ',', '.') }}</div>
                <div class="stat-label">Tổng chi tiêu (VND)</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🎫</div>
                <div class="stat-value">{{ $loyaltyInfo->total_bookings }}</div>
                <div class="stat-label">Tổng booking</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-value">{{ number_format($loyaltyInfo->points) }}</div>
                <div class="stat-label">Điểm tích lũy</div>
            </div>
        </div>

        <div class="row">
            {{-- Promotions --}}
            <div class="col-lg-4 mb-40" data-aos="fade-right" data-aos-duration="1500">
                <h4 class="section-title-loyalty">🎁 Ưu đãi của bạn</h4>
                @if($promotions->count() > 0)
                    @foreach($promotions as $promo)
                    <div class="promo-card">
                        <div class="promo-code">{{ $promo->code }}</div>
                        <div class="promo-discount">
                            @if($promo->discount_percent > 0)
                                Giảm {{ $promo->discount_percent }}%
                            @elseif($promo->discount_amount > 0)
                                Giảm {{ number_format($promo->discount_amount, 0, ',', '.') }} VND
                            @endif
                        </div>
                        <div class="promo-meta">
                            {{ $promo->description ?? 'Áp dụng cho mọi tour' }}<br>
                            HSD: {{ date('d/m/Y', strtotime($promo->valid_until)) }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align:center;padding:40px;background:#f8fafc;border-radius:16px;">
                        <span style="font-size:48px;">🎟️</span>
                        <p style="color:#94a3b8;margin-top:12px;">Chưa có ưu đãi nào. Hãy tiếp tục du lịch để nhận ưu đãi!</p>
                    </div>
                @endif

                {{-- Recent Views --}}
                <h4 class="section-title-loyalty mt-40">👁️ Tour đã xem gần đây</h4>
                @if($recentViews->count() > 0)
                    @foreach($recentViews as $view)
                    <a href="{{ route('tour-detail', ['id' => $view->tourId]) }}" style="text-decoration:none;">
                        <div class="recent-view-card">
                            <div class="rv-info">
                                <h6>{{ $view->title }}</h6>
                                <div class="rv-meta">
                                    📍 {{ $view->destination }} &bull;
                                    {{ date('d/m/Y', strtotime($view->viewed_at)) }}
                                    @if($view->converted)
                                        <span class="status-badge completed">Đã đặt</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                @else
                    <p style="color:#94a3b8;text-align:center;padding:20px;">Chưa có tour nào được xem.</p>
                @endif
            </div>

            {{-- Booking History --}}
            <div class="col-lg-8 mb-40" data-aos="fade-left" data-aos-duration="1500">
                <h4 class="section-title-loyalty">📋 Lịch sử đặt tour</h4>
                @if($bookingHistory->count() > 0)
                <div style="overflow-x:auto;background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tour</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookingHistory as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $booking->title }}</strong></td>
                                <td>{{ date('d/m/Y', strtotime($booking->bookingDate)) }}</td>
                                <td style="color:#dc2626;font-weight:600;">{{ number_format($booking->totalPrice, 0, ',', '.') }} VND</td>
                                <td>
                                    @php
                                        $paymentLabels = [
                                            'paypal-payment' => 'PayPal',
                                            'momo-payment' => 'MoMo',
                                            'vnpay-payment' => 'VNPay',
                                            'bank-transfer' => 'Chuyển khoản',
                                            'office-payment' => 'Tại văn phòng'
                                        ];
                                    @endphp
                                    {{ $paymentLabels[$booking->paymentMethod] ?? $booking->paymentMethod }}
                                </td>
                                <td>
                                    @if($booking->bookingStatus == 'f')
                                        <span class="status-badge completed">Hoàn thành</span>
                                    @elseif($booking->bookingStatus == 'y')
                                        <span class="status-badge confirmed">Đã xác nhận</span>
                                    @elseif($booking->bookingStatus == 'c')
                                        <span class="status-badge cancelled">Đã hủy</span>
                                    @else
                                        <span class="status-badge pending">Chờ xử lý</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="text-align:center;padding:60px;background:#f8fafc;border-radius:16px;">
                    <span style="font-size:64px;">✈️</span>
                    <p style="color:#94a3b8;margin-top:16px;font-size:16px;">Bạn chưa có booking nào. Hãy khám phá tour ngay!</p>
                    <a href="{{ route('tours') }}" class="theme-btn style-two bgc-secondary mt-3">
                        <span data-hover="Xem Tours">Xem Tours</span>
                        <i class="fal fa-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Tier Info --}}
        <div class="row mt-40" data-aos="fade-up" data-aos-duration="1500">
            <div class="col-12">
                <h4 class="section-title-loyalty">🏆 Hệ thống hạng thành viên</h4>
                <div class="row">
                    @php
                        $tiers = [
                            ['name' => 'Đồng', 'key' => 'bronze', 'icon' => '🥉', 'threshold' => '0 VND+', 'color' => '#d4a373'],
                            ['name' => 'Bạc', 'key' => 'silver', 'icon' => '🥈', 'threshold' => '5,000,000 VND+', 'color' => '#94a3b8'],
                            ['name' => 'Vàng', 'key' => 'gold', 'icon' => '🥇', 'threshold' => '20,000,000 VND+', 'color' => '#f59e0b'],
                            ['name' => 'Kim cương', 'key' => 'platinum', 'icon' => '💎', 'threshold' => '50,000,000 VND+', 'color' => '#6366f1'],
                        ];
                    @endphp
                    @foreach($tiers as $t)
                    <div class="col-md-3 col-6 mb-20">
                        <div style="background:#fff;border-radius:16px;padding:24px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.06);
                            {{ $loyaltyInfo->loyalty_tier == $t['key'] ? 'border:3px solid '.$t['color'].';' : '' }}">
                            <span style="font-size:48px;">{{ $t['icon'] }}</span>
                            <h5 style="margin:12px 0 4px;color:{{ $t['color'] }};">{{ $t['name'] }}</h5>
                            <small style="color:#94a3b8;">{{ $t['threshold'] }}</small>
                            @if($loyaltyInfo->loyalty_tier == $t['key'])
                                <div style="margin-top:8px;"><span class="status-badge completed">Hạng hiện tại</span></div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@include('clients.blocks.new_letter')
@include('clients.blocks.footer')
