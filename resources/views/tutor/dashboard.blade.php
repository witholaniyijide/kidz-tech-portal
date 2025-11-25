<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Welcome back, {{ $tutor->first_name }}!
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Here's your overview for today
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Students Count -->
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">My Students</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $studentsCount }}</h3>
                </div>
                <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reports Count -->
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Reports</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $reportsCount }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $draftReportsCount }} drafts</p>
                </div>
                <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Attendance -->
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Attendance</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $pendingAttendanceCount }}</h3>
                </div>
                <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Notifications</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $unreadNotificationsCount }}</h3>
                </div>
                <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('tutor.attendance.create') }}" class="group bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 hover:shadow-lg transition-all hover:-translate-y-1">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Submit Attendance</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Record class attendance</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('tutor.reports.create') }}" class="group bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 hover:shadow-lg transition-all hover:-translate-y-1">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Create Report</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Write a new student report</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('tutor.availability.index') }}" class="group bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 hover:shadow-lg transition-all hover:-translate-y-1">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Manage Availability</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your schedule</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Attendance -->
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Attendance</h2>
            @if($recentAttendance->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No attendance records yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentAttendance as $attendance)
                        <div class="bg-white/10 dark:bg-gray-800/30 rounded-xl p-4 border border-white/5">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $attendance->student->fullName() }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $attendance->class_date->format('M d, Y') }} - {{ $attendance->duration_minutes }} mins</p>
                                    @if($attendance->topic)
                                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Topic: {{ $attendance->topic }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($attendance->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($attendance->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @endif">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- My Students -->
        <div id="students" class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">My Students</h2>
            @if($students->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No students assigned yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($students->take(5) as $student)
                        <div class="bg-white/10 dark:bg-gray-800/30 rounded-xl p-4 border border-white/5 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $student->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Age: {{ $student->age }} • {{ $student->location }}</p>
                            </div>
                            <a href="{{ route('tutor.reports.create', ['student_id' => $student->id]) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
                @if($studentsCount > 5)
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-3">Showing 5 of {{ $studentsCount }} students</p>
                @endif
            @endif
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="mt-8 bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Reports</h2>
            <a href="{{ route('tutor.reports.index') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">View all →</a>
        </div>
        @if($recentReports->isEmpty())
            <p class="text-gray-600 dark:text-gray-400">No reports yet.</p>
        @else
            <div class="space-y-3">
                @foreach($recentReports as $report)
                    <div class="bg-white/10 dark:bg-gray-800/30 rounded-xl p-4 border border-white/5">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <a href="{{ route('tutor.reports.show', $report) }}" class="font-medium text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400">
                                    {{ $report->title }}
                                </a>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $report->student->fullName() }} • {{ $report->month }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($report->status === 'submitted') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($report->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                @elseif($report->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-tutor-layout>
