<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-600 dark:from-pink-400 dark:to-rose-400">
                {{ __('Parent Dashboard') }}
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
            <div class="absolute inset-0 bg-gradient-to-br from-pink-50 via-rose-50 to-red-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 -z-10"></div>

            <!-- Welcome Card -->
            <div class="glass-card rounded-xl p-6 shadow-xl mb-8 bg-gradient-to-r from-pink-600 to-rose-600 dark:from-pink-700 dark:to-rose-700 border border-white/20">
                <div class="text-white">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-pink-100">Here's an overview of your children's progress at Kidz Tech Coding Club.</p>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- My Children -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">My Children</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 mt-2">{{ $children->count() }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-full">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Recent Reports</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 mt-2">{{ $recentReports->count() }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Attendance Rate -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Attendance ({{ $currentMonth }})</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 mt-2">{{ $attendanceRate }}%</p>
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

                <!-- My Children Card -->
                <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Children</h3>
                        </div>

                        <div class="space-y-4">
                            @foreach($children as $child)
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100/50 dark:from-gray-700/50 dark:to-gray-700/30 rounded-lg hover:shadow-md transition-all">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full flex items-center justify-center mr-4 shadow-md">
                                            <span class="text-lg font-bold text-white">{{ strtoupper(substr($child->first_name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $child->full_name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $child->student_id }} • {{ $child->location }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('parent.child.show', $child) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
                                        View Profile →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Reports Card -->
                <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Reports</h3>
                        </div>

                        @if($recentReports->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentReports as $report)
                                    <div class="p-4 bg-gradient-to-r from-purple-50/50 to-pink-50/50 dark:from-purple-900/10 dark:to-pink-900/10 rounded-lg hover:shadow-md transition-all border border-purple-100 dark:border-purple-900/30">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr($report->student->first_name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $report->student->full_name }}</h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->month }} {{ $report->year }}</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Approved
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ Str::limit($report->comments, 80) }}</p>
                                        <a href="{{ route('parent.child.report.view', [$report->student, $report]) }}" class="inline-flex items-center text-sm text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 font-medium transition-colors">
                                            View Full Report
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 mb-4">
                                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No reports available yet</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Reports will appear here once submitted by tutors</p>
                            </div>
                        @endif
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
