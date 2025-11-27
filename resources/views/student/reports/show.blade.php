@extends('layouts.student')

@section('content')
<div class="space-y-6 max-w-5xl">

            {{-- Back Button and Actions --}}
            <div class="mb-8 flex justify-between items-start flex-wrap gap-4">
                <a href="{{ route('student.reports.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 transition-colors">
                    ← Back to All Reports
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('student.reports.pdf', $report) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </a>
                    <a href="{{ route('student.reports.print', $report) }}" target="_blank" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 transition-colors">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </a>
                </div>
            </div>

            {{-- Report Header Card --}}
            <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Monthly Progress Report</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ $report->month }}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        ✓ Approved by Director
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tutor</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $report->tutor->first_name }} {{ $report->tutor->last_name }}</p>
                    </div>
                    <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Attendance Score</p>
                        <p class="font-semibold text-blue-600 dark:text-blue-400 text-2xl">{{ $report->attendance_score }}%</p>
                    </div>
                    <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Performance Rating</p>
                        <p class="font-semibold text-blue-600 dark:text-blue-400 text-2xl capitalize">{{ $report->performance_rating }}</p>
                    </div>
                </div>
            </div>

            {{-- Progress Summary --}}
            @if($report->progress_summary)
            <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Progress Summary
                </h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $report->progress_summary }}</p>
            </div>
            @endif

            {{-- Strengths --}}
            @if($report->strengths)
            <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    Strengths
                </h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $report->strengths }}</p>
            </div>
            @endif

            {{-- Areas for Improvement --}}
            @if($report->weaknesses)
            <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </span>
                    Areas for Growth
                </h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $report->weaknesses }}</p>
            </div>
            @endif

            {{-- Next Steps --}}
            @if($report->next_steps)
            <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Next Steps
                </h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $report->next_steps }}</p>
            </div>
            @endif

            {{-- Manager Comment --}}
            @if($report->manager_comment)
            <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-yellow-500 to-orange-500 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </span>
                    Manager's Feedback
                </h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed italic">"{{ $report->manager_comment }}"</p>
            </div>
            @endif

            {{-- Director Comment --}}
            @if($report->director_comment)
            <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </span>
                    Director's Comment
                </h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed italic">"{{ $report->director_comment }}"</p>
                @if($report->director && $report->director_signature)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Signed by {{ $report->director->name }}</p>
                </div>
                @endif
            </div>
            @endif

</div>
@endsection
