<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print - Progress Report - {{ $report->student->fullName() }}</title>
    <style>
        @media print {
            @page {
                margin: 1.5cm;
            }

            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: white;
            padding: 20px;
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #333;
        }

        .header h1 {
            font-size: 24pt;
            margin-bottom: 8px;
            color: #333;
        }

        .header .subtitle {
            font-size: 14pt;
            color: #666;
        }

        .report-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 25px;
            border: 1px solid #ddd;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #000;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #666;
        }

        .section-content {
            padding: 10px 0;
            text-align: justify;
            line-height: 1.8;
            color: #222;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }

        .metric-box {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .metric-label {
            font-size: 11pt;
            color: #666;
            margin-bottom: 5px;
        }

        .metric-value {
            font-size: 20pt;
            font-weight: bold;
            color: #000;
        }

        .comment-box {
            background-color: #fafafa;
            border: 1px solid #ccc;
            border-left: 4px solid #666;
            padding: 15px;
            margin-top: 10px;
        }

        .comment-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 12pt;
        }

        .approval-timeline {
            background-color: #f5f5f5;
            padding: 15px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #333;
            text-align: center;
            font-size: 10pt;
            color: #666;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 14pt;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .print-button:hover {
            background-color: #555;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button onclick="window.print()" class="print-button no-print">🖨️ Print Report</button>

    <!-- Header -->
    <div class="header">
        <h1>KidzTech Progress Report</h1>
        <div class="subtitle">Monthly Student Progress Review</div>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Student Name:</span>
                <span class="info-value">{{ $report->student->fullName() }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tutor:</span>
                <span class="info-value">{{ $report->tutor->first_name }} {{ $report->tutor->last_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Report Period:</span>
                <span class="info-value">{{ date('F Y', strtotime($report->month . '-01')) }}</span>
            </div>
            @if($report->period_from && $report->period_to)
            <div class="info-item">
                <span class="info-label">Period Range:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($report->period_from)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($report->period_to)->format('M d, Y') }}</span>
            </div>
            @endif
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">{{ ucfirst(str_replace('-', ' ', $report->status)) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Generated:</span>
                <span class="info-value">{{ now()->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Progress Summary -->
    <div class="section">
        <div class="section-title">Progress Summary</div>
        <div class="section-content">
            {{ $report->progress_summary ?? 'No progress summary provided.' }}
        </div>
    </div>

    <!-- Strengths -->
    <div class="section">
        <div class="section-title">Strengths & Achievements</div>
        <div class="section-content">
            {{ $report->strengths ?? 'No strengths noted.' }}
        </div>
    </div>

    <!-- Weaknesses / Areas for Improvement -->
    <div class="section">
        <div class="section-title">Areas for Improvement</div>
        <div class="section-content">
            {{ $report->weaknesses ?? 'No areas for improvement noted.' }}
        </div>
    </div>

    <!-- Next Steps -->
    <div class="section">
        <div class="section-title">Next Steps & Recommendations</div>
        <div class="section-content">
            {{ $report->next_steps ?? 'No next steps provided.' }}
        </div>
    </div>

    <!-- Metrics -->
    <div class="section">
        <div class="section-title">Performance Metrics</div>
        <div class="metrics-grid">
            <div class="metric-box">
                <div class="metric-label">Attendance Score</div>
                <div class="metric-value">{{ $report->attendance_score ?? 'N/A' }}@if($report->attendance_score)%@endif</div>
            </div>
            <div class="metric-box">
                <div class="metric-label">Performance Rating</div>
                <div class="metric-value">{{ $report->performance_rating ?? $report->rating ?? 'N/A' }}/10</div>
            </div>
        </div>
    </div>

    <!-- Manager Comment -->
    @if($report->manager_comment)
    <div class="section">
        <div class="section-title">Manager Feedback</div>
        <div class="comment-box">
            <div class="comment-title">Manager's Comment</div>
            <div>{{ $report->manager_comment }}</div>
        </div>
    </div>
    @endif

    <!-- Director Comment -->
    @if($report->director_comment)
    <div class="section">
        <div class="section-title">Director's Review</div>
        <div class="comment-box">
            <div class="comment-title">Director's Comment</div>
            <div>{{ $report->director_comment }}</div>
        </div>
    </div>
    @endif

    <!-- Approval Information -->
    @if($report->approved_by_manager_at || $report->approved_by_director_at)
    <hr>
    <div class="section">
        <div class="section-title">Approval Timeline</div>
        <div class="approval-timeline">
            @if($report->submitted_at)
            <div class="info-item">
                <span class="info-label">Submitted:</span>
                <span class="info-value">{{ $report->submitted_at->format('M d, Y h:i A') }}</span>
            </div>
            @endif
            @if($report->approved_by_manager_at)
            <div class="info-item">
                <span class="info-label">Manager Approved:</span>
                <span class="info-value">{{ $report->approved_by_manager_at->format('M d, Y h:i A') }}</span>
            </div>
            @endif
            @if($report->approved_by_director_at)
            <div class="info-item">
                <span class="info-label">Director Approved:</span>
                <span class="info-value">{{ $report->approved_by_director_at->format('M d, Y h:i A') }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>&copy; {{ date('Y') }} KidzTech - Empowering Young Minds Through Technology</div>
        <div>This report is confidential and intended for the student and their parents/guardians only.</div>
    </div>

    <script>
        // Auto-print when page loads (optional - can be removed if not desired)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
