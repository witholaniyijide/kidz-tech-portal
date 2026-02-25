<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Report Card - {{ $assessment->tutor->first_name }} {{ $assessment->tutor->last_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }

        .rating-excellent, .rating-on-time {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .rating-good {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        .rating-acceptable {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        .rating-needs-improvement, .rating-late {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .rating-unacceptable {
            background-color: #7f1d1d;
            color: #ffffff;
        }

        .overall-excellent {
            background: #f0fdf4;
            border: 2px solid #22c55e;
        }
        .overall-good {
            background: #eff6ff;
            border: 2px solid #3b82f6;
        }
        .overall-acceptable {
            background: #fefce8;
            border: 2px solid #eab308;
        }
        .overall-needs-improvement {
            background: #fef2f2;
            border: 2px solid #ef4444;
        }

        .strength-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        .weakness-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <!-- Print Button -->
    <div class="no-print max-w-4xl mx-auto mb-4 flex justify-between items-center">
        <a href="{{ route('tutor.performance.show', $assessment) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            &larr; Back
        </a>
        <button onclick="window.print()" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
            Print Report Card
        </button>
    </div>

    <!-- Performance Card -->
    <div class="performance-card bg-white rounded-lg shadow-lg overflow-hidden max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-sky-500 to-sky-600 text-white p-6 text-center">
            <h1 class="text-2xl font-bold">KIDZ TECH CODING CLUB</h1>
            <h2 class="text-lg font-semibold mt-1">Tutor Performance Report</h2>
        </div>

        {{-- Tutor Info --}}
        <div class="p-6 border-b">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Tutor Name</span>
                    <p class="font-semibold text-gray-800">{{ $assessment->tutor->first_name }} {{ $assessment->tutor->last_name }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Assessment Period</span>
                    <p class="font-semibold text-gray-800">{{ $assessment->assessment_period }}</p>
                    @if($assessment->assessment_date)
                        <p class="text-xs text-gray-500">Date: {{ $assessment->assessment_date->format('d M Y') }}</p>
                    @endif
                </div>
                <div>
                    <span class="text-sm text-gray-500">Total Penalties</span>
                    <p class="font-semibold {{ ($assessment->directorAction?->penalty_amount ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ ($assessment->directorAction?->penalty_amount ?? 0) > 0 ? '₦' . number_format($assessment->directorAction->penalty_amount, 2) : 'None' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Performance Breakdown Section --}}
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                🧾 PERFORMANCE BREAKDOWN
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="px-4 py-3 text-left">Criteria</th>
                            <th class="px-4 py-3 text-left">Performance</th>
                            <th class="px-4 py-3 text-left">Visual Bar</th>
                            <th class="px-4 py-3 text-left">Rating Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessment->ratings as $rating)
                            @php
                                $percentage = $rating->percentage;
                                $ratingInfo = getEmojiAndLabel($percentage);
                                $visualBar = createVisualBar($percentage);
                            @endphp
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="px-4 py-3 font-medium">{{ $rating->criteria->name }}</td>
                                <td class="px-4 py-3">{{ number_format($percentage, 1) }}%</td>
                                <td class="px-4 py-3 font-mono text-lg tracking-wider">{{ $visualBar }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1">
                                        {{ $ratingInfo['emoji'] }} {{ $ratingInfo['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Overall Rating Box --}}
        @php
            $overallClass = getOverallRatingClass($overallScore);
        @endphp
        <div class="p-6">
            <div class="{{ $overallClass }} rounded-lg p-6 text-center">
                <span class="text-lg">Overall Rating:</span>
                <span class="text-3xl font-bold ml-2">{{ number_format($overallScore, 1) }}%</span>
                <span class="text-lg ml-2">({{ $overallInfo['label'] }})</span>
            </div>
        </div>

        {{-- Penalty Deductions --}}
        @if(($assessment->punctuality_late_count ?? 0) > 0 || ($assessment->video_off_count ?? 0) > 0)
        <div class="p-6 border-t">
            <h3 class="text-lg font-semibold text-red-800 mb-3">Penalty Deductions</h3>
            @if(($assessment->punctuality_late_count ?? 0) > 0)
            <div class="flex justify-between py-2 border-b border-gray-200">
                <span class="text-gray-700">Punctuality — {{ $assessment->punctuality_late_count }} late incident(s)</span>
                <span class="font-medium text-red-600">₦{{ number_format($assessment->punctuality_penalty ?? 0) }}</span>
            </div>
            @endif
            @if(($assessment->video_off_count ?? 0) > 0)
            <div class="flex justify-between py-2 border-b border-gray-200">
                <span class="text-gray-700">Video-off — {{ $assessment->video_off_count }} incident(s)</span>
                <span class="font-medium text-red-600">₦{{ number_format($assessment->video_penalty ?? 0) }}</span>
            </div>
            @endif
            <div class="flex justify-between py-2 font-bold">
                <span class="text-gray-900">Total Deductions</span>
                <span class="text-red-600">₦{{ number_format($assessment->total_penalty_deductions ?? 0) }}</span>
            </div>
        </div>
        @endif

        {{-- Student Chips --}}
        @if($assessment->student_chips && count($assessment->student_chips) > 0)
        <div class="p-6 border-t">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Students Assigned</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($assessment->student_chips as $chip)
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-100 border border-sky-200 rounded-full text-sm">
                        <span class="font-medium text-gray-800">{{ $chip['name'] ?? '' }}</span>
                        <span class="text-gray-500">({{ $chip['classes_attended'] ?? 0 }}/{{ $chip['total_classes'] ?? 0 }})</span>
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Strengths & Weaknesses Summary --}}
        <div class="p-6 bg-amber-50 border-t border-amber-200">
            <div class="space-y-3">
                <div class="flex items-start gap-2">
                    <span class="text-lg">🟢</span>
                    <div>
                        <strong>Strength Area Summary:</strong>
                        <span class="text-gray-700">{{ $strengthSummary }}</span>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-lg">🔴</span>
                    <div>
                        <strong>Weakness Area Summary:</strong>
                        <span class="text-gray-700">{{ $weaknessSummary }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Insights Section --}}
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🧠 Tutor Performance Insights</h3>

            <div class="grid md:grid-cols-2 gap-4">
                {{-- Strengths Box --}}
                <div class="strength-box rounded-lg p-4">
                    <h4 class="font-semibold mb-3">✅ Strengths</h4>
                    @if(count($strengths) > 0)
                        <ul class="space-y-2">
                            @foreach($strengths as $strength)
                                <li class="flex justify-between border-b border-green-200 pb-2">
                                    <span>{{ $strength['name'] }}</span>
                                    <span class="font-semibold">{{ number_format($strength['percentage'], 1) }}%</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm italic">All criteria currently below strength threshold (75%)</p>
                    @endif
                </div>

                {{-- Weaknesses Box --}}
                <div class="weakness-box rounded-lg p-4">
                    <h4 class="font-semibold mb-3">⚠️ Weaknesses</h4>
                    @if(count($weaknesses) > 0)
                        <ul class="space-y-2">
                            @foreach($weaknesses as $weakness)
                                <li class="flex justify-between border-b border-red-200 pb-2">
                                    <span>{{ $weakness['name'] }}</span>
                                    <span class="font-semibold">{{ number_format($weakness['percentage'], 1) }}%</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm italic">All criteria exceed improvement threshold (75%+) - excellent work!</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Director Comment --}}
        @if($assessment->directorAction && $assessment->directorAction->director_comment)
            <div class="p-6 bg-amber-50 border-t border-amber-300">
                <h4 class="font-semibold text-amber-900 mb-2">🗒️ Director Comment</h4>
                <p class="text-amber-800 italic">"{{ $assessment->directorAction->director_comment }}"</p>
            </div>
        @else
            <div class="p-6 bg-gray-50 border-t">
                <h4 class="font-semibold text-gray-600 mb-2">🗒️ Director Comment</h4>
                <p class="text-gray-500 italic">[No comment provided]</p>
            </div>
        @endif

        {{-- Footer --}}
        <div class="p-6 bg-gray-100 border-t text-center text-sm text-gray-600">
            <p><strong>Generated:</strong> {{ now()->format('l, F j, Y \a\t g:i A') }}</p>
            <p class="mt-1"><strong>Kidz Tech Coding Club &copy; {{ date('Y') }}</strong></p>
            <p>Tutor Quality Assurance System</p>
        </div>
    </div>
</body>
</html>
