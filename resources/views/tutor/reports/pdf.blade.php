<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report - {{ $report->student->fullName() }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%);
            color: white;
            padding: 30px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24pt;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 12pt;
            opacity: 0.9;
        }

        .report-info {
            background-color: #f9fafb;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #9333ea;
        }

        .report-info table {
            width: 100%;
        }

        .report-info td {
            padding: 5px 0;
        }

        .report-info td:first-child {
            font-weight: bold;
            width: 35%;
            color: #6b7280;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #9333ea;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .section-content {
            padding: 10px 0;
            text-align: justify;
            line-height: 1.8;
        }

        .metrics {
            display: table;
            width: 100%;
            margin-top: 15px;
        }

        .metric-item {
            display: table-cell;
            width: 50%;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
        }

        .metric-item:first-child {
            margin-right: 10px;
        }

        .metric-label {
            font-size: 10pt;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .metric-value {
            font-size: 18pt;
            font-weight: bold;
            color: #9333ea;
        }

        .comment-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin-top: 10px;
        }

        .comment-box.manager {
            background-color: #f0fdf4;
            border-left-color: #10b981;
        }

        .comment-box.director {
            background-color: #ede9fe;
            border-left-color: #8b5cf6;
        }

        .comment-title {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
            font-size: 11pt;
        }

        .comment-box.manager .comment-title {
            color: #065f46;
        }

        .comment-box.director .comment-title {
            color: #5b21b6;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 30px;
            border-top: 1px solid #e5e7eb;
            font-size: 9pt;
            color: #6b7280;
            text-align: center;
        }

        .page-number:after {
            content: counter(page);
        }

        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>KidzTech Progress Report</h1>
        <div class="subtitle">Monthly Student Progress Review</div>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <table>
            <tr>
                <td>Student Name:</td>
                <td><strong>{{ $report->student->fullName() }}</strong></td>
            </tr>
            <tr>
                <td>Tutor:</td>
                <td><strong>{{ $report->tutor->first_name }} {{ $report->tutor->last_name }}</strong></td>
            </tr>
            <tr>
                <td>Report Period:</td>
                <td><strong>{{ date('F Y', strtotime($report->month . '-01')) }}</strong></td>
            </tr>
            @if($report->period_from && $report->period_to)
            <tr>
                <td>Period Range:</td>
                <td><strong>{{ \Carbon\Carbon::parse($report->period_from)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($report->period_to)->format('M d, Y') }}</strong></td>
            </tr>
            @endif
            <tr>
                <td>Report Status:</td>
                <td><strong>{{ ucfirst(str_replace('-', ' ', $report->status)) }}</strong></td>
            </tr>
            <tr>
                <td>Generated:</td>
                <td><strong>{{ now()->format('M d, Y h:i A') }}</strong></td>
            </tr>
        </table>
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
        <div class="metrics">
            <div class="metric-item">
                <div class="metric-label">Attendance Score</div>
                <div class="metric-value">{{ $report->attendance_score ?? 'N/A' }}@if($report->attendance_score)%@endif</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Performance Rating</div>
                <div class="metric-value">{{ $report->performance_rating ?? $report->rating ?? 'N/A' }}/10</div>
            </div>
        </div>
    </div>

    <!-- Manager Comment -->
    @if($report->manager_comment)
    <div class="section">
        <div class="section-title">Manager Feedback</div>
        <div class="comment-box manager">
            <div class="comment-title">Manager's Comment</div>
            <div>{{ $report->manager_comment }}</div>
        </div>
    </div>
    @endif

    <!-- Director Comment -->
    @if($report->director_comment)
    <div class="section">
        <div class="section-title">Director's Review</div>
        <div class="comment-box director">
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
        <div class="report-info">
            <table>
                @if($report->submitted_at)
                <tr>
                    <td>Submitted:</td>
                    <td>{{ $report->submitted_at->format('M d, Y h:i A') }}</td>
                </tr>
                @endif
                @if($report->approved_by_manager_at)
                <tr>
                    <td>Manager Approved:</td>
                    <td>{{ $report->approved_by_manager_at->format('M d, Y h:i A') }}</td>
                </tr>
                @endif
                @if($report->approved_by_director_at)
                <tr>
                    <td>Director Approved:</td>
                    <td>{{ $report->approved_by_director_at->format('M d, Y h:i A') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>&copy; {{ date('Y') }} KidzTech - Empowering Young Minds Through Technology</div>
        <div>This report is confidential and intended for the student and their parents/guardians only.</div>
    </div>
</body>
</html>
