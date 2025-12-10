<x-parent-layout>
    <x-slot name="title">Progress Reports</x-slot>
    <x-slot name="subtitle">View director-approved monthly progress reports</x-slot>

    {{-- Child Filter --}}
    @if($children->count() > 1)
    <div class="mb-6">
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by child:</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('parent.reports') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !$selectedChild ? 'bg-gradient-to-r from-sky-500 to-cyan-400 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        All Children
                    </a>
                    @foreach($children as $child)
                        <a href="{{ route('parent.reports', ['child' => $child->id]) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $selectedChild && $selectedChild->id === $child->id ? 'bg-gradient-to-r from-sky-500 to-cyan-400 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            {{ $child->first_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Reports Grid --}}
    @if($reports->isEmpty())
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-sky-100 to-cyan-100 dark:from-sky-900/30 dark:to-cyan-900/30 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-heading font-semibold text-gray-900 dark:text-white mb-2">No Reports Available</h3>
            <p class="text-gray-600 dark:text-gray-400">Director-approved reports will appear here once available</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reports as $report)
                <div class="glass-card rounded-2xl p-6 hover-lift">
                    {{-- Report Header --}}
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-heading font-bold text-lg text-gray-900 dark:text-white">{{ $report->month }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $report->approved_by_director_at ? $report->approved_by_director_at->format('M d, Y') : 'N/A' }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Approved
                        </span>
                    </div>

                    {{-- Student Info (if showing all children) --}}
                    @if(!$selectedChild && $children->count() > 1)
                    <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-cyan-400 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($report->student->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $report->student->fullName() }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Age {{ $report->student->age }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Tutor Info --}}
                    <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Tutor</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $report->tutor->fullName() }}
                        </p>
                    </div>

                    {{-- Report Summary --}}
                    @if($report->progress_summary)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Summary</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">
                            {{ $report->progress_summary }}
                        </p>
                    </div>
                    @endif

                    {{-- Performance Indicators --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        @if($report->attendance_score)
                        <div class="bg-sky-50 dark:bg-sky-900/20 rounded-lg p-3">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Attendance</p>
                            <p class="text-lg font-bold text-sky-600 dark:text-sky-400">{{ $report->attendance_score }}%</p>
                        </div>
                        @endif
                        @if($report->performance_rating)
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 rounded-lg p-3">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Rating</p>
                            <p class="text-lg font-bold text-cyan-600 dark:text-cyan-400 capitalize">{{ $report->performance_rating }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- View Button --}}
                    <a href="{{ route('parent.reports.show', [$report->student, $report]) }}"
                       class="block w-full px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-400 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium text-center">
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
</x-parent-layout>
