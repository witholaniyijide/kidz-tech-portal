<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Tutor Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Tutor Dashboard') }}</x-slot>

    {{-- Main Dashboard Container --}}
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">

        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-rose-300 dark:bg-rose-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            {{-- Welcome Banner with Gradient --}}
            <x-ui.glass-card padding="p-8" class="mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">John Tutor</span>! 📚
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-lg">Ready to inspire young minds today?</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-700 dark:text-gray-300 font-semibold">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </x-ui.glass-card>

            {{-- Stats Grid (4 Cards) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- My Students --}}
                <x-tutor.stat-card
                    title="My Students"
                    value="12"
                    subtitle="Students under your guidance"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                />

                {{-- Reports This Month --}}
                <x-tutor.stat-card
                    title="Reports This Month"
                    value="8"
                    subtitle="Monthly reports submitted"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                />

                {{-- Pending Reports --}}
                <x-tutor.stat-card
                    title="Pending Reports"
                    value="3"
                    subtitle="Reports to complete"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                />

                {{-- My Schedule --}}
                <x-tutor.stat-card
                    title="My Schedule"
                    value="5"
                    subtitle="Classes today • 18 this week"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
                />
            </div>

            {{-- Quick Actions --}}
            <x-ui.glass-card padding="p-6" class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-center">Create Report</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-semibold text-center">View My Reports</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-rose-500 to-red-500 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="font-semibold text-center">View My Students</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold text-center">View Attendance</span>
                    </a>
                </div>
            </x-ui.glass-card>

            {{-- Two-Column Grid: Recent Reports & My Students --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                {{-- LEFT: My Recent Reports --}}
                <x-tutor.section-card title="My Recent Reports">
                    <div class="space-y-3">
                        @php
                            $dummyReports = [
                                ['student' => 'Samuel Johnson', 'month' => 'November 2025', 'status' => 'draft'],
                                ['student' => 'Mary Okoro', 'month' => 'November 2025', 'status' => 'approved'],
                                ['student' => 'David Akinwale', 'month' => 'November 2025', 'status' => 'submitted'],
                                ['student' => 'Grace Adeyemi', 'month' => 'October 2025', 'status' => 'manager_review'],
                                ['student' => 'Emmanuel Nwosu', 'month' => 'October 2025', 'status' => 'director_approved'],
                            ];
                        @endphp

                        @foreach($dummyReports as $report)
                            <x-tutor.recent-report-card
                                :studentName="$report['student']"
                                :month="$report['month']"
                                :status="$report['status']"
                                link="#"
                            />
                        @endforeach
                    </div>
                </x-tutor.section-card>

                {{-- RIGHT: My Students List --}}
                <x-tutor.section-card title="My Students">
                    <div class="space-y-3">
                        @php
                            $dummyStudents = [
                                ['name' => 'Samuel Johnson', 'last_class' => 'Yesterday'],
                                ['name' => 'Mary Okoro', 'last_class' => '2 days ago'],
                                ['name' => 'David Akinwale', 'last_class' => '3 days ago'],
                                ['name' => 'Grace Adeyemi', 'last_class' => '1 week ago'],
                                ['name' => 'Emmanuel Nwosu', 'last_class' => '1 week ago'],
                                ['name' => 'Blessing Okonkwo', 'last_class' => '2 weeks ago'],
                            ];
                        @endphp

                        @foreach($dummyStudents as $student)
                            <x-tutor.student-list-card
                                :studentName="$student['name']"
                                :lastClass="$student['last_class']"
                                createReportLink="#"
                            />
                        @endforeach
                    </div>

                    <div class="mt-4 text-center">
                        <a href="#" class="text-purple-600 dark:text-purple-400 hover:text-pink-600 dark:hover:text-pink-400 font-semibold text-sm transition-colors">
                            View All Students →
                        </a>
                    </div>
                </x-tutor.section-card>
            </div>

        </div>
    </div>

    {{-- Custom Animations --}}
    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Tutor Gradient Utility */
        .bg-gradient-tutor {
            background: linear-gradient(to right, #8B5CF6, #EC4899);
        }

        /* Focus visible styles for accessibility */
        *:focus-visible {
            outline: 2px solid #8B5CF6;
            outline-offset: 2px;
            border-radius: 0.375rem;
        }
    </style>
</x-app-layout>
