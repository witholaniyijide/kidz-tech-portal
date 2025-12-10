<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Analytics') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Analytics') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Analytics Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Students & Tutors performance overview</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-blue-700 dark:text-blue-400">Admin view (Students & Tutors only)</span>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_students'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total Students</div>
                        </div>
                        <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center">
                            <span class="text-2xl">👨‍🎓</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_tutors'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total Tutors</div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <span class="text-2xl">👨‍🏫</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-emerald-600">{{ $stats['classes_this_month'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Classes This Month</div>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                            <span class="text-2xl">📚</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['avg_attendance_rate'] ?? 0, 0) }}%</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Attendance Rate</div>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                            <span class="text-2xl">📊</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Analytics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Students Analytics --}}
                <a href="{{ route('admin.analytics.students') }}" class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-1 group">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                        <h3 class="text-lg font-semibold flex items-center">
                            <span class="mr-2">👨‍🎓</span> Students Analytics
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">View detailed student performance, attendance trends, and progress metrics.</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                                <div class="text-2xl font-bold text-teal-600">{{ $stats['active_students'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Active</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-600">{{ $stats['inactive_students'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Inactive</div>
                            </div>
                        </div>

                        <div class="flex items-center text-teal-600 dark:text-teal-400 font-medium group-hover:translate-x-2 transition-transform">
                            View Students Analytics
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Tutors Analytics --}}
                <a href="{{ route('admin.analytics.tutors') }}" class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-1 group">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <h3 class="text-lg font-semibold flex items-center">
                            <span class="mr-2">👨‍🏫</span> Tutors Analytics
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Track tutor workloads, class completion rates, and performance assessments.</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['active_tutors'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Active</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-600">{{ $stats['avg_students_per_tutor'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Avg Students/Tutor</div>
                            </div>
                        </div>

                        <div class="flex items-center text-blue-600 dark:text-blue-400 font-medium group-hover:translate-x-2 transition-transform">
                            View Tutors Analytics
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Monthly Overview --}}
            <div class="mt-8 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Class Overview</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                        @php
                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            $currentMonth = date('n') - 1;
                        @endphp
                        @foreach($months as $index => $month)
                            @php
                                $count = $monthlyClasses[$index + 1] ?? 0;
                                $maxCount = max($monthlyClasses ?? [1]);
                                $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                                $isCurrent = $index === $currentMonth;
                            @endphp
                            <div class="text-center">
                                <div class="h-24 flex items-end justify-center mb-2">
                                    <div class="w-8 rounded-t transition-all {{ $isCurrent ? 'bg-teal-500' : 'bg-gray-300 dark:bg-gray-600' }}"
                                         style="height: {{ max($height, 10) }}%">
                                    </div>
                                </div>
                                <div class="text-xs font-medium {{ $isCurrent ? 'text-teal-600' : 'text-gray-500' }}">{{ $month }}</div>
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Note about Finance --}}
            <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-xl">ℹ️</span>
                    <p class="text-sm text-amber-800 dark:text-amber-400">
                        Financial analytics are only available to the Director. Contact the Director for financial reports.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
