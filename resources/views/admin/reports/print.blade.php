<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - {{ $report->student->first_name ?? 'Student' }} - {{ $report->report_month }} {{ $report->report_year }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            padding-bottom: 20px;
            border-bottom: 3px solid #14B8A6;
        }
        .header h1 { 
            color: #14B8A6; 
            font-size: 28px; 
            margin-bottom: 5px;
        }
        .header .subtitle { 
            color: #666; 
            font-size: 14px; 
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .meta-info .item { text-align: center; }
        .meta-info .label { font-size: 11px; color: #666; text-transform: uppercase; }
        .meta-info .value { font-size: 16px; font-weight: 600; color: #333; }
        .section { margin-bottom: 25px; }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #14B8A6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e0e0e0;
        }
        .section-content { 
            color: #444; 
            font-size: 14px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-box {
            background: linear-gradient(135deg, #14B8A6, #06B6D4);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box .number { font-size: 24px; font-weight: 700; }
        .stat-box .label { font-size: 11px; opacity: 0.9; }
        .people-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        .person-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .person-box .role { font-size: 11px; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .person-box .name { font-size: 16px; font-weight: 600; }
        .comment-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .comment-box .author { font-size: 11px; color: #14B8A6; font-weight: 600; margin-bottom: 5px; }
        .comment-box .text { font-size: 13px; color: #444; }
        .approval-badge {
            background: #10B981;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #14B8A6; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Report
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>

    <div class="header">
        <h1>Monthly Student Report</h1>
        <div class="subtitle">{{ $report->report_month }} {{ $report->report_year }}</div>
    </div>

    <div class="approval-badge">
        ✓ Director Approved — {{ $report->director_approved_at?->format('F j, Y') }}
    </div>

    <div class="people-grid">
        <div class="person-box">
            <div class="role">Student</div>
            <div class="name">{{ $report->student->first_name ?? 'Unknown' }} {{ $report->student->last_name ?? '' }}</div>
        </div>
        <div class="person-box">
            <div class="role">Tutor</div>
            <div class="name">{{ $report->tutor->first_name ?? 'Unknown' }} {{ $report->tutor->last_name ?? '' }}</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="number">{{ $report->classes_held ?? 0 }}</div>
            <div class="label">Classes Held</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $report->classes_attended ?? 0 }}</div>
            <div class="label">Attended</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $report->course_level ?? '-' }}</div>
            <div class="label">Course Level</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $report->total_periods ?? 0 }}</div>
            <div class="label">Total Periods</div>
        </div>
    </div>

    @if($report->topics_covered)
        <div class="section">
            <div class="section-title">Topics Covered</div>
            <div class="section-content">{!! nl2br(e($report->topics_covered)) !!}</div>
        </div>
    @endif

    @if($report->progress_summary)
        <div class="section">
            <div class="section-title">Progress Summary</div>
            <div class="section-content">{!! nl2br(e($report->progress_summary)) !!}</div>
        </div>
    @endif

    @if($report->challenges)
        <div class="section">
            <div class="section-title">Challenges</div>
            <div class="section-content">{!! nl2br(e($report->challenges)) !!}</div>
        </div>
    @endif

    @if($report->recommendations)
        <div class="section">
            <div class="section-title">Recommendations</div>
            <div class="section-content">{!! nl2br(e($report->recommendations)) !!}</div>
        </div>
    @endif

    @if($report->next_month_goals)
        <div class="section">
            <div class="section-title">Next Month Goals</div>
            <div class="section-content">{!! nl2br(e($report->next_month_goals)) !!}</div>
        </div>
    @endif

    @if($report->manager_comment || $report->director_comment)
        <div class="section">
            <div class="section-title">Comments</div>
            @if($report->manager_comment)
                <div class="comment-box">
                    <div class="author">Manager's Comment</div>
                    <div class="text">{{ $report->manager_comment }}</div>
                </div>
            @endif
            @if($report->director_comment)
                <div class="comment-box">
                    <div class="author">Director's Comment</div>
                    <div class="text">{{ $report->director_comment }}</div>
                </div>
            @endif
        </div>
    @endif

    <div class="footer">
        <p>Kidz Tech Portal — Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
