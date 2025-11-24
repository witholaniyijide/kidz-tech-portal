<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-white">
                {{ __('Director Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <x-slot name="title">{{ __('Director Dashboard') }}</x-slot>

    <!-- Animated Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">
        <!-- Floating Orbs Background -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-300 dark:bg-yellow-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Welcome Section -->
            <x-ui.glass-card padding="p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-director">{{ Auth::user()->name }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Here's what's happening with your organization today.</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="text-right">
                            <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">
                                {{ $activeStudents }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Students Active</div>
                        </div>
                    </div>
                </div>
            </x-ui.glass-card>

            <!-- Main Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 my-8">
                <x-ui.stat-card
                    title="Total Students"
                    value="{{ $totalStudents }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                    gradient="bg-gradient-to-br from-blue-500 to-cyan-600"
                />

                <x-ui.stat-card
                    title="Total Tutors"
                    value="{{ $totalTutors }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
                    gradient="bg-gradient-to-br from-purple-500 to-pink-600"
                />

                <x-ui.stat-card
                    title="Attendance Rate"
                    value="{{ $attendanceRate }}%"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-green-500 to-emerald-600"
                />

                <x-ui.stat-card
                    title="Monthly Revenue"
                    value="₦{{ number_format($monthlyRevenue) }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-orange-500 to-red-600"
                />
            </div>

            <!-- Charts and Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                <!-- Revenue Trend Chart -->
                <div class="lg:col-span-2">
                    <x-ui.glass-card>
                        <x-ui.section-title>Revenue Trend</x-ui.section-title>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Monthly revenue over the last 6 months</p>
                        <div class="h-80">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </x-ui.glass-card>
                </div>

                <!-- Student Distribution Chart -->
                <x-ui.glass-card>
                    <x-ui.section-title>Student Distribution</x-ui.section-title>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">By location</p>
                    <div class="h-80">
                        <canvas id="studentDistributionChart"></canvas>
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Attendance & Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                <!-- Weekly Attendance Chart -->
                <x-ui.glass-card>
                    <x-ui.section-title>Weekly Attendance</x-ui.section-title>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Last 7 days attendance tracking</p>
                    <div class="h-64">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </x-ui.glass-card>

                <!-- Recent Activity Feed -->
                <x-ui.glass-card>
                    <x-ui.section-title>Recent Activity</x-ui.section-title>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Latest updates and actions</p>
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        <!-- Activity Item 1 -->
                        <div class="flex items-start space-x-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">New student enrolled</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">John Doe joined Python Programming class</p>
                                <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                            </div>
                        </div>

                        <!-- Activity Item 2 -->
                        <div class="flex items-start space-x-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Attendance marked</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Web Development class - 15/15 students present</p>
                                <p class="text-xs text-gray-400 mt-1">3 hours ago</p>
                            </div>
                        </div>

                        <!-- Activity Item 3 -->
                        <div class="flex items-start space-x-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Report submitted</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Monthly progress report by Sarah Williams</p>
                                <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                            </div>
                        </div>

                        <!-- Activity Item 4 -->
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Payment received</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">₦25,000 from Jane Okafor for Q1 tuition</p>
                                <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <x-ui.glass-card>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Recent Reports</h4>
                    <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600 mb-3">
                        {{ $recentReports }}
                    </p>
                    <x-ui.gradient-button href="{{ route('reports.index') }}" gradient="bg-gradient-to-r from-indigo-500 to-blue-600">
                        View All Reports
                    </x-ui.gradient-button>
                </x-ui.glass-card>

                <x-ui.glass-card>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Pending Approvals</h4>
                    <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600 mb-3">
                        {{ $pendingApprovals }}
                    </p>
                    <x-ui.gradient-button href="{{ route('reports.index') }}?status=submitted" gradient="bg-gradient-to-r from-yellow-500 to-orange-600">
                        Review Pending
                    </x-ui.gradient-button>
                </x-ui.glass-card>

                <x-ui.glass-card>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Monthly Reports</h4>
                    <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600 mb-3">
                        {{ $monthlyReports }}
                    </p>
                    <div class="text-sm text-gray-600 dark:text-gray-300">This month's total</div>
                </x-ui.glass-card>

            </div>

            <!-- Quick Actions -->
            <x-ui.glass-card padding="p-8">
                <x-ui.section-title>Quick Actions</x-ui.section-title>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6">

                    <a href="{{ route('students.create') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600">Add Student</span>
                        </div>
                    </a>

                    <a href="{{ route('attendance.create') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-green-600">Take Attendance</span>
                        </div>
                    </a>

                    <a href="{{ route('reports.create') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-purple-600">Create Report</span>
                        </div>
                    </a>

                    <a href="{{ route('reports.index') }}?status=submitted" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300 relative">
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
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-yellow-600">Approve Reports</span>
                        </div>
                    </a>

                    <a href="{{ route('analytics') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600">View Analytics</span>
                        </div>
                    </a>

                </div>
            </x-ui.glass-card>

        </div>
    </div>

    <!-- Chart.js Initialization Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Trend Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['June', 'July', 'August', 'September', 'October', 'November'],
                    datasets: [{
                        label: 'Revenue',
                        data: [320000, 385000, 420000, 458000, 512000, 575000],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return '₦' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₦' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });

            // Student Distribution Chart
            const distributionCtx = document.getElementById('studentDistributionChart').getContext('2d');
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Lagos', 'Abuja', 'Ibadan', 'Others'],
                    datasets: [{
                        data: [45, 30, 15, 10],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(249, 115, 22, 0.8)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '70%'
                }
            });

            // Weekly Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Present',
                        data: [85, 92, 88, 95, 90, 78, 65],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderRadius: 8,
                    }, {
                        label: 'Absent',
                        data: [15, 8, 12, 5, 10, 22, 35],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
