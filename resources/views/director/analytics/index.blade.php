<x-app-layout>
    <x-slot name="header">
        {{ __('Analytics & Insights') }}
    </x-slot>

    <x-slot name="title">Director Analytics</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Header --}}
            <div class="mb-8 flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Analytics Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400">Executive insights and performance metrics</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('director.analytics.reports.export', ['month' => now()->format('Y-m')]) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Reports CSV
                    </a>
                    <a href="{{ route('director.analytics.tutors.export', ['month' => now()->format('Y-m')]) }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Tutors CSV
                    </a>
                </div>
            </div>

            {{-- Dashboard Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {{-- Total Students --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl hover:-translate-y-1 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_students'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Active: {{ $stats['active_students'] }} | Inactive: {{ $stats['inactive_students'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Tutors --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl hover:-translate-y-1 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tutors</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_tutors'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Active: {{ $stats['active_tutors'] }} | On Leave: {{ $stats['on_leave_tutors'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Reports This Month --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl hover:-translate-y-1 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Reports This Month</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['reports_this_month'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Submitted: {{ $stats['reports_submitted_this_month'] }} | Approved: {{ $stats['reports_approved_this_month'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Assessments This Month --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl hover:-translate-y-1 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Assessments This Month</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['assessments_this_month'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Approved: {{ $stats['assessments_approved_this_month'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pending Approvals --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl hover:-translate-y-1 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Approvals</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_reports'] + $stats['pending_assessments'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Reports: {{ $stats['pending_reports'] }} | Assessments: {{ $stats['pending_assessments'] }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-yellow-500 to-amber-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('director.reports.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View Pending →</a>
                </div>

                {{-- System Activity --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl hover:-translate-y-1 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Activity Today</p>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['activity_today'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Director actions</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('director.activity-logs.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View All →</a>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="space-y-8">

                {{-- Enrollment & Growth --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Enrollment & Growth</h2>
                        <button onclick="toggleTable('enrollments')" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View Data Table</button>
                    </div>
                    <div class="h-80">
                        <canvas id="enrollmentsChart"></canvas>
                    </div>
                    <div id="enrollments-table" class="hidden mt-6 overflow-x-auto">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Loading data...</p>
                    </div>
                </div>

                {{-- Report Analytics --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Monthly Report Submissions</h2>
                            <button onclick="toggleTable('reports')" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View Table</button>
                        </div>
                        <div class="h-64">
                            <canvas id="reportsMonthlyChart"></canvas>
                        </div>
                        <div id="reports-table" class="hidden mt-4"></div>
                    </div>

                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Reports by Status (This Month)</h2>
                        </div>
                        <div class="h-64">
                            <canvas id="reportsStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Tutor Performance --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Students per Tutor</h2>
                        </div>
                        <div class="h-80 overflow-y-auto">
                            <canvas id="studentsPerTutorChart"></canvas>
                        </div>
                    </div>

                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Average Attendance by Tutor</h2>
                        </div>
                        <div class="h-80 overflow-y-auto">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Assessment Metrics --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Average Performance Score Trend</h2>
                        </div>
                        <div class="h-64">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>

                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Professionalism Rating Distribution</h2>
                        </div>
                        <div class="h-64">
                            <canvas id="ratingDistributionChart"></canvas>
                        </div>
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
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Global Chart.js configuration
        Chart.defaults.font.family = 'Inter, Plus Jakarta Sans, sans-serif';
        Chart.defaults.color = '#6B7280';

        // Toggle table visibility
        function toggleTable(id) {
            const table = document.getElementById(id + '-table');
            table.classList.toggle('hidden');
        }

        // Fetch and render enrollments chart
        fetch('{{ route('director.analytics.enrollments') }}')
            .then(res => res.json())
            .then(data => {
                new Chart(document.getElementById('enrollmentsChart'), {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            });

        // Fetch and render reports charts
        fetch('{{ route('director.analytics.reports') }}')
            .then(res => res.json())
            .then(data => {
                new Chart(document.getElementById('reportsMonthlyChart'), {
                    type: 'line',
                    data: data.monthly,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                new Chart(document.getElementById('reportsStatusChart'), {
                    type: 'doughnut',
                    data: data.status,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'right' } }
                    }
                });
            });

        // Fetch and render tutor performance charts
        fetch('{{ route('director.analytics.tutors') }}')
            .then(res => res.json())
            .then(data => {
                new Chart(document.getElementById('studentsPerTutorChart'), {
                    type: 'bar',
                    data: data.students_per_tutor,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true } }
                    }
                });

                new Chart(document.getElementById('attendanceChart'), {
                    type: 'bar',
                    data: data.attendance,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true, max: 100 } }
                    }
                });
            });

        // Fetch and render assessment charts
        fetch('{{ route('director.analytics.assessments') }}')
            .then(res => res.json())
            .then(data => {
                new Chart(document.getElementById('performanceChart'), {
                    type: 'line',
                    data: data.monthly_performance,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true, max: 100 } }
                    }
                });

                new Chart(document.getElementById('ratingDistributionChart'), {
                    type: 'pie',
                    data: data.rating_distribution,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'right' } }
                    }
                });
            });
    </script>
    @endpush
</x-app-layout>
