<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat - MyMoney</title>
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
            text-align: center;
        }
        .content {
            padding: 40px 32px;
            color: #374151;
        }
        .welcome-text {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
            margin-left: 20px;
            margin-top: 10px;
            color: #1f2937;
        }
        .description {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 32px;
            margin-top: 10px;
            margin-left: 20px;
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
        .reminder-details {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .reminder-details p {
            margin: 8px 0;
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
            <div class="welcome-text">Halo {{ $user->name }}!</div>
            
            <p class="description">
                Anda memiliki pengingat yang perlu diperhatikan:
            </p>
            
            <div class="reminder-details">
                <h2 style="margin-top: 0; color: #0ea5e9;">{{ $reminder->title }}</h2>
                
                @if($reminder->description)
                <p>{{ $reminder->description }}</p>
                @endif
                
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('d F Y') }}</p>
                
                <p><strong>Jenis Pengingat:</strong> 
                    @switch($reminder->reminder_type)
                        @case('once')
                            Sekali
                            @break
                        @case('daily')
                            Harian
                            @break
                        @case('weekly')
                            Mingguan
                            @break
                        @case('monthly')
                            Bulanan
                            @break
                    @endswitch
                </p>
            </div>
            
            <div class="button-container">
                <a href="{{ route('reminders.index') }}" class="button">Lihat Pengingat</a>
            </div>
            
            <p class="help-text">
                Jika Anda tidak membuat pengingat ini di MyMoney, Anda dapat mengabaikan email ini.
            </p>
            
            <p class="help-text">
                Jika Anda mengalami masalah dengan tombol di atas, copy dan paste URL berikut ke browser Anda:<br>
                {{ route('reminders.index') }}
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} MyMoney. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html> 