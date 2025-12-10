<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $assessment->tutor->first_name ?? 'Tutor' }}_Report_{{ str_replace(' ', '_', $assessment->assessment_month ?? 'Assessment') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        
        body {
            font-family: 'Courier Prime', 'Courier New', Courier, monospace;
            background: #f5f5f5;
            padding: 40px;
            line-height: 1.6;
            color: #333;
            margin: 0;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 2px solid #333;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 24px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }
        
        .divider {
            border-top: 1px solid #333;
            margin: 20px 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .info-row {
            margin: 8px 0;
        }
        
        .info-label {
            font-weight: bold;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }
        
        .table th {
            background: #333;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
        }
        
        .table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
        }
        
        .table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .score-box {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }
        
        .score-excellent {
            background: #d1fae5;
            color: #065f46;
        }
        
        .score-good {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .score-acceptable {
            background: #fef3c7;
            color: #92400e;
        }
        
        .score-needs {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .overall-score {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 8px;
        }
        
        .overall-score .score {
            font-size: 48px;
            font-weight: bold;
            color: #0284c7;
        }
        
        .overall-score .label {
            font-size: 14px;
            color: #64748b;
            margin-top: 5px;
        }
        
        .rating-bar {
            background: #e5e7eb;
            border-radius: 4px;
            height: 20px;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .rating-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 4px;
        }
        
        .remarks-box {
            margin: 20px 0;
            padding: 15px;
            background: #fefce8;
            border: 2px solid #eab308;
            border-radius: 8px;
        }
        
        .remarks-title {
            font-weight: bold;
            color: #854d0e;
            margin-bottom: 10px;
        }
        
        .remarks-content {
            color: #713f12;
        }
        
        .strengths-box {
            margin: 20px 0;
            padding: 15px;
            background: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 8px;
        }
        
        .weaknesses-box {
            margin: 20px 0;
            padding: 15px;
            background: #fff7ed;
            border: 2px solid #f97316;
            border-radius: 8px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #333;
            color: #64748b;
            font-size: 12px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border: none;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Kidz Tech Coding Club</h1>
            <h2>Tutor Performance Report Card</h2>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Tutor Name</div>
                <div>{{ $assessment->tutor->first_name ?? 'N/A' }} {{ $assessment->tutor->last_name ?? '' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Assessment Period</div>
                <div>{{ $assessment->assessment_month ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Assessed By</div>
                <div>{{ $assessment->manager->name ?? 'Manager' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Approved By</div>
                <div>{{ $assessment->director->name ?? 'Director' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Approval Date</div>
                <div>{{ $assessment->approved_by_director_at ? $assessment->approved_by_director_at->format('M j, Y') : 'Pending' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div>{{ ucfirst(str_replace('-', ' ', $assessment->status)) }}</div>
            </div>
        </div>

        @if($assessment->performance_score)
            <div class="overall-score">
                <div class="score">{{ $assessment->performance_score }}%</div>
                <div class="label">Overall Performance Score</div>
            </div>
        @endif

        <div class="section-title">📊 Performance Ratings</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Criteria</th>
                    <th>Rating</th>
                    <th>Visual</th>
                </tr>
            </thead>
            <tbody>
                @if($assessment->professionalism_rating)
                <tr>
                    <td><strong>Professionalism</strong></td>
                    <td>{{ $assessment->professionalism_rating }}/5</td>
                    <td>
                        <div class="rating-bar">
                            <div class="rating-bar-fill" style="width: {{ ($assessment->professionalism_rating / 5) * 100 }}%"></div>
                        </div>
                    </td>
                </tr>
                @endif
                @if($assessment->communication_rating)
                <tr>
                    <td><strong>Communication</strong></td>
                    <td>{{ $assessment->communication_rating }}/5</td>
                    <td>
                        <div class="rating-bar">
                            <div class="rating-bar-fill" style="width: {{ ($assessment->communication_rating / 5) * 100 }}%"></div>
                        </div>
                    </td>
                </tr>
                @endif
                @if($assessment->punctuality_rating)
                <tr>
                    <td><strong>Punctuality</strong></td>
                    <td>{{ $assessment->punctuality_rating }}/5</td>
                    <td>
                        <div class="rating-bar">
                            <div class="rating-bar-fill" style="width: {{ ($assessment->punctuality_rating / 5) * 100 }}%"></div>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        @if($assessment->strengths)
            <div class="strengths-box">
                <div class="remarks-title">✅ Strengths</div>
                <div class="remarks-content">{{ $assessment->strengths }}</div>
            </div>
        @endif

        @if($assessment->weaknesses)
            <div class="weaknesses-box">
                <div class="remarks-title">📈 Areas for Improvement</div>
                <div class="remarks-content">{{ $assessment->weaknesses }}</div>
            </div>
        @endif

        @if($assessment->recommendations)
            <div class="remarks-box" style="background: #eff6ff; border-color: #3b82f6;">
                <div class="remarks-title" style="color: #1e40af;">💡 Manager Recommendations</div>
                <div class="remarks-content" style="color: #1e3a8a;">{{ $assessment->recommendations }}</div>
            </div>
        @endif

        @if($assessment->director_comment)
            <div class="remarks-box">
                <div class="remarks-title">📝 Director's Remarks</div>
                <div class="remarks-content">{{ $assessment->director_comment }}</div>
            </div>
        @endif

        <div class="footer">
            <p><strong>Generated:</strong> {{ now()->format('M j, Y \a\t g:i A') }}</p>
            <p>Kidz Tech Coding Club © {{ date('Y') }}</p>
            <p>Tutor Quality Assurance System - Director Portal</p>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" style="padding: 12px 24px; background: #0ea5e9; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;">
                🖨️ Print Report
            </button>
            <button onclick="window.close()" style="padding: 12px 24px; background: #64748b; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; margin-left: 10px;">
                ✕ Close
            </button>
        </div>
    </div>
</body>
</html>
