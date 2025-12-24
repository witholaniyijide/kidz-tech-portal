<x-app-layout>
    <x-slot name="header">{{ __('Admin Dashboard') }}</x-slot>
    <x-slot name="title">{{ __('Admin Dashboard') }}</x-slot>

    <div x-data="adminDashboard()" class="min-h-screen bg-gradient-to-br from-indigo-50 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/20 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/20 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-[#0D6EFD]/30 dark:bg-[#0D6EFD]/20 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Welcome Banner --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-[#423A8E] to-[#00CCCD] p-4 rounded-2xl shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                                Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#423A8E] to-[#00CCCD]">{{ auth()->user()->name ?? 'Admin' }}</span>!
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-lg">Full System Administration & Management Hub</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-700 dark:text-gray-300 font-semibold">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION 1: Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Students Card --}}
                <a href="{{ route('admin.students.index') }}" class="block">
                    <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-[#423A8E] to-[#00CCCD] p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-[#423A8E]/10 text-[#423A8E] dark:bg-[#00CCCD]/20 dark:text-[#00CCCD] rounded-full font-medium">Students</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['totalStudents'] ?? 0 }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Total Students</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['activeStudents'] ?? 0 }} active • {{ $stats['inactiveStudents'] ?? 0 }} inactive</div>
                    </div>
                </a>

                {{-- Total Tutors Card --}}
                <a href="{{ route('admin.tutors.index') }}" class="block">
                    <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-[#00CCCD] to-[#423A8E] p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-[#00CCCD]/10 text-[#423A8E] dark:bg-[#00CCCD]/20 dark:text-[#00CCCD] rounded-full font-medium">Tutors</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['totalTutors'] ?? 0 }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Total Tutors</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['activeTutors'] ?? 0 }} active • {{ $stats['onLeaveTutors'] ?? 0 }} on leave</div>
                    </div>
                </a>

                {{-- Today's Classes Card --}}
                <a href="{{ route('admin.schedules.index') }}" class="block">
                    <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-[#198754] to-[#00CCCD] p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-[#198754]/10 dark:bg-[#198754]/20 text-[#198754] dark:text-emerald-300 rounded-full font-medium">Today</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['todayClasses'] ?? 0 }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Today's Classes</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Scheduled for {{ now()->format('l') }}</div>
                    </div>
                </a>

                {{-- Pending Attendance Card --}}
                <a href="{{ route('admin.attendance.index', ['status' => 'pending']) }}" class="block">
                    <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-gradient-to-r from-[#FFC107] to-[#DC3545] p-3 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <span class="text-xs px-2 py-1 bg-[#FFC107]/10 dark:bg-[#FFC107]/20 text-amber-700 dark:text-amber-300 rounded-full font-medium">Action</span>
                        </div>
                        <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['pendingAttendance'] ?? 0 }}</div>
                        <div class="text-gray-600 dark:text-gray-300 font-medium">Pending Attendance</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Awaiting approval</div>
                    </div>
                </a>
            </div>

            {{-- SECTION 2: Daily Class Schedule & To-Do List --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Daily Class Schedule --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-gradient-to-r from-[#423A8E] to-[#00CCCD] p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Today's Classes</h3>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                @if($schedulePosted ?? false)
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400">
                                        ✅ Posted {{ $schedulePostedAt ? \Carbon\Carbon::parse($schedulePostedAt)->format('g:i A') : '' }}
                                    </p>
                                @else
                                    <p class="text-xs text-amber-600 dark:text-amber-400">⏳ Draft</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <button @click="copyToClipboard()" class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors" title="Copy for WhatsApp">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                    </svg>
                                </button>
                                @if(!($schedulePosted ?? false))
                                    <form action="{{ route('admin.schedules.post') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white rounded-lg hover:shadow-lg transition-all" title="Post Schedule">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.schedules.index') }}" class="p-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Manage Schedules">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 max-h-80 overflow-y-auto">
                        @if(empty($todayClasses))
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <div class="text-4xl mb-3">📭</div>
                                <p>No classes scheduled</p>
                                <a href="{{ route('admin.schedules.create') }}" class="inline-block mt-3 text-[#423A8E] dark:text-[#00CCCD] hover:underline text-sm">+ Add classes</a>
                            </div>
                        @else
                            <div class="space-y-3" id="scheduleList">
                                @foreach($todayClasses as $index => $class)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <div class="w-7 h-7 bg-gradient-to-br from-[#423A8E] to-[#00CCCD] rounded-full flex items-center justify-center text-white font-bold text-xs">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-gray-800 dark:text-white text-sm truncate">
                                                {{ $class['student_name'] ?? 'Unknown' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                by {{ $class['tutor_name'] ?? 'Unknown' }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-semibold text-[#423A8E] dark:text-[#00CCCD] text-sm">
                                                @php
                                                    try {
                                                        $time = \Carbon\Carbon::parse($class['time'] ?? '00:00')->format('g:i A');
                                                    } catch (\Exception $e) {
                                                        $time = $class['time'] ?? '00:00';
                                                    }
                                                @endphp
                                                {{ $time }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Admin To-Do List --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-[#00CCCD] to-[#423A8E] p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">To-Do List</h3>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ now()->format('M j, Y') }}</span>
                    </div>
                    <div class="p-5 max-h-80 overflow-y-auto">
                        <div class="space-y-3">
                            {{-- Review Pending Attendance --}}
                            @if(($stats['pendingAttendance'] ?? 0) > 0)
                                <a href="{{ route('admin.attendance.index', ['status' => 'pending']) }}" class="flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors">
                                    <div class="w-7 h-7 bg-amber-500 rounded-full flex items-center justify-center text-white text-xs">
                                        ⏳
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-amber-800 dark:text-amber-300 text-sm">Review Attendance</div>
                                        <div class="text-xs text-amber-600 dark:text-amber-400">{{ $stats['pendingAttendance'] }} pending approvals</div>
                                    </div>
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Post Today's Schedule --}}
                            @if(!($schedulePosted ?? false) && !empty($todayClasses))
                                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30 rounded-xl">
                                    <div class="w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                        📤
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-blue-800 dark:text-blue-300 text-sm">Post Schedule</div>
                                        <div class="text-xs text-blue-600 dark:text-blue-400">{{ count($todayClasses) }} classes ready</div>
                                    </div>
                                    <form action="{{ route('admin.schedules.post') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-blue-500 text-white text-xs rounded-lg hover:bg-blue-600">Post</button>
                                    </form>
                                </div>
                            @endif

                            {{-- Check Reports --}}
                            @if(($stats['pendingReports'] ?? 0) > 0)
                                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800/30 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                                    <div class="w-7 h-7 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs">
                                        📋
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-purple-800 dark:text-purple-300 text-sm">Review Reports</div>
                                        <div class="text-xs text-purple-600 dark:text-purple-400">{{ $stats['pendingReports'] }} awaiting review</div>
                                    </div>
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Students without tutor --}}
                            @if(($stats['studentsWithoutTutor'] ?? 0) > 0)
                                <a href="{{ route('admin.students.index', ['without_tutor' => 1]) }}" class="flex items-center gap-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                                    <div class="w-7 h-7 bg-red-500 rounded-full flex items-center justify-center text-white text-xs">
                                        ⚠️
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-red-800 dark:text-red-300 text-sm">Assign Tutors</div>
                                        <div class="text-xs text-red-600 dark:text-red-400">{{ $stats['studentsWithoutTutor'] }} students unassigned</div>
                                    </div>
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- All caught up message --}}
                            @if(($stats['pendingAttendance'] ?? 0) == 0 && ($schedulePosted ?? true) && ($stats['pendingReports'] ?? 0) == 0 && ($stats['studentsWithoutTutor'] ?? 0) == 0)
                                <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    <div class="text-4xl mb-3">🎉</div>
                                    <p class="font-medium">All caught up!</p>
                                    <p class="text-sm">No pending tasks for today</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Recent Activities & SECTION 4: Notice Board --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Recent Activities --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-[#0D6EFD] to-[#423A8E] p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Activities</h3>
                        </div>
                    </div>
                    <div class="p-5 max-h-96 overflow-y-auto">
                        @if(($recentActivities ?? collect())->isEmpty())
                            <div class="text-center py-8 text-gray-500">No recent activities</div>
                        @else
                            <div class="space-y-3">
                                @foreach($recentActivities->take(10) as $activity)
                                    <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="w-8 h-8 bg-[#423A8E]/10 dark:bg-[#423A8E]/30 rounded-full flex items-center justify-center text-[#423A8E] dark:text-[#00CCCD] flex-shrink-0">
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
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-[#423A8E] to-[#00CCCD] p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Notice Board</h3>
                        </div>
                        <a href="{{ route('admin.notices.create') }}" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white text-sm font-medium rounded-lg hover:shadow-lg transition-all">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create
                        </a>
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
                                        @else border-[#423A8E]
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
                            <a href="{{ route('admin.notices.index') }}" class="block text-center mt-4 text-[#423A8E] dark:text-[#00CCCD] hover:underline text-sm">View All Notices →</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Students & Tutors Tables --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Recent Students --}}
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-[#423A8E] to-[#00CCCD] p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Students</h3>
                        </div>
                        <a href="{{ route('admin.students.index') }}" class="text-[#423A8E] hover:underline dark:text-[#00CCCD] text-sm font-medium">View All →</a>
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
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-[#00CCCD] to-[#423A8E] p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Tutors</h3>
                        </div>
                        <a href="{{ route('admin.tutors.index') }}" class="text-[#423A8E] hover:underline dark:text-[#00CCCD] text-sm font-medium">View All →</a>
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

            {{-- SECTION: Quick Actions (at bottom) --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-gradient-to-r from-[#423A8E] to-[#00CCCD] p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    {{-- Add Student --}}
                    <a href="{{ route('admin.students.create') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#423A8E] hover:to-[#00CCCD] transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-[#423A8E] to-[#00CCCD] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Add Student</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['totalStudents'] ?? 0 }} total</p>
                    </a>

                    {{-- Add Tutor --}}
                    <a href="{{ route('admin.tutors.create') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#423A8E] hover:to-[#00CCCD] transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-[#00CCCD] to-[#423A8E] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Add Tutor</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['totalTutors'] ?? 0 }} total</p>
                    </a>

                    {{-- Review Attendance --}}
                    <a href="{{ route('admin.attendance.index', ['status' => 'pending']) }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#423A8E] hover:to-[#00CCCD] transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-[#0D6EFD] to-[#423A8E] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Attendance</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingAttendance'] ?? 0 }} pending</p>
                    </a>

                    {{-- Post Schedule --}}
                    <a href="{{ route('admin.schedules.index') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#423A8E] hover:to-[#00CCCD] transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-[#198754] to-[#00CCCD] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Schedules</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['todayClasses'] ?? 0 }} today</p>
                    </a>

                    {{-- View Reports --}}
                    <a href="{{ route('admin.reports.index') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#423A8E] hover:to-[#00CCCD] transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-[#FFC107] to-[#DC3545] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Reports</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingReports'] ?? 0 }} pending</p>
                    </a>

                    {{-- Create Notice --}}
                    <a href="{{ route('admin.notices.create') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#423A8E] hover:to-[#00CCCD] transition-all duration-300 text-center">
                        <div class="bg-gradient-to-r from-[#DC3545] to-[#423A8E] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Create Notice</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">Announcements</p>
                    </a>
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
