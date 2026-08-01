<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveEase OTP Verification</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; color: #1e293b; }
        .email-card { max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
        .header { background: #0f172a; padding: 30px; text-align: center; color: #ffffff; }
        .brand-name { font-size: 24px; font-weight: 800; letter-spacing: -0.5px; margin: 0; }
        .brand-name span { color: #2563eb; }
        .content { padding: 35px 30px; text-align: center; }
        .greeting { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
        .message { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 25px; }
        .otp-box { background: #f8fafc; border: 2px dashed #2563eb; border-radius: 12px; padding: 20px; margin: 0 auto 25px; display: inline-block; width: 80%; }
        .otp-code { font-size: 36px; font-weight: 800; letter-spacing: 12px; color: #2563eb; font-family: monospace; margin: 0; }
        .warning { font-size: 12px; color: #ef4444; font-weight: 600; background: #fef2f2; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; display: inline-block; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h1 class="brand-name">Drive<span>Ease</span></h1>
            <p style="margin: 5px 0 0; font-size: 13px; color: #94a3b8;">Password Reset Verification</p>
        </div>
        <div class="content">
            <div class="greeting">Hello {{ $customerName }},</div>
            <div class="message">
                We received a request to reset the password for your DriveEase account. Use the 6-digit OTP code below to complete your password reset:
            </div>
            
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <div class="warning">
                ⏳ This OTP is valid for <strong>10 minutes</strong>. Do not share this code with anyone.
            </div>

            <p style="font-size: 13px; color: #94a3b8; margin: 0;">
                If you did not request a password reset, please ignore this email or contact support.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} DriveEase SaaS Portal. All rights reserved.
        </div>
    </div>
</body>
</html>
