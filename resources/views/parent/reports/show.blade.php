<x-parent-layout>
    <x-slot name="title">{{ $student->fullName() }} - {{ $report->month }}</x-slot>
    <x-slot name="subtitle">Tutor: {{ $report->tutor ? $report->tutor->fullName() : 'Unknown' }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Report Metadata --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4">Report Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Student</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $student->fullName() }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Age {{ $student->age ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tutor</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $report->tutor ? $report->tutor->fullName() : 'Unknown' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Assigned Tutor</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Report Month</label>
                        <p class="text-gray-900 dark:text-white">{{ $report->month }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Director Approved</label>
                        <p class="text-gray-900 dark:text-white">
                            {{ $report->approved_by_director_at ? $report->approved_by_director_at->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                    @if($report->attendance_score)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Attendance Score</label>
                        <p class="text-2xl font-bold text-sky-600 dark:text-sky-400">{{ $report->attendance_score }}%</p>
                    </div>
                    @endif
                    @if($report->performance_rating)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Performance Rating</label>
                        <p class="text-2xl font-bold text-cyan-600 dark:text-cyan-400 capitalize">{{ $report->performance_rating }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Courses Section --}}
            @if($report->courses && count($report->courses) > 0)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Courses ({{ count($report->courses) }})
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($report->courses as $course)
                        <span class="px-3 py-1.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded-full text-sm font-medium">{{ $course }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Skills Mastered Section --}}
            @if($report->skills_mastered && count($report->skills_mastered) > 0)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Projects Completed ({{ count(array_filter($report->projects, fn($p) => !empty($p['title']))) }})
                </h3>
                <div class="space-y-3">
                    @foreach($report->projects as $project)
                        @if(!empty($project['title']))
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $project['title'] }}</span>
                            @if(!empty($project['link']))
                                @if(filter_var($project['link'], FILTER_VALIDATE_URL))
                                    <a href="{{ $project['link'] }}" target="_blank" class="ml-2 inline-flex items-center gap-1 text-sm text-sky-600 dark:text-sky-400 hover:underline">
                                        View Project
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
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Comments & Observations
                </h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->comments_observation }}</p>
                </div>
            </div>
            @endif

            {{-- Legacy: Progress Summary --}}
            @if($report->progress_summary)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Progress Summary
                </h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->progress_summary }}</p>
                </div>
            </div>
            @endif

            {{-- Legacy: Strengths --}}
            @if($report->strengths)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Strengths
                </h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->strengths }}</p>
                </div>
            </div>
            @endif

            {{-- Legacy: Areas for Improvement (weaknesses) --}}
            @if($report->weaknesses)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Areas for Growth
                </h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->weaknesses }}</p>
                </div>
            </div>
            @endif

            {{-- Legacy: Next Steps --}}
            @if($report->next_steps)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    Next Steps
                </h3>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->next_steps }}</p>
                </div>
            </div>
            @endif

            {{-- Director Comment (visible to parents) --}}
            @if($report->director_comment)
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Director's Note
                </h3>
                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800/50 rounded-xl p-4">
                    <p class="text-gray-700 dark:text-gray-300">{{ $report->director_comment }}</p>
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar - Actions --}}
        <div class="space-y-6">

            {{-- Status Badge --}}
            <div class="glass-card rounded-2xl p-6 text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Director Approved
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    This report has been reviewed and approved by the Director
                </p>
            </div>

            {{-- Report Stats --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4">Report Stats</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $report->skills_mastered ? count($report->skills_mastered) : 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Skills</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $report->projects ? count(array_filter($report->projects, function ($p) { return !empty($p['title']); })) : 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Projects</p>
                    </div>
                    <div class="text-center p-3 bg-sky-50 dark:bg-sky-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-sky-600 dark:text-sky-400">{{ $report->courses ? count($report->courses) : 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Courses</p>
                    </div>
                    <div class="text-center p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $report->new_skills ? count($report->new_skills) : 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">New Skills</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>

                {{-- Message Director Button --}}
                <a href="{{ route('parent.messages.create', ['subject' => $student->first_name . ' Report for ' . $report->month]) }}"
                   class="w-full mb-3 px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Message Director
                </a>

                {{-- Print Button --}}
                <a href="{{ route('parent.reports.print', [$student, $report]) }}" target="_blank" class="w-full mb-3 px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-400 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Report
                </a>

                {{-- Download PDF Button --}}
                <a href="{{ route('parent.reports.pdf', [$student, $report]) }}" class="w-full block mb-3 px-6 py-3 bg-white/40 dark:bg-gray-800/40 text-gray-700 dark:text-gray-300 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-white/60 dark:hover:bg-gray-800/60 transition-colors font-medium text-center">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </a>

                {{-- Back to Reports --}}
                <a href="{{ route('parent.reports.index') }}" class="w-full block px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium text-center">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Reports
                </a>
            </div>

            {{-- Report Timeline --}}
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-heading font-semibold text-gray-900 dark:text-white mb-4">Report Timeline</h3>
                <ul class="space-y-4 text-sm">
                    @if($report->submitted_at)
                    <li class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Submitted</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $report->submitted_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </li>
                    @endif
                    @if($report->approved_by_director_at)
                    <li class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Director Approved</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $report->approved_by_director_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>

        </div>
    </div>

    {{-- Print Styles --}}
    @push('styles')
    <style media="print">
        @page {
            margin: 1cm;
        }
        .no-print, nav, footer, header {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .glass-card {
            background: white !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: none !important;
        }
    </style>
    @endpush
</x-parent-layout>
