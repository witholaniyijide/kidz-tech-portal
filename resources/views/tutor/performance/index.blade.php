<x-tutor-layout title="My Performance">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">My Performance</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">View your performance assessments and feedback</p>
        </div>
    </div>

    <!-- Stats Cards -->
    @if($stats['total_assessments'] > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total Assessments -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_assessments'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Reviews</p>
                </div>
            </div>
        </div>

        <!-- Latest Score -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-1">
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['latest_score'] ?? '-' }}</p>
                        @if($stats['score_trend'] === 'up')
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        @elseif($stats['score_trend'] === 'down')
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Latest</p>
                </div>
            </div>
        </div>

        <!-- Average Score -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['average_score'] ?? '-' }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Avg Score</p>
                </div>
            </div>
        </div>

        <!-- Professionalism -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['average_professionalism'] ?? '-' }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Professional</p>
                </div>
            </div>
        </div>

        <!-- Communication -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ $stats['average_communication'] ?? '-' }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Comms</p>
                </div>
            </div>
        </div>

        <!-- Punctuality -->
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['average_punctuality'] ?? '-' }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Punctual</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    @if($years->isNotEmpty())
    <div class="glass-card rounded-2xl p-4">
        <form method="GET" action="{{ route('tutor.performance.index') }}" class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <label for="year" class="text-sm font-medium text-slate-700 dark:text-slate-300">Year:</label>
                <select id="year" name="year" onchange="this.form.submit()" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            @if(request('year'))
                <a href="{{ route('tutor.performance.index') }}" class="text-sm text-slate-500 hover:text-[#4B51FF]">Clear filter</a>
            @endif
        </form>
    </div>
    @endif

    <!-- Assessments List -->
    @if($assessments->isEmpty())
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">No Assessments Yet</h3>
            <p class="text-slate-500 dark:text-slate-400">Your performance assessments will appear here once they are finalized by the director.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($assessments as $assessment)
                <a href="{{ route('tutor.performance.show', $assessment) }}" class="glass-card rounded-xl p-5 hover:shadow-lg transition-all group">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#1D2A6D] to-[#4B51FF] rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ \Carbon\Carbon::parse($assessment->assessment_month)->format('M') }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($assessment->assessment_month)->format('F Y') }}</p>
                                <p class="text-xs text-slate-500">Performance Review</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-[#4B51FF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    <!-- Score -->
                    <div class="flex items-center justify-center mb-4">
                        <div class="relative w-20 h-20">
                            <svg class="w-20 h-20 transform -rotate-90">
                                <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="6" fill="none" class="text-slate-200 dark:text-slate-700"/>
                                <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="6" fill="none" 
                                    stroke-dasharray="{{ 226 }}" 
                                    stroke-dashoffset="{{ 226 - (226 * ($assessment->performance_score ?? 0) / 100) }}"
                                    class="@if(($assessment->performance_score ?? 0) >= 80) text-emerald-500 @elseif(($assessment->performance_score ?? 0) >= 60) text-amber-500 @else text-rose-500 @endif"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xl font-bold text-slate-900 dark:text-white">{{ $assessment->performance_score ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ratings -->
                    <div class="grid grid-cols-3 gap-2 text-center text-xs">
                        <div class="p-2 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $assessment->professionalism_rating ?? '-' }}</p>
                            <p class="text-slate-500">Prof.</p>
                        </div>
                        <div class="p-2 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $assessment->communication_rating ?? '-' }}</p>
                            <p class="text-slate-500">Comm.</p>
                        </div>
                        <div class="p-2 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $assessment->punctuality_rating ?? '-' }}</p>
                            <p class="text-slate-500">Punct.</p>
                        </div>
                    </div>

                    <!-- Approved Date -->
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 text-center">
                            Finalized {{ $assessment->approved_by_director_at ? $assessment->approved_by_director_at->diffForHumans() : 'N/A' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $assessments->links() }}
        </div>
    @endif
</div>
</x-tutor-layout>
