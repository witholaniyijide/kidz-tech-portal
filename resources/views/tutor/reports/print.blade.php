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
                display: none !important;
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
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            background: white;
            padding: 20px;
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #9333ea;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .header-text {
            text-align: right;
        }

        .header h1 {
            font-size: 22pt;
            color: #9333ea;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 12pt;
            color: #666;
        }

        .report-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #9333ea;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 10pt;
        }

        .info-value {
            color: #000;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #9333ea;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e5e5;
        }

        .section-content {
            padding: 8px 0;
            text-align: justify;
            line-height: 1.7;
            color: #222;
        }

        .courses-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .course-badge {
            background-color: #f3e8ff;
            color: #7c3aed;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 10pt;
            font-weight: 500;
        }

        .skills-section {
            margin-top: 10px;
        }

        .skills-subsection {
            margin-bottom: 15px;
        }

        .skills-subtitle {
            font-weight: bold;
            color: #333;
            font-size: 11pt;
            margin-bottom: 8px;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .skill-tag {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 9pt;
        }

        .skill-tag.new {
            background-color: #d1fae5;
            color: #065f46;
        }

        .projects-list {
            margin-top: 10px;
        }

        .project-item {
            background-color: #f9fafb;
            padding: 10px 12px;
            margin-bottom: 8px;
            border-left: 3px solid #6366f1;
        }

        .project-name {
            font-weight: bold;
            color: #333;
            font-size: 11pt;
        }

        .project-link {
            font-size: 9pt;
            color: #6366f1;
            word-break: break-all;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 10px;
        }

        .metric-box {
            background-color: #f9f9f9;
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .metric-label {
            font-size: 10pt;
            color: #666;
            margin-bottom: 5px;
        }

        .metric-value {
            font-size: 18pt;
            font-weight: bold;
            color: #9333ea;
        }

        .comment-box {
            background-color: #fafafa;
            border: 1px solid #ccc;
            border-left: 4px solid #666;
            padding: 12px;
            margin-top: 10px;
        }

        .comment-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 6px;
            font-size: 11pt;
        }

        .approval-timeline {
            background-color: #f5f5f5;
            padding: 12px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #9333ea;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 12pt;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .print-button:hover {
            opacity: 0.9;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 15px 0;
        }

        .text-content {
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button onclick="window.print()" class="print-button no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print Report
    </button>

    <!-- Header with Logo -->
    <div class="header">
        <img src="{{ asset('images/logo_light.png') }}" alt="KidzTech Logo" class="logo">
        <div class="header-text">
            <h1>Progress Report</h1>
            <div class="subtitle">Monthly Student Progress Review</div>
        </div>
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
                <span class="info-value">{{ $report->month }} {{ $report->year }}</span>
            </div>
            @if($report->period_from && $report->period_to)
            <div class="info-item">
                <span class="info-label">Period Range:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($report->period_from)->format('M d') }} - {{ \Carbon\Carbon::parse($report->period_to)->format('M d, Y') }}</span>
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

    <!-- Courses Covered -->
    @php
        $courses = is_array($report->courses) ? $report->courses : [];
    @endphp
    @if(count($courses) > 0)
    <div class="section">
        <div class="section-title">Courses Covered</div>
        <div class="courses-list">
            @foreach($courses as $course)
                <span class="course-badge">{{ is_array($course) ? ($course['name'] ?? $course) : $course }}</span>
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
            <div class="skills-subsection">
                <div class="skills-subtitle">Skills Mastered</div>
                <div class="skills-list">
                    @foreach($skillsMastered as $skill)
                        <span class="skill-tag">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($newSkills) > 0)
            <div class="skills-subsection">
                <div class="skills-subtitle">New Skills Being Learned</div>
                <div class="skills-list">
                    @foreach($newSkills as $skill)
                        <span class="skill-tag new">{{ $skill }}</span>
                    @endforeach
                </div>
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
        <div class="projects-list">
            @foreach($projects as $project)
                <div class="project-item">
                    <div class="project-name">{{ $project['name'] ?? 'Project' }}</div>
                    @if(!empty($project['link']))
                        @if(filter_var($project['link'], FILTER_VALIDATE_URL))
                            <div class="project-link">{{ $project['link'] }}</div>
                        @else
                            <div class="project-link" style="color: #666;">{{ $project['link'] }}</div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Areas for Improvement -->
    @if($report->areas_for_improvement)
    <div class="section">
        <div class="section-title">Areas for Improvement</div>
        <div class="section-content text-content">{{ $report->areas_for_improvement }}</div>
    </div>
    @endif

    <!-- Goals for Next Month -->
    @if($report->goals_next_month)
    <div class="section">
        <div class="section-title">Goals for Next Month</div>
        <div class="section-content text-content">{{ $report->goals_next_month }}</div>
    </div>
    @endif

    <!-- Assignments -->
    @if($report->assignments)
    <div class="section">
        <div class="section-title">Assignments</div>
        <div class="section-content text-content">{{ $report->assignments }}</div>
    </div>
    @endif

    <!-- Comments & Observations -->
    @if($report->comments_observation)
    <div class="section">
        <div class="section-title">Comments & Observations</div>
        <div class="section-content text-content">{{ $report->comments_observation }}</div>
    </div>
    @endif

    <!-- Performance Metrics -->
    <div class="section">
        <div class="section-title">Performance Metrics</div>
        <div class="metrics-grid">
            <div class="metric-box">
                <div class="metric-label">Attendance Score</div>
                <div class="metric-value">{{ $report->attendance_score ?? 'N/A' }}@if($report->attendance_score)%@endif</div>
            </div>
            <div class="metric-box">
                <div class="metric-label">Performance Rating</div>
                <div class="metric-value">{{ $report->rating ?? 'N/A' }}@if($report->rating)/5@endif</div>
            </div>
        </div>
    </div>

    <!-- Legacy Fields (if present and MVP fields are empty) -->
    @if($report->progress_summary && empty($courses) && empty($skillsMastered))
    <div class="section">
        <div class="section-title">Progress Summary</div>
        <div class="section-content text-content">{{ $report->progress_summary }}</div>
    </div>
    @endif

    @if($report->strengths && empty($skillsMastered))
    <div class="section">
        <div class="section-title">Strengths & Achievements</div>
        <div class="section-content text-content">{{ $report->strengths }}</div>
    </div>
    @endif

    @if($report->weaknesses && empty($report->areas_for_improvement))
    <div class="section">
        <div class="section-title">Areas for Improvement</div>
        <div class="section-content text-content">{{ $report->weaknesses }}</div>
    </div>
    @endif

    @if($report->next_steps && empty($report->goals_next_month))
    <div class="section">
        <div class="section-title">Next Steps & Recommendations</div>
        <div class="section-content text-content">{{ $report->next_steps }}</div>
    </div>
    @endif

    <!-- Manager Comment -->
    @if($report->manager_comment)
    <div class="section">
        <div class="section-title">Manager Feedback</div>
        <div class="comment-box" style="border-left-color: #10b981; background-color: #f0fdf4;">
            <div class="comment-title" style="color: #065f46;">Manager's Comment</div>
            <div class="text-content">{{ $report->manager_comment }}</div>
        </div>
    </div>
    @endif

    <!-- Director Comment -->
    @if($report->director_comment)
    <div class="section">
        <div class="section-title">Director's Review</div>
        <div class="comment-box" style="border-left-color: #8b5cf6; background-color: #ede9fe;">
            <div class="comment-title" style="color: #5b21b6;">Director's Comment</div>
            <div class="text-content">{{ $report->director_comment }}</div>
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
</body>
</html>
