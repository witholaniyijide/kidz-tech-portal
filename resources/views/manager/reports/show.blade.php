<x-manager-layout title="Review Report">
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
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                @if($report->status === 'submitted') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                @elseif($report->status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                @endif">
                {{ ucfirst(str_replace('-', ' ', $report->status)) }}
            </span>
            <a href="{{ route('manager.tutor-reports.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
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
                        <p class="text-sm text-gray-600 dark:text-gray-400">Age {{ $report->student->age }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tutor</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $report->tutor->fullName() }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $report->tutor->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Month</label>
                        <p class="text-gray-900 dark:text-white">{{ $report->month }} {{ $report->year }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted</label>
                        <p class="text-gray-900 dark:text-white">
                            {{ $report->submitted_at ? $report->submitted_at->format('M d, Y g:i A') : 'N/A' }}
                        </p>
                        @if($report->first_submitted_at && $report->submitted_at && $report->first_submitted_at->ne($report->submitted_at))
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                First submitted: {{ $report->first_submitted_at->format('M d, Y g:i A') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Courses Section --}}
            @if($report->courses && count($report->courses) > 0)
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Courses ({{ count($report->courses) }})
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($report->courses as $course)
                        <span class="px-3 py-1.5 bg-[#C15F3C]/10 text-[#C15F3C] dark:text-[#DA7756] rounded-full text-sm font-medium">{{ $course }}</span>
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
                        @if(!empty($project['title']))
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $project['title'] }}</span>
                            @if(!empty($project['link']))
                                @if(filter_var($project['link'], FILTER_VALIDATE_URL))
                                    <a href="{{ $project['link'] }}" target="_blank" class="ml-2 inline-flex items-center gap-1 text-sm text-[#C15F3C] hover:underline">
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

            {{-- Legacy Fields (if any) --}}
            @if($report->progress_summary)
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Progress Summary</h3>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->progress_summary }}</p>
            </div>
            @endif

            {{-- Previous Comments Section --}}
            @if($report->manager_comment || $report->director_comment)
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Previous Comments</h3>

                @if($report->manager_comment)
                <div class="mb-4 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 border border-[#C15F3C]/30 dark:border-[#C15F3C]/40 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="font-medium text-[#C15F3C] dark:text-[#DA7756]">Manager Comment</span>
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

        {{-- Sidebar - Manager Review Box --}}
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
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $report->projects ? count(array_filter($report->projects, function ($p) { return !empty($p['title']); })) : 0 }}</p>
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

            {{-- Manager Review Actions --}}
            @if($report->status === 'submitted')
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manager Review</h3>

                {{-- Approve Form --}}
                <form action="{{ route('manager.tutor-reports.approve', $report) }}" method="POST" class="mb-4" id="approveForm">
                    @csrf
                    <div class="mb-4">
                        <label for="manager_comment_approve" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Manager Comment (Optional)
                        </label>
                        <textarea
                            id="manager_comment_approve"
                            name="manager_comment"
                            rows="4"
                            maxlength="2000"
                            placeholder="Add feedback or notes for the tutor and director..."
                            class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#C15F3C] focus:border-transparent resize-none"></textarea>
                        @error('manager_comment')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to approve this report? It will be forwarded to the director.')"
                        class="w-full px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approve Report
                    </button>
                </form>

                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white/70 dark:bg-gray-800/70 text-gray-600 dark:text-gray-400">OR</span>
                    </div>
                </div>

                {{-- Send Back for Correction Form --}}
                <form action="{{ route('manager.tutor-reports.correction', $report) }}" method="POST" id="correctionForm">
                    @csrf
                    <div class="mb-4">
                        <label for="manager_comment_correction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Correction Notes (Required)
                        </label>
                        <textarea
                            id="manager_comment_correction"
                            name="manager_comment"
                            rows="4"
                            required
                            maxlength="2000"
                            placeholder="Explain what needs to be corrected..."
                            class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"></textarea>
                    </div>
                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to send this report back to the tutor for corrections?')"
                        class="w-full px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Send Back for Correction
                    </button>
                </form>
            </div>
            @else
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Status</h3>
                <p class="text-gray-600 dark:text-gray-400">This report has already been reviewed.</p>
            </div>
            @endif

            {{-- Export & Share Card --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Export & Share</h3>
                <div class="space-y-3">
                    <a href="{{ route('manager.tutor-reports.pdf', $report) }}"
                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Download PDF
                    </a>

                    <button type="button" onclick="exportWhatsApp()"
                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Copy for WhatsApp
                    </button>

                    <a href="{{ route('manager.tutor-reports.print', $report) }}" target="_blank"
                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Report
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- WhatsApp Text Modal -->
    <div id="whatsappModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeWhatsAppModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-2xl w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">WhatsApp Report</h3>
                    <button onclick="closeWhatsAppModal()" class="p-1 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <textarea id="whatsappText" rows="12" readonly class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl font-mono text-sm"></textarea>
                <div class="flex justify-end gap-3 mt-4">
                    <button onclick="closeWhatsAppModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 font-medium">Close</button>
                    <button onclick="copyWhatsAppText()" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">Copy to Clipboard</button>
                </div>
            </div>
        </div>
    </div>
</x-manager-layout>

@push('scripts')
<script>
async function exportWhatsApp() {
    try {
        const response = await fetch('{{ route("manager.tutor-reports.whatsapp", $report) }}');
        const data = await response.json();
        if (data.success) {
            document.getElementById('whatsappText').value = data.text;
            document.getElementById('whatsappModal').classList.remove('hidden');
        }
    } catch (error) {
        alert('Failed to generate WhatsApp text');
    }
}

function closeWhatsAppModal() {
    document.getElementById('whatsappModal').classList.add('hidden');
}

async function copyWhatsAppText() {
    const text = document.getElementById('whatsappText').value;

    // Try modern Clipboard API first (requires HTTPS)
    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(text);
            alert('Copied to clipboard!');
            return;
        } catch (error) {
            console.error('Clipboard API failed:', error);
        }
    }

    // Fallback for HTTP sites or older browsers
    try {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-9999px';
        textArea.style.top = '-9999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);

        if (successful) {
            alert('Copied to clipboard!');
        } else {
            alert('Failed to copy. Please select the text and copy manually (Ctrl+C).');
        }
    } catch (error) {
        console.error('Fallback copy failed:', error);
        alert('Failed to copy. Please select the text and copy manually (Ctrl+C).');
    }
}
</script>
@endpush
