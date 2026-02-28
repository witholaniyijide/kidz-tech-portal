<x-manager-layout title="Tutor Reports">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tutor Reports Management</h1>
        <p class="text-gray-500 dark:text-gray-400">Review and approve tutor monthly reports</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
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

        {{-- Awaiting Approval --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Awaiting Approval</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
    </div>

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
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Pending</option>
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
                <div x-show="selectedIds.length > 0" x-transition class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedIds.length + ' selected'"></span>
                    <button @click="submitBulkApprove()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Approve Selected
                    </button>
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
