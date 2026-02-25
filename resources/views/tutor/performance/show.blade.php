<x-tutor-layout title="Performance Report">
<div class="space-y-6">
    <!-- Navigation -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('tutor.performance.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                &larr; Back to List
            </a>
            @if($previousAssessment)
                <a href="{{ route('tutor.performance.show', $previousAssessment) }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    &larr; Previous
                </a>
            @endif
            @if($nextAssessment)
                <a href="{{ route('tutor.performance.show', $nextAssessment) }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Next &rarr;
                </a>
            @endif
        </div>
        <a href="{{ route('tutor.performance.report-card', $assessment) }}" class="px-6 py-2 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-medium hover:shadow-lg transition-all flex items-center gap-2" target="_blank">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Report Card
        </a>
    </div>

    <!-- Performance Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-sky-500 to-sky-600 text-white p-6 text-center">
            <h1 class="text-2xl font-bold">KIDZ TECH CODING CLUB</h1>
            <h2 class="text-lg font-semibold mt-1">Tutor Performance Report</h2>
        </div>

        {{-- Tutor Info --}}
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">Tutor Name</span>
                    <p class="font-semibold text-slate-800 dark:text-white">{{ $assessment->tutor->first_name }} {{ $assessment->tutor->last_name }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">Assessment Period</span>
                    <p class="font-semibold text-slate-800 dark:text-white">{{ $assessment->assessment_period }}</p>
                    @if($assessment->assessment_date)
                        <p class="text-xs text-slate-500 dark:text-slate-400">Date: {{ $assessment->assessment_date->format('d M Y') }}</p>
                    @endif
                </div>
                <div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">Total Penalties</span>
                    <p class="font-semibold {{ ($assessment->directorAction?->penalty_amount ?? 0) > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ ($assessment->directorAction?->penalty_amount ?? 0) > 0 ? '₦' . number_format($assessment->directorAction->penalty_amount, 2) : 'None' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Performance Breakdown --}}
        @if($assessment->ratings && $assessment->ratings->count() > 0)
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                🧾 PERFORMANCE BREAKDOWN
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-800 text-white">
                            <th class="px-4 py-3 text-left rounded-tl-lg">Criteria</th>
                            <th class="px-4 py-3 text-left">Performance</th>
                            <th class="px-4 py-3 text-left">Visual Bar</th>
                            <th class="px-4 py-3 text-left rounded-tr-lg">Rating Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessment->ratings as $rating)
                            @php
                                $percentage = $rating->percentage;
                                $ratingInfo = getEmojiAndLabel($percentage);
                                $visualBar = createVisualBar($percentage);
                            @endphp
                            <tr class="{{ $loop->even ? 'bg-slate-50 dark:bg-slate-800/50' : 'bg-white dark:bg-slate-900/30' }}">
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $rating->criteria->name }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ number_format($percentage, 1) }}%</td>
                                <td class="px-4 py-3 font-mono text-lg tracking-wider text-slate-600 dark:text-slate-400">{{ $visualBar }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 text-sm">
                                        {{ $ratingInfo['emoji'] }} {{ $ratingInfo['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Overall Rating Box --}}
        @php
            $overallClass = match(true) {
                $overallScore >= 90 => 'bg-green-50 dark:bg-green-900/20 border-2 border-green-500',
                $overallScore >= 70 => 'bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-500',
                $overallScore >= 50 => 'bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-500',
                default => 'bg-red-50 dark:bg-red-900/20 border-2 border-red-500'
            };
        @endphp
        <div class="p-6">
            <div class="{{ $overallClass }} rounded-lg p-6 text-center">
                <span class="text-lg text-slate-700 dark:text-slate-300">Overall Rating:</span>
                <span class="text-3xl font-bold ml-2 text-slate-900 dark:text-white">{{ number_format($overallScore, 1) }}%</span>
                <span class="text-lg ml-2 text-slate-700 dark:text-slate-300">({{ $overallInfo['label'] }})</span>
            </div>
        </div>

        {{-- Penalty Deductions --}}
        @if(($assessment->punctuality_late_count ?? 0) > 0 || ($assessment->video_off_count ?? 0) > 0)
        <div class="p-6 border-t border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-3">Penalty Deductions</h3>
            @if(($assessment->punctuality_late_count ?? 0) > 0)
            <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                <span class="text-slate-700 dark:text-slate-300">Punctuality — {{ $assessment->punctuality_late_count }} late incident(s)</span>
                <span class="font-medium text-red-600">₦{{ number_format($assessment->punctuality_penalty ?? 0) }}</span>
            </div>
            @endif
            @if(($assessment->video_off_count ?? 0) > 0)
            <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                <span class="text-slate-700 dark:text-slate-300">Video-off — {{ $assessment->video_off_count }} incident(s)</span>
                <span class="font-medium text-red-600">₦{{ number_format($assessment->video_penalty ?? 0) }}</span>
            </div>
            @endif
            <div class="flex justify-between py-2 font-bold">
                <span class="text-slate-900 dark:text-white">Total Deductions</span>
                <span class="text-red-600">₦{{ number_format($assessment->total_penalty_deductions ?? 0) }}</span>
            </div>
        </div>
        @endif

        {{-- Student Chips --}}
        @if($assessment->student_chips && count($assessment->student_chips) > 0)
        <div class="p-6 border-t border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-3">Students Assigned</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($assessment->student_chips as $chip)
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-100 dark:bg-sky-900/30 border border-sky-200 dark:border-sky-700 rounded-full text-sm">
                        <span class="font-medium text-slate-800 dark:text-white">{{ $chip['name'] ?? '' }}</span>
                        <span class="text-slate-500 dark:text-slate-400">({{ $chip['classes_attended'] ?? 0 }}/{{ $chip['total_classes'] ?? 0 }})</span>
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Strengths & Weaknesses Summary --}}
        <div class="p-6 bg-amber-50 dark:bg-amber-900/20 border-t border-amber-200 dark:border-amber-800">
            <div class="space-y-3">
                <div class="flex items-start gap-2">
                    <span class="text-lg">🟢</span>
                    <div>
                        <strong class="text-slate-800 dark:text-white">Strength Area Summary:</strong>
                        <span class="text-slate-700 dark:text-slate-300">{{ $strengthSummary }}</span>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-lg">🔴</span>
                    <div>
                        <strong class="text-slate-800 dark:text-white">Weakness Area Summary:</strong>
                        <span class="text-slate-700 dark:text-slate-300">{{ $weaknessSummary }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Insights Section --}}
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">🧠 Tutor Performance Insights</h3>

            <div class="grid md:grid-cols-2 gap-4">
                {{-- Strengths Box --}}
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-green-800 dark:text-green-300">
                    <h4 class="font-semibold mb-3">✅ Strengths</h4>
                    @if(count($strengths) > 0)
                        <ul class="space-y-2">
                            @foreach($strengths as $strength)
                                <li class="flex justify-between border-b border-green-200 dark:border-green-700 pb-2">
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
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 text-red-800 dark:text-red-300">
                    <h4 class="font-semibold mb-3">⚠️ Weaknesses</h4>
                    @if(count($weaknesses) > 0)
                        <ul class="space-y-2">
                            @foreach($weaknesses as $weakness)
                                <li class="flex justify-between border-b border-red-200 dark:border-red-700 pb-2">
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
            <div class="p-6 bg-amber-50 dark:bg-amber-900/20 border-t border-amber-300 dark:border-amber-700">
                <h4 class="font-semibold text-amber-900 dark:text-amber-300 mb-2">🗒️ Director Comment</h4>
                <p class="text-amber-800 dark:text-amber-200 italic">"{{ $assessment->directorAction->director_comment }}"</p>
            </div>
        @elseif($assessment->director_comment)
            <div class="p-6 bg-amber-50 dark:bg-amber-900/20 border-t border-amber-300 dark:border-amber-700">
                <h4 class="font-semibold text-amber-900 dark:text-amber-300 mb-2">🗒️ Director Comment</h4>
                <p class="text-amber-800 dark:text-amber-200 italic">"{{ $assessment->director_comment }}"</p>
            </div>
        @else
            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
                <h4 class="font-semibold text-slate-600 dark:text-slate-400 mb-2">🗒️ Director Comment</h4>
                <p class="text-slate-500 dark:text-slate-500 italic">[No comment provided]</p>
            </div>
        @endif

        {{-- Footer --}}
        <div class="p-6 bg-slate-100 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 text-center text-sm text-slate-600 dark:text-slate-400">
            <p><strong>Approved:</strong> {{ $assessment->approved_by_director_at ? $assessment->approved_by_director_at->format('l, F j, Y \a\t g:i A') : 'N/A' }}</p>
            <p class="mt-1"><strong>Kidz Tech Coding Club &copy; {{ date('Y') }}</strong></p>
            <p>Tutor Quality Assurance System</p>
        </div>
    </div>
</div>
</x-tutor-layout>
