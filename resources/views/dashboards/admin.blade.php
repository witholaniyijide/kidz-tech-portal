<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-cyan-600 dark:from-teal-400 dark:to-cyan-400">
                {{ __('Admin Dashboard') }}
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
            <div class="absolute inset-0 bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 -z-10"></div>

            <!-- Welcome Card -->
            <div class="glass-card rounded-xl p-6 shadow-xl mb-8 bg-gradient-to-r from-teal-600 to-cyan-600 dark:from-teal-700 dark:to-cyan-700 border border-white/20">
                <div class="text-white">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}! 🛠️</h3>
                    <p class="text-teal-100">System administration and data management</p>
                </div>
            </div>

            <!-- Main Statistics - 4 Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total System Users -->
                <a href="#" class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total System Users</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-cyan-600 dark:from-teal-400 dark:to-cyan-400 mt-2">{{ $totalUsers }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Active accounts</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-full">
                            <svg class="w-8 h-8 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Total Students -->
                <a href="{{ route('students.index') }}" class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 mt-2">{{ $totalStudents }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">All students</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Total Tutors -->
                <a href="{{ route('tutors.index') }}" class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tutors</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 mt-2">{{ $totalTutors }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Teaching staff</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Total Reports -->
                <a href="{{ route('reports.index') }}" class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Reports</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 mt-2">{{ $totalReports }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">All time</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-full">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Secondary Stats - 3 Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- System Health -->
                <div class="glass-card rounded-xl p-6 shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">System Health</h4>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">All systems operational</span>
                            <span class="flex items-center text-xs font-medium text-green-600 dark:text-green-400">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                Online
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Database</span>
                            <span class="flex items-center text-xs font-medium text-green-600 dark:text-green-400">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                Connected
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-500 mt-3">
                            Last backup: {{ now()->subHours(2)->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <!-- This Month Activity -->
                <div class="glass-card rounded-xl p-6 shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">This Month Activity</h4>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">New Students</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $studentsThisMonth }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Reports Created</span>
                            <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $reportsThisMonth }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Payments Received</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $paymentsThisMonth }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Summary -->
                <div class="glass-card rounded-xl p-6 shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Data Summary</h4>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Students</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $totalStudents }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tutors</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $totalTutors }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Reports</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $totalReports }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Payments</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $totalPayments }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Attendance</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $totalAttendance }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-card rounded-xl p-6 shadow-xl mb-8 bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <!-- View Students -->
                    <a href="{{ route('students.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg hover:shadow-md transition-all group">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">View Students</span>
                    </a>

                    <!-- View Tutors -->
                    <a href="{{ route('tutors.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg hover:shadow-md transition-all group">
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">View Tutors</span>
                    </a>

                    <!-- View Reports -->
                    <a href="{{ route('reports.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-lg hover:shadow-md transition-all group">
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-full mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">View Reports</span>
                    </a>

                    <!-- View Payments -->
                    <a href="{{ route('payments.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg hover:shadow-md transition-all group">
                        <div class="p-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-full mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">View Payments</span>
                    </a>

                    <!-- View Attendance -->
                    <a href="{{ route('attendance.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg hover:shadow-md transition-all group">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">View Attendance</span>
                    </a>

                    <!-- Export Data -->
                    <a href="#" class="flex flex-col items-center p-4 bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 rounded-lg hover:shadow-md transition-all group">
                        <div class="p-3 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-full mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300 text-center">Export Data</span>
                    </a>
                </div>
            </div>

            <!-- Bottom Section - 2 Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent System Activity -->
                <div class="glass-card rounded-xl p-6 shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Recent System Activity
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">New student enrolled</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->subMinutes(15)->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg mr-3">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Report submitted by tutor</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->subHour()->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Payment received</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->subHours(2)->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="p-2 bg-teal-100 dark:bg-teal-900/30 rounded-lg mr-3">
                                <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">User login: manager1@kidztech.com</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->subHours(3)->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Table -->
                <div class="glass-card rounded-xl p-6 shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Quick Stats Overview
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Module</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">This Month</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Students</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-400">{{ $totalStudents }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ $studentsThisMonth }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Tutors</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-400">{{ $totalTutors }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                            0
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Reports</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-400">{{ $totalReports }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                            {{ $reportsThisMonth }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Payments</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-400">{{ $totalPayments }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ $paymentsThisMonth }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">Attendance</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-400">{{ $totalAttendance }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-400">
                                            -
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
