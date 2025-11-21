<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                {{ __('Director Dashboard') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <!-- Animated Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12 relative overflow-hidden">
        <!-- Floating Orbs Background -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Welcome Section with Glassmorphism -->
            <div class="mb-8 glass-card rounded-2xl p-8 shadow-xl animate-slide-in">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">{{ Auth::user()->name }}</span>! 👋
                        </h3>
                        <p class="text-gray-600 text-lg">Here's what's happening with your organization today.</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="text-right">
                            <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">
                                {{ $activeStudents }}
                            </div>
                            <div class="text-sm text-gray-600">Students Active</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Stats Cards with Brand Colors -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Total Students - Blue Gradient -->
                <div class="glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer transform transition-all duration-300" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg icon-bounce">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center animate-pulse-glow">
                            <span class="text-2xl">👥</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Total Students</h4>
                    <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600 mb-3">
                        {{ $totalStudents }}
                    </p>
                    <div class="flex items-center text-sm">
                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
                            {{ $activeStudents }} active
                        </span>
                    </div>
                </div>

                <!-- Total Tutors - Purple Gradient -->
                <div class="glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer transform transition-all duration-300" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg icon-bounce">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center animate-pulse-glow">
                            <span class="text-2xl">👨‍🏫</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Total Tutors</h4>
                    <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-3">
                        {{ $totalTutors }}
                    </p>
                    <div class="flex items-center text-sm">
                        <a href="{{ route('tutors.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold flex items-center">
                            View all
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Attendance Rate - Green Gradient -->
                <div class="glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer transform transition-all duration-300" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg icon-bounce">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center animate-pulse-glow">
                            <span class="text-2xl">✅</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Attendance Rate</h4>
                    <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 mb-3">
                        {{ $attendanceRate }}%
                    </p>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        Today's attendance
                    </div>
                </div>

                <!-- Monthly Revenue - Yellow/Orange Gradient -->
                <div class="glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer transform transition-all duration-300" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg icon-bounce">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center animate-pulse-glow">
                            <span class="text-2xl">💰</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Monthly Revenue</h4>
                    <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600 mb-3">
                        ₦{{ number_format($monthlyRevenue) }}
                    </p>
                    <div class="flex items-center text-sm">
                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 font-semibold flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                            </svg>
                            ↑ 12% from last month
                        </span>
                    </div>
                </div>

            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <!-- Recent Reports -->
                <div class="glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer" style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-3xl">📄</span>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Recent Reports</h4>
                    <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600 mb-3">
                        {{ $recentReports }}
                    </p>
                    <a href="{{ route('reports.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold flex items-center">
                        View all reports
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <!-- Pending Approvals -->
                <div class="glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer relative overflow-hidden" style="animation-delay: 0.6s;">
                    @if($pendingApprovals > 0)
                        <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-400 rounded-full -mr-12 -mt-12 opacity-20 animate-pulse"></div>
                    @endif
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-3xl">⏰</span>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Pending Approvals</h4>
                    <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600 mb-3">
                        {{ $pendingApprovals }}
                    </p>
                    <a href="{{ route('reports.index') }}?status=submitted" class="text-yellow-600 hover:text-yellow-800 text-sm font-semibold flex items-center">
                        Review pending
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <!-- Monthly Reports -->
                <div class="glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer" style="animation-delay: 0.7s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-3xl">📅</span>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Monthly Reports</h4>
                    <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600 mb-3">
                        {{ $monthlyReports }}
                    </p>
                    <div class="text-sm text-gray-600">This month's total</div>
                </div>

            </div>

            <!-- Quick Actions with Glassmorphism -->
            <div class="glass-card rounded-2xl p-8 shadow-xl" style="animation-delay: 0.8s;">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="text-3xl mr-3">⚡</span>
                    Quick Actions
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

                    <!-- Add Student -->
                    <a href="{{ route('students.create') }}" class="group">
                        <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-600">Add Student</span>
                        </div>
                    </a>

                    <!-- Take Attendance -->
                    <a href="{{ route('attendance.create') }}" class="group">
                        <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 group-hover:text-green-600">Take Attendance</span>
                        </div>
                    </a>

                    <!-- Create Report -->
                    <a href="{{ route('reports.create') }}" class="group">
                        <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 group-hover:text-purple-600">Create Report</span>
                        </div>
                    </a>

                    <!-- Approve Reports -->
                    <a href="{{ route('reports.index') }}?status=submitted" class="group">
                        <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300 relative">
                            @if($pendingApprovals > 0)
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold animate-pulse">
                                    {{ $pendingApprovals }}
                                </div>
                            @endif
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 group-hover:text-yellow-600">Approve Reports</span>
                        </div>
                    </a>

                    <!-- View Analytics -->
                    <a href="{{ route('analytics') }}" class="group">
                        <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600">View Analytics</span>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
