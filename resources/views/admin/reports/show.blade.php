<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('View Report') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - View Report') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Monthly Report</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $report->report_month }} {{ $report->report_year }}</p>
                </div>
                <div class="flex gap-2">
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
                            Approved by {{ $report->director->name ?? 'Director' }} on {{ $report->director_approved_at?->format('M j, Y \a\t g:i A') }}
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
                        <div class="w-14 h-14 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
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

            {{-- Report Period & Stats --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <h3 class="text-lg font-semibold">Report Summary</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $report->classes_held ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Classes Held</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-emerald-600">{{ $report->classes_attended ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Attended</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">Level {{ $report->course_level ?? '-' }}</div>
                            <div class="text-sm text-gray-500">Course Level</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">{{ $report->total_periods ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Total Periods</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Report Content --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <h3 class="text-lg font-semibold">Report Details</h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Topics Covered --}}
                    @if($report->topics_covered)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Topics Covered</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->topics_covered)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Progress Summary --}}
                    @if($report->progress_summary)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Progress Summary</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->progress_summary)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Challenges --}}
                    @if($report->challenges)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Challenges</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->challenges)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Recommendations --}}
                    @if($report->recommendations)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Recommendations</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->recommendations)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Next Month Goals --}}
                    @if($report->next_month_goals)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">Next Month Goals</h4>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($report->next_month_goals)) !!}
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
