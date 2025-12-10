<x-tutor-layout>
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                Welcome back, {{ $tutor->first_name }}!
            </h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">
                Here's your teaching overview for {{ now()->format('l, F j, Y') }}
            </p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Last updated: {{ now()->format('g:i A') }}
        </div>
    </div>

    <!-- SECTION 1: Main Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- My Students -->
        <a href="{{ route('tutor.students.index') }}" class="glass-card stat-card rounded-2xl p-5 shadow-lg group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">My Students</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $studentsCount }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Active students</p>
                </div>
                <div class="p-3 bg-gradient-to-br from-[#1D2A6D] to-[#4B51FF] rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Reports This Month -->
        <a href="{{ route('tutor.reports.index') }}" class="glass-card stat-card rounded-2xl p-5 shadow-lg group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Reports This Month</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $submittedThisMonth }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="p-3 bg-gradient-to-br from-[#4B51FF] to-[#22D3EE] rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Pending Reports -->
        <a href="{{ route('tutor.reports.index', ['status' => 'draft']) }}" class="glass-card stat-card rounded-2xl p-5 shadow-lg group {{ $pendingReportsCount > 0 ? 'border-l-4 border-amber-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending Reports</p>
                    <h3 class="text-3xl font-bold {{ $pendingReportsCount > 0 ? 'text-amber-600' : 'text-slate-900 dark:text-white' }} mt-1">{{ $pendingReportsCount }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Draft / Returned</p>
                </div>
                <div class="p-3 {{ $pendingReportsCount > 0 ? 'bg-gradient-to-br from-amber-500 to-orange-500' : 'bg-gradient-to-br from-[#1D2A6D] to-[#4B51FF]' }} rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Today's Classes -->
        <a href="{{ route('tutor.schedule.today') }}" class="glass-card stat-card rounded-2xl p-5 shadow-lg group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Today's Classes</p>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $classesTodayCount }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ now()->format('M d, Y') }}</p>
                </div>
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- SECTION 2: Daily Class Schedule -->
    <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#22D3EE]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h2 class="text-lg font-semibold text-white">Today's Class Schedule</h2>
                </div>
                <span class="text-sm text-slate-300">{{ now()->format('l, F j') }}</span>
            </div>
        </div>
        <div class="p-6">
            @if(!isset($schedulePosted) || !$schedulePosted)
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Waiting for Schedule</h3>
                    <p class="text-slate-500 dark:text-slate-400">Today's schedule has not been posted by Admin yet.</p>
                </div>
            @elseif($todayClasses->isEmpty())
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">No Classes Today</h3>
                    <p class="text-slate-500 dark:text-slate-400">You have no scheduled classes for today.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($todayClasses as $index => $class)
                        <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#4B51FF] to-[#22D3EE] rounded-full flex items-center justify-center text-white font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900 dark:text-white">
                                    {{ $class['student_name'] ?? 'Student' }}
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ isset($class['class_time']) ? \Carbon\Carbon::parse($class['class_time'])->format('g:i A') : 'Time TBD' }}
                                </p>
                            </div>
                            @if(isset($class['class_link']) && $class['class_link'])
                                <a href="{{ $class['class_link'] }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Join Class
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Two Column Layout: Notices + Recent Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- SECTION 3: Notices -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <h2 class="text-lg font-semibold text-white">Notice Board</h2>
                    </div>
                    <a href="{{ route('tutor.notices.index') }}" class="text-sm text-amber-100 hover:text-white">View all →</a>
                </div>
            </div>
            <div class="p-4">
                @if(!isset($recentNotices) || $recentNotices->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-slate-500 dark:text-slate-400">No notices at this time.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentNotices as $notice)
                            <a href="{{ route('tutor.notices.show', $notice) }}" class="block p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <div class="flex items-start gap-3">
                                    @if($notice->priority === 'high' || $notice->priority === 'urgent')
                                        <span class="mt-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                    @else
                                        <span class="mt-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if($notice->priority === 'high')
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded">High</span>
                                            @elseif($notice->priority === 'urgent')
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded animate-pulse">Urgent</span>
                                            @endif
                                            <h4 class="font-semibold text-slate-900 dark:text-white truncate">{{ $notice->title }}</h4>
                                        </div>
                                        <p class="text-sm text-slate-500 line-clamp-2">{{ Str::limit(strip_tags($notice->content), 80) }}</p>
                                        <p class="text-xs text-slate-400 mt-2">{{ $notice->published_at?->diffForHumans() ?? $notice->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- SECTION 4: Recent Reports -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-[#4B51FF] to-[#22D3EE]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h2 class="text-lg font-semibold text-white">Recent Reports</h2>
                    </div>
                    <a href="{{ route('tutor.reports.index') }}" class="text-sm text-cyan-100 hover:text-white">View all →</a>
                </div>
            </div>
            <div class="p-4">
                @if($recentReports->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-slate-500 dark:text-slate-400">No reports yet.</p>
                        <a href="{{ route('tutor.reports.create') }}" class="inline-flex items-center gap-2 mt-3 text-[#4B51FF] hover:text-[#22D3EE] font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create your first report
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentReports as $report)
                            <a href="{{ route('tutor.reports.show', $report) }}" class="block p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-slate-900 dark:text-white truncate">
                                            {{ $report->title ?? $report->student->first_name . ' - ' . $report->month }}
                                        </h4>
                                        <p class="text-sm text-slate-500 mt-1">{{ $report->student->first_name }} {{ $report->student->last_name }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $report->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($report->status === 'draft') badge-draft
                                        @elseif($report->status === 'submitted') badge-submitted
                                        @elseif($report->status === 'returned') badge-returned
                                        @elseif($report->status === 'approved' || $report->status === 'director_approved') badge-approved
                                        @else badge-pending
                                        @endif">
                                        @if($report->status === 'director_approved')
                                            ✓ Approved
                                        @else
                                            {{ ucfirst($report->status) }}
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SECTION 5: Quick Actions -->
    <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Quick Actions</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Submit Attendance -->
                <a href="{{ route('tutor.attendance.create') }}" class="flex flex-col items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:-translate-y-1 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#1D2A6D] to-[#4B51FF] rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Submit Attendance</span>
                </a>

                <!-- Submit Report -->
                <a href="{{ route('tutor.reports.create') }}" class="flex flex-col items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:-translate-y-1 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#4B51FF] to-[#22D3EE] rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Submit Report</span>
                </a>

                <!-- View My Reports -->
                <a href="{{ route('tutor.reports.index') }}" class="flex flex-col items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:-translate-y-1 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#1D2A6D] to-[#4B51FF] rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">My Reports</span>
                </a>

                <!-- View My Students -->
                <a href="{{ route('tutor.students.index') }}" class="flex flex-col items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:-translate-y-1 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">My Students</span>
                </a>

                <!-- View Performance -->
                <a href="{{ route('tutor.performance.index') }}" class="flex flex-col items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:-translate-y-1 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Performance</span>
                </a>

                <!-- View Schedule -->
                <a href="{{ route('tutor.schedule.today') }}" class="flex flex-col items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:-translate-y-1 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Schedule</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: Recent Attendance + My Students -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Attendance -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Attendance</h2>
                    <a href="{{ route('tutor.attendance.index') }}" class="text-sm text-[#4B51FF] hover:text-[#22D3EE]">View all →</a>
                </div>
            </div>
            <div class="p-4">
                @if($recentAttendance->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-slate-500 dark:text-slate-400">No attendance records yet.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentAttendance as $attendance)
                            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}</p>
                                        <p class="text-sm text-slate-500 mt-1">{{ $attendance->class_date->format('M d, Y') }} • {{ $attendance->duration_minutes }} mins</p>
                                        @if($attendance->topic)
                                            <p class="text-sm text-slate-400 mt-1">{{ Str::limit($attendance->topic, 40) }}</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($attendance->status === 'approved') badge-approved
                                        @elseif($attendance->status === 'pending') badge-pending
                                        @elseif($attendance->is_late) badge-late
                                        @else badge-draft
                                        @endif">
                                        @if($attendance->is_late)
                                            Late
                                        @else
                                            {{ ucfirst($attendance->status) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- My Students Quick List -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">My Students</h2>
                    <a href="{{ route('tutor.students.index') }}" class="text-sm text-[#4B51FF] hover:text-[#22D3EE]">View all →</a>
                </div>
            </div>
            <div class="p-4">
                @if($students->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-slate-500 dark:text-slate-400">No students assigned yet.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($students->take(5) as $student)
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#4B51FF] to-[#22D3EE] rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</p>
                                        <p class="text-sm text-slate-500">{{ $student->course_level ?? 'Level 1' }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('tutor.reports.create', ['student_id' => $student->id]) }}" class="p-2 text-[#4B51FF] hover:text-[#22D3EE] hover:bg-[#4B51FF]/10 rounded-lg transition-colors" title="Create Report">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($studentsCount > 5)
                        <p class="text-sm text-slate-500 text-center mt-4">Showing 5 of {{ $studentsCount }} students</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
</x-tutor-layout>
