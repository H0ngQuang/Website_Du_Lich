<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhắc nhở Tour</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4f8;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#f59e0b,#ef4444,#ec4899);padding:40px;text-align:center;">
                            <h1 style="color:#ffffff;font-size:28px;margin:0 0 8px;font-weight:700;">✈️ TRAVELA</h1>
                            <p style="color:rgba(255,255,255,0.9);font-size:14px;margin:0;">Bạn sắp bỏ lỡ chuyến đi tuyệt vời!</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px;">
                            <h2 style="color:#1e293b;font-size:22px;margin:0 0 16px;">Xin chào {{ $username }}! 👋</h2>
                            <p style="color:#475569;font-size:15px;line-height:1.7;margin:0 0 24px;">
                                Chúng tôi nhận thấy bạn đã quan tâm đến tour dưới đây. Tour này đang rất được ưa chuộng
                                và số lượng có hạn — đừng bỏ lỡ nhé!
                            </p>

                            <!-- Tour Card -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#fef3c7,#fde68a);border-radius:12px;overflow:hidden;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:24px;">
                                        <h3 style="color:#92400e;font-size:20px;margin:0 0 8px;">{{ $tour->title }}</h3>
                                        <table width="100%" style="margin:12px 0;">
                                            <tr>
                                                <td style="padding:4px 0;">
                                                    <span style="color:#78716c;font-size:14px;">📍 Điểm đến:</span>
                                                    <strong style="color:#44403c;">{{ $tour->destination }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0;">
                                                    <span style="color:#78716c;font-size:14px;">⏱️ Thời gian:</span>
                                                    <strong style="color:#44403c;">{{ $tour->time }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0;">
                                                    <span style="color:#78716c;font-size:14px;">💰 Giá từ:</span>
                                                    <strong style="color:#dc2626;font-size:18px;">{{ number_format($tour->priceAdult, 0, ',', '.') }} VND</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <div style="text-align:center;margin:32px 0 16px;">
                                <a href="{{ url('/tour-detail/' . $tour->tourId) }}"
                                   style="display:inline-block;padding:14px 40px;background:linear-gradient(135deg,#f59e0b,#ef4444);color:#ffffff;text-decoration:none;border-radius:50px;font-weight:600;font-size:16px;box-shadow:0 4px 16px rgba(239,68,68,0.4);">
                                    🔥 Xem & Đặt ngay
                                </a>
                            </div>

                            <p style="color:#94a3b8;font-size:13px;text-align:center;margin:16px 0 0;">
                                ⚡ Ưu đãi có hạn, đặt ngay để không bỏ lỡ!
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
                                Bạn nhận được email này vì đã xem tour trên Travela.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
