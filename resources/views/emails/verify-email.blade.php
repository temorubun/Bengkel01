<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email MyMoney</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #1f2937;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background: #0ea5e9;
            padding: 32px 20px;
            text-align: center;
        }
        .logo-container {
            background: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo-container img {
            width: 50px;
            height: auto;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 32px;
            color: #374151;
        }
        .welcome-text {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1f2937;
        }
        .description {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 32px;
        }
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            background-color: #0ea5e9;
            color: white;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #0284c7;
        }
        .footer {
            text-align: center;
            padding: 24px;
            background-color: #f9fafb;
            color: #6b7280;
            font-size: 14px;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 24px 0;
        }
        .help-text {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 16px;
        }
        .social-links {
            margin-top: 24px;
        }
        .social-links a {
            color: #6b7280;
            text-decoration: none;
            margin: 0 8px;
        }
        .social-links a:hover {
            color: #0ea5e9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="MyMoney Logo">
            </div>
            <h1>MyMoney</h1>
        </div>
        
        <div class="content">
            <div class="welcome-text">Selamat Datang di MyMoney! 👋</div>
            
            <p class="description">
                Terima kasih telah bergabung dengan MyMoney - solusi terbaik untuk mengelola keuangan Anda dengan lebih pintar.
                Untuk memulai perjalanan Anda bersama kami, mohon verifikasi email Anda terlebih dahulu.
            </p>
            
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="button">Verifikasi Email Sekarang</a>
            </div>
            
            <p class="help-text">
                Jika Anda tidak membuat akun di MyMoney, Anda dapat mengabaikan email ini.
            </p>
            
            <div class="divider"></div>
            
            <p style="color: #4b5563; font-size: 15px;">
                Salam Sukses,<br>
                <strong>Tim MyMoney</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} MyMoney. Semua hak dilindungi.</p>
            <div class="social-links">
                <a href="#">Facebook</a> • 
                <a href="#">Twitter</a> • 
                <a href="#">Instagram</a>
            </div>
            <p class="help-text">
                Jika tombol tidak berfungsi, copy dan paste link berikut ke browser Anda:<br>
                <span style="color: #0ea5e9;">{{ $verificationUrl }}</span>
            </p>
        </div>
    </div>
</body>
</html> 