<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với Travela</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4f8;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#0ea5e9,#6366f1,#8b5cf6);padding:50px 40px;text-align:center;">
                            <h1 style="color:#ffffff;font-size:32px;margin:0 0 8px;font-weight:700;">✈️ TRAVELA</h1>
                            <p style="color:rgba(255,255,255,0.9);font-size:16px;margin:0;">Khám phá thế giới cùng bạn</p>
                        </td>
                    </tr>

                    <!-- Welcome Content -->
                    <tr>
                        <td style="padding:40px;">
                            <h2 style="color:#1e293b;font-size:24px;margin:0 0 16px;">Xin chào {{ $username }}! 🎉</h2>
                            <p style="color:#475569;font-size:15px;line-height:1.7;margin:0 0 20px;">
                                Chúng tôi rất vui khi bạn đã tham gia cộng đồng Travela. Giờ đây, bạn có thể khám phá
                                hàng trăm tour du lịch tuyệt vời trên khắp Việt Nam!
                            </p>

                            <!-- Features -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
                                <tr>
                                    <td style="padding:12px 16px;background:#f0f9ff;border-radius:12px;margin-bottom:8px;">
                                        <table width="100%">
                                            <tr>
                                                <td width="40" style="font-size:24px;">🗺️</td>
                                                <td>
                                                    <strong style="color:#0369a1;">Tour đa dạng</strong><br>
                                                    <span style="color:#64748b;font-size:13px;">Bắc - Trung - Nam, đầy đủ các điểm đến</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td style="height:8px;"></td></tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#fdf4ff;border-radius:12px;">
                                        <table width="100%">
                                            <tr>
                                                <td width="40" style="font-size:24px;">💎</td>
                                                <td>
                                                    <strong style="color:#7e22ce;">Tích điểm thưởng</strong><br>
                                                    <span style="color:#64748b;font-size:13px;">Mỗi chuyến đi tích điểm, đổi ưu đãi hấp dẫn</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr><td style="height:8px;"></td></tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f0fdf4;border-radius:12px;">
                                        <table width="100%">
                                            <tr>
                                                <td width="40" style="font-size:24px;">🎯</td>
                                                <td>
                                                    <strong style="color:#15803d;">Gợi ý thông minh</strong><br>
                                                    <span style="color:#64748b;font-size:13px;">AI gợi ý tour phù hợp sở thích của bạn</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align:center;margin:32px 0 16px;">
                                <a href="{{ url('/tours') }}"
                                   style="display:inline-block;padding:14px 40px;background:linear-gradient(135deg,#0ea5e9,#6366f1);color:#ffffff;text-decoration:none;border-radius:50px;font-weight:600;font-size:16px;box-shadow:0 4px 16px rgba(99,102,241,0.4);">
                                    🌟 Khám phá tour ngay
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc;padding:24px 40px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="color:#94a3b8;font-size:13px;margin:0 0 8px;">
                                © {{ date('Y') }} Travela. Mọi quyền được bảo lưu.
                            </p>
                            <p style="color:#cbd5e1;font-size:12px;margin:0;">
                                Bạn nhận được email này vì đã đăng ký tài khoản tại Travela.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
