<x-app-layout>
    <x-slot name="header">
        {{ __('Director Final Review') }}
    </x-slot>

    <x-slot name="title">{{ __('Director Final Review') }}</x-slot>

    {{-- Animated Background - Director Indigo/Purple Gradient --}}
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background - Director Indigo Theme --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Page Header --}}
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Final Approval — {{ $report->month }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ $report->student->fullName() }} • Tutor: {{ $report->tutor->fullName() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($report->status === 'approved-by-manager') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                        @elseif($report->status === 'approved-by-director') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                        @elseif($report->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                        @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                        @endif">
                        {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                    </span>
                    <a href="{{ route('director.reports.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
                        Back to List
                    </a>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-700 text-blue-800 dark:text-blue-400 px-6 py-4 rounded-xl">
                    {{ session('info') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Report Metadata --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                                <p class="text-gray-900 dark:text-white">{{ $report->month }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $report->submitted_at ? $report->submitted_at->format('M d, Y g:i A') : 'N/A' }}
                                </p>
                            </div>
                            @if($report->approved_by_manager_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Approved by Manager</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $report->approved_by_manager_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                            @endif
                            @if($report->attendance_score)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Attendance Score</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $report->attendance_score }}%</p>
                            </div>
                            @endif
                            @if($report->performance_rating)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Performance Rating</label>
                                <p class="text-gray-900 dark:text-white font-bold capitalize">{{ $report->performance_rating }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Courses Section --}}
                    @if($report->courses && count($report->courses) > 0)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Courses ({{ count($report->courses) }})
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($report->courses as $course)
                                <span class="px-3 py-1.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-full text-sm font-medium">{{ $course }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Skills Mastered Section --}}
                    @if($report->skills_mastered && count($report->skills_mastered) > 0)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                                            <a href="{{ $project['link'] }}" target="_blank" class="ml-2 inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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

                    {{-- Legacy Progress Summary --}}
                    @if($report->progress_summary)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Progress Summary</h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->progress_summary }}</p>
                    </div>
                    @endif

                    {{-- Manager Comment --}}
                    @if($report->manager_comment)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manager Comment</h3>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-emerald-800 dark:text-emerald-400">Manager Feedback</span>
                                @if($report->approved_by_manager_at)
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    • {{ $report->approved_by_manager_at->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->manager_comment }}</p>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Sidebar - Director Actions & Audit Trail --}}
                <div class="space-y-6">

                    {{-- Director Final Approval Actions --}}
                    @if($report->status === 'approved-by-manager')
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Director Final Approval</h3>

                        {{-- Edit Report Button --}}
                        <a href="{{ route('director.reports.edit', $report) }}"
                           class="w-full mb-4 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Report Before Approval
                        </a>

                        <div class="relative mb-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white/30 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400">THEN</span>
                            </div>
                        </div>

                        {{-- Approve Form --}}
                        <form action="{{ route('director.reports.approve', $report) }}" method="POST" class="mb-4" id="approveForm">
                            @csrf
                            <div class="mb-4">
                                <label for="director_comment_approve" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Director Comment (Optional)
                                </label>
                                <textarea
                                    id="director_comment_approve"
                                    name="director_comment"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Add final approval notes or feedback..."
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent resize-none"></textarea>
                                @error('director_comment')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Optional comments for final approval
                                </p>
                            </div>
                            <button
                                type="submit"
                                onclick="return confirm('Are you sure you want to give FINAL APPROVAL to this report? This will notify the tutor and manager.')"
                                class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Approve (Final)
                            </button>
                        </form>

                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white/30 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400">OR</span>
                            </div>
                        </div>

                        {{-- Reject Form --}}
                        <form action="{{ route('director.reports.reject', $report) }}" method="POST" id="rejectForm">
                            @csrf
                            <div class="mb-4">
                                <label for="director_comment_reject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Rejection Reason (Required)
                                </label>
                                <textarea
                                    id="director_comment_reject"
                                    name="director_comment"
                                    rows="4"
                                    required
                                    maxlength="2000"
                                    placeholder="Explain why this report is being rejected..."
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                                @error('director_comment')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Required when rejecting a report
                                </p>
                            </div>
                            <button
                                type="submit"
                                onclick="return confirm('Are you sure you want to REJECT this report? This will notify the tutor and manager.')"
                                class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject Report
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Status</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            This report has already been processed. Current status:
                            <strong class="capitalize">{{ str_replace('-', ' ', $report->status) }}</strong>
                        </p>
                    </div>
                    @endif

                    {{-- Audit Trail --}}
                    @if($report->audits()->count() > 0)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Audit Trail
                        </h3>
                        <div class="space-y-3">
                            @foreach($report->audits()->orderBy('created_at', 'desc')->get() as $audit)
                            <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">
                                        {{ ucfirst(str_replace('.', ' → ', $audit->action)) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $audit->created_at->format('M d, g:i A') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    By: <strong>{{ $audit->user->name ?? 'Unknown' }}</strong>
                                </p>
                                @if(isset($audit->meta['director_comment']) || isset($audit->meta['manager_comment']))
                                <div class="mt-2 text-xs text-gray-700 dark:text-gray-300">
                                    @if(isset($audit->meta['director_comment']))
                                    <p><em>"{{ \Illuminate\Support\Str::limit($audit->meta['director_comment'], 100) }}"</em></p>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Quick Info --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Guidelines</h3>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Review all report sections and manager feedback</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Ensure accuracy and completeness</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Final approval locks the report from editing</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Reject with detailed feedback if needed</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
