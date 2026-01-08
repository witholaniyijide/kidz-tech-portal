<x-app-layout>
    <x-slot name="header">{{ __('View Report') }}</x-slot>
    <x-slot name="title">{{ __('Admin - View Report') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Monthly Report</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $report->month }} {{ $report->year }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.pdf', $report) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('admin.reports.print', $report) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            {{-- Approval Badge --}}
            <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-semibold text-emerald-800 dark:text-emerald-400">Director Approved</p>
                        <p class="text-sm text-emerald-700 dark:text-emerald-500">
                            Approved by {{ $report->director->name ?? 'Director' }} on {{ $report->approved_by_director_at?->format('M j, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Student & Tutor Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Student --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Student</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#00CCCD] to-[#00CCCD] rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($report->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($report->student->last_name ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-lg">
                                {{ $report->student->first_name ?? 'Unknown' }} {{ $report->student->last_name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $report->student->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tutor --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Tutor</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($report->tutor->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($report->tutor->last_name ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-lg">
                                {{ $report->tutor->first_name ?? 'Unknown' }} {{ $report->tutor->last_name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $report->tutor->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Performance Indicators --}}
            @if($report->attendance_score || $report->performance_rating)
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                    <h3 class="text-lg font-semibold">Performance Overview</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @if($report->attendance_score)
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-600">{{ $report->attendance_score }}%</div>
                            <div class="text-sm text-gray-500">Attendance</div>
                        </div>
                        @endif
                        @if($report->performance_rating)
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 capitalize">{{ $report->performance_rating }}</div>
                            <div class="text-sm text-gray-500">Performance Rating</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Report Content --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <h3 class="text-lg font-semibold">Report Details</h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Progress Summary --}}
                    @if($report->progress_summary)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Progress Summary</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->progress_summary)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Areas for Improvement --}}
                    @if($report->areas_for_improvement)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Areas for Improvement</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->areas_for_improvement)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Goals for Next Month --}}
                    @if($report->goals_next_month)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Goals for Next Month</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->goals_next_month)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Assignments --}}
                    @if($report->assignments)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Assignments</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->assignments)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Comments & Observations --}}
                    @if($report->comments_observation)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Comments & Observations</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->comments_observation)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Manager & Director Comments --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($report->manager_comment)
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-3">Manager's Comment</h4>
                        <p class="text-gray-700 dark:text-gray-300">{{ $report->manager_comment }}</p>
                        <p class="text-xs text-gray-500 mt-2">— {{ $report->manager->name ?? 'Manager' }}</p>
                    </div>
                @endif

                @if($report->director_comment)
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-3">Director's Comment</h4>
                        <p class="text-gray-700 dark:text-gray-300">{{ $report->director_comment }}</p>
                        <p class="text-xs text-gray-500 mt-2">— {{ $report->director->name ?? 'Director' }}</p>
                    </div>
                @endif
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
