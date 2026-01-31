<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report - {{ $report->student->first_name ?? 'Student' }} {{ $report->student->last_name ?? '' }}</title>
    <style>
        @page {
            margin: 20mm 15mm;
        }

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
            padding: 25px 0;
            margin-bottom: 25px;
            border-bottom: 3px solid #423A8E;
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
            color: #423A8E;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 11pt;
            color: #666;
        }

        .report-info {
            background-color: #f9fafb;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #423A8E;
        }

        .report-info table {
            width: 100%;
        }

        .report-info td {
            padding: 6px 0;
            font-size: 10pt;
        }

        .report-info td:first-child {
            font-weight: bold;
            width: 35%;
            color: #6b7280;
        }

        .section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #423A8E;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e5e7eb;
        }

        .section-content {
            padding: 8px 0;
            text-align: justify;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .badge-container {
            margin-top: 8px;
        }

        .badge {
            display: inline-block;
            background-color: #e8e6f5;
            color: #423A8E;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 9pt;
            margin: 3px 5px 3px 0;
        }

        .skill-badge {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .skill-badge.new {
            background-color: #e0f7fa;
            color: #00838f;
        }

        .skills-section {
            margin-top: 10px;
        }

        .skills-subtitle {
            font-weight: bold;
            color: #333;
            font-size: 10pt;
            margin-bottom: 8px;
            margin-top: 12px;
        }

        .project-item {
            background-color: #f9fafb;
            padding: 10px 12px;
            margin-bottom: 8px;
            border-left: 3px solid #423A8E;
        }

        .project-name {
            font-weight: bold;
            color: #333;
            font-size: 10pt;
        }

        .project-link {
            font-size: 8pt;
            color: #423A8E;
            word-break: break-all;
        }

        .comment-box {
            background-color: #e0f7fa;
            border-left: 4px solid #00CCCD;
            padding: 15px;
            margin-top: 10px;
        }

        .comment-title {
            font-weight: bold;
            color: #00838f;
            margin-bottom: 8px;
            font-size: 10pt;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            border-top: 2px solid #423A8E;
            font-size: 8pt;
            color: #6b7280;
            text-align: center;
        }

        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 18px 0;
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
                <td><strong>{{ $report->student->first_name ?? '' }} {{ $report->student->last_name ?? '' }}</strong></td>
            </tr>
            <tr>
                <td>Tutor:</td>
                <td><strong>{{ $report->tutor->first_name ?? '' }} {{ $report->tutor->last_name ?? '' }}</strong></td>
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
                <td>Generated:</td>
                <td><strong>{{ now()->format('M d, Y') }}</strong></td>
            </tr>
        </table>
    </div>

    @php
        $courses = is_array($report->courses) ? $report->courses : [];
        $skillsMastered = is_array($report->skills_mastered) ? $report->skills_mastered : [];
        $newSkills = is_array($report->new_skills) ? $report->new_skills : [];
        $projects = is_array($report->projects) ? $report->projects : [];
    @endphp

    <!-- Courses Covered -->
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
    @if(count($projects) > 0)
    <div class="section">
        <div class="section-title">Projects Completed</div>
        @foreach($projects as $project)
            <div class="project-item">
                <div class="project-name">{{ $project['name'] ?? $project['title'] ?? 'Project' }}</div>
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

    <!-- Legacy Fields (if present) -->
    @if($report->strengths)
    <div class="section">
        <div class="section-title">Strengths & Achievements</div>
        <div class="section-content">{{ $report->strengths }}</div>
    </div>
    @endif

    @if($report->weaknesses)
    <div class="section">
        <div class="section-title">Areas Needing Attention</div>
        <div class="section-content">{{ $report->weaknesses }}</div>
    </div>
    @endif

    @if($report->next_steps)
    <div class="section">
        <div class="section-title">Next Steps & Recommendations</div>
        <div class="section-content">{{ $report->next_steps }}</div>
    </div>
    @endif

    <!-- Director Comment -->
    @if($report->director_comment)
    <div class="section">
        <div class="section-title">Director's Note</div>
        <div class="comment-box">
            <div class="comment-title">Message from the Director</div>
            <div class="section-content">{{ $report->director_comment }}</div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>&copy; {{ date('Y') }} KidzTech Coding Club - Empowering Young Minds Through Technology</div>
        <div>This report is confidential and intended for the student and their parents/guardians only.</div>
    </div>
</body>
</html>
