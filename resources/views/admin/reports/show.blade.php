<x-app-layout>
    <x-slot name="header">{{ __('View Report') }}</x-slot>
    <x-slot name="title">{{ __('Admin - View Report') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Page Header --}}
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Report Review - {{ $report->month }} {{ $report->year }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ $report->student->fullName() }} | Tutor: {{ $report->tutor->fullName() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                        Director Approved
                    </span>
                    <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Back to List
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Report Metadata --}}
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Student</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $report->student->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Age {{ $report->student->age ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tutor</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $report->tutor->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $report->tutor->email ?? '' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Month</label>
                                <p class="text-gray-900 dark:text-white">{{ $report->month }} {{ $report->year }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Approved</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $report->approved_by_director_at ? $report->approved_by_director_at->format('M d, Y g:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Courses Section --}}
                    @if($report->courses && count($report->courses) > 0)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#423A8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Courses ({{ count($report->courses) }})
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($report->courses as $course)
                                <span class="px-3 py-1.5 bg-[#423A8E]/10 text-[#423A8E] dark:text-[#00CCCD] rounded-full text-sm font-medium">{{ $course }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Skills Mastered Section --}}
                    @if($report->skills_mastered && count($report->skills_mastered) > 0)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            Skills Mastered ({{ count($report->skills_mastered) }})
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($report->skills_mastered as $skill)
                                <span class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-sm">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- New Skills Section --}}
                    @if($report->new_skills && count($report->new_skills) > 0)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Skills Learned
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($report->new_skills as $skill)
                                <span class="px-3 py-1.5 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 rounded-full text-sm">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Projects Section --}}
                    @if($report->projects && count($report->projects) > 0)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Projects Completed
                        </h3>
                        <div class="space-y-3">
                            @foreach($report->projects as $project)
                                @if(!empty($project['title']) || !empty($project['name']))
                                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $project['title'] ?? $project['name'] ?? 'Project' }}</span>
                                    @if(!empty($project['link']))
                                        @if(filter_var($project['link'], FILTER_VALIDATE_URL))
                                            <a href="{{ $project['link'] }}" target="_blank" class="ml-2 inline-flex items-center gap-1 text-sm text-[#423A8E] hover:underline">
                                                View
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @else
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $project['link'] }}</p>
                                        @endif
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Areas for Improvement --}}
                    @if($report->areas_for_improvement)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Areas for Improvement
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->areas_for_improvement }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Goals for Next Month --}}
                    @if($report->goals_next_month)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Goals for Next Month
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->goals_next_month }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Assignments --}}
                    @if($report->assignments)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Assignments Given
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->assignments }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Comments & Observations --}}
                    @if($report->comments_observation)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Comments & Observations
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->comments_observation }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Progress Summary (Legacy) --}}
                    @if($report->progress_summary)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Progress Summary</h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->progress_summary }}</p>
                    </div>
                    @endif

                    {{-- Previous Comments Section --}}
                    @if($report->manager_comment || $report->director_comment)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Approval Comments</h3>

                        @if($report->manager_comment)
                        <div class="mb-4 bg-[#423A8E]/10 dark:bg-[#423A8E]/20 border border-[#423A8E]/30 dark:border-[#423A8E]/40 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-[#423A8E] dark:text-[#00CCCD]">Manager Comment</span>
                                @if($report->approved_by_manager_at)
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    | {{ $report->approved_by_manager_at->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->manager_comment }}</p>
                        </div>
                        @endif

                        @if($report->director_comment)
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-purple-800 dark:text-purple-400">Director Comment</span>
                                @if($report->approved_by_director_at)
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    | {{ $report->approved_by_director_at->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->director_comment }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">

                    {{-- Report Stats --}}
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Stats</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $report->skills_mastered ? count($report->skills_mastered) : 0 }}</p>
                                <p class="text-xs text-gray-500">Skills</p>
                            </div>
                            <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $report->projects ? count(array_filter($report->projects, function ($p) { return !empty($p['title']) || !empty($p['name']); })) : 0 }}</p>
                                <p class="text-xs text-gray-500">Projects</p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $report->courses ? count($report->courses) : 0 }}</p>
                                <p class="text-xs text-gray-500">Courses</p>
                            </div>
                            <div class="text-center p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $report->new_skills ? count($report->new_skills) : 0 }}</p>
                                <p class="text-xs text-gray-500">New Skills</p>
                            </div>
                        </div>
                    </div>

                    {{-- Performance Metrics --}}
                    @if($report->attendance_score || $report->performance_rating)
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance</h3>
                        <div class="space-y-4">
                            @if($report->attendance_score)
                            <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $report->attendance_score }}%</p>
                                <p class="text-sm text-gray-500">Attendance Score</p>
                            </div>
                            @endif
                            @if($report->performance_rating)
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 capitalize">{{ $report->performance_rating }}</p>
                                <p class="text-sm text-gray-500">Performance Rating</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Export & Share Card --}}
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Export & Share</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.reports.pdf', $report) }}"
                                class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Download PDF
                            </a>

                            <a href="{{ route('admin.reports.print', $report) }}" target="_blank"
                                class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Report
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
