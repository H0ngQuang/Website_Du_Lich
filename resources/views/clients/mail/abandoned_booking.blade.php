<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking chưa hoàn tất</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4f8;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed,#a855f7);padding:40px;text-align:center;">
                            <h1 style="color:#ffffff;font-size:28px;margin:0 0 8px;font-weight:700;">✈️ TRAVELA</h1>
                            <p style="color:rgba(255,255,255,0.9);font-size:14px;margin:0;">Đơn đặt tour đang chờ bạn!</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px;">
                            <h2 style="color:#1e293b;font-size:22px;margin:0 0 16px;">Xin chào {{ $booking->fullName }}! ⏰</h2>
                            <p style="color:#475569;font-size:15px;line-height:1.7;margin:0 0 24px;">
                                Chúng tôi nhận thấy bạn đã đặt tour nhưng chưa hoàn tất thanh toán.
                                Đừng lo, đơn đặt của bạn vẫn đang được giữ chỗ!
                            </p>

                            <!-- Booking Info Card -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#faf5ff;border:2px solid #e9d5ff;border-radius:12px;overflow:hidden;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px 24px;background:#7c3aed;">
                                        <h3 style="color:#ffffff;font-size:18px;margin:0;">📋 Chi tiết đơn đặt</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <table width="100%">
                                            <tr>
                                                <td style="padding:8px 0;border-bottom:1px solid #e9d5ff;">
                                                    <span style="color:#6b7280;">🏖️ Tour:</span><br>
                                                    <strong style="color:#1e293b;font-size:16px;">{{ $booking->tourTitle }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;border-bottom:1px solid #e9d5ff;">
                                                    <span style="color:#6b7280;">📍 Điểm đến:</span>
                                                    <strong style="color:#44403c;">{{ $booking->destination }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;border-bottom:1px solid #e9d5ff;">
                                                    <span style="color:#6b7280;">👥 Số khách:</span>
                                                    <strong style="color:#44403c;">{{ $booking->numAdults }} người lớn, {{ $booking->numChildren }} trẻ em</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0;">
                                                    <span style="color:#6b7280;">💰 Tổng tiền:</span><br>
                                                    <strong style="color:#dc2626;font-size:22px;">{{ number_format($booking->totalPrice, 0, ',', '.') }} VND</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @if($reminderCount >= 2)
                            <!-- Urgency message -->
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:24px;text-align:center;">
                                <p style="color:#dc2626;font-size:14px;margin:0;font-weight:600;">
                                    ⚠️ Chỗ đặt sẽ được hủy nếu không thanh toán sớm!
                                </p>
                            </div>
                            @endif

                            <!-- CTA -->
                            <div style="text-align:center;margin:24px 0 16px;">
                                <a href="{{ url('/tours') }}"
                                   style="display:inline-block;padding:14px 40px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#ffffff;text-decoration:none;border-radius:50px;font-weight:600;font-size:16px;box-shadow:0 4px 16px rgba(124,58,237,0.4);">
                                    💳 Hoàn tất thanh toán
                                </a>
                            </div>

                            <p style="color:#94a3b8;font-size:13px;text-align:center;margin:16px 0 0;">
                                Nếu bạn cần hỗ trợ, vui lòng liên hệ chúng tôi qua email hoặc hotline.
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
                                Bạn nhận được email này vì có đơn đặt tour chưa thanh toán tại Travela.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
