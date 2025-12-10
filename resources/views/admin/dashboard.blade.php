<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Admin Dashboard') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin Dashboard') }}</x-slot>

    <div x-data="adminDashboard()" class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Welcome Banner --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-lg mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-cyan-500">{{ auth()->user()->name }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Here's what's happening with your organization today.</p>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-600 dark:text-gray-300 font-medium">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION 1: Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Students --}}
                <a href="{{ route('admin.students.index') }}" class="group">
                    <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-4 right-4 opacity-30 group-hover:opacity-50 transition-opacity">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['totalStudents'] ?? 0 }}</div>
                        <div class="text-lg font-medium opacity-90">Total Students</div>
                        <div class="text-sm opacity-75 mt-1">{{ $stats['activeStudents'] ?? 0 }} active • {{ $stats['inactiveStudents'] ?? 0 }} inactive</div>
                    </div>
                </a>

                {{-- Total Tutors --}}
                <a href="{{ route('admin.tutors.index') }}" class="group">
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-4 right-4 opacity-30 group-hover:opacity-50 transition-opacity">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['totalTutors'] ?? 0 }}</div>
                        <div class="text-lg font-medium opacity-90">Total Tutors</div>
                        <div class="text-sm opacity-75 mt-1">{{ $stats['activeTutors'] ?? 0 }} active • {{ $stats['onLeaveTutors'] ?? 0 }} on leave</div>
                    </div>
                </a>

                {{-- Today's Classes --}}
                <a href="{{ route('admin.schedules.index') }}" class="group">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-4 right-4 opacity-30 group-hover:opacity-50 transition-opacity">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['todayClasses'] ?? 0 }}</div>
                        <div class="text-lg font-medium opacity-90">Today's Classes</div>
                        <div class="text-sm opacity-75 mt-1">{{ $stats['completedClasses'] ?? 0 }} completed • {{ $stats['upcomingClasses'] ?? 0 }} upcoming</div>
                    </div>
                </a>

                {{-- Pending Attendance --}}
                <a href="{{ route('admin.attendance.index', ['status' => 'pending']) }}" class="group">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-4 right-4 opacity-30 group-hover:opacity-50 transition-opacity">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold mb-2">{{ $stats['pendingAttendance'] ?? 0 }}</div>
                        <div class="text-lg font-medium opacity-90">Pending Attendance</div>
                        <div class="text-sm opacity-75 mt-1">Awaiting approval</div>
                    </div>
                </a>
            </div>

            {{-- SECTION 2: Daily Class Schedule --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 rounded-2xl shadow-lg mb-8 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                📅 Classes Scheduled for Today
                            </h3>
                            @if($schedulePosted ?? false)
                                <p class="text-sm text-emerald-600 dark:text-emerald-400 mt-1">
                                    ✅ Posted {{ $schedulePostedAt ? \Carbon\Carbon::parse($schedulePostedAt)->format('g:i A') : '' }}
                                </p>
                            @else
                                <p class="text-sm text-amber-600 dark:text-amber-400 mt-1">⏳ Draft - Not yet posted</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button @click="copyToClipboard()" class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors text-sm font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                                Copy for WhatsApp
                            </button>
                            @if(!($schedulePosted ?? false))
                                <form action="{{ route('admin.schedules.post') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-lg hover:shadow-lg transition-all text-sm font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Post Schedule
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.schedules.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                                Manage Schedules
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if(($todaySchedule ?? collect())->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <div class="text-5xl mb-4">📭</div>
                            <p class="text-lg">No classes scheduled for today</p>
                            <a href="{{ route('admin.schedules.create') }}" class="inline-block mt-4 text-teal-600 dark:text-teal-400 hover:underline">+ Add classes</a>
                        </div>
                    @else
                        <div class="space-y-3" id="scheduleList">
                            @foreach($todaySchedule as $index => $class)
                                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-800 dark:text-white">
                                            {{ $class->student->first_name ?? 'Unknown' }} {{ $class->student->last_name ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            by {{ $class->tutor->first_name ?? 'Unknown' }} {{ $class->tutor->last_name ?? '' }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-teal-600 dark:text-teal-400">
                                            {{ \Carbon\Carbon::parse($class->class_time)->format('g:i A') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if($class->status === 'completed')
                                                <span class="text-emerald-600">✅ Completed</span>
                                            @elseif($class->status === 'in_progress')
                                                <span class="text-blue-600">🔵 In Progress</span>
                                            @else
                                                <span class="text-gray-500">⏳ Scheduled</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- SECTION 3: Recent Activities & SECTION 4: Notice Board --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Recent Activities --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            🕐 Recent Activities
                        </h3>
                    </div>
                    <div class="p-5 max-h-96 overflow-y-auto">
                        @if(($recentActivities ?? collect())->isEmpty())
                            <div class="text-center py-8 text-gray-500">No recent activities</div>
                        @else
                            <div class="space-y-3">
                                @foreach($recentActivities->take(10) as $activity)
                                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="w-8 h-8 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center text-teal-600 dark:text-teal-400 flex-shrink-0">
                                            @switch($activity->action ?? 'default')
                                                @case('created')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    @break
                                                @case('updated')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    @break
                                                @case('approved')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    @break
                                                @default
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endswitch
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $activity->description ?? 'Activity recorded' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $activity->user->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Notice Board Preview --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            📌 Notice Board
                        </h3>
                        <a href="{{ route('admin.notices.create') }}" class="text-sm text-teal-600 dark:text-teal-400 hover:underline font-medium">+ Create Notice</a>
                    </div>
                    <div class="p-5">
                        @if(($notices ?? collect())->isEmpty())
                            <div class="text-center py-8 text-gray-500">No notices published</div>
                        @else
                            <div class="space-y-4">
                                @foreach($notices as $notice)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border-l-4 
                                        @if($notice->priority === 'urgent') border-red-500
                                        @elseif($notice->priority === 'high') border-amber-500
                                        @else border-teal-500
                                        @endif">
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="font-semibold text-gray-800 dark:text-white">{{ $notice->title }}</h4>
                                            @if($notice->priority === 'urgent')
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Urgent</span>
                                            @elseif($notice->priority === 'high')
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">High</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit($notice->content, 100) }}</p>
                                        <p class="text-xs text-gray-500 mt-2">{{ $notice->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.notices.index') }}" class="block text-center mt-4 text-teal-600 dark:text-teal-400 hover:underline text-sm">View All Notices →</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SECTION 5: Quick Actions --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                    ⚡ Quick Actions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    {{-- Add Student --}}
                    <a href="{{ route('admin.students.create') }}" class="group flex flex-col items-center p-5 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl text-white shadow hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span class="text-sm font-medium">Add Student</span>
                    </a>

                    {{-- Add Tutor --}}
                    <a href="{{ route('admin.tutors.create') }}" class="group flex flex-col items-center p-5 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl text-white shadow hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span class="text-sm font-medium">Add Tutor</span>
                    </a>

                    {{-- Mark Attendance --}}
                    <a href="{{ route('admin.attendance.create') }}" class="group flex flex-col items-center p-5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl text-white shadow hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">Mark Attendance</span>
                    </a>

                    {{-- Post Schedule --}}
                    <a href="{{ route('admin.schedules.index') }}" class="group flex flex-col items-center p-5 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl text-white shadow hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-medium">Post Schedule</span>
                    </a>

                    {{-- View Reports --}}
                    <a href="{{ route('admin.reports.index') }}" class="group flex flex-col items-center p-5 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl text-white shadow hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-medium">View Reports</span>
                    </a>

                    {{-- Create Notice --}}
                    <a href="{{ route('admin.notices.create') }}" class="group flex flex-col items-center p-5 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl text-white shadow hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="text-sm font-medium">Create Notice</span>
                    </a>
                </div>
            </div>

            {{-- Recent Students & Tutors Tables --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Recent Students --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">👨‍🎓 Recent Students</h3>
                        <a href="{{ route('admin.students.index') }}" class="text-sm text-teal-600 dark:text-teal-400 hover:underline">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tutor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentStudents ?? [] as $student)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-800 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $student->email }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $student->tutor->first_name ?? 'Unassigned' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($student->status === 'active') bg-emerald-100 text-emerald-700
                                                @elseif($student->status === 'inactive') bg-gray-100 text-gray-700
                                                @else bg-amber-100 text-amber-700
                                                @endif">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No students yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Recent Tutors --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-md border border-white/20 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">👨‍🏫 Recent Tutors</h3>
                        <a href="{{ route('admin.tutors.index') }}" class="text-sm text-teal-600 dark:text-teal-400 hover:underline">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Students</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentTutors ?? [] as $tutor)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-800 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $tutor->email }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $tutor->students_count ?? 0 }} assigned
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($tutor->status === 'active') bg-emerald-100 text-emerald-700
                                                @elseif($tutor->status === 'on_leave') bg-amber-100 text-amber-700
                                                @else bg-gray-100 text-gray-700
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $tutor->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No tutors yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function adminDashboard() {
            return {
                async copyToClipboard() {
                    try {
                        const response = await fetch('{{ route("admin.schedules.whatsapp") }}');
                        const data = await response.json();
                        await navigator.clipboard.writeText(data.format);
                        alert('Schedule copied! Paste in WhatsApp to share.');
                    } catch (error) {
                        console.error('Failed to copy:', error);
                        alert('Failed to copy schedule. Please try again.');
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
