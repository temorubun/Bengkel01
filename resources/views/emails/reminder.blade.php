<!DOCTYPE html>
<html>
<head>
    <title>Reminder Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .brand-name {
            display: inline-block;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reminder: {{ $reminder->title }}</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>This is a reminder for:</p>
        
        <h2>{{ $reminder->title }}</h2>
        
        @if($reminder->description)
            <p><strong>Description:</strong><br>
            {{ $reminder->description }}</p>
        @endif
        
        <p><strong>Due Date:</strong> {{ $reminder->reminder_date->format('d F Y') }}</p>
        
        <p><strong>Reminder Type:</strong> 
            @switch($reminder->reminder_type)
                @case('once')
                    One-time reminder
                    @break
                @case('daily')
                    Daily reminder
                    @break
                @case('weekly')
                    Weekly reminder
                    @break
                @case('monthly')
                    Monthly reminder
                    @break
            @endswitch
        </p>
        
        <a href="{{ url('/reminders') }}" class="button">View Reminders</a>
    </div>
    
    <div class="footer">
        <p>This is an automated message from <span class="brand-name">MyMoney</span>. Please do not reply to this email.</p>
        <p>If you wish to stop receiving these notifications, you can update your preferences in the settings page.</p>
    </div>
</body>
</html> 