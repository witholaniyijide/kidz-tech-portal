<x-tutor-layout>
    <!-- Breadcrumbs -->
    <x-tutor.breadcrumbs :items="[
        ['label' => 'My Reports']
    ]" />

    <!-- Page Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                My Reports
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Manage and track your student progress reports
            </p>
        </div>
        <a href="{{ route('tutor.reports.create') }}"
            class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Report
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
        <form method="GET" action="{{ route('tutor.reports.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Month Filter -->
            <div>
                <label for="month" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Filter by Month
                </label>
                <select id="month" name="month"
                    class="w-full px-4 py-2.5 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All Months</option>
                    @foreach($months as $monthOption)
                        <option value="{{ $monthOption }}" {{ request('month') === $monthOption ? 'selected' : '' }}>
                            {{ date('F Y', strtotime($monthOption . '-01')) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Filter by Status
                </label>
                <select id="status" name="status"
                    class="w-full px-4 py-2.5 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="manager_review" {{ request('status') === 'manager_review' ? 'selected' : '' }}>Manager Review</option>
                    <option value="director_approved" {{ request('status') === 'director_approved' ? 'selected' : '' }}>Approved by Director</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="flex-1 px-5 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg transition-all font-medium">
                    Apply Filters
                </button>
                @if(request()->hasAny(['month', 'status']))
                    <a href="{{ route('tutor.reports.index') }}"
                        class="px-5 py-2.5 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors font-medium">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports List -->
    @if($reports->isEmpty())
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-12 text-center shadow-lg">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                @if(request()->hasAny(['month', 'status']))
                    No Reports Found
                @else
                    No Reports Yet
                @endif
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                @if(request()->hasAny(['month', 'status']))
                    Try adjusting your filters to see more reports
                @else
                    Create your first student report to get started
                @endif
            </p>
            @if(!request()->hasAny(['month', 'status']))
                <a href="{{ route('tutor.reports.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Report
                </a>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach($reports as $report)
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <a href="{{ route('tutor.reports.show', $report) }}" class="text-xl font-semibold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                    {{ $report->student->fullName() }} - {{ date('F Y', strtotime($report->month . '-01')) }}
                                </a>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($report->status === 'submitted') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($report->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                    @elseif($report->status === 'manager_review') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($report->status === 'director_approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($report->status === 'returned') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $report->student->fullName() }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ date('F Y', strtotime($report->month . '-01')) }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $report->created_at->format('M d, Y') }}
                                </span>
                                @if($report->submitted_at)
                                    <span class="flex items-center text-green-600 dark:text-green-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Submitted {{ $report->submitted_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            @if($report->progress_summary)
                                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $report->progress_summary }}</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('tutor.reports.show', $report) }}"
                                class="p-2 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/30 rounded-lg transition-colors"
                                title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(in_array($report->status, ['draft', 'returned']))
                                <a href="{{ route('tutor.reports.edit', $report) }}"
                                    class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                            @if($report->status === 'draft')
                                <form action="{{ route('tutor.reports.destroy', $report) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                        title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    @endif
</x-tutor-layout>
