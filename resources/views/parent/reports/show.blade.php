<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Monthly Report - ') . $report->month }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('View Report') }}</x-slot>

    {{-- Animated Background - Parent Pink to Rose Gradient --}}
    <div class="min-h-screen bg-gradient-to-br from-pink-50 via-rose-50 to-pink-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-rose-300 dark:bg-rose-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Page Header --}}
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ $student->fullName() }} - {{ $report->month }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Tutor: {{ $report->tutor->fullName() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        Director Approved
                    </span>
                    <a href="{{ route('parent.reports.index', $student) }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
                        ← Back to Reports
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Report Metadata --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Student</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $student->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Age {{ $student->age }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tutor</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $report->tutor->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $report->tutor->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Report Month</label>
                                <p class="text-gray-900 dark:text-white">{{ $report->month }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $report->submitted_at ? $report->submitted_at->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                            @if($report->attendance_score)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Attendance Score</label>
                                <p class="text-gray-900 dark:text-white font-bold text-2xl text-pink-600">{{ $report->attendance_score }}%</p>
                            </div>
                            @endif
                            @if($report->performance_rating)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Performance Rating</label>
                                <p class="text-gray-900 dark:text-white font-bold text-2xl text-pink-600 capitalize">{{ $report->performance_rating }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Progress Summary --}}
                    @if($report->progress_summary)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Progress Summary
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->progress_summary }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Strengths --}}
                    @if($report->strengths)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            Strengths
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->strengths }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Areas for Improvement --}}
                    @if($report->weaknesses)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Areas for Improvement
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->weaknesses }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Next Steps --}}
                    @if($report->next_steps)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Next Steps
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->next_steps }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Manager Comment --}}
                    @if($report->manager_comment)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manager Feedback</h3>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->manager_comment }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Director Comment --}}
                    @if($report->director_comment)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Director Feedback</h3>
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->director_comment }}</p>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Sidebar - Actions --}}
                <div class="space-y-6">

                    {{-- Quick Actions --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>

                        {{-- Print Button --}}
                        <button onclick="window.print()" class="w-full mb-3 px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Report
                        </button>

                        {{-- Download PDF Button --}}
                        <a href="{{ route('parent.reports.pdf', [$student, $report]) }}" class="w-full block px-6 py-3 bg-white/40 dark:bg-gray-800/40 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/50 dark:hover:bg-gray-800/60 transition-colors font-medium text-center">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF
                        </a>
                    </div>

                    {{-- Report Info --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Timeline</h3>
                        <ul class="space-y-3 text-sm">
                            @if($report->submitted_at)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-pink-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Submitted</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $report->submitted_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </li>
                            @endif
                            @if($report->approved_by_manager_at)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Manager Approved</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $report->approved_by_manager_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </li>
                            @endif
                            @if($report->approved_by_director_at)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Director Approved</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $report->approved_by_director_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Print Styles --}}
    <style media="print">
        @page {
            margin: 1cm;
        }
        .no-print {
            display: none !important;
        }
        body {
            background: white !important;
        }
    </style>
</x-app-layout>
