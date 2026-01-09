<x-tutor-layout title="My Performance">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">My Performance Reports</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">View your assessment results after Director approval</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Assessments -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white">
            <div class="text-sm opacity-90">Total Assessments</div>
            <div class="text-3xl font-bold">{{ $stats['total_assessments'] }}</div>
        </div>

        <!-- Average Score -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white">
            <div class="text-sm opacity-90">Average Score</div>
            <div class="text-3xl font-bold">{{ number_format($stats['average_score'], 1) }}%</div>
        </div>

        <!-- This Month -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white">
            <div class="text-sm opacity-90">This Month</div>
            <div class="text-3xl font-bold">{{ $stats['this_month_count'] }}</div>
        </div>

        <!-- Total Penalties -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 text-white">
            <div class="text-sm opacity-90">Total Penalties</div>
            <div class="text-3xl font-bold">₦{{ number_format($stats['total_penalties']) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4">
        <form method="GET" action="{{ route('tutor.performance.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="year" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Year</label>
                <select id="year" name="year" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="month" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Month</label>
                <select id="month" name="month" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
                    <option value="">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="student_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Student</label>
                <select id="student_id" name="student_id" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white rounded-xl font-medium hover:shadow-lg transition-all">
                    Filter
                </button>
                @if(request()->hasAny(['year', 'month', 'student_id']))
                    <a href="{{ route('tutor.performance.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Assessments List -->
    @if($assessments->isEmpty())
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">No Approved Assessments Yet</h3>
            <p class="text-slate-500 dark:text-slate-400">Your performance reports will appear here after Director approval.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($assessments as $assessment)
                @php
                    $overallScore = $assessment->calculateOverallScore();
                    $ratingInfo = getEmojiAndLabel($overallScore);
                @endphp
                <div class="glass-card rounded-xl p-5 hover:shadow-lg transition-shadow cursor-pointer border-l-4 {{ $overallScore >= 70 ? 'border-l-green-500' : ($overallScore >= 50 ? 'border-l-yellow-500' : 'border-l-red-500') }}"
                     onclick="window.location='{{ route('tutor.performance.show', $assessment->id) }}'">
                    <div class="flex flex-wrap justify-between items-start gap-4">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white text-lg">
                                {{ $assessment->student ? $assessment->student->first_name . ' ' . $assessment->student->last_name : 'N/A' }}
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">
                                Week {{ $assessment->week ?? 'N/A' }} · {{ $assessment->class_date ? $assessment->class_date->format('M d, Y') : $assessment->assessment_month }} · Session {{ $assessment->session ?? 1 }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold {{ $overallScore >= 70 ? 'text-green-600 dark:text-green-400' : ($overallScore >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                {{ number_format($overallScore, 1) }}%
                            </div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $ratingInfo['emoji'] }} {{ $ratingInfo['label'] }}
                            </div>
                        </div>
                    </div>

                    {{-- Criteria Mini-Display --}}
                    @if($assessment->ratings && $assessment->ratings->count() > 0)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($assessment->ratings as $rating)
                            @php
                                $badgeClass = getRatingBadgeClass($rating->rating);
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full {{ $badgeClass }}">
                                {{ $rating->criteria->name }}: {{ $rating->rating }}
                            </span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Penalty Display --}}
                    @if($assessment->directorAction && $assessment->directorAction->penalty_amount > 0)
                        <div class="mt-3 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded text-sm">
                            <span class="text-red-700 dark:text-red-300 font-medium">
                                Penalty Applied: ₦{{ number_format($assessment->directorAction->penalty_amount, 2) }}
                            </span>
                        </div>
                    @endif

                    {{-- Director Comment Preview --}}
                    @if($assessment->directorAction && $assessment->directorAction->director_comment)
                        <div class="mt-3 p-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded text-sm">
                            <span class="text-amber-700 dark:text-amber-300">
                                Director: "{{ Str::limit($assessment->directorAction->director_comment, 100) }}"
                            </span>
                        </div>
                    @endif

                    {{-- View Full Report Button --}}
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('tutor.performance.show', $assessment->id) }}"
                           class="px-4 py-2 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white text-sm font-medium rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center gap-2"
                           onclick="event.stopPropagation();">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            View Full Report
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $assessments->links() }}
        </div>
    @endif
</div>

<style>
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

    .dark .rating-excellent, .dark .rating-on-time {
        background-color: rgba(6, 95, 70, 0.3);
        color: #6ee7b7;
    }
    .dark .rating-good {
        background-color: rgba(30, 64, 175, 0.3);
        color: #93c5fd;
    }
    .dark .rating-acceptable {
        background-color: rgba(146, 64, 14, 0.3);
        color: #fcd34d;
    }
    .dark .rating-needs-improvement, .dark .rating-late {
        background-color: rgba(153, 27, 27, 0.3);
        color: #fca5a5;
    }
</style>
</x-tutor-layout>
