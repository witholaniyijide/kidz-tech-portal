@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Monthly Reports</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View your tutor's monthly progress reports</p>
        </div>
    </div>

    <!-- Filters & Search -->
    @if($reports->count() > 0 || request()->has('search') || request()->has('month') || request()->has('sort'))
        <x-ui.glass-card padding="p-5">
            <form method="GET" action="{{ route('student.reports.index') }}" class="grid gap-4 md:grid-cols-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wider">Search Reports</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Search by keyword..."
                            class="w-full px-4 py-2 pl-10 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400 focus:border-transparent transition-all">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Month Selector -->
                <div>
                    <label for="month" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wider">Filter by Month</label>
                    <select
                        name="month"
                        id="month"
                        class="w-full px-4 py-2 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400 focus:border-transparent transition-all">
                        <option value="">All Months</option>
                        @if(isset($availableMonths))
                            @foreach($availableMonths as $month)
                                <option value="{{ $month }}" {{ request('month') === $month ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wider">Sort By</label>
                    <select
                        name="sort"
                        id="sort"
                        class="w-full px-4 py-2 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400 focus:border-transparent transition-all">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rating</option>
                    </select>
                </div>

                <!-- Search/Filter Buttons -->
                <div class="md:col-span-4 flex items-center space-x-2">
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-400 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all duration-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply Filters
                    </button>

                    @if(request()->hasAny(['search', 'month', 'sort']))
                        <a href="{{ route('student.reports.index') }}" class="px-4 py-2 bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-white/70 dark:hover:bg-gray-800/70 transition-all border border-gray-200 dark:border-gray-700">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </x-ui.glass-card>
    @endif

    <!-- Reports List -->
    @if($reports->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($reports as $report)
                <x-ui.glass-card padding="p-0">
                    <div class="p-6">
                        <!-- Report Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $report->month }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    @if($report->period_from && $report->period_to)
                                        {{ \Carbon\Carbon::parse($report->period_from)->format('M d') }} - {{ \Carbon\Carbon::parse($report->period_to)->format('M d, Y') }}
                                    @endif
                                </p>
                            </div>
                            <x-ui.status-badge :status="$report->status" />
                        </div>

                        <!-- Report Summary -->
                        @if($report->summary)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ $report->summary }}</p>
                        @endif

                        <!-- Performance Rating -->
                        @if($report->performance_rating)
                            <div class="mb-4 p-3 rounded-xl bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800">
                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Performance Rating</p>
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $report->performance_rating ? 'text-amber-500' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        @endif

                        <!-- Tutor Info -->
                        @if($report->tutor)
                            <div class="flex items-center space-x-3 mb-4">
                                @if($report->tutor->profile_photo_path)
                                    <img src="{{ asset('storage/' . $report->tutor->profile_photo_path) }}" alt="{{ $report->tutor->full_name }}" class="w-8 h-8 rounded-full object-cover border-2 border-purple-500">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold text-xs">
                                        {{ strtoupper(substr($report->tutor->first_name, 0, 1)) }}{{ strtoupper(substr($report->tutor->last_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tutor</p>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $report->tutor->full_name }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Submitted Date -->
                        @if($report->approved_by_director_at)
                            <p class="text-xs text-gray-500 dark:text-gray-500 mb-4">
                                Approved {{ $report->approved_by_director_at->format('M d, Y') }}
                            </p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-white/10 dark:border-gray-700/10 p-4 flex space-x-2">
                        <a href="{{ route('student.reports.show', $report->id) }}" class="flex-1 px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-400 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all duration-200 text-center">
                            View Report
                        </a>
                        <a href="{{ route('student.reports.download', $report->id) }}" class="px-4 py-2 bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-white/70 dark:hover:bg-gray-800/70 transition-all duration-200 border border-gray-200 dark:border-gray-700" title="Download PDF">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>
                    </div>
                </x-ui.glass-card>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
            <div class="mt-6">
                {{ $reports->links() }}
            </div>
        @endif
    @else
        <x-ui.glass-card>
            <x-ui.empty-state
                title="No reports available yet"
                description="Your tutor's monthly progress reports will appear here once they're approved by the director"
                icon="document" />
        </x-ui.glass-card>
    @endif
</div>
@endsection
