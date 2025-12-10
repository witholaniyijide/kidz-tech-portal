<x-tutor-layout title="Assessment Details">
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
            <a href="{{ route('tutor.performance.index') }}" class="hover:text-[#4B51FF]">Performance</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span>{{ \Carbon\Carbon::parse($assessment->assessment_month)->format('F Y') }}</span>
        </div>
        
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ \Carbon\Carbon::parse($assessment->assessment_month)->format('F Y') }} Assessment
                </h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">
                    Finalized {{ $assessment->approved_by_director_at ? $assessment->approved_by_director_at->format('M d, Y') : 'N/A' }}
                </p>
            </div>

            <!-- Navigation -->
            <div class="flex items-center gap-2">
                @if($previousAssessment)
                    <a href="{{ route('tutor.performance.show', $previousAssessment) }}" class="p-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors" title="Previous">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif
                @if($nextAssessment)
                    <a href="{{ route('tutor.performance.show', $nextAssessment) }}" class="p-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors" title="Next">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Performance Score Card -->
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#4B51FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Overall Performance
                </h2>

                <div class="flex items-center justify-center mb-6">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="10" fill="none" class="text-slate-200 dark:text-slate-700"/>
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="10" fill="none" 
                                stroke-dasharray="{{ 352 }}" 
                                stroke-dashoffset="{{ 352 - (352 * ($assessment->performance_score ?? 0) / 100) }}"
                                stroke-linecap="round"
                                class="@if(($assessment->performance_score ?? 0) >= 80) text-emerald-500 @elseif(($assessment->performance_score ?? 0) >= 60) text-amber-500 @else text-rose-500 @endif transition-all duration-1000"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-bold text-slate-900 dark:text-white">{{ $assessment->performance_score ?? '-' }}</span>
                            <span class="text-sm text-slate-500">out of 100</span>
                        </div>
                    </div>
                </div>

                <!-- Rating Bars -->
                <div class="space-y-4">
                    <!-- Professionalism -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Professionalism</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $assessment->professionalism_rating ?? '-' }}/10</span>
                        </div>
                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full transition-all duration-500" style="width: {{ ($assessment->professionalism_rating ?? 0) * 10 }}%"></div>
                        </div>
                    </div>

                    <!-- Communication -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Communication</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $assessment->communication_rating ?? '-' }}/10</span>
                        </div>
                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-cyan-500 rounded-full transition-all duration-500" style="width: {{ ($assessment->communication_rating ?? 0) * 10 }}%"></div>
                        </div>
                    </div>

                    <!-- Punctuality -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Punctuality</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $assessment->punctuality_rating ?? '-' }}/10</span>
                        </div>
                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ ($assessment->punctuality_rating ?? 0) * 10 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strengths -->
            @if($assessment->strengths)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Strengths
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ $assessment->strengths }}</p>
            </div>
            @endif

            <!-- Areas for Improvement (Weaknesses) -->
            @if($assessment->weaknesses)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Areas for Improvement
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ $assessment->weaknesses }}</p>
            </div>
            @endif

            <!-- Recommendations -->
            @if($assessment->recommendations)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Recommendations
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ $assessment->recommendations }}</p>
            </div>
            @endif

            <!-- Director's Comment ONLY (Manager's comment is intentionally hidden) -->
            @if($assessment->director_comment)
            <div class="glass-card rounded-2xl p-6 border-l-4 border-[#4B51FF]">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#4B51FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Director's Feedback
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ $assessment->director_comment }}</p>
                @if($assessment->director)
                    <p class="mt-4 text-sm text-slate-500">— {{ $assessment->director->name }}, Director</p>
                @endif
            </div>
            @endif

            {{-- 
                NOTE: Manager's comment ($assessment->manager_comment) is intentionally NOT displayed here.
                Per business requirement, tutors should only see the director's feedback, not the manager's internal notes.
            --}}
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Assessment Info -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Assessment Info</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400">Period</label>
                        <p class="font-medium text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($assessment->assessment_month)->format('F Y') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400">Status</label>
                        <p class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-sm font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Finalized
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400">Approved On</label>
                        <p class="font-medium text-slate-900 dark:text-white">{{ $assessment->approved_by_director_at ? $assessment->approved_by_director_at->format('M d, Y g:i A') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Score Summary -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Score Summary</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Overall Score</span>
                        <span class="font-bold text-lg @if(($assessment->performance_score ?? 0) >= 80) text-emerald-600 @elseif(($assessment->performance_score ?? 0) >= 60) text-amber-600 @else text-rose-600 @endif">{{ $assessment->performance_score ?? '-' }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Professionalism</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $assessment->professionalism_rating ?? '-' }}/10</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Communication</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $assessment->communication_rating ?? '-' }}/10</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <span class="text-sm text-slate-600 dark:text-slate-400">Punctuality</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $assessment->punctuality_rating ?? '-' }}/10</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('tutor.performance.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors font-medium">
                        ← Back to All Assessments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-tutor-layout>
