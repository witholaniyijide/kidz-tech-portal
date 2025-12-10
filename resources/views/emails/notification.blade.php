<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #4F46E5 0%, #9333EA 50%, #EC4899 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header .subtitle {
            opacity: 0.9;
            font-size: 14px;
            margin-top: 5px;
        }
        .content {
            padding: 30px;
        }
        .notification-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .type-report_approved, .type-assessment_approved {
            background: #DEF7EC;
            color: #03543F;
        }
        .type-attendance_approved, .type-attendance_submitted {
            background: #E1EFFE;
            color: #1E40AF;
        }
        .type-report_available, .type-certification_awarded {
            background: #FDF2F8;
            color: #9D174D;
        }
        .type-course_change {
            background: #FEF3C7;
            color: #92400E;
        }
        .type-notice, .type-message {
            background: #F3E8FF;
            color: #6B21A8;
        }
        .message {
            font-size: 16px;
            color: #374151;
            margin-bottom: 20px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #4F46E5 0%, #9333EA 100%);
            color: white !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
        }
        .footer {
            background: #F9FAFB;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
        }
        .footer a {
            color: #4F46E5;
            text-decoration: none;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">KidzTech</div>
            <div class="subtitle">Empowering Young Minds Through Technology</div>
        </div>
        
        <div class="content">
            <span class="notification-type type-{{ $type }}">
                @switch($type)
                    @case('report_approved')
                        Report Approved
                        @break
                    @case('attendance_approved')
                        Attendance Approved
                        @break
                    @case('attendance_submitted')
                        New Submission
                        @break
                    @case('assessment_approved')
                        Assessment Approved
                        @break
                    @case('report_available')
                        Report Ready
                        @break
                    @case('certification_awarded')
                        Certification
                        @break
                    @case('course_change')
                        Course Update
                        @break
                    @case('notice')
                        Notice
                        @break
                    @case('message')
                        Message
                        @break
                    @default
                        Notification
                @endswitch
            </span>
            
            <h2 style="margin: 0 0 15px 0; color: #111827;">{{ $subject }}</h2>
            
            <div class="message">
                {!! nl2br(e($body)) !!}
            </div>
            
            <a href="{{ config('app.url') }}/dashboard" class="cta-button">
                View in Portal
            </a>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from KidzTech Portal.</p>
            <p>
                <a href="{{ config('app.url') }}">Visit Portal</a> |
                <a href="{{ config('app.url') }}/settings">Notification Settings</a>
            </p>
            <p style="margin-top: 15px;">&copy; {{ date('Y') }} KidzTech. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
