<x-app-layout>
    <x-slot name="header">
        {{ __('Analytics & Insights') }}
    </x-slot>

    <x-slot name="title">Director Analytics</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs - Director Indigo Theme --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Header --}}
            <div class="mb-8 flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Analytics Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400">Executive insights and performance metrics</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- Year Filter --}}
                    <select id="yearFilter" class="px-4 py-2 bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-[#4F46E5]">
                        @for($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <a href="{{ route('director.analytics.reports.export', ['month' => now()->format('Y-m')]) }}" class="px-4 py-2 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all flex items-center">
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
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-[#4F46E5] to-[#818CF8] flex items-center justify-center">
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
                    <a href="{{ route('director.reports.index') }}" class="text-xs text-[#4F46E5] dark:text-[#818CF8] hover:underline">View Pending →</a>
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
                    <a href="{{ route('director.activity-logs.index') }}" class="text-xs text-[#4F46E5] dark:text-[#818CF8] hover:underline">View All →</a>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="space-y-8">

                {{-- Student Learning Tracker - Moved to top --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl" x-data="studentLearningTracker()" x-init="init()">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Student Learning Tracker</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Track courses and topics taught to each student</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <select id="studentSelect" x-ref="studentSelect" @change="selectedStudent = $event.target.value; loadStudentData()" class="px-4 py-2 bg-white/50 dark:bg-gray-800/50 border border-white/20 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-[#4F46E5] min-w-[200px]">
                                <option value="">Select a student...</option>
                            </select>
                            <input type="date" x-model="dateFrom" @change="if(selectedStudent) loadStudentData()" class="px-3 py-2 bg-white/50 dark:bg-gray-800/50 border border-white/20 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-[#4F46E5]">
                            <span class="text-gray-500">to</span>
                            <input type="date" x-model="dateTo" @change="if(selectedStudent) loadStudentData()" class="px-3 py-2 bg-white/50 dark:bg-gray-800/50 border border-white/20 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-[#4F46E5]">
                        </div>
                    </div>

                    {{-- Content Area --}}
                    <div class="max-h-96 overflow-y-auto">
                        {{-- No Student Selected --}}
                        <div x-show="!selectedStudent" class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Select a student to view their learning history</p>
                        </div>

                        {{-- Loading State --}}
                        <div x-show="loading" x-cloak class="text-center py-12">
                            <svg class="animate-spin h-8 w-8 mx-auto text-[#4F46E5]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">Loading...</p>
                        </div>

                        {{-- Student Data Display --}}
                        <div x-show="selectedStudent && !loading && studentData" x-cloak>
                            {{-- Student Info Summary --}}
                            <div x-show="studentData && studentData.student" class="mb-6 p-4 bg-gradient-to-r from-[#4F46E5]/10 to-[#818CF8]/10 rounded-xl border border-[#4F46E5]/20">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Student</p>
                                        <p class="font-semibold text-gray-900 dark:text-white" x-text="studentData?.student?.name || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Current Tutor</p>
                                        <p class="font-semibold text-gray-900 dark:text-white" x-text="studentData?.student?.tutor || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Classes</p>
                                        <p class="font-semibold text-[#4F46E5]" x-text="studentData?.summary?.total_classes || 0"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Unique Topics Covered</p>
                                        <p class="font-semibold text-[#4F46E5]" x-text="studentData?.summary?.unique_topics || 0"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- No Topics --}}
                            <div x-show="studentData && studentData.topics && studentData.topics.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No topics recorded for this period
                            </div>

                            {{-- Topics Timeline --}}
                            <div x-show="studentData && studentData.topics && studentData.topics.length > 0" class="space-y-3">
                                <template x-for="(topic, index) in (studentData?.topics || [])" :key="index">
                                    <div class="flex items-start gap-4 p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl border border-white/10">
                                        <div class="flex-shrink-0 w-20 text-right">
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400" x-text="topic.date"></span>
                                        </div>
                                        <div class="w-1 min-h-[2rem] bg-gradient-to-b from-[#4F46E5] to-[#818CF8] rounded-full"></div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white" x-text="topic.topic"></p>
                                            <span x-show="topic.course" class="inline-block mt-1 px-2 py-0.5 text-xs bg-[#4F46E5]/10 text-[#4F46E5] dark:text-[#818CF8] rounded-full" x-text="topic.course"></span>
                                            <p x-show="topic.notes" class="mt-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2" x-text="topic.notes"></p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full"
                                              :class="topic.type === 'attendance' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'"
                                              x-text="topic.type === 'attendance' ? 'Attendance' : 'Report'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Enrollment & Growth --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Enrollment & Growth</h2>
                    </div>
                    <div class="h-80">
                        <canvas id="enrollmentsChart"></canvas>
                    </div>
                </div>

                {{-- Report Analytics --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Monthly Report Submissions</h2>
                        </div>
                        <div class="h-64">
                            <canvas id="reportsMonthlyChart"></canvas>
                        </div>
                    </div>

                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Courses Taught</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="courseStats"></p>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="coursesChart"></canvas>
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
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Performance Score Trend</h2>
                        </div>
                        <div class="h-64">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>

                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Performance Score Distribution</h2>
                        </div>
                        <div class="h-64">
                            <canvas id="ratingDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Criteria Breakdown --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Criteria Breakdown</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Average scores by assessment criteria</p>
                    </div>
                    <div class="h-80">
                        <canvas id="criteriaBreakdownChart"></canvas>
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

        // Store chart instances for updating
        let charts = {};
        let selectedYear = new Date().getFullYear();

        // Show no data message
        function showNoData(canvasId, message = 'No data available') {
            const canvas = document.getElementById(canvasId);
            if (canvas) {
                canvas.parentElement.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500 dark:text-gray-400 py-8">' + message + '</p></div>';
            }
        }

        // Year filter change handler
        document.getElementById('yearFilter').addEventListener('change', function() {
            selectedYear = this.value;
            loadAllCharts();
        });

        // Load all charts
        function loadAllCharts() {
            loadEnrollmentsChart();
            loadReportsCharts();
            loadCoursesChart();
            loadTutorPerformanceCharts();
            loadAssessmentCharts();
        }

        // Fetch and render enrollments chart
        function loadEnrollmentsChart() {
            fetch('{{ route('director.analytics.enrollments') }}?year=' + selectedYear)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok: ' + res.statusText);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Enrollments data received:', data);
                    if (charts.enrollments) charts.enrollments.destroy();

                    if (!data.labels || data.labels.length === 0) {
                        showNoData('enrollmentsChart', 'No enrollment data available for ' + selectedYear);
                        return;
                    }

                    const ctx = document.getElementById('enrollmentsChart');
                    if (!ctx) {
                        console.error('Canvas element enrollmentsChart not found');
                        return;
                    }

                    charts.enrollments = new Chart(ctx, {
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
                })
                .catch(err => {
                    console.error('Error loading enrollments:', err);
                    showNoData('enrollmentsChart', 'Error loading enrollment data: ' + err.message);
                });
        }

        // Fetch and render reports charts
        function loadReportsCharts() {
            fetch('{{ route('director.analytics.reports') }}?year=' + selectedYear)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok: ' + res.statusText);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Reports data received:', data);
                    // Monthly Reports Chart
                    if (charts.reportsMonthly) charts.reportsMonthly.destroy();

                    if (data.monthly && data.monthly.labels && data.monthly.labels.length > 0) {
                        const ctx = document.getElementById('reportsMonthlyChart');
                        if (ctx) {
                            charts.reportsMonthly = new Chart(ctx, {
                                type: 'line',
                                data: data.monthly,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { position: 'top' } },
                                    scales: { y: { beginAtZero: true } }
                                }
                            });
                        }
                    } else {
                        showNoData('reportsMonthlyChart', 'No report data available for ' + selectedYear);
                    }
                })
                .catch(err => {
                    console.error('Error loading reports:', err);
                    showNoData('reportsMonthlyChart', 'Error loading report data: ' + err.message);
                });
        }

        // Fetch and render courses chart
        function loadCoursesChart() {
            fetch('{{ route('director.analytics.courses') }}?year=' + selectedYear)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok: ' + res.statusText);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Courses data received:', data);
                    if (charts.courses) charts.courses.destroy();

                    // Update stats text
                    const statsEl = document.getElementById('courseStats');
                    if (statsEl && data.total_classes) {
                        statsEl.textContent = `${data.unique_courses} courses across ${data.total_classes} classes`;
                    }

                    if (data.courses && data.courses.labels && data.courses.labels.length > 0) {
                        const ctx = document.getElementById('coursesChart');
                        if (ctx) {
                            charts.courses = new Chart(ctx, {
                                type: 'doughnut',
                                data: data.courses,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { position: 'right' } }
                                }
                            });
                        }
                    } else {
                        showNoData('coursesChart', 'No course data available for ' + selectedYear);
                    }
                })
                .catch(err => {
                    console.error('Error loading courses:', err);
                    showNoData('coursesChart', 'Error loading course data: ' + err.message);
                });
        }

        // Fetch and render tutor performance charts
        function loadTutorPerformanceCharts() {
            fetch('{{ route('director.analytics.tutors') }}?year=' + selectedYear)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok: ' + res.statusText);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Tutor performance data received:', data);
                    // Students per Tutor Chart
                    if (charts.studentsPerTutor) charts.studentsPerTutor.destroy();

                    if (data.students_per_tutor && data.students_per_tutor.labels && data.students_per_tutor.labels.length > 0) {
                        const ctx = document.getElementById('studentsPerTutorChart');
                        if (ctx) {
                            charts.studentsPerTutor = new Chart(ctx, {
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
                        }
                    } else {
                        showNoData('studentsPerTutorChart', 'No tutor student data available');
                    }

                    // Attendance Chart
                    if (charts.attendance) charts.attendance.destroy();

                    if (data.attendance && data.attendance.labels && data.attendance.labels.length > 0) {
                        const ctx = document.getElementById('attendanceChart');
                        if (ctx) {
                            charts.attendance = new Chart(ctx, {
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
                        }
                    } else {
                        showNoData('attendanceChart', 'No attendance data available');
                    }
                })
                .catch(err => {
                    console.error('Error loading tutor performance:', err);
                    showNoData('studentsPerTutorChart', 'Error loading tutor data: ' + err.message);
                    showNoData('attendanceChart', 'Error loading attendance data: ' + err.message);
                });
        }

        // Fetch and render assessment charts
        function loadAssessmentCharts() {
            fetch('{{ route('director.analytics.assessments') }}?year=' + selectedYear)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok: ' + res.statusText);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Assessment data received:', data);
                    // Performance Trend Chart
                    if (charts.performance) charts.performance.destroy();

                    if (data.monthly_performance && data.monthly_performance.labels && data.monthly_performance.labels.length > 0) {
                        const ctx = document.getElementById('performanceChart');
                        if (ctx) {
                            charts.performance = new Chart(ctx, {
                                type: 'line',
                                data: data.monthly_performance,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { position: 'top' } },
                                    scales: { y: { beginAtZero: true, max: 100 } }
                                }
                            });
                        }
                    } else {
                        showNoData('performanceChart', 'No performance trend data available for ' + selectedYear);
                    }

                    // Rating Distribution Chart
                    if (charts.ratingDistribution) charts.ratingDistribution.destroy();

                    if (data.rating_distribution && data.rating_distribution.datasets && data.rating_distribution.datasets[0].data.some(v => v > 0)) {
                        const ctx = document.getElementById('ratingDistributionChart');
                        if (ctx) {
                            charts.ratingDistribution = new Chart(ctx, {
                                type: 'doughnut',
                                data: data.rating_distribution,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { position: 'right' } }
                                }
                            });
                        }
                    } else {
                        showNoData('ratingDistributionChart', 'No rating distribution data available');
                    }

                    // Criteria Breakdown Chart
                    if (charts.criteriaBreakdown) charts.criteriaBreakdown.destroy();

                    if (data.criteria_breakdown && data.criteria_breakdown.labels && data.criteria_breakdown.labels.length > 0) {
                        const ctx = document.getElementById('criteriaBreakdownChart');
                        if (ctx) {
                            charts.criteriaBreakdown = new Chart(ctx, {
                                type: 'bar',
                                data: data.criteria_breakdown,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    indexAxis: 'y',
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return context.raw + '%';
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
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
                        }
                    } else {
                        showNoData('criteriaBreakdownChart', 'No criteria data available. Assessments need to be approved by director to show here.');
                    }
                })
                .catch(err => {
                    console.error('Error loading assessments:', err);
                    showNoData('performanceChart', 'Error loading performance data: ' + err.message);
                    showNoData('ratingDistributionChart', 'Error loading rating data: ' + err.message);
                    showNoData('criteriaBreakdownChart', 'Error loading criteria data: ' + err.message);
                });
        }

        // Initial load
        loadAllCharts();

        // Student Learning Tracker Alpine.js Component
        function studentLearningTracker() {
            return {
                students: [],
                selectedStudent: '',
                studentData: null,
                loading: false,
                dateFrom: new Date(Date.now() - 90 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // 3 months ago
                dateTo: new Date().toISOString().split('T')[0], // today

                init() {
                    this.loadStudents();
                },

                async loadStudents() {
                    try {
                        const response = await fetch('{{ route('director.analytics.student-learning') }}');
                        const data = await response.json();
                        this.students = data.students || [];

                        // Populate select dropdown with options
                        const select = this.$refs.studentSelect || document.getElementById('studentSelect');
                        if (select && this.students.length > 0) {
                            // Clear existing options except first
                            select.innerHTML = '<option value="">Select a student...</option>';
                            this.students.forEach(student => {
                                const option = document.createElement('option');
                                option.value = student.id;
                                option.textContent = student.name + (student.student_id ? ' (' + student.student_id + ')' : '');
                                select.appendChild(option);
                            });
                        }
                    } catch (error) {
                        console.error('Error loading students:', error);
                    }
                },

                async loadStudentData() {
                    if (!this.selectedStudent) {
                        this.studentData = null;
                        return;
                    }

                    this.loading = true;
                    try {
                        const response = await fetch(`{{ route('director.analytics.student-learning') }}?student_id=${this.selectedStudent}&date_from=${this.dateFrom}&date_to=${this.dateTo}`);
                        this.studentData = await response.json();
                        console.log('Student data loaded:', this.studentData);
                    } catch (error) {
                        console.error('Error loading student data:', error);
                        this.studentData = null;
                    }
                    this.loading = false;
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
