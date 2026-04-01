<x-app-layout>
    <x-slot name="header">
        {{ __('Director Final Approval') }}
    </x-slot>

    <x-slot name="title">{{ __('Director Final Approval') }}</x-slot>

    {{-- Animated Background - Director Royal Blue to Purple Gradient --}}
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Page Header --}}
            <div class="mb-8 flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Reports Management
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Review, approve, and manage tutor reports
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('director.activity-logs.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Activity Logs
                    </a>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                {{-- Total Reports --}}
                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Reports</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-[#4F46E5] to-[#818CF8] p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Manager Pending --}}
                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Manager Queue</p>
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['manager_pending'] ?? 0 }}</p>
                        </div>
                        <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Awaiting Director Approval --}}
                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Your Queue</p>
                            <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                        <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Completed (Director Approved) --}}
                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['approved'] ?? 0 }}</p>
                        </div>
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Late Submissions --}}
                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Late Submissions</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['late_submissions'] ?? 0 }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Awaiting Reports --}}
                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Not Submitted</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['awaiting_reports'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $currentMonth ?? now()->format('F') }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div x-data="{ activeTab: 'reports' }" class="mb-6">
                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl border border-white/10 rounded-2xl p-1 inline-flex shadow-sm mb-6">
                    <button @click="activeTab = 'reports'"
                            :class="activeTab === 'reports' ? 'bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="px-6 py-2 rounded-xl font-medium transition-all">
                        Reports List
                    </button>
                    <button @click="activeTab = 'analytics'"
                            :class="activeTab === 'analytics' ? 'bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="px-6 py-2 rounded-xl font-medium transition-all">
                        Analytics
                    </button>
                    <button @click="activeTab = 'students'"
                            :class="activeTab === 'students' ? 'bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="px-6 py-2 rounded-xl font-medium transition-all">
                        Students Overview
                    </button>
                </div>

            {{-- Reports List Tab --}}
            <div x-show="activeTab === 'reports'" x-transition>

            {{-- Status Tabs --}}
            <div class="mb-6">
                <div class="flex gap-2">
                    <a href="{{ route('director.reports.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
                       class="px-6 py-3 rounded-xl font-medium transition-all flex items-center gap-2
                           {{ $statusFilter === 'pending'
                               ? 'bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white shadow-lg'
                               : 'bg-white/30 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 hover:bg-white/50 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pending Approval
                        @if($pendingCount > 0)
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $statusFilter === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('director.reports.index', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}"
                       class="px-6 py-3 rounded-xl font-medium transition-all flex items-center gap-2
                           {{ $statusFilter === 'approved'
                               ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-lg'
                               : 'bg-white/30 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 hover:bg-white/50 dark:hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approved Reports
                        @if($approvedCount > 0)
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $statusFilter === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                                {{ $approvedCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-400 px-6 py-4 rounded-xl">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-700 text-blue-800 dark:text-blue-400 px-6 py-4 rounded-xl">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filters</h3>
                <form method="GET" action="{{ route('director.reports.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    {{-- Search by Student Name --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student Name</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search student..."
                               class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                    </div>

                    {{-- Month Filter --}}
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                        <select name="month" id="month" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                            <option value="">All Months</option>
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Year Filter --}}
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                        <select name="year" id="year" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                            <option value="">All Years</option>
                            @foreach($years ?? [] as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tutor Filter --}}
                    <div>
                        <label for="tutor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tutor</label>
                        <select name="tutor_id" id="tutor_id" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                            <option value="">All Tutors</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->fullName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Student Filter --}}
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student</label>
                        <select name="student_id" id="student_id" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->fullName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Buttons --}}
                    <div class="md:col-span-5 flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('director.reports.index') }}" class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            {{-- Reports List --}}
            @if($reports->isEmpty())
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-12 text-center shadow-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    @if($statusFilter === 'approved')
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Approved Reports</h3>
                        <p class="text-gray-600 dark:text-gray-400">There are no reports that have been approved by the Director yet</p>
                    @else
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Reports Pending Director Approval</h3>
                        <p class="text-gray-600 dark:text-gray-400">There are currently no manager-approved reports awaiting your final approval</p>
                    @endif
                </div>
            @else
                <div x-data="bulkApproveDirector()" class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg overflow-hidden">
                    @if($statusFilter === 'pending')
                        <div x-show="selectedIds.length > 0" x-transition class="px-6 py-3 border-b border-white/10 bg-indigo-500/10 flex items-center justify-between">
                            <span class="text-sm text-gray-700 dark:text-gray-300" x-text="selectedIds.length + ' report(s) selected'"></span>
                            <button @click="submitBulkApprove()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-500 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Approve Selected
                            </button>
                        </div>
                        <form id="bulkApproveDirectorForm" action="{{ route('director.reports.bulk-approve') }}" method="POST" class="hidden">
                            @csrf
                            <template x-for="id in selectedIds" :key="id">
                                <input type="hidden" name="report_ids[]" :value="id">
                            </template>
                        </form>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    @if($statusFilter === 'pending')
                                        <th class="px-4 py-4 text-left">
                                            <input type="checkbox" @change="toggleAll($event)" class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500" :checked="allSelected">
                                        </th>
                                    @endif
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Month</th>
                                    @if($statusFilter === 'approved')
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Director Comment</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Approved by Director</th>
                                    @else
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Manager Comment</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Approved by Manager</th>
                                    @endif
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                        @if($statusFilter === 'pending')
                                            <td class="px-4 py-4">
                                                <input type="checkbox" value="{{ $report->id }}" @change="toggleReport({{ $report->id }})" :checked="selectedIds.includes({{ $report->id }})" class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                            </td>
                                        @endif
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $report->student ? $report->student->fullName() : 'Student Deleted' }}
                                                    </div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $report->student ? 'Age ' . $report->student->age : 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $report->tutor ? $report->tutor->fullName() : 'Tutor Deleted' }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $report->tutor ? $report->tutor->email : 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->month }}</div>
                                        </td>
                                        @if($statusFilter === 'approved')
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                                    {{ $report->director_comment ? \Illuminate\Support\Str::limit($report->director_comment, 80) : 'No comment' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $report->approved_by_director_at ? $report->approved_by_director_at->format('M d, Y') : 'N/A' }}
                                                </div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $report->approved_by_director_at ? $report->approved_by_director_at->format('g:i A') : '' }}
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                                    {{ $report->manager_comment ? \Illuminate\Support\Str::limit($report->manager_comment, 80) : 'No comment' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $report->approved_by_manager_at ? $report->approved_by_manager_at->format('M d, Y') : 'N/A' }}
                                                </div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $report->approved_by_manager_at ? $report->approved_by_manager_at->format('g:i A') : '' }}
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-6 py-4">
                                            <a href="{{ route('director.reports.show', $report) }}" class="inline-flex items-center px-4 py-2 {{ $statusFilter === 'approved' ? 'bg-gradient-to-r from-emerald-500 to-green-500' : 'bg-gradient-to-r from-[#4F46E5] to-[#818CF8]' }} text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ $statusFilter === 'approved' ? 'View Report' : 'Review & Approve' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $reports->appends(request()->query())->links() }}
                </div>
            @endif

            </div>{{-- End Reports List Tab --}}

            {{-- Analytics Tab --}}
            <div x-show="activeTab === 'analytics'" x-transition>
                <div class="space-y-6">
                    {{-- Analytics Month Filter --}}
                    <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Analytics by Month</h3>
                        <form method="GET" action="{{ route('director.reports.index') }}" class="flex flex-wrap items-end gap-4">
                            {{-- Preserve existing filters --}}
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('tutor_id'))
                                <input type="hidden" name="tutor_id" value="{{ request('tutor_id') }}">
                            @endif
                            @if(request('student_id'))
                                <input type="hidden" name="student_id" value="{{ request('student_id') }}">
                            @endif
                            @if(request('month'))
                                <input type="hidden" name="month" value="{{ request('month') }}">
                            @endif
                            @if(request('year'))
                                <input type="hidden" name="year" value="{{ request('year') }}">
                            @endif

                            <div>
                                <label for="analytics_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                                <select name="analytics_month" id="analytics_month" class="px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                        <option value="{{ $m }}" {{ ($analyticsMonth ?? now()->format('F')) == $m ? 'selected' : '' }}>
                                            {{ $m }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="analytics_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                                <select name="analytics_year" id="analytics_year" class="px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                                    @foreach($years ?? [now()->format('Y')] as $y)
                                        <option value="{{ $y }}" {{ ($analyticsYear ?? now()->format('Y')) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Apply Filter
                            </button>
                        </form>
                    </div>

                    {{-- Monthly Breakdown --}}
                    <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-lg">
                        <div class="p-6 border-b border-white/10">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Report Summary</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Breakdown of reports by month with approval counts</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/30 dark:bg-gray-800/30">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Month/Year</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Draft</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Pending</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Manager Approved</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Completed</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Returned</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Approval Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    @forelse($monthlyAnalytics ?? [] as $monthly)
                                        @php
                                            $approvalRate = $monthly->total > 0 ? round((($monthly->approved_by_manager + $monthly->completed) / $monthly->total) * 100) : 0;
                                        @endphp
                                        <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $monthly->month }}</span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $monthly->year }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="px-2 py-1 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-full">{{ $monthly->total }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $monthly->draft }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($monthly->pending > 0)
                                                    <span class="px-2 py-1 text-sm font-medium text-amber-800 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full">{{ $monthly->pending }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">0</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($monthly->approved_by_manager > 0)
                                                    <span class="px-2 py-1 text-sm font-medium text-blue-800 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">{{ $monthly->approved_by_manager }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">0</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($monthly->completed > 0)
                                                    <span class="px-2 py-1 text-sm font-medium text-emerald-800 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">{{ $monthly->completed }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">0</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($monthly->returned > 0)
                                                    <span class="px-2 py-1 text-sm font-medium text-red-800 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-full">{{ $monthly->returned }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">0</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="h-2 rounded-full {{ $approvalRate >= 80 ? 'bg-emerald-500' : ($approvalRate >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $approvalRate }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium {{ $approvalRate >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($approvalRate >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">{{ $approvalRate }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                                No monthly data available
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Reports Awaiting Submission for Selected Month --}}
                    <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-lg">
                        <div class="p-6 border-b border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reports Yet to be Submitted</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Students without a submitted report for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-semibold text-purple-800 dark:text-purple-300 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                                    {{ count($studentsAwaitingReports ?? []) }} students
                                </span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/30 dark:bg-gray-800/30">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    @forelse($studentsAwaitingReports ?? [] as $student)
                                        <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $student->first_name }} {{ $student->last_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $student->student_id ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($student->tutor)
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">No tutor assigned</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                    Not Submitted
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center">
                                                <svg class="w-12 h-12 mx-auto text-emerald-300 dark:text-emerald-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-emerald-600 dark:text-emerald-400 font-medium">All students have submitted reports for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Late Submissions for Selected Month --}}
                    <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-lg">
                        <div class="p-6 border-b border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Late Submissions</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Reports submitted after deadline for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-semibold text-red-800 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-full">
                                    {{ count($lateSubmissionsForMonth ?? []) }} late
                                </span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/30 dark:bg-gray-800/30">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Submitted At</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    @forelse($lateSubmissionsForMonth ?? [] as $report)
                                        <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-red-500 to-orange-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($report->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($report->student->last_name ?? '', 0, 1)) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $report->student->first_name }} {{ $report->student->last_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($report->tutor)
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $report->tutor->first_name }} {{ $report->tutor->last_name }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $report->submitted_at->format('M d, Y g:i A') }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                    Late
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <svg class="w-12 h-12 mx-auto text-emerald-300 dark:text-emerald-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-emerald-600 dark:text-emerald-400 font-medium">No late submissions for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Completed Reports for Selected Month --}}
                    <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-lg">
                        <div class="p-6 border-b border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Completed Reports</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Director approved reports for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-semibold text-emerald-800 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                                    {{ count($completedReportsForMonth ?? []) }} completed
                                </span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/30 dark:bg-gray-800/30">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Approved At</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    @forelse($completedReportsForMonth ?? [] as $report)
                                        <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-emerald-500 to-green-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($report->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($report->student->last_name ?? '', 0, 1)) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $report->student->first_name }} {{ $report->student->last_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($report->tutor)
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $report->tutor->first_name }} {{ $report->tutor->last_name }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $report->approved_by_director_at ? $report->approved_by_director_at->format('M d, Y g:i A') : 'N/A' }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('director.reports.show', $report) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">No completed reports for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Manager Approved Reports for Selected Month --}}
                    <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-lg">
                        <div class="p-6 border-b border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manager Approved (Pending Director)</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Reports awaiting director approval for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-semibold text-blue-800 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                    {{ count($managerApprovedForMonth ?? []) }} pending
                                </span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/30 dark:bg-gray-800/30">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Manager Approved At</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    @forelse($managerApprovedForMonth ?? [] as $report)
                                        <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($report->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($report->student->last_name ?? '', 0, 1)) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $report->student->first_name }} {{ $report->student->last_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($report->tutor)
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $report->tutor->first_name }} {{ $report->tutor->last_name }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $report->approved_by_manager_at ? $report->approved_by_manager_at->format('M d, Y g:i A') : 'N/A' }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('director.reports.show', $report) }}" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-lg hover:shadow-lg transition-all text-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <svg class="w-12 h-12 mx-auto text-emerald-300 dark:text-emerald-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-emerald-600 dark:text-emerald-400 font-medium">No reports pending director approval for {{ $analyticsMonth ?? now()->format('F') }} {{ $analyticsYear ?? now()->format('Y') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>{{-- End Analytics Tab --}}

            {{-- Students Overview Tab --}}
            <div x-show="activeTab === 'students'" x-transition>
                {{-- Student Overview Month Filter --}}
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Student Overview by Month</h3>
                    <form method="GET" action="{{ route('director.reports.index') }}" class="flex flex-wrap items-end gap-4">
                        {{-- Preserve existing filters --}}
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('tutor_id'))
                            <input type="hidden" name="tutor_id" value="{{ request('tutor_id') }}">
                        @endif
                        @if(request('student_id'))
                            <input type="hidden" name="student_id" value="{{ request('student_id') }}">
                        @endif
                        @if(request('month'))
                            <input type="hidden" name="month" value="{{ request('month') }}">
                        @endif
                        @if(request('year'))
                            <input type="hidden" name="year" value="{{ request('year') }}">
                        @endif
                        @if(request('analytics_month'))
                            <input type="hidden" name="analytics_month" value="{{ request('analytics_month') }}">
                        @endif
                        @if(request('analytics_year'))
                            <input type="hidden" name="analytics_year" value="{{ request('analytics_year') }}">
                        @endif

                        <div>
                            <label for="overview_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                            <select name="overview_month" id="overview_month" class="px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                                <option value="">All Months</option>
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                    <option value="{{ $m }}" {{ ($overviewMonth ?? '') == $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="overview_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                            <select name="overview_year" id="overview_year" class="px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                                <option value="">All Years</option>
                                @foreach($years ?? [now()->format('Y')] as $y)
                                    <option value="{{ $y }}" {{ ($overviewYear ?? '') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filter
                        </button>

                        @if($overviewMonth || $overviewYear)
                            <a href="{{ route('director.reports.index', array_filter(array_merge(request()->except(['overview_month', 'overview_year']), []))) }}" class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear Filter
                            </a>
                        @endif
                    </form>
                    @if($overviewMonth || $overviewYear)
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Showing data for: <span class="font-medium text-gray-900 dark:text-white">{{ $overviewMonth ?? 'All months' }} {{ $overviewYear ?? '' }}</span>
                        </p>
                    @endif
                </div>

                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-lg">
                    <div class="p-6 border-b border-white/10">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Student-Tutor Report Overview</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @if($overviewMonth || $overviewYear)
                                Report status for {{ $overviewMonth ?? 'all months' }} {{ $overviewYear ?? '' }}
                            @else
                                Complete overview of each student's report status with their assigned tutor
                            @endif
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50/30 dark:bg-gray-800/30">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Pending</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Manager Approved</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Completed</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Latest Submission</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Latest Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Late?</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @forelse($studentTutorReports ?? [] as $student)
                                    <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#4F46E5] to-[#818CF8] flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $student->student_id ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($student->tutor)
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-medium text-xs">
                                                        {{ strtoupper(substr($student->tutor->first_name ?? 'T', 0, 1)) }}{{ strtoupper(substr($student->tutor->last_name ?? '', 0, 1)) }}
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 italic">No tutor assigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(($student->pending_reports ?? 0) > 0)
                                                <span class="px-2 py-1 text-sm font-medium text-amber-800 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                                                    {{ $student->pending_reports }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(($student->manager_approved_reports ?? 0) > 0)
                                                <span class="px-2 py-1 text-sm font-medium text-blue-800 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                                    {{ $student->manager_approved_reports }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(($student->approved_reports ?? 0) > 0)
                                                <span class="px-2 py-1 text-sm font-medium text-emerald-800 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                                                    {{ $student->approved_reports }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">0</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($student->latest_submitted_at)
                                                <div>
                                                    <span class="text-sm text-gray-900 dark:text-white">{{ $student->latest_submitted_at->format('M d, Y') }}</span>
                                                    <br><span class="text-xs text-gray-500 dark:text-gray-400">{{ $student->latest_month }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 italic">No submissions</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($student->latest_status)
                                                <span class="px-2 py-1 text-xs rounded-full font-medium
                                                    @if($student->latest_status === 'submitted') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                                    @elseif($student->latest_status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                                    @elseif($student->latest_status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                                    @elseif($student->latest_status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                    @endif">
                                                    @if($student->latest_status === 'submitted')
                                                        Pending
                                                    @elseif($student->latest_status === 'approved-by-manager')
                                                        Manager Approved
                                                    @elseif($student->latest_status === 'approved-by-director')
                                                        Completed
                                                    @else
                                                        {{ ucfirst(str_replace('-', ' ', $student->latest_status)) }}
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($student->is_late_submission ?? false)
                                                <span class="px-2 py-1 text-xs rounded-full font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                    Late
                                                </span>
                                            @elseif($student->latest_submitted_at)
                                                <span class="text-emerald-500">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            @if($overviewMonth || $overviewYear)
                                                No report data found for {{ $overviewMonth ?? 'all months' }} {{ $overviewYear ?? '' }}
                                            @else
                                                No students found
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>{{-- End Students Overview Tab --}}

            </div>{{-- End Tab Navigation x-data wrapper --}}

        </div>
    </div>

    @push('scripts')
    <script>
        function bulkApproveDirector() {
            return {
                selectedIds: [],
                allIds: @json($statusFilter === 'pending' ? $reports->pluck('id')->values() : []),
                get allSelected() {
                    return this.allIds.length > 0 && this.allIds.every(id => this.selectedIds.includes(id));
                },
                toggleAll(event) {
                    if (event.target.checked) {
                        this.selectedIds = [...this.allIds];
                    } else {
                        this.selectedIds = [];
                    }
                },
                toggleReport(id) {
                    const idx = this.selectedIds.indexOf(id);
                    if (idx > -1) {
                        this.selectedIds.splice(idx, 1);
                    } else {
                        this.selectedIds.push(id);
                    }
                },
                submitBulkApprove() {
                    if (this.selectedIds.length === 0) return;
                    if (!confirm(`Are you sure you want to give FINAL APPROVAL to ${this.selectedIds.length} report(s)? No director comments will be added.`)) return;
                    document.getElementById('bulkApproveDirectorForm').submit();
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
