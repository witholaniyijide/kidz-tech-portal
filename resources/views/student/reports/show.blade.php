@extends('layouts.student')

@section('content')
<div class="space-y-6 max-w-6xl">
    <!-- Back Button & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('student.reports.index') }}" class="p-2 rounded-xl bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:bg-white/70 dark:hover:bg-gray-800/70 transition-all border border-gray-200 dark:border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $report->month }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Monthly Progress Report</p>
            </div>
        </div>

        <!-- PDF & Print Actions -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('student.reports.pdf', $report) }}" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="hidden sm:inline">PDF</span>
            </a>
            <a href="{{ route('student.reports.print', $report) }}" target="_blank" class="px-4 py-2 bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-white/70 dark:hover:bg-gray-800/70 transition-all border border-gray-200 dark:border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Student Overview Card -->
    <x-ui.glass-card>
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Student Info -->
            <div class="flex items-center space-x-4">
                @if($report->student && $report->student->profile_photo)
                    <img src="{{ $report->student->profile_photo }}" alt="{{ $report->student->full_name }}" class="w-16 h-16 rounded-full object-cover border-4 border-sky-500">
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center text-white font-bold text-xl">
                        @if($report->student)
                            {{ strtoupper(substr($report->student->first_name, 0, 1)) }}{{ strtoupper(substr($report->student->last_name, 0, 1)) }}
                        @else
                            S
                        @endif
                    </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Student</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $report->student->full_name ?? 'Student' }}</p>
                    @if($report->student && $report->student->roadmap_stage)
                        <p class="text-xs text-sky-600 dark:text-sky-400 mt-1">{{ $report->student->roadmap_stage }}</p>
                    @endif
                </div>
            </div>

            <!-- Tutor Info -->
            @if($report->tutor)
                <div class="flex items-center space-x-4">
                    @if($report->tutor->profile_photo_path)
                        <img src="{{ asset('storage/' . $report->tutor->profile_photo_path) }}" alt="{{ $report->tutor->full_name }}" class="w-16 h-16 rounded-full object-cover border-4 border-purple-500">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($report->tutor->first_name, 0, 1)) }}{{ strtoupper(substr($report->tutor->last_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tutor</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $report->tutor->full_name }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">{{ $report->tutor->email ?? '' }}</p>
                    </div>
                </div>
            @endif

            <!-- Report Period -->
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Report Period</p>
                    <p class="text-base font-bold text-gray-900 dark:text-white">
                        @if($report->period_from && $report->period_to)
                            {{ \Carbon\Carbon::parse($report->period_from)->format('M d') }} - {{ \Carbon\Carbon::parse($report->period_to)->format('M d, Y') }}
                        @else
                            {{ $report->month }}
                        @endif
                    </p>
                    <x-ui.status-badge :status="$report->status" class="mt-1" />
                </div>
            </div>
        </div>
    </x-ui.glass-card>

    <!-- Performance Metrics Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Attendance Score -->
        @if($report->attendance_score !== null)
            <x-ui.glass-card padding="p-6">
                <div class="text-center">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-3">Attendance</p>
                    <div class="relative inline-flex items-center justify-center mb-3">
                        <x-ui.progress-circle :percentage="$report->attendance_score" :size="100" :strokeWidth="6" />
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $report->attendance_score >= 90 ? 'Excellent' : ($report->attendance_score >= 75 ? 'Good' : 'Needs Improvement') }}
                    </p>
                </div>
            </x-ui.glass-card>
        @endif

        <!-- Performance Rating -->
        @if($report->performance_rating)
            <x-ui.glass-card padding="p-6">
                <div class="text-center">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-3">Performance Rating</p>
                    <div class="flex justify-center items-center space-x-1 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-8 h-8 {{ $i <= $report->performance_rating ? 'text-amber-500' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $report->performance_rating }} out of 5 stars</p>
                </div>
            </x-ui.glass-card>
        @endif

        <!-- Overall Rating Badge -->
        @if($report->rating)
            <x-ui.glass-card padding="p-6">
                <div class="text-center">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-3">Overall Rating</p>
                    @php
                        $ratingColors = [
                            'excellent' => 'from-green-500 to-emerald-500',
                            'good' => 'from-blue-500 to-sky-500',
                            'average' => 'from-amber-500 to-orange-500',
                            'poor' => 'from-red-500 to-rose-500',
                        ];
                        $colorClass = $ratingColors[strtolower($report->rating)] ?? 'from-gray-500 to-gray-600';
                    @endphp
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br {{ $colorClass }} text-white font-bold text-2xl mb-3 uppercase">
                        {{ substr($report->rating, 0, 1) }}
                    </div>
                    <p class="text-base font-semibold text-gray-900 dark:text-white capitalize">{{ $report->rating }}</p>
                </div>
            </x-ui.glass-card>
        @endif
    </div>

    <!-- Performance Radar Chart -->
    @if($report->attendance_score || $report->performance_rating)
        <x-ui.glass-card>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Performance Overview</h3>
            <div class="max-w-md mx-auto">
                <canvas id="performanceRadar" height="300"></canvas>
            </div>
        </x-ui.glass-card>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('performanceRadar');
            if (ctx) {
                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['Attendance', 'Performance', 'Progress', 'Engagement', 'Technical Skills'],
                        datasets: [{
                            label: 'Student Performance',
                            data: [
                                {{ $report->attendance_score ?? 0 }},
                                {{ ($report->performance_rating ?? 0) * 20 }},
                                {{ $report->student->progressPercentage() ?? 0 }},
                                {{ rand(70, 95) }}, // Placeholder
                                {{ rand(65, 90) }}  // Placeholder
                            ],
                            backgroundColor: 'rgba(14, 165, 233, 0.2)',
                            borderColor: 'rgba(14, 165, 233, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(14, 165, 233, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(14, 165, 233, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    stepSize: 20,
                                    color: getComputedStyle(document.documentElement).getPropertyValue('color-scheme') === 'dark' ? '#9ca3af' : '#6b7280'
                                },
                                grid: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('color-scheme') === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                                },
                                pointLabels: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('color-scheme') === 'dark' ? '#d1d5db' : '#374151',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        </script>
        @endpush
    @endif

    <!-- Progress Breakdown -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Summary -->
        @if($report->summary || $report->progress_summary)
            <x-ui.glass-card>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Progress Summary
                </h3>
                <div class="prose prose-sm dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $report->summary ?? $report->progress_summary }}</p>
                </div>
            </x-ui.glass-card>
        @endif

        <!-- Strengths -->
        @if($report->strengths)
            <x-ui.glass-card>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Strengths
                </h3>
                <div class="prose prose-sm dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $report->strengths }}</p>
                </div>
            </x-ui.glass-card>
        @endif

        <!-- Weaknesses -->
        @if($report->weaknesses)
            <x-ui.glass-card>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Areas for Improvement
                </h3>
                <div class="prose prose-sm dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $report->weaknesses }}</p>
                </div>
            </x-ui.glass-card>
        @endif

        <!-- Next Steps -->
        @if($report->next_steps)
            <x-ui.glass-card>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Next Steps
                </h3>
                <div class="prose prose-sm dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $report->next_steps }}</p>
                </div>
            </x-ui.glass-card>
        @endif
    </div>

    <!-- Full Content -->
    @if($report->content)
        <x-ui.glass-card>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Detailed Report
            </h3>
            <div class="prose prose-sm dark:prose-invert max-w-none">
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $report->content }}</div>
            </div>
        </x-ui.glass-card>
    @endif

    <!-- Approval Trail -->
    <x-ui.glass-card>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Approval Trail</h3>

        <div class="relative">
            <!-- Vertical Line -->
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-purple-500 via-indigo-500 to-green-500"></div>

            <div class="space-y-6">
                <!-- Tutor Submission -->
                @if($report->submitted_at)
                    <div class="relative flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-white z-10 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800">
                            <p class="text-sm font-semibold text-purple-900 dark:text-purple-300">Tutor Submitted</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $report->submitted_at->format('M d, Y \a\t h:i A') }}</p>
                            @if($report->tutor)
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">By: {{ $report->tutor->full_name }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Manager Approval -->
                @if($report->approved_by_manager_at)
                    <div class="relative flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white z-10 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800">
                            <p class="text-sm font-semibold text-indigo-900 dark:text-indigo-300">Manager Approved</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $report->approved_by_manager_at->format('M d, Y \a\t h:i A') }}</p>
                            @if($report->manager_comment)
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-3 italic">"{{ $report->manager_comment }}"</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Director Approval -->
                @if($report->approved_by_director_at)
                    <div class="relative flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white z-10 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                            <p class="text-sm font-semibold text-green-900 dark:text-green-300">Director Final Approval</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $report->approved_by_director_at->format('M d, Y \a\t h:i A') }}</p>
                            @if($report->director_comment)
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-3 italic">"{{ $report->director_comment }}"</p>
                            @endif
                            @if($report->director_signature)
                                <div class="mt-3 pt-3 border-t border-green-200 dark:border-green-800">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Digitally Signed By:</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $report->director_signature }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-ui.glass-card>
</div>
@endsection
