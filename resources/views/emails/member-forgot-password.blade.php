<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งรหัสผ่านชั่วคราว</title>
</head>
<body style="margin:0; padding:0; background:#fff7ed; font-family:Arial, Tahoma, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#fff7ed; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#f97316; padding:28px 32px; text-align:center;">
                            <img src="{{ $logoUrl }}" alt="JGO" style="max-width:150px; height:auto; display:inline-block;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 36px 28px;">
                            <h1 style="margin:0 0 12px; font-size:22px; line-height:1.4; color:#111827;">แจ้งรหัสผ่านชั่วคราว</h1>
                            <p style="margin:0 0 18px; font-size:15px; line-height:1.8; color:#4b5563;">
                                สวัสดี {{ $member->username ?? $member->email }} ระบบได้รับคำขอลืมรหัสผ่านสำหรับบัญชีของคุณ
                            </p>
                            <p style="margin:0 0 10px; font-size:15px; line-height:1.8; color:#4b5563;">
                                กรุณาใช้รหัสผ่านชั่วคราวด้านล่างเพื่อเข้าสู่ระบบ
                            </p>
                            <div style="margin:18px 0 24px; padding:18px 20px; background:#fff7ed; border:1px solid #fed7aa; border-radius:10px; text-align:center;">
                                <div style="font-size:13px; color:#9a3412; margin-bottom:8px;">รหัสผ่านชั่วคราว</div>
                                <div style="font-size:28px; line-height:1.2; font-weight:700; letter-spacing:1px; color:#c2410c;">{{ $temporaryPassword }}</div>
                            </div>
                            <p style="margin:0 0 18px; font-size:15px; line-height:1.8; color:#4b5563;">
                                เพื่อความปลอดภัย กรุณาเข้าสู่ระบบและเปลี่ยนรหัสผ่านใหม่ทันทีหลังใช้งานรหัสนี้
                            </p>
                            <div style="margin:22px 0; padding:14px 16px; background:#fff7ed; border-left:4px solid #f97316; border-radius:8px; color:#9a3412; font-size:14px; line-height:1.7;">
                                หากคุณไม่ได้เป็นผู้ขอรีเซ็ตรหัสผ่าน กรุณาติดต่อผู้ดูแลระบบโดยเร็ว
                            </div>
                            <p style="margin:24px 0 0; font-size:14px; line-height:1.7; color:#6b7280;">
                                ขอแสดงความนับถือ<br>
                                ทีมงาน JGO
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px; background:#f9fafb; text-align:center; font-size:12px; line-height:1.6; color:#9ca3af;">
                            อีเมลนี้ส่งจากระบบอัตโนมัติ กรุณาอย่าตอบกลับอีเมลนี้
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
