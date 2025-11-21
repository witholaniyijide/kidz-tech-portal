<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600 dark:from-orange-400 dark:to-amber-400">
                {{ __('Monthly Progress Reports') }}
            </h2>
            <a href="{{ route('reports.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Create Report
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 -z-10"></div>

            @if(session('success'))
                <div class="glass-card rounded-xl p-4 mb-6 bg-orange-50/90 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-orange-700 dark:text-orange-300 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Reports Card -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Reports</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600 dark:from-orange-400 dark:to-amber-400 mt-2">{{ $reports->total() }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 rounded-full">
                            <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submitted Reports Card -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Submitted</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-amber-600 dark:from-yellow-400 dark:to-amber-400 mt-2">{{ $reports->where('status', 'submitted')->count() }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Approved Reports Card -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-400 dark:to-emerald-400 mt-2">{{ $reports->where('status', 'approved')->count() }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-full">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- This Page Card -->
                <div class="glass-card rounded-xl p-6 shadow-xl hover-lift bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Page</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 mt-2">{{ $reports->count() }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Search and Filter Section -->
            <div class="glass-card rounded-xl shadow-xl mb-8 bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Filter Reports</h3>
                    </div>

                    <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <!-- Month Filter -->
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                                <select id="month" name="month" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">All Months</option>
                                    <option value="January" {{ request('month') == 'January' ? 'selected' : '' }}>January</option>
                                    <option value="February" {{ request('month') == 'February' ? 'selected' : '' }}>February</option>
                                    <option value="March" {{ request('month') == 'March' ? 'selected' : '' }}>March</option>
                                    <option value="April" {{ request('month') == 'April' ? 'selected' : '' }}>April</option>
                                    <option value="May" {{ request('month') == 'May' ? 'selected' : '' }}>May</option>
                                    <option value="June" {{ request('month') == 'June' ? 'selected' : '' }}>June</option>
                                    <option value="July" {{ request('month') == 'July' ? 'selected' : '' }}>July</option>
                                    <option value="August" {{ request('month') == 'August' ? 'selected' : '' }}>August</option>
                                    <option value="September" {{ request('month') == 'September' ? 'selected' : '' }}>September</option>
                                    <option value="October" {{ request('month') == 'October' ? 'selected' : '' }}>October</option>
                                    <option value="November" {{ request('month') == 'November' ? 'selected' : '' }}>November</option>
                                    <option value="December" {{ request('month') == 'December' ? 'selected' : '' }}>December</option>
                                </select>
                            </div>

                            <!-- Year Filter -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                                <select id="year" name="year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">All Years</option>
                                    <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                                    <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                                    <option value="2026" {{ request('year') == '2026' ? 'selected' : '' }}>2026</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('reports.index') }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="glass-card rounded-xl shadow-xl overflow-hidden bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                <div class="p-6">

                    @if($reports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-gray-700 dark:to-gray-700">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Month/Year</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Instructor</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($reports as $report)
                                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-amber-600 flex items-center justify-center text-white font-semibold mr-3">
                                                        {{ strtoupper(substr($report->student->full_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->student->full_name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $report->student->student_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                                    <svg class="w-4 h-4 mr-2 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ $report->month }} {{ $report->year }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $report->instructor->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($report->status == 'draft')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-100 to-slate-100 dark:from-gray-900/30 dark:to-slate-900/30 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                        </svg>
                                                        Draft
                                                    </span>
                                                @elseif($report->status == 'submitted')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Submitted
                                                    </span>
                                                @elseif($report->status == 'approved')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $report->created_at->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors duration-150" title="View Report">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>

                                                    @if($report->status == 'draft' || $report->status == 'rejected')
                                                        <a href="{{ route('reports.edit', $report) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors duration-150" title="Edit Report">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </a>
                                                    @endif

                                                    @if($report->status == 'submitted')
                                                        <form action="{{ route('reports.approve', $report) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors duration-150" title="Approve">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('reports.reject', $report) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-150" title="Reject">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reports->links() }}
                        </div>

                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 mb-6">
                                <svg class="w-10 h-10 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">No reports found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-8">Get started by creating a new monthly progress report.</p>
                            <a href="{{ route('reports.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Create Report
                            </a>
                        </div>
                    @endif

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
