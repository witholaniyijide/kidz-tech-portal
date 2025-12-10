<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Reports for ') . $student->fullName() }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Student Reports') }}</x-slot>

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
                        Monthly Progress Reports
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        View approved monthly progress reports for {{ $student->fullName() }}
                    </p>
                </div>
                <a href="{{ route('parent.child.show', $student) }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
                    ← Back to Profile
                </a>
            </div>

            {{-- Reports List --}}
            @if($reports->isEmpty())
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-12 text-center shadow-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Reports Available</h3>
                    <p class="text-gray-600 dark:text-gray-400">Director-approved reports will appear here once available</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($reports as $report)
                        <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6 hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                            {{-- Report Header --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $report->month }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Submitted: {{ $report->submitted_at ? $report->submitted_at->format('M d, Y') : 'N/A' }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Approved
                                </span>
                            </div>

                            {{-- Tutor Info --}}
                            <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    <strong class="text-gray-900 dark:text-white">Tutor:</strong>
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $report->tutor->fullName() }}
                                </p>
                            </div>

                            {{-- Report Summary --}}
                            @if($report->progress_summary)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    <strong class="text-gray-900 dark:text-white">Summary:</strong>
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">
                                    {{ $report->progress_summary }}
                                </p>
                            </div>
                            @endif

                            {{-- Performance Indicators --}}
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                @if($report->attendance_score)
                                <div class="bg-white/40 dark:bg-gray-800/40 rounded-lg p-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Attendance</p>
                                    <p class="text-lg font-bold text-pink-600 dark:text-pink-400">{{ $report->attendance_score }}%</p>
                                </div>
                                @endif
                                @if($report->performance_rating)
                                <div class="bg-white/40 dark:bg-gray-800/40 rounded-lg p-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Rating</p>
                                    <p class="text-lg font-bold text-pink-600 dark:text-pink-400 capitalize">{{ $report->performance_rating }}</p>
                                </div>
                                @endif
                            </div>

                            {{-- View Button --}}
                            <a href="{{ route('parent.reports.show', [$student, $report]) }}" class="block w-full px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium text-center">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Full Report
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $reports->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
