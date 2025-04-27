<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password MyMoney</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f9fafb; font-family: 'Inter', 'Segoe UI', sans-serif;">
    <div style="width: 100%; max-width: 600px; margin: 20px auto; background: white; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); overflow: hidden;">
        <!-- Header -->
        <div style="background-color: #0ea5e9; padding: 32px 20px; text-align: center;">
            <!-- Logo Container -->
            <div style="width: 100px; height: 100px; margin: 0 auto 16px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 700;">MyMoney</h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 40px 32px;">
            <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 16px; color: #1f2937; text-align: center;">Reset Password</h2>
            
            <p style="color: #6b7280; font-size: 16px; margin-bottom: 20px;">
                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/password/reset/'.$token.'?email='.urlencode($email)) }}" style="display: inline-block; background-color: #0ea5e9; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px; text-align: center;" target="_blank" rel="noopener">
                    <span style="color: white;">Reset Password</span>
                </a>
            </div>

            <p style="color: #6b7280; font-size: 16px; margin-bottom: 20px;">
                Link reset password ini akan kedaluwarsa dalam 60 menit.
            </p>

            <p style="color: #6b7280; font-size: 16px;">
                Jika Anda tidak meminta reset password, tidak ada tindakan lebih lanjut yang diperlukan.
            </p>

            <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
                Jika Anda mengalami masalah dengan tombol di atas, copy dan paste URL berikut ke browser Anda:<br>
                <span style="color: #0ea5e9;">{{ url('/password/reset/'.$token.'?email='.urlencode($email)) }}</span>
            </p>
        </div>
        
        <!-- Footer -->
        <div style="text-align: center; padding: 24px; background-color: #f9fafb; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0;">© {{ date('Y') }} MyMoney. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 