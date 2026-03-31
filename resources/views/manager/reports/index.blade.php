<x-manager-layout title="Tutor Reports">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tutor Reports Management</h1>
        <p class="text-gray-500 dark:text-gray-400">Review and approve tutor monthly reports</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        {{-- Total Reports --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Reports</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] p-3 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Awaiting Approval --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Approved (Sent to Director) --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['approved'] }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Sent to Director</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completed (Director Approved) --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['completed'] }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Director Approved</p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Late Submissions --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
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
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
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
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-1 inline-flex shadow-sm">
            <button @click="activeTab = 'reports'"
                    :class="activeTab === 'reports' ? 'bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-6 py-2 rounded-xl font-medium transition-all">
                Reports List
            </button>
            <button @click="activeTab = 'analytics'"
                    :class="activeTab === 'analytics' ? 'bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-6 py-2 rounded-xl font-medium transition-all">
                Analytics
            </button>
            <button @click="activeTab = 'students'"
                    :class="activeTab === 'students' ? 'bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-6 py-2 rounded-xl font-medium transition-all">
                Students Overview
            </button>
        </div>

        {{-- Reports List Tab --}}
        <div x-show="activeTab === 'reports'" x-transition class="mt-6">

    {{-- Filter Section --}}
    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 mb-6 shadow-sm">
        <form action="{{ route('manager.tutor-reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            {{-- Search by Student Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student Name</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student..."
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
            </div>

            {{-- Month Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                <select name="month" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                    <option value="">All Months</option>
                    @foreach($months ?? [] as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Year Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                <select name="year" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                    <option value="">All Years</option>
                    @foreach($years ?? [] as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Pending</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="approved-by-manager" {{ request('status') == 'approved-by-manager' ? 'selected' : '' }}>Approved</option>
                    <option value="approved-by-director" {{ request('status') == 'approved-by-director' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            {{-- Tutor Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor</label>
                <select name="tutor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                    <option value="">All Tutors</option>
                    @foreach($tutors ?? [] as $tutor)
                        <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                            {{ $tutor->first_name }} {{ $tutor->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white font-semibold rounded-lg hover:from-[#A34E30] hover:to-[#C15F3C] transition-all shadow-lg shadow-orange-500/25">
                    Filter
                </button>
                <a href="{{ route('manager.tutor-reports.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Reports List --}}
    <div x-data="bulkApproveManager()" class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Report List</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $reports->total() }} reports found</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Export Button --}}
                    <a href="{{ route('manager.tutor-reports.export', request()->query()) }}"
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-all shadow-md"
                       title="Export to Excel (includes late submission tracking)">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </a>

                    {{-- Bulk Approve --}}
                    <div x-show="selectedIds.length > 0" x-transition class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedIds.length + ' selected'"></span>
                        <button @click="submitBulkApprove()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Approve Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form id="bulkApproveManagerForm" action="{{ route('manager.tutor-reports.bulk-approve') }}" method="POST" class="hidden">
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="report_ids[]" :value="id">
            </template>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 dark:bg-gray-800/80">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" @change="toggleAll($event)" class="w-4 h-4 text-[#C15F3C] rounded focus:ring-[#C15F3C]" :checked="allPendingSelected">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($report->status === 'submitted')
                                    <input type="checkbox" value="{{ $report->id }}" @change="toggleReport({{ $report->id }})" :checked="selectedIds.includes({{ $report->id }})" class="w-4 h-4 text-[#C15F3C] rounded focus:ring-[#C15F3C]">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($report->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($report->student->last_name ?? '', 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $report->student->first_name ?? 'Unknown' }} {{ $report->student->last_name ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $report->student->student_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $report->tutor->first_name ?? 'Unknown' }} {{ $report->tutor->last_name ?? '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->month ?? 'N/A' }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $report->year ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full font-medium
                                    @if($report->status === 'submitted') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                    @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @elseif($report->status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                    @elseif($report->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    @if($report->status === 'submitted')
                                        Pending
                                    @elseif($report->status === 'approved-by-manager')
                                        Manager Approved
                                    @elseif($report->status === 'approved-by-director')
                                        Completed
                                    @else
                                        {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->submitted_at ? $report->submitted_at->format('M j, Y') : ($report->created_at ? $report->created_at->format('M j, Y') : 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('manager.tutor-reports.show', $report) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 text-[#C15F3C] dark:text-[#DA7756] text-sm font-medium rounded-lg hover:bg-[#C15F3C]/20 dark:hover:bg-[#C15F3C]/30 transition-all">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                    @if($report->status === 'submitted')
                                        <form action="{{ route('manager.tutor-reports.approve', $report) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">No reports found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
    </div>
    </div>

        {{-- Analytics Tab --}}
        <div x-show="activeTab === 'analytics'" x-transition class="mt-6">
            <div class="space-y-6">
                {{-- Monthly Breakdown --}}
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Report Summary</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Breakdown of reports by month with approval counts</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50/80 dark:bg-gray-800/80">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Month/Year</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Draft</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Manager Approved</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Completed</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Returned</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approval Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($monthlyAnalytics ?? [] as $monthly)
                                    @php
                                        $approvalRate = $monthly->total > 0 ? round((($monthly->approved_by_manager + $monthly->completed) / $monthly->total) * 100) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
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

                {{-- Reports Awaiting Submission for Current Month --}}
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reports Yet to be Submitted</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Students without a submitted report for {{ $currentMonth ?? now()->format('F') }} {{ $currentYear ?? now()->format('Y') }}</p>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold text-purple-800 dark:text-purple-300 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                                {{ count($studentsAwaitingReports ?? []) }} students
                            </span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50/80 dark:bg-gray-800/80">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($studentsAwaitingReports ?? [] as $student)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
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
                                            <p class="text-emerald-600 dark:text-emerald-400 font-medium">All students have submitted reports!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Students Overview Tab --}}
        <div x-show="activeTab === 'students'" x-transition class="mt-6">
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Student-Tutor Report Overview</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Complete overview of each student's report status with their assigned tutor</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/80 dark:bg-gray-800/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Reports</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Latest Submission</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Latest Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Late?</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($studentTutorReports ?? [] as $student)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold">
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
                                        <span class="px-2 py-1 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-full">
                                            {{ $student->total_reports ?? 0 }}
                                        </span>
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
                                        No students found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        function bulkApproveManager() {
            return {
                selectedIds: [],
                pendingIds: @json($reports->where('status', 'submitted')->pluck('id')->values()),
                get allPendingSelected() {
                    return this.pendingIds.length > 0 && this.pendingIds.every(id => this.selectedIds.includes(id));
                },
                toggleAll(event) {
                    if (event.target.checked) {
                        this.selectedIds = [...this.pendingIds];
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
                    if (!confirm(`Are you sure you want to approve ${this.selectedIds.length} report(s)? No comments will be added.`)) return;
                    document.getElementById('bulkApproveManagerForm').submit();
                }
            };
        }
    </script>
    @endpush
</x-manager-layout>
