<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report - {{ $report->student->fullName() }} - {{ $report->month }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3B82F6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #1F2937;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #6B7280;
            margin: 5px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        .info-box {
            background: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
        }
        .info-label {
            font-size: 12px;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            color: #3B82F6;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #DBEAFE;
        }
        .section-content {
            color: #4B5563;
            text-align: justify;
        }
        .status-badge {
            display: inline-block;
            background: #10B981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            color: #9CA3AF;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #3B82F6; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px;">
            Print Report
        </button>
        <button onclick="window.close()" style="background: #6B7280; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="header">
        <h1>Monthly Progress Report</h1>
        <p><strong>{{ $report->student->fullName() }}</strong></p>
        <p>{{ $report->month }}</p>
        <span class="status-badge">✓ Approved by Director</span>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <div class="info-label">Student Name</div>
            <div class="info-value">{{ $report->student->fullName() }}</div>
        </div>
        <div class="info-box">
            <div class="info-label">Tutor</div>
            <div class="info-value">{{ $report->tutor->first_name }} {{ $report->tutor->last_name }}</div>
        </div>
        <div class="info-box">
            <div class="info-label">Report Period</div>
            <div class="info-value">{{ $report->month }}</div>
        </div>
        <div class="info-box">
            <div class="info-label">Attendance Score</div>
            <div class="info-value">{{ $report->attendance_score }}%</div>
        </div>
        <div class="info-box">
            <div class="info-label">Performance Rating</div>
            <div class="info-value" style="text-transform: capitalize;">{{ $report->performance_rating }}</div>
        </div>
        <div class="info-box">
            <div class="info-label">Approved Date</div>
            <div class="info-value">{{ $report->approved_by_director_at ? $report->approved_by_director_at->format('M d, Y') : 'N/A' }}</div>
        </div>
    </div>

    @if($report->progress_summary)
    <div class="section">
        <div class="section-title">Progress Summary</div>
        <div class="section-content">{{ $report->progress_summary }}</div>
    </div>
    @endif

    @if($report->strengths)
    <div class="section">
        <div class="section-title">Strengths</div>
        <div class="section-content">{{ $report->strengths }}</div>
    </div>
    @endif

    @if($report->weaknesses)
    <div class="section">
        <div class="section-title">Areas for Growth</div>
        <div class="section-content">{{ $report->weaknesses }}</div>
    </div>
    @endif

    @if($report->next_steps)
    <div class="section">
        <div class="section-title">Next Steps</div>
        <div class="section-content">{{ $report->next_steps }}</div>
    </div>
    @endif

    @if($report->manager_comment)
    <div class="section">
        <div class="section-title">Manager's Feedback</div>
        <div class="section-content"><em>"{{ $report->manager_comment }}"</em></div>
    </div>
    @endif

    @if($report->director_comment)
    <div class="section">
        <div class="section-title">Director's Comment</div>
        <div class="section-content"><em>"{{ $report->director_comment }}"</em></div>
        @if($report->director && $report->director_signature)
        <div style="margin-top: 10px; color: #6B7280; font-size: 14px;">
            — {{ $report->director->name }}
        </div>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>&copy; {{ date('Y') }} KidzTech - Empowering Young Minds Through Technology</p>
        <p>This report was generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
