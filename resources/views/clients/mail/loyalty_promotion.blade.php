<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ưu đãi dành cho bạn</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4f8;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#059669,#0d9488,#0ea5e9);padding:40px;text-align:center;">
                            <h1 style="color:#ffffff;font-size:28px;margin:0 0 8px;font-weight:700;">✈️ TRAVELA</h1>
                            <p style="color:rgba(255,255,255,0.9);font-size:14px;margin:0;">Ưu đãi đặc biệt dành riêng cho bạn!</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px;">
                            <h2 style="color:#1e293b;font-size:22px;margin:0 0 16px;">Xin chào {{ $username }}! 🎁</h2>
                            <p style="color:#475569;font-size:15px;line-height:1.7;margin:0 0 24px;">
                                Cảm ơn bạn đã luôn đồng hành cùng Travela! Là khách hàng thân thiết cấp
                                <strong style="color:#059669;">{{ ucfirst($tier) }}</strong>, bạn xứng đáng nhận những ưu đãi tuyệt vời.
                            </p>

                            <!-- Tier Badge -->
                            <div style="text-align:center;margin:24px 0;">
                                @php
                                    $tierColors = [
                                        'bronze' => ['bg' => '#f59e0b', 'text' => '#92400e', 'icon' => '🥉'],
                                        'silver' => ['bg' => '#94a3b8', 'text' => '#334155', 'icon' => '🥈'],
                                        'gold' => ['bg' => '#eab308', 'text' => '#713f12', 'icon' => '🥇'],
                                        'platinum' => ['bg' => '#6366f1', 'text' => '#312e81', 'icon' => '💎']
                                    ];
                                    $color = $tierColors[$tier] ?? $tierColors['bronze'];
                                @endphp
                                <div style="display:inline-block;padding:16px 32px;background:linear-gradient(135deg,{{ $color['bg'] }},{{ $color['bg'] }}dd);border-radius:16px;text-align:center;">
                                    <span style="font-size:36px;">{{ $color['icon'] }}</span><br>
                                    <strong style="color:{{ $color['text'] }};font-size:20px;text-transform:uppercase;letter-spacing:2px;">{{ $tier }}</strong><br>
                                    <span style="color:{{ $color['text'] }};font-size:14px;">{{ number_format($points) }} điểm tích lũy</span>
                                </div>
                            </div>

                            @if($promotion)
                            <!-- Promotion Code -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:2px dashed #10b981;border-radius:12px;margin:24px 0;">
                                <tr>
                                    <td style="padding:24px;text-align:center;">
                                        <p style="color:#065f46;font-size:14px;margin:0 0 8px;">🎟️ Mã ưu đãi dành riêng cho bạn:</p>
                                        <div style="display:inline-block;padding:12px 32px;background:#10b981;border-radius:8px;margin:8px 0;">
                                            <span style="color:#ffffff;font-size:24px;font-weight:700;letter-spacing:4px;">{{ $promotion->code }}</span>
                                        </div>
                                        <p style="color:#047857;font-size:16px;font-weight:600;margin:12px 0 4px;">
                                            @if($promotion->discount_percent > 0)
                                                Giảm {{ $promotion->discount_percent }}%
                                            @elseif($promotion->discount_amount > 0)
                                                Giảm {{ number_format($promotion->discount_amount, 0, ',', '.') }} VND
                                            @endif
                                        </p>
                                        <p style="color:#6b7280;font-size:12px;margin:0;">
                                            {{ $promotion->description ?? 'Áp dụng cho mọi tour' }}<br>
                                            Hạn sử dụng: {{ date('d/m/Y', strtotime($promotion->valid_until)) }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- CTA -->
                            <div style="text-align:center;margin:32px 0 16px;">
                                <a href="{{ url('/tours') }}"
                                   style="display:inline-block;padding:14px 40px;background:linear-gradient(135deg,#059669,#0d9488);color:#ffffff;text-decoration:none;border-radius:50px;font-weight:600;font-size:16px;box-shadow:0 4px 16px rgba(5,150,105,0.4);">
                                    🌟 Khám phá tour mới
                                </a>
                            </div>

                            <p style="color:#94a3b8;font-size:13px;text-align:center;margin:16px 0 0;">
                                Tiếp tục du lịch cùng Travela để tích thêm điểm và nâng cấp hạng thành viên!
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc;padding:24px 40px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="color:#94a3b8;font-size:13px;margin:0 0 8px;">
                                © {{ date('Y') }} Travela. Mọi quyền được bảo lưu.
                            </p>
                            <p style="color:#cbd5e1;font-size:12px;margin:0;">
                                Bạn nhận được email này vì là khách hàng thân thiết của Travela.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
