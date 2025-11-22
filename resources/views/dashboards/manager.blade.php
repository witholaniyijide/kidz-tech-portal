<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-400 dark:to-blue-400">
                {{ __('Manager Dashboard') }}
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
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-blue-50 to-cyan-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 -z-10"></div>

            <!-- Welcome Card -->
            <div class="glass-card rounded-xl p-6 shadow-xl mb-8 bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-700 dark:to-blue-700 border border-white/20">
                <div class="text-white">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-indigo-100">Here's your management overview for today.</p>
                </div>
            </div>

            <!-- Quick Stats Cards - Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Students -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 mt-2">{{ $totalStudents }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $activeStudents }} active</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-full">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Tutors -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tutors</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 mt-2">{{ $totalTutors }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $activeTutors }} active</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Revenue</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 mt-2">₦{{ number_format($monthlyRevenue, 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">This month</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-full">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Attendance Rate -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Attendance</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-400 dark:to-blue-400 mt-2">{{ $attendanceRate }}%</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $presentToday }}/{{ $todayAttendance }} today</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 rounded-full">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards - Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Pending Reports -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Reports</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-amber-600 dark:from-yellow-400 dark:to-amber-400 mt-2">{{ $pendingReports }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Awaiting approval</p>
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved Reports</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 mt-2">{{ $approvedReports }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">This month</p>
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Recent Students -->
                <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Students</h3>
                            <a href="{{ route('students.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">View All →</a>
                        </div>
                        @if($recentStudents->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentStudents as $student)
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center text-white font-semibold mr-3">
                                            {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $student->full_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $student->student_id }} • {{ $student->location }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($student->status == 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">No students yet</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Reports</h3>
                            <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">View All →</a>
                        </div>
                        @if($recentReports->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentReports as $report)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->student->full_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $report->month }} {{ $report->year }} • by {{ $report->instructor->name }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($report->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @elseif($report->status == 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @elseif($report->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">No reports yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('students.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-lg hover:shadow-md transition-all">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-medium text-sm text-gray-900 dark:text-gray-100 text-center">Manage Students</span>
                        </a>
                        <a href="{{ route('tutors.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg hover:shadow-md transition-all">
                            <svg class="w-10 h-10 text-purple-600 dark:text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="font-medium text-sm text-gray-900 dark:text-gray-100 text-center">Manage Tutors</span>
                        </a>
                        <a href="{{ route('payments.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg hover:shadow-md transition-all">
                            <svg class="w-10 h-10 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium text-sm text-gray-900 dark:text-gray-100 text-center">View Payments</span>
                        </a>
                        <a href="{{ route('analytics') }}" class="flex flex-col items-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg hover:shadow-md transition-all">
                            <svg class="w-10 h-10 text-amber-600 dark:text-amber-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="font-medium text-sm text-gray-900 dark:text-gray-100 text-center">View Analytics</span>
                        </a>
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
