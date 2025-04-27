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
        .logo-wrapper {
            background: white;
            width: 80px;
            height: 80px;
            border-radius: 12px;
            margin: 0 auto 16px;
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
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
            color: #ffffff !important;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
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
            border-top: 1px solid #e5e7eb;
        }
        .help-text {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-wrapper">
                <img src="https://res.cloudinary.com/dh4fde6ss/image/upload/v1710604951/logo_zhmy9c.png" alt="MyMoney Logo">
            </div>
            <h1>MyMoney</h1>
        </div>
        
        <div class="content">
            <div class="welcome-text">Halo!</div>
            
            <p class="description">
                Terima kasih telah mendaftar di MyMoney. Untuk memverifikasi alamat email Anda, silakan klik tombol di bawah ini:
            </p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button" target="_blank" rel="noopener">Verifikasi Email</a>
            </div>
            
            <p class="help-text">
                Jika Anda tidak membuat akun di MyMoney, Anda dapat mengabaikan email ini.
            </p>
            
            <p class="help-text">
                Jika Anda mengalami masalah dengan tombol di atas, copy dan paste URL berikut ke browser Anda:<br>
                {{ $url }}
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} MyMoney. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html> 