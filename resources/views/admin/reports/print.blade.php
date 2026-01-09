<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print - Progress Report - {{ $report->student->first_name ?? 'Student' }} {{ $report->student->last_name ?? '' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            background: white;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #14B8A6;
        }
        .logo { width: 120px; height: auto; }
        .header-text { text-align: right; }
        .header h1 { font-size: 22pt; color: #14B8A6; margin-bottom: 5px; }
        .header .subtitle { font-size: 12pt; color: #666; }
        .report-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #14B8A6;
        }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .info-item { margin-bottom: 5px; }
        .info-label { font-weight: bold; color: #555; font-size: 10pt; }
        .info-value { color: #000; }
        .section { margin-bottom: 20px; page-break-inside: avoid; }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #14B8A6;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e5e5;
        }
        .section-content { padding: 8px 0; text-align: justify; line-height: 1.7; color: #222; white-space: pre-wrap; }
        .badge-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .badge {
            background-color: #e0f2f1;
            color: #00796b;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 10pt;
            font-weight: 500;
        }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .badge-green { background-color: #d1fae5; color: #065f46; }
        .project-item {
            background-color: #f9fafb;
            padding: 10px 12px;
            margin-bottom: 8px;
            border-left: 3px solid #14B8A6;
        }
        .project-name { font-weight: bold; color: #333; font-size: 11pt; }
        .project-link { font-size: 9pt; color: #14B8A6; word-break: break-all; }
        .metrics-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 10px; }
        .metric-box {
            background-color: #f9f9f9;
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .metric-label { font-size: 10pt; color: #666; margin-bottom: 5px; }
        .metric-value { font-size: 18pt; font-weight: bold; color: #14B8A6; }
        .comment-box {
            background-color: #fafafa;
            border: 1px solid #ccc;
            border-left: 4px solid #666;
            padding: 12px;
            margin-top: 10px;
        }
        .comment-title { font-weight: bold; color: #333; margin-bottom: 6px; font-size: 11pt; }
        .approval-timeline {
            background-color: #f5f5f5;
            padding: 12px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #14B8A6;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #14B8A6, #06B6D4);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 12pt;
            cursor: pointer;
        }
        .manager-comment { border-left-color: #10b981; background-color: #f0fdf4; }
        .director-comment { border-left-color: #8b5cf6; background-color: #ede9fe; }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn" style="display: block;">Print Report</button>

    <div class="header">
        <img src="{{ asset('images/logo_light.png') }}" alt="KidzTech Logo" class="logo">
        <div class="header-text">
            <h1>Progress Report</h1>
            <div class="subtitle">Monthly Student Progress Review</div>
        </div>
    </div>

    <div class="report-info">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Student Name:</span>
                <span class="info-value">{{ $report->student->first_name ?? '' }} {{ $report->student->last_name ?? '' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tutor:</span>
                <span class="info-value">{{ $report->tutor->first_name ?? '' }} {{ $report->tutor->last_name ?? '' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Report Period:</span>
                <span class="info-value">{{ $report->month ?? '' }} {{ $report->year ?? '' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">{{ ucfirst(str_replace('-', ' ', $report->status ?? 'N/A')) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Generated:</span>
                <span class="info-value">{{ now()->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    @php
        $courses = is_array($report->courses) ? $report->courses : [];
        $skillsMastered = is_array($report->skills_mastered) ? $report->skills_mastered : [];
        $newSkills = is_array($report->new_skills) ? $report->new_skills : [];
        $projects = is_array($report->projects) ? $report->projects : [];
    @endphp

    @if(count($courses) > 0)
        <div class="section">
            <div class="section-title">Courses Covered</div>
            <div class="badge-list">
                @foreach($courses as $course)
                    <span class="badge">{{ is_array($course) ? ($course['name'] ?? json_encode($course)) : $course }}</span>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($skillsMastered) > 0)
        <div class="section">
            <div class="section-title">Skills Mastered</div>
            <div class="badge-list">
                @foreach($skillsMastered as $skill)
                    <span class="badge badge-blue">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($newSkills) > 0)
        <div class="section">
            <div class="section-title">New Skills Being Learned</div>
            <div class="badge-list">
                @foreach($newSkills as $skill)
                    <span class="badge badge-green">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
    @endif

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

    @if($report->areas_for_improvement)
        <div class="section">
            <div class="section-title">Areas for Improvement</div>
            <div class="section-content">{{ $report->areas_for_improvement }}</div>
        </div>
    @endif

    @if($report->goals_next_month)
        <div class="section">
            <div class="section-title">Goals for Next Month</div>
            <div class="section-content">{{ $report->goals_next_month }}</div>
        </div>
    @endif

    @if($report->assignments)
        <div class="section">
            <div class="section-title">Assignments</div>
            <div class="section-content">{{ $report->assignments }}</div>
        </div>
    @endif

    @if($report->comments_observation)
        <div class="section">
            <div class="section-title">Comments & Observations</div>
            <div class="section-content">{{ $report->comments_observation }}</div>
        </div>
    @endif

    <div class="section">
        <div class="section-title">Performance Metrics</div>
        <div class="metrics-grid">
            <div class="metric-box">
                <div class="metric-label">Attendance Score</div>
                <div class="metric-value">{{ $report->attendance_score ? $report->attendance_score . '%' : 'N/A' }}</div>
            </div>
            <div class="metric-box">
                <div class="metric-label">Performance Rating</div>
                <div class="metric-value">{{ $report->rating ? $report->rating . '/5' : 'N/A' }}</div>
            </div>
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

    @if($report->manager_comment)
        <div class="section">
            <div class="section-title">Manager Feedback</div>
            <div class="comment-box manager-comment">
                <div class="comment-title" style="color: #065f46;">Manager's Comment</div>
                <div class="section-content">{{ $report->manager_comment }}</div>
            </div>
        </div>
    @endif

    @if($report->director_comment)
        <div class="section">
            <div class="section-title">Director's Review</div>
            <div class="comment-box director-comment">
                <div class="comment-title" style="color: #5b21b6;">Director's Comment</div>
                <div class="section-content">{{ $report->director_comment }}</div>
            </div>
        </div>
    @endif

    @if($report->approved_by_manager_at || $report->approved_by_director_at)
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

    <div class="footer">
        <div>KidzTech - Empowering Young Minds Through Technology</div>
        <div>This report is confidential and intended for the student and their parents/guardians only.</div>
    </div>
</body>
</html>
