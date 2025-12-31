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
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
        }

        .header {
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 3px solid #9333ea;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .logo-cell {
            display: table-cell;
            width: 30%;
            vertical-align: middle;
        }

        .logo {
            max-width: 100px;
            height: auto;
        }

        .title-cell {
            display: table-cell;
            width: 70%;
            text-align: right;
            vertical-align: middle;
        }

        .header h1 {
            font-size: 20pt;
            color: #9333ea;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 11pt;
            color: #666;
        }

        .report-info {
            background-color: #f9fafb;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #9333ea;
        }

        .report-info table {
            width: 100%;
        }

        .report-info td {
            padding: 4px 0;
            font-size: 10pt;
        }

        .report-info td:first-child {
            font-weight: bold;
            width: 35%;
            color: #6b7280;
        }

        .section {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #9333ea;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e5e7eb;
        }

        .section-content {
            padding: 6px 0;
            text-align: justify;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .badge-container {
            margin-top: 6px;
        }

        .badge {
            display: inline-block;
            background-color: #f3e8ff;
            color: #7c3aed;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 9pt;
            margin: 2px 4px 2px 0;
        }

        .skill-badge {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .skill-badge.new {
            background-color: #d1fae5;
            color: #065f46;
        }

        .skills-section {
            margin-top: 8px;
        }

        .skills-subtitle {
            font-weight: bold;
            color: #333;
            font-size: 10pt;
            margin-bottom: 6px;
            margin-top: 10px;
        }

        .project-item {
            background-color: #f9fafb;
            padding: 8px 10px;
            margin-bottom: 6px;
            border-left: 3px solid #6366f1;
        }

        .project-name {
            font-weight: bold;
            color: #333;
            font-size: 10pt;
        }

        .project-link {
            font-size: 8pt;
            color: #6366f1;
            word-break: break-all;
        }

        .metrics {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .metric-item {
            display: table-cell;
            width: 50%;
            padding: 12px;
            background-color: #f9fafb;
            text-align: center;
        }

        .metric-label {
            font-size: 9pt;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .metric-value {
            font-size: 16pt;
            font-weight: bold;
            color: #9333ea;
        }

        .comment-box {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 12px;
            margin-top: 8px;
        }

        .comment-box.director {
            background-color: #ede9fe;
            border-left-color: #8b5cf6;
        }

        .comment-title {
            font-weight: bold;
            color: #065f46;
            margin-bottom: 6px;
            font-size: 10pt;
        }

        .comment-box.director .comment-title {
            color: #5b21b6;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 8pt;
            color: #6b7280;
            text-align: center;
        }

        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <!-- Header with Logo -->
    <div class="header">
        <div class="header-content">
            <div class="logo-cell">
                <img src="{{ public_path('images/logo_light.png') }}" alt="KidzTech Logo" class="logo">
            </div>
            <div class="title-cell">
                <h1>Progress Report</h1>
                <div class="subtitle">Monthly Student Progress Review</div>
            </div>
        </div>
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
                <td><strong>{{ $report->month }} {{ $report->year }}</strong></td>
            </tr>
            @if($report->period_from && $report->period_to)
            <tr>
                <td>Period Range:</td>
                <td><strong>{{ \Carbon\Carbon::parse($report->period_from)->format('M d') }} - {{ \Carbon\Carbon::parse($report->period_to)->format('M d, Y') }}</strong></td>
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

    <!-- Courses Covered -->
    @php
        $courses = is_array($report->courses) ? $report->courses : [];
    @endphp
    @if(count($courses) > 0)
    <div class="section">
        <div class="section-title">Courses Covered</div>
        <div class="badge-container">
            @foreach($courses as $course)
                <span class="badge">{{ is_array($course) ? ($course['name'] ?? $course) : $course }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Skills Section -->
    @php
        $skillsMastered = is_array($report->skills_mastered) ? $report->skills_mastered : [];
        $newSkills = is_array($report->new_skills) ? $report->new_skills : [];
    @endphp
    @if(count($skillsMastered) > 0 || count($newSkills) > 0)
    <div class="section">
        <div class="section-title">Skills Development</div>
        <div class="skills-section">
            @if(count($skillsMastered) > 0)
            <div class="skills-subtitle">Skills Mastered</div>
            <div class="badge-container">
                @foreach($skillsMastered as $skill)
                    <span class="badge skill-badge">{{ $skill }}</span>
                @endforeach
            </div>
            @endif

            @if(count($newSkills) > 0)
            <div class="skills-subtitle">New Skills Being Learned</div>
            <div class="badge-container">
                @foreach($newSkills as $skill)
                    <span class="badge skill-badge new">{{ $skill }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Projects Completed -->
    @php
        $projects = is_array($report->projects) ? $report->projects : [];
    @endphp
    @if(count($projects) > 0)
    <div class="section">
        <div class="section-title">Projects Completed</div>
        @foreach($projects as $project)
            <div class="project-item">
                <div class="project-name">{{ $project['name'] ?? 'Project' }}</div>
                @if(!empty($project['link']))
                    <div class="project-link">{{ $project['link'] }}</div>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    <!-- Areas for Improvement -->
    @if($report->areas_for_improvement)
    <div class="section">
        <div class="section-title">Areas for Improvement</div>
        <div class="section-content">{{ $report->areas_for_improvement }}</div>
    </div>
    @endif

    <!-- Goals for Next Month -->
    @if($report->goals_next_month)
    <div class="section">
        <div class="section-title">Goals for Next Month</div>
        <div class="section-content">{{ $report->goals_next_month }}</div>
    </div>
    @endif

    <!-- Assignments -->
    @if($report->assignments)
    <div class="section">
        <div class="section-title">Assignments</div>
        <div class="section-content">{{ $report->assignments }}</div>
    </div>
    @endif

    <!-- Comments & Observations -->
    @if($report->comments_observation)
    <div class="section">
        <div class="section-title">Comments & Observations</div>
        <div class="section-content">{{ $report->comments_observation }}</div>
    </div>
    @endif

    <!-- Performance Metrics -->
    <div class="section">
        <div class="section-title">Performance Metrics</div>
        <div class="metrics">
            <div class="metric-item">
                <div class="metric-label">Attendance Score</div>
                <div class="metric-value">{{ $report->attendance_score ?? 'N/A' }}@if($report->attendance_score)%@endif</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Performance Rating</div>
                <div class="metric-value">{{ $report->rating ?? 'N/A' }}@if($report->rating)/5@endif</div>
            </div>
        </div>
    </div>

    <!-- Legacy Fields (if present and MVP fields are empty) -->
    @if($report->progress_summary && empty($courses) && empty($skillsMastered))
    <div class="section">
        <div class="section-title">Progress Summary</div>
        <div class="section-content">{{ $report->progress_summary }}</div>
    </div>
    @endif

    @if($report->strengths && empty($skillsMastered))
    <div class="section">
        <div class="section-title">Strengths & Achievements</div>
        <div class="section-content">{{ $report->strengths }}</div>
    </div>
    @endif

    @if($report->weaknesses && empty($report->areas_for_improvement))
    <div class="section">
        <div class="section-title">Areas for Improvement</div>
        <div class="section-content">{{ $report->weaknesses }}</div>
    </div>
    @endif

    @if($report->next_steps && empty($report->goals_next_month))
    <div class="section">
        <div class="section-title">Next Steps & Recommendations</div>
        <div class="section-content">{{ $report->next_steps }}</div>
    </div>
    @endif

    <!-- Manager Comment -->
    @if($report->manager_comment)
    <div class="section">
        <div class="section-title">Manager Feedback</div>
        <div class="comment-box">
            <div class="comment-title">Manager's Comment</div>
            <div class="section-content">{{ $report->manager_comment }}</div>
        </div>
    </div>
    @endif

    <!-- Director Comment -->
    @if($report->director_comment)
    <div class="section">
        <div class="section-title">Director's Review</div>
        <div class="comment-box director">
            <div class="comment-title">Director's Comment</div>
            <div class="section-content">{{ $report->director_comment }}</div>
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
