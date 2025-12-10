<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment - {{ $assessment->tutor->first_name ?? 'Tutor' }} - {{ $assessment->assessment_month }}</title>
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
        .header .subtitle { color: #666; font-size: 14px; }
        .tutor-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .tutor-name { font-size: 20px; font-weight: 600; }
        .tutor-email { font-size: 13px; color: #666; }
        .score-badge {
            font-size: 36px;
            font-weight: 700;
            padding: 15px 25px;
            border-radius: 10px;
        }
        .score-excellent { background: #D1FAE5; color: #059669; }
        .score-good { background: #FEF3C7; color: #D97706; }
        .score-poor { background: #FEE2E2; color: #DC2626; }
        .section { margin-bottom: 25px; }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #14B8A6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e0e0e0;
        }
        .criteria-table { width: 100%; border-collapse: collapse; }
        .criteria-table th, .criteria-table td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #e0e0e0;
        }
        .criteria-table th { 
            background: #f8f9fa; 
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
        }
        .rating-bar {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
            width: 100px;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        .rating-fill { height: 100%; border-radius: 4px; }
        .rating-excellent { background: #10B981; }
        .rating-good { background: #F59E0B; }
        .rating-poor { background: #EF4444; }
        .comments-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .comment-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .comment-box .author { font-size: 11px; color: #14B8A6; font-weight: 600; margin-bottom: 5px; text-transform: uppercase; }
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
            🖨️ Print Assessment
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>

    <div class="header">
        <h1>Tutor Performance Assessment</h1>
        <div class="subtitle">{{ $assessment->assessment_month }}</div>
    </div>

    <div class="approval-badge">
        ✓ Director Approved — {{ $assessment->director_approved_at?->format('F j, Y') }}
    </div>

    @php
        $score = $assessment->performance_score ?? 0;
        $scoreClass = $score >= 80 ? 'score-excellent' : ($score >= 60 ? 'score-good' : 'score-poor');
    @endphp

    <div class="tutor-info">
        <div>
            <div class="tutor-name">{{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}</div>
            <div class="tutor-email">{{ $assessment->tutor->email ?? '-' }}</div>
        </div>
        <div class="score-badge {{ $scoreClass }}">
            {{ number_format($score, 0) }}%
        </div>
    </div>

    <div class="section">
        <div class="section-title">Assessment Criteria</div>
        <table class="criteria-table">
            <thead>
                <tr>
                    <th>Criteria</th>
                    <th>Rating</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $criteria = [
                        'punctuality' => 'Punctuality',
                        'class_preparation' => 'Class Preparation',
                        'teaching_quality' => 'Teaching Quality',
                        'communication' => 'Communication',
                        'student_engagement' => 'Student Engagement',
                        'report_submission' => 'Report Submission',
                        'professionalism' => 'Professionalism',
                        'adaptability' => 'Adaptability',
                    ];
                @endphp
                @foreach($criteria as $key => $label)
                    @php
                        $rating = $assessment->{$key} ?? 0;
                        $barClass = $rating >= 4 ? 'rating-excellent' : ($rating >= 3 ? 'rating-good' : 'rating-poor');
                    @endphp
                    <tr>
                        <td>{{ $label }}</td>
                        <td>
                            <div class="rating-bar">
                                <div class="rating-fill {{ $barClass }}" style="width: {{ ($rating / 5) * 100 }}%"></div>
                            </div>
                        </td>
                        <td><strong>{{ $rating }}</strong>/5</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($assessment->strengths || $assessment->areas_for_improvement)
        <div class="section">
            <div class="section-title">Performance Notes</div>
            <div class="comments-grid">
                @if($assessment->strengths)
                    <div class="comment-box">
                        <div class="author">💪 Strengths</div>
                        <div class="text">{{ $assessment->strengths }}</div>
                    </div>
                @endif
                @if($assessment->areas_for_improvement)
                    <div class="comment-box">
                        <div class="author">📈 Areas for Improvement</div>
                        <div class="text">{{ $assessment->areas_for_improvement }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($assessment->manager_comment || $assessment->director_comment)
        <div class="section">
            <div class="section-title">Comments</div>
            <div class="comments-grid">
                @if($assessment->manager_comment)
                    <div class="comment-box">
                        <div class="author">Manager's Comment</div>
                        <div class="text">{{ $assessment->manager_comment }}</div>
                    </div>
                @endif
                @if($assessment->director_comment)
                    <div class="comment-box">
                        <div class="author">Director's Comment</div>
                        <div class="text">{{ $assessment->director_comment }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="footer">
        <p>Kidz Tech Portal — Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
