<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Manager Dashboard') }}</x-slot>

    {{-- Animated Background with Emerald Theme --}}
    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-emerald-300 dark:bg-emerald-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Welcome Banner --}}
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-8 mb-10">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                                Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500">{{ auth()->user()->name ?? 'Manager' }}</span>!
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-lg">Operations & Tutor Performance Coordination Hub</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-700 dark:text-gray-300 font-semibold">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </div>

            {{-- Section 1: Main Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {{-- Active Students Card --}}
                <a href="{{ route('manager.students.index') }}" class="block">
                    <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-full">Students</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['activeStudents'] }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Active Students</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['totalStudents'] }} total • {{ $stats['inactiveStudents'] }} inactive</div>
                    </div>
                </a>

                {{-- Active Tutors Card --}}
                <a href="{{ route('manager.tutors.index') }}" class="block">
                    <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-teal-500 to-cyan-500 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300 rounded-full">Tutors</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['activeTutors'] }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Active Tutors</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['totalTutors'] }} total • {{ $stats['onLeaveTutors'] }} on leave</div>
                    </div>
                </a>

                {{-- Today's Classes Card --}}
                <a href="{{ route('manager.attendance.index') }}" class="block">
                    <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-amber-500 to-orange-400 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full">Today</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['todayClasses'] }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Today's Classes</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Scheduled for {{ now()->format('l') }}</div>
                    </div>
                </a>

                {{-- Pending Assessments Card --}}
                <a href="{{ route('manager.assessments.index') }}" class="block">
                    <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-rose-500 to-pink-500 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 rounded-full">Action</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['pendingAssessments'] }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Pending Assessments</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['awaitingDirectorAssessments'] }} awaiting director</div>
                    </div>
                </a>
            </div>

            {{-- Section 2: Today's Class Schedule (Full Width) --}}
            <div class="mb-10">
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Today's Class Schedule</h3>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-full text-sm font-semibold">
                            {{ now()->format('l, M j') }}
                        </span>
                    </div>

                    @if($todaySchedule && $todaySchedule->classes && count($todaySchedule->classes) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-80 overflow-y-auto">
                            @foreach($todaySchedule->classes as $class)
                                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($class['student_name'] ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $class['student_name'] ?? 'Student' }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $class['time'] ?? 'TBD' }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Tutor:</span> {{ $class['tutor_name'] ?? 'Assigned' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No classes scheduled for today</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section 3: Recent Reports & Assessments --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                {{-- Recent Submitted Reports --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Submitted Reports</h3>
                        </div>
                        <a href="{{ route('manager.reports.index') }}" class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 text-sm font-medium">View All →</a>
                    </div>

                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($recentReports as $report)
                            <a href="{{ route('manager.tutor-reports.show', $report) }}" class="block bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-4 border border-white/10 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $report->student->first_name ?? 'Student' }} {{ $report->student->last_name ?? '' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->month ?? 'N/A' }} • by {{ $report->tutor->first_name ?? 'Tutor' }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full font-medium
                                        @if($report->status === 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($report->status === 'approved-by-director') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No recent reports</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Assessments Awaiting Director --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Assessments Awaiting Director</h3>
                        </div>
                        <a href="{{ route('manager.assessments.index') }}" class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 text-sm font-medium">View All →</a>
                    </div>

                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($recentAssessments as $assessment)
                            <a href="{{ route('manager.assessments.show', $assessment) }}" class="block bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-4 border border-white/10 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $assessment->tutor->first_name ?? 'Tutor' }} {{ $assessment->tutor->last_name ?? '' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $assessment->assessment_month ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full font-medium
                                        @if($assessment->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @elseif($assessment->status === 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($assessment->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @endif">
                                        {{ ucfirst(str_replace('-', ' ', $assessment->status)) }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No assessments pending</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Section 4: Notice Board & To-Do List --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                {{-- Notice Board --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Notice Board</h3>
                        </div>
                        <a href="{{ route('manager.notices.create') }}" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create
                        </a>
                    </div>

                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($notices as $notice)
                            <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $notice->title }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit(strip_tags($notice->content), 100) }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $notice->published_at ? $notice->published_at->diffForHumans() : 'Draft' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No notices yet</p>
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('manager.notices.index') }}" class="block mt-4 text-center text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 text-sm font-medium">
                        View All Notices →
                    </a>
                </div>

                {{-- To-Do List --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">To-Do List</h3>
                        </div>
                    </div>

                    {{-- Add Task Form --}}
                    <div class="flex gap-2 mb-4">
                        <input type="text" id="newTaskInput" placeholder="Add a new task..."
                               class="flex-1 px-4 py-2 bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <button id="addTaskBtn" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>

                    {{-- Task List --}}
                    <div id="todoList" class="space-y-2 max-h-48 overflow-y-auto">
                        {{-- Tasks will be rendered here by JavaScript --}}
                    </div>
                </div>
            </div>

            {{-- Section 5: Quick Actions --}}
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    {{-- Approve Reports --}}
                    <a href="{{ route('manager.tutor-reports.index') }}" class="group bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-500 transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Approve Reports</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingReports'] }} pending</p>
                    </a>

                    {{-- Approve Assessment --}}
                    <a href="{{ route('manager.assessments.index') }}" class="group bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-500 transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Approve Assessment</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingAssessments'] }} pending</p>
                    </a>

                    {{-- View Students --}}
                    <a href="{{ route('manager.students.index') }}" class="group bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-500 transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">View Students</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['activeStudents'] }} active</p>
                    </a>

                    {{-- View Tutors --}}
                    <a href="{{ route('manager.tutors.index') }}" class="group bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-500 transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-teal-500 to-cyan-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">View Tutors</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['activeTutors'] }} active</p>
                    </a>

                    {{-- View Attendance --}}
                    <a href="{{ route('manager.attendance.index') }}" class="group bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-500 transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">View Attendance</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingAttendance'] }} pending</p>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- To-Do List JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const todoList = document.getElementById('todoList');
            const newTaskInput = document.getElementById('newTaskInput');
            const addTaskBtn = document.getElementById('addTaskBtn');

            function loadTasks() {
                const tasks = localStorage.getItem('managerTodos');
                return tasks ? JSON.parse(tasks) : [];
            }

            function saveTasks(tasks) {
                localStorage.setItem('managerTodos', JSON.stringify(tasks));
            }

            function renderTasks() {
                const tasks = loadTasks();
                todoList.innerHTML = '';

                if (tasks.length === 0) {
                    todoList.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-4">No tasks yet. Add one above!</p>';
                    return;
                }

                tasks.forEach(task => {
                    const taskEl = document.createElement('label');
                    taskEl.className = 'flex items-center gap-3 p-3 bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-lg border border-white/10 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all cursor-pointer group';

                    taskEl.innerHTML = `
                        <input type="checkbox" ${task.checked ? 'checked' : ''}
                            data-id="${task.id}"
                            class="task-checkbox w-5 h-5 text-emerald-500 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2">
                        <span class="text-gray-700 dark:text-gray-300 flex-1 ${task.checked ? 'line-through opacity-60' : ''}">${task.text}</span>
                        <button class="remove-task opacity-0 group-hover:opacity-100 transition-opacity text-red-500 hover:text-red-700 p-1" data-id="${task.id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    `;

                    todoList.appendChild(taskEl);
                });

                document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', toggleTask);
                });

                document.querySelectorAll('.remove-task').forEach(btn => {
                    btn.addEventListener('click', removeTask);
                });
            }

            function toggleTask(e) {
                const taskId = parseInt(e.target.dataset.id);
                const tasks = loadTasks();
                const task = tasks.find(t => t.id === taskId);
                if (task) {
                    task.checked = e.target.checked;
                    saveTasks(tasks);
                    renderTasks();
                }
            }

            function removeTask(e) {
                e.preventDefault();
                const taskId = parseInt(e.currentTarget.dataset.id);
                let tasks = loadTasks();
                tasks = tasks.filter(t => t.id !== taskId);
                saveTasks(tasks);
                renderTasks();
            }

            function addTask() {
                const taskText = newTaskInput.value.trim();
                if (taskText) {
                    const tasks = loadTasks();
                    tasks.push({
                        id: Date.now(),
                        text: taskText,
                        checked: false
                    });
                    saveTasks(tasks);
                    newTaskInput.value = '';
                    renderTasks();
                }
            }

            addTaskBtn.addEventListener('click', addTask);
            newTaskInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addTask();
                }
            });

            renderTasks();
        });
    </script>

    {{-- Custom Animations --}}
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .overflow-y-auto::-webkit-scrollbar { width: 6px; }
        .overflow-y-auto::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .overflow-y-auto::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.5); border-radius: 10px; }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover { background: rgba(16, 185, 129, 0.7); }
    </style>
</x-app-layout>
