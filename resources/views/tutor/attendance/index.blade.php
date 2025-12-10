<x-tutor-layout title="Attendance History">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Attendance History</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">View and manage your submitted attendance records</p>
        </div>
        <a href="{{ route('tutor.attendance.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Submit Attendance
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card rounded-xl p-4">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">This Month</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Total submissions</p>
        </div>
        <div class="glass-card rounded-xl p-4">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Approved</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['approved'] }}</p>
            <p class="text-xs text-slate-500 mt-1">This month</p>
        </div>
        <div class="glass-card rounded-xl p-4">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Awaiting approval</p>
        </div>
        <div class="glass-card rounded-xl p-4">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Stand-in</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['standin_count'] }}</p>
            <p class="text-xs text-slate-500 mt-1">This month</p>
        </div>
    </div>

    <!-- Tabs + Filters -->
    <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
        <!-- Tabs -->
        <div class="flex border-b border-slate-200 dark:border-slate-700">
            <a href="{{ route('tutor.attendance.index', ['tab' => 'my-students', 'month' => $month, 'status' => $status]) }}" 
               class="flex-1 px-6 py-4 text-center font-medium transition-colors {{ $tab === 'my-students' ? 'text-[#4B51FF] border-b-2 border-[#4B51FF] bg-[#4B51FF]/5' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    My Students
                </div>
            </a>
            <a href="{{ route('tutor.attendance.index', ['tab' => 'stand-in', 'month' => $month, 'status' => $status]) }}" 
               class="flex-1 px-6 py-4 text-center font-medium transition-colors {{ $tab === 'stand-in' ? 'text-[#4B51FF] border-b-2 border-[#4B51FF] bg-[#4B51FF]/5' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Stand-in
                    @if($stats['standin_count'] > 0)
                        <span class="px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">{{ $stats['standin_count'] }}</span>
                    @endif
                </div>
            </a>
        </div>

        <!-- Filters -->
        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
            <form method="GET" class="flex flex-wrap items-center gap-4">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Month:</label>
                    <input type="month" name="month" value="{{ $month }}" 
                           class="px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                </div>
                
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">Status:</label>
                    <select name="status" class="px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-[#4B51FF] text-white text-sm font-medium rounded-lg hover:bg-[#3D43E0] transition-colors">
                    Filter
                </button>
                
                <a href="{{ route('tutor.attendance.index', ['tab' => $tab]) }}" class="px-4 py-2 text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                    Reset
                </a>
            </form>
        </div>

        <!-- Stand-in Info Banner -->
        @if($tab === 'stand-in')
            <div class="px-6 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-100 dark:border-blue-800">
                <div class="flex items-center gap-2 text-blue-700 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">Stand-in attendance is for students not assigned to you. This happens when covering for another tutor.</span>
                </div>
            </div>
        @endif

        <!-- Attendance List -->
        <div class="p-4">
            @if($attendanceRecords->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">No Attendance Records</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-4">
                        @if($tab === 'stand-in')
                            You haven't submitted any stand-in attendance yet.
                        @else
                            No attendance records found for the selected filters.
                        @endif
                    </p>
                    @if($tab === 'stand-in')
                        <a href="{{ route('tutor.attendance.create', ['standin' => 1]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#4B51FF] to-[#22D3EE] text-white font-medium rounded-lg hover:opacity-90 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Submit Stand-in Attendance
                        </a>
                    @else
                        <a href="{{ route('tutor.attendance.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-medium rounded-lg hover:opacity-90 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Submit Attendance
                        </a>
                    @endif
                </div>
            @else
                <div class="space-y-3">
                    @foreach($attendanceRecords as $attendance)
                        <a href="{{ route('tutor.attendance.show', $attendance) }}" 
                           class="block p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-[#4B51FF]/20">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <!-- Student Avatar -->
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#4B51FF] to-[#22D3EE] rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ strtoupper(substr($attendance->student->first_name, 0, 1)) }}{{ strtoupper(substr($attendance->student->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-semibold text-slate-900 dark:text-white">
                                                {{ $attendance->student->first_name }} {{ $attendance->student->last_name }}
                                            </h4>
                                            @if($attendance->is_stand_in ?? ($tab === 'stand-in'))
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded">Stand-in</span>
                                            @endif
                                            @if($attendance->is_late)
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded">Late</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-4 mt-1 text-sm text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $attendance->class_date->format('M d, Y') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $attendance->class_time ? \Carbon\Carbon::parse($attendance->class_time)->format('g:i A') : 'N/A' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $attendance->duration_minutes }} mins
                                            </span>
                                        </div>
                                        @if($attendance->topic)
                                            <p class="text-sm text-slate-400 mt-1 line-clamp-1">{{ $attendance->topic }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($attendance->status === 'approved') badge-approved
                                        @elseif($attendance->status === 'pending') badge-pending
                                        @elseif($attendance->status === 'rejected') badge-late
                                        @else badge-draft
                                        @endif">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $attendance->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($attendanceRecords->hasPages())
                    <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $attendanceRecords->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('tutor.attendance.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            My Students
        </a>
        <a href="{{ route('tutor.attendance.create', ['standin' => 1]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#4B51FF] to-[#22D3EE] text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Stand-in Attendance
        </a>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
</x-tutor-layout>
