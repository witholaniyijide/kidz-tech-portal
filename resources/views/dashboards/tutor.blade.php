<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400">
                {{ __('Tutor Dashboard') }}
            </h2>
            <div class="text-right">
                <div class="text-sm text-gray-600 dark:text-gray-400">{{ now()->timezone('Africa/Lagos')->format('l, F j, Y') }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-500">{{ now()->timezone('Africa/Lagos')->format('g:i A') }}</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 -z-10"></div>

            <!-- Welcome Card -->
            <div class="glass-card rounded-xl p-6 shadow-xl mb-8 bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-700 dark:to-pink-700 border border-white/20">
                <div class="text-white">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ $tutor ? $tutor->first_name . ' ' . $tutor->last_name : Auth::user()->name }}!</h3>
                    <p class="text-purple-100">Here's what's happening with your students today.</p>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Students -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 mt-2">{{ $totalStudents }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-full">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- My Reports -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">My Reports</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 mt-2">{{ $myReports }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Reports -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-amber-600 dark:from-yellow-400 dark:to-amber-400 mt-2">{{ $pendingReports }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Approved Reports -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 mt-2">{{ $approvedReports }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-full">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Content - 2 columns -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Quick Actions -->
                    <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('reports.create') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg hover:shadow-md transition-all">
                                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Create Report</span>
                                </a>
                                <a href="{{ route('attendance.create') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-lg hover:shadow-md transition-all">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Take Attendance</span>
                                </a>
                                <a href="{{ route('students.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg hover:shadow-md transition-all">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">View Students</span>
                                </a>
                                <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg hover:shadow-md transition-all">
                                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">My Reports</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Students -->
                    <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Students</h3>
                                <a href="{{ route('students.index') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">View All →</a>
                            </div>
                            @if($recentStudents->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($recentStudents as $student)
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-xl mb-2">
                                                {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $student->first_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $student->student_id }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-gray-500 dark:text-gray-400 py-8">No students yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar - 1 column -->
                <div class="space-y-6">

                    <!-- Today's Attendance -->
                    <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Today's Attendance</h3>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 mb-2">
                                    {{ $todayAttendance }}
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Total Records</p>
                                <div class="flex items-center justify-center space-x-4 text-sm">
                                    <div>
                                        <span class="text-green-600 dark:text-green-400 font-bold">{{ $presentToday }}</span>
                                        <span class="text-gray-600 dark:text-gray-400">Present</span>
                                    </div>
                                    <div>
                                        <span class="text-red-600 dark:text-red-400 font-bold">{{ $todayAttendance - $presentToday }}</span>
                                        <span class="text-gray-600 dark:text-gray-400">Absent</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reports -->
                    <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">My Recent Reports</h3>
                            @if($recentReports->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentReports as $report)
                                        <div class="flex items-center justify-between p-3 bg-purple-50/50 dark:bg-purple-900/10 rounded-lg">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->student->full_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $report->month }} {{ $report->year }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($report->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                @elseif($report->status == 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-gray-500 dark:text-gray-400 py-8 text-sm">No reports yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .glass-card {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</x-app-layout>
